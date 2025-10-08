<?php
// index.php — Fan Feed Calendar (season-select prototype)
declare(strict_types=1);
error_reporting(E_ALL);

$c = require __DIR__ . '/config.php';

$seasonOptions = $c['seasons_to_fetch'] ?? [$c['default_season'] ?? '2023'];
$seasonOptions = array_values(array_map('strval', $seasonOptions));
if (!$seasonOptions) {
    $seasonOptions = [$c['default_season'] ?? '2023'];
}

$requestedSeason = isset($_GET['season']) && preg_match('/^\d{4}$/', (string)$_GET['season'])
    ? (string)$_GET['season']
    : '';
$season = in_array($requestedSeason, $seasonOptions, true)
    ? $requestedSeason
    : $seasonOptions[0];

$leaguesFile = $c['fixtures_dir'] . "/leagues_{$season}.json";
$leagues = [];
if (is_file($leaguesFile)) {
    $data = json_decode(file_get_contents($leaguesFile), true);
    if (is_array($data)) {
        $leagues = $data['leagues'] ?? [];
    }
}

$leagueOptions = array_map(static function ($l) {
    return [
        'id' => (string)($l['id'] ?? ''),
        'label' => trim((string)($l['country'] ?? '') !== ''
            ? ($l['country'] . ' — ' . ($l['name'] ?? ''))
            : ($l['name'] ?? '')
        ),
        'name' => (string)($l['name'] ?? ''),
    ];
}, $leagues);

$requestedLeague = isset($_GET['league']) && preg_match('/^\d+$/', (string)$_GET['league'])
    ? (string)$_GET['league']
    : '';

$leagueIds = array_column($leagueOptions, 'id');
$league = in_array($requestedLeague, $leagueIds, true)
    ? $requestedLeague
    : (($c['default_league'] ?? '') !== '' && in_array((string)$c['default_league'], $leagueIds, true)
        ? (string)$c['default_league']
        : ($leagueIds[0] ?? '')
    );

$selectedLeague = null;
foreach ($leagueOptions as $opt) {
    if ($opt['id'] === $league) {
        $selectedLeague = $opt;
        break;
    }
}

$teams = [];
$generatedAt = null;
if ($league !== '') {
    $teamsFile = $c['fixtures_dir'] . "/teams_{$league}_{$season}.json";
    if (is_file($teamsFile)) {
        $j = json_decode(file_get_contents($teamsFile), true);
        if (is_array($j)) {
            $teams = $j['teams'] ?? [];
            $generatedAt = $j['generated_at'] ?? null;
        }
    }
    if ($generatedAt === null) {
        $fixturesFile = $c['fixtures_dir'] . "/fixtures_{$league}_{$season}.json";
        if (is_file($fixturesFile)) {
            $f = json_decode(file_get_contents($fixturesFile), true);
            if (is_array($f)) {
                $generatedAt = $f['generated_at'] ?? null;
            }
        }
    }
}

if ($generatedAt) {
    $generatedTs = strtotime((string)$generatedAt);
    $generatedLabel = $generatedTs ? gmdate('j M Y H:i \U\T\C', $generatedTs) : $generatedAt;
} else {
    $generatedLabel = null;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Prototype</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>
<h1>Fan Feed Calendar</h1>
<p class="muted">Data refreshes daily in the background. Generate an ICS you can import into your calendar.</p>

<div class="card note">
  <strong>Note:</strong> While testing on the free API plan, only <strong>2023</strong> fixture data is available.
</div>

<div class="card">
  <h3>Select a season &amp; league</h3>
  <form method="get" action="">
    <label for="season">Season year</label>
    <select id="season" name="season">
      <?php foreach ($seasonOptions as $optSeason): ?>
        <option value="<?= htmlspecialchars($optSeason) ?>" <?= $season===$optSeason?'selected':'' ?>><?= htmlspecialchars($optSeason) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="league">League</label>
    <select id="league" name="league">
      <?php if ($leagueOptions): ?>
        <?php foreach ($leagueOptions as $opt): ?>
          <option value="<?= htmlspecialchars($opt['id']) ?>" <?= $opt['id']===$league?'selected':'' ?>><?= htmlspecialchars($opt['label']) ?></option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="">No leagues available – run cron_fetch.php</option>
      <?php endif; ?>
    </select>

    <p style="margin-top:10px"><button class="btn" type="submit">Update selection</button></p>
  </form>

  <?php if ($selectedLeague): ?>
    <p class="muted">Current league: <?= htmlspecialchars($selectedLeague['name'] ?: $selectedLeague['label']) ?>.</p>
  <?php endif; ?>
  <?php if ($generatedLabel): ?>
    <p class="muted">Data last refreshed <?= htmlspecialchars($generatedLabel) ?>.</p>
  <?php else: ?>
    <p class="warn">We haven’t fetched data for this league and season yet. Ensure <code>cron_fetch.php</code> runs daily.</p>
  <?php endif; ?>
  <?php if ($teams): ?>
    <p class="muted">Loaded <?= count($teams) ?> teams for <?= htmlspecialchars($season) ?>.</p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Generate your calendar file</h3>
  <form method="post" action="make_ics.php">
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
    <p class="muted">Using League <?= htmlspecialchars($selectedLeague['name'] ?? $league) ?> · Season <?= htmlspecialchars($season) ?>.</p>
  </form>
</div>

</body>
</html>
