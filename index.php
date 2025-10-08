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
<script src="assets/theme.js"></script>
<style>
:root {
    --ff-font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    --ff-radius-lg: 16px;
    --ff-radius-md: 12px;
    --ff-space-1: 0.5rem;
    --ff-space-2: 0.75rem;
    --ff-space-3: 1rem;
    --ff-space-4: 1.5rem;
    --ff-space-5: 2rem;
    --ff-width-content: min(720px, 100%);
    --ff-color-bg: #f8fafc;
    --ff-color-surface: #ffffff;
    --ff-color-surface-alt: #f1f5f9;
    --ff-color-border: #d0d7de;
    --ff-color-text: #0f172a;
    --ff-color-muted: #64748b;
    --ff-color-primary: #2563eb;
    --ff-color-primary-text: #ffffff;
    --ff-color-note-bg: #e0f2fe;
    --ff-color-note-border: #82c4f8;
    --ff-color-warn-bg: #fff7e6;
    --ff-color-warn-border: #ffd591;
    --ff-shadow-card: 0 2px 8px rgba(15, 23, 42, 0.08);
}

[data-theme="dark"] {
    --ff-color-bg: #0b1220;
    --ff-color-surface: #111b2b;
    --ff-color-surface-alt: rgba(255, 255, 255, 0.04);
    --ff-color-border: rgba(148, 163, 184, 0.35);
    --ff-color-text: #e2e8f0;
    --ff-color-muted: #94a3b8;
    --ff-color-primary: #38bdf8;
    --ff-color-primary-text: #0f172a;
    --ff-color-note-bg: rgba(56, 189, 248, 0.16);
    --ff-color-note-border: rgba(56, 189, 248, 0.55);
    --ff-color-warn-bg: rgba(251, 191, 36, 0.12);
    --ff-color-warn-border: rgba(251, 191, 36, 0.45);
    --ff-shadow-card: 0 2px 12px rgba(15, 23, 42, 0.4);
}

html,
body {
    background: var(--ff-color-bg);
    color: var(--ff-color-text);
    font-family: var(--ff-font-family);
    line-height: 1.5;
    margin: 0;
    min-height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: var(--ff-space-4) var(--ff-space-3);
}

main {
    width: var(--ff-width-content);
    max-width: 100%;
}

.page-header {
    width: var(--ff-width-content);
    max-width: 100%;
    margin-bottom: var(--ff-space-3);
}

.page-header h1 {
    margin: 0 0 var(--ff-space-1);
    font-size: clamp(1.75rem, 5vw, 2.25rem);
}

.page-header p {
    margin: 0;
    color: var(--ff-color-muted);
}

.card {
    background: var(--ff-color-surface);
    border-radius: var(--ff-radius-lg);
    border: 1px solid var(--ff-color-border);
    padding: var(--ff-space-3);
    margin: var(--ff-space-3) 0;
    box-shadow: var(--ff-shadow-card);
}

.card h3 {
    margin: 0 0 var(--ff-space-2);
    font-size: 1.1rem;
}

label {
    display: block;
    margin: var(--ff-space-2) 0 var(--ff-space-1);
    font-weight: 600;
}

select,
input[type=email],
input[type=number] {
    width: 100%;
    padding: 0.75rem 0.85rem;
    border-radius: var(--ff-radius-md);
    border: 1px solid var(--ff-color-border);
    background: var(--ff-color-surface);
    color: var(--ff-color-text);
    font-size: 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

select:focus,
input[type=email]:focus,
input[type=number]:focus {
    outline: none;
    border-color: var(--ff-color-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
}

.row {
    display: flex;
    gap: var(--ff-space-2);
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--ff-color-primary);
    color: var(--ff-color-primary-text);
    border-radius: var(--ff-radius-md);
    padding: 0.75rem 1rem;
    text-decoration: none;
    border: 0;
    cursor: pointer;
    font-weight: 600;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover,
.btn:focus-visible {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
}

.muted {
    color: var(--ff-color-muted);
}

.warn {
    background: var(--ff-color-warn-bg);
    border: 1px solid var(--ff-color-warn-border);
    border-radius: var(--ff-radius-md);
    padding: var(--ff-space-2);
    margin-top: var(--ff-space-2);
}

.note {
    background: var(--ff-color-note-bg);
    border: 1px solid var(--ff-color-note-border);
    border-radius: var(--ff-radius-md);
    padding: var(--ff-space-2);
}

.nav {
    display: flex;
    gap: var(--ff-space-2);
    margin-bottom: var(--ff-space-2);
}

.nav a {
    color: var(--ff-color-muted);
    text-decoration: none;
    font-weight: 600;
    padding: 0.4rem 0.75rem;
    border-radius: var(--ff-radius-md);
    transition: background 0.2s ease, color 0.2s ease;
}

.nav a:hover,
.nav a:focus-visible {
    background: rgba(37, 99, 235, 0.12);
    color: var(--ff-color-text);
}

.checkbox-row {
    align-items: flex-start;
}

.checkbox-row label {
    margin: 0;
}

@media (min-width: 768px) {
    body {
        padding-top: var(--ff-space-5);
    }
}
</style>
</head>
<body>
<header class="page-header">
  <nav class="nav" aria-label="Primary">
    <a href="index.php" aria-current="page">Home</a>
    <a href="settings.php">Settings</a>
  </nav>
  <h1>Fan Feed Calendar</h1>
  <p class="muted">Manual fetch, then generate an ICS you can import into your calendar.</p>
</header>
<main>

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
    <p style="margin-top: var(--ff-space-2); margin-bottom: 0;">
      <button class="btn" type="submit">Use this season</button>
    </p>
  </form>

  <p>After selecting the season, populate teams and fixtures for that season:</p>
  <p style="margin-bottom: 0;">
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
    <div class="row checkbox-row">
      <input id="all" type="checkbox" name="all" value="1">
      <label for="all">Add all available fixtures</label>
    </div>

    <div class="row checkbox-row" style="margin-top: var(--ff-space-2);">
      <input id="consent" type="checkbox" name="consent" value="1" required>
      <label for="consent">I consent to you generating a one-off calendar file (.ics) with the fixtures I’ve selected.</label>
    </div>

    <p style="margin-top: var(--ff-space-3); margin-bottom: var(--ff-space-1);">
      <button class="btn" type="submit">Download my Fan Feed .ics</button>
    </p>
    <p class="muted" style="margin-top: 0;">Using League <?= htmlspecialchars($league) ?> · Season <?= htmlspecialchars($season) ?>.</p>
  </form>
</div>

</main>

</body>
</html>
