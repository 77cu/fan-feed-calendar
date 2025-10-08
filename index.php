<?php
// index.php — Fan Feed Calendar (season-select prototype)
declare(strict_types=1);
error_reporting(E_ALL);

$c = require __DIR__ . '/config.php';

// Use the default league from config throughout (e.g., Premier League = 39)
$league = $c['default_league'] ?? '39';

// Season selection: default to 2023 due to API free-plan restriction
$season = isset($_GET['season']) && preg_match('/^\d{4}$/', $_GET['season'])
    ? $_GET['season']
    : '2023';

// Load teams for the selected season (requires admin_fetch.php run for that season)
$teamsFile = $c['fixtures_dir'] . "/teams_{$league}_{$season}.json";
$teams = [];
if (is_file($teamsFile)) {
    $j = json_decode(file_get_contents($teamsFile), true);
    $teams = $j['teams'] ?? [];
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Prototype</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Google Tag Manager placeholder: insert GTM container script here when available. -->
<style>
body{font-family:system-ui,-apple-system,Segoe UI,Arial,sans-serif;max-width:720px;margin:40px auto;padding:0 16px;line-height:1.45}
h1{margin:0 0 8px} .card{border:1px solid #ddd;border-radius:12px;padding:16px;margin:16px 0}
label{display:block;margin:10px 0 6px} select,input[type=email],input[type=number]{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px}
.row{display:flex;gap:10px;align-items:center}
.btn{display:inline-block;background:#111;color:#fff;border-radius:10px;padding:10px 14px;text-decoration:none;border:0;cursor:pointer}
.muted{color:#666}
.warn{background:#fff7e6;border:1px solid #ffd591}
.note{background:#eef7ff;border:1px solid #b8defc}
</style>
<script>
(function (w) {
  w.dataLayer = w.dataLayer || [];

  var requestId = (function generateRequestId() {
    if (w.crypto && typeof w.crypto.randomUUID === 'function') {
      try { return w.crypto.randomUUID(); } catch (e) {}
    }
    var random = Math.random().toString(36).slice(2);
    return 'req-' + random + '-' + Date.now().toString(36);
  })();

  w.ffRequestId = requestId;

  function ffPush(eventName, payload) {
    var base = {
      event: eventName,
      ts: new Date().toISOString(),
      request_id: requestId
    };

    if (payload && typeof payload === 'object') {
      for (var key in payload) {
        if (Object.prototype.hasOwnProperty.call(payload, key)) {
          base[key] = payload[key];
        }
      }
    }

    w.dataLayer.push(base);
    return base;
  }

  w.ffPush = ffPush;

  ffPush('page_view', {
    page_name: 'fan_feed_calendar',
    page_type: 'calendar',
    page_path: w.location.pathname + w.location.search,
    tenant_id: null,
    league: <?= json_encode($league) ?>,
    season: <?= json_encode($season) ?>,
    locale: (w.document.documentElement && w.document.documentElement.lang) || 'en',
    theme: (w.matchMedia && w.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light'
  });

  w.addEventListener('DOMContentLoaded', function () {
    var fetchLink = w.document.querySelector('[data-ff-fetch]');
    if (fetchLink) {
      fetchLink.addEventListener('click', function () {
        ffPush('calendar_fetch_requested', {
          league: <?= json_encode($league) ?>,
          season: <?= json_encode($season) ?>
        });
      });
    }

    var icsForm = w.document.querySelector('[data-ff-ics-form]');
    if (icsForm) {
      icsForm.addEventListener('submit', function () {
        var teamSelect = icsForm.querySelector('#team');
        var countInput = icsForm.querySelector('#count');
        var allInput = icsForm.querySelector('#all');

        var teamIdRaw = teamSelect ? teamSelect.value : '';
        var teamId = teamIdRaw ? parseInt(teamIdRaw, 10) : null;
        var allChecked = !!(allInput && allInput.checked);
        var countValue = null;
        if (!allChecked && countInput) {
          var parsedCount = parseInt(countInput.value, 10);
          countValue = isNaN(parsedCount) ? null : parsedCount;
        }

        ffPush('ics_downloaded', {
          team_ids: teamId !== null && !isNaN(teamId) ? [teamId] : [],
          count: countValue,
          all: allChecked
        });
      });
    }
  });
})(window);
</script>
</head>
<body>
<h1>Fan Feed Calendar</h1>
<p class="muted">Manual fetch, then generate an ICS you can import into your calendar.</p>

<div class="card note">
  <strong>Note:</strong> While testing on the free API plan, only <strong>2023</strong> fixture data is available.
</div>

<div class="card">
  <h3>Step 1: Fetch league data</h3>
  <form method="get" action="">
    <label for="season">Season year</label>
    <select id="season" name="season">
      <option value="2023" <?= $season==='2023'?'selected':'' ?>>2023</option>
      <option value="2022" <?= $season==='2022'?'selected':'' ?>>2022</option>
      <option value="2021" <?= $season==='2021'?'selected':'' ?>>2021</option>
    </select>
    <p style="margin-top:10px"><button class="btn" type="submit">Use this season</button></p>
  </form>

  <p>After selecting the season, populate teams and fixtures for that season:</p>
  <p>
    <a class="btn" data-ff-fetch href="admin_fetch.php?league=<?= urlencode($league) ?>&season=<?= urlencode($season) ?>">
      Fetch teams & fixtures (<?= htmlspecialchars($season) ?>)
    </a>
  </p>
  <?php if (!$teams): ?>
    <p class="warn">No teams found for <?= htmlspecialchars($season) ?> yet. Click “Fetch teams & fixtures” above, then reload this page.</p>
  <?php else: ?>
    <p class="muted">Loaded <?= count($teams) ?> teams for <?= htmlspecialchars($season) ?>.</p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Step 2: Generate your calendar file</h3>
  <form method="post" action="make_ics.php" data-ff-ics-form>
    <input type="hidden" name="season" value="<?= htmlspecialchars($season) ?>">
    <input type="hidden" name="league" value="<?= htmlspecialchars($league) ?>">

    <label for="email">Your email</label>
    <input id="email" name="email" type="email" placeholder="you@example.com" required>

    <label for="team">Team</label>
    <select id="team" name="team_id" required>
      <option value="">Select a team...</option>
      <?php foreach ($teams as $t): ?>
        <option value="<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="count">How many upcoming fixtures?</label>
    <input id="count" name="count" type="number" min="1" max="60" value="5">
    <div class="row">
      <input id="all" type="checkbox" name="all" value="1">
      <label for="all" style="margin:0">Add all available fixtures</label>
    </div>

    <div class="row" style="margin-top:8px">
      <input id="consent" type="checkbox" name="consent" value="1" required>
      <label for="consent" style="margin:0">I consent to you generating a one-off calendar file (.ics) with the fixtures I’ve selected.</label>
    </div>

    <p><button class="btn" type="submit">Download my Fan Feed .ics</button></p>
    <p class="muted">Using League <?= htmlspecialchars($league) ?> · Season <?= htmlspecialchars($season) ?>.</p>
  </form>
</div>

</body>
</html>
