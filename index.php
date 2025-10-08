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
<style>
:root {
  color-scheme: light dark;
  --bg: #f5f5f7;
  --surface: #ffffff;
  --surface-muted: #f8fafc;
  --border: #d0d5dd;
  --text-primary: #111827;
  --text-muted: #667085;
  --accent: #2563eb;
  --accent-text: #ffffff;
  --warn-bg: #fff7e6;
  --warn-border: #ffd591;
  --note-bg: #eff8ff;
  --note-border: #b2ddff;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg: #0f172a;
    --surface: #1e293b;
    --surface-muted: #111827;
    --border: #334155;
    --text-primary: #f8fafc;
    --text-muted: #cbd5f5;
    --accent: #60a5fa;
    --accent-text: #0f172a;
    --warn-bg: rgba(249, 115, 22, 0.18);
    --warn-border: rgba(249, 115, 22, 0.6);
    --note-bg: rgba(96, 165, 250, 0.2);
    --note-border: rgba(96, 165, 250, 0.6);
  }
}

* { box-sizing: border-box; }
a {
  color: var(--accent);
}

a:hover,
a:focus {
  text-decoration: underline;
}
body {
  margin: 0;
  font-family: system-ui, -apple-system, Segoe UI, Arial, sans-serif;
  background: var(--bg);
  color: var(--text-primary);
  line-height: 1.5;
}

.site-header {
  background: var(--surface);
  border-bottom: 1px solid var(--border);
}

.site-header-inner {
  max-width: 960px;
  margin: 0 auto;
  padding: 20px;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.site-title {
  font-weight: 600;
  font-size: 1.1rem;
}

.site-nav {
  display: flex;
  gap: 12px;
}

.site-nav a {
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 500;
  padding: 6px 10px;
  border-radius: 8px;
}

.site-nav a:hover,
.site-nav a:focus {
  color: var(--accent);
  background: var(--surface-muted);
  outline: none;
  text-decoration: none;
}

.site-nav a[aria-current="page"] {
  color: var(--accent);
  background: var(--surface-muted);
}

main {
  max-width: 960px;
  margin: 0 auto;
  padding: 32px 20px 64px;
}

h1 {
  margin: 0 0 12px;
  font-size: 2rem;
}

p {
  margin: 0 0 12px;
}

.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 20px;
  margin: 20px 0;
}

label {
  display: block;
  margin: 12px 0 6px;
  font-weight: 500;
}

select,
input[type=email],
input[type=number] {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--surface-muted);
  color: inherit;
}

select:focus,
input:focus {
  outline: 2px solid var(--accent);
  outline-offset: 2px;
}

.row {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: var(--accent);
  color: var(--accent-text);
  border-radius: 10px;
  padding: 10px 16px;
  text-decoration: none;
  border: 0;
  cursor: pointer;
  font-weight: 600;
}

.btn:hover,
.btn:focus {
  opacity: 0.9;
}

.muted {
  color: var(--text-muted);
}

.warn {
  background: var(--warn-bg);
  border: 1px solid var(--warn-border);
  border-radius: 12px;
  padding: 12px 16px;
}

.note {
  background: var(--note-bg);
  border: 1px solid var(--note-border);
  border-radius: 12px;
  padding: 12px 16px;
}
</style>
</head>
<body>
<header class="site-header">
  <div class="site-header-inner">
    <div class="site-title">Fan Feed Calendar</div>
    <nav class="site-nav">
      <a href="index.php" aria-current="page">Home</a>
      <a href="integrations.php">Integrations</a>
    </nav>
  </div>
</header>
<main>
  <h1>Generate a Fan Feed calendar</h1>
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
      <a class="btn" href="admin_fetch.php?league=<?= urlencode($league) ?>&season=<?= urlencode($season) ?>">
        Fetch teams &amp; fixtures (<?= htmlspecialchars($season) ?>)
      </a>
    </p>
    <?php if (!$teams): ?>
      <p class="warn">No teams found for <?= htmlspecialchars($season) ?> yet. Click “Fetch teams &amp; fixtures” above, then reload this page.</p>
    <?php else: ?>
      <p class="muted">Loaded <?= count($teams) ?> teams for <?= htmlspecialchars($season) ?>.</p>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>Step 2: Generate your calendar file</h3>
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
      <p class="muted">Using League <?= htmlspecialchars($league) ?> · Season <?= htmlspecialchars($season) ?>.</p>
    </form>
  </div>
</main>
</body>
</html>
