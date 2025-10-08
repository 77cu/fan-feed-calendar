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
  --ffc-bg: #f5f6f8;
  --ffc-surface: #ffffff;
  --ffc-border: #d8dce1;
  --ffc-text: #111111;
  --ffc-muted: #5c6270;
  --ffc-link: #0f62fe;
  --ffc-header-bg: rgba(255, 255, 255, 0.9);
  --ffc-footer-bg: rgba(255, 255, 255, 0.95);
  --ffc-radius: 12px;
}

[data-theme="dark"] {
  --ffc-bg: #111418;
  --ffc-surface: #1b1f26;
  --ffc-border: #2e333c;
  --ffc-text: #f5f7fa;
  --ffc-muted: #a6adbb;
  --ffc-link: #6aa6ff;
  --ffc-header-bg: rgba(17, 20, 24, 0.9);
  --ffc-footer-bg: rgba(17, 20, 24, 0.95);
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
  line-height: 1.45;
  background: var(--ffc-bg);
  color: var(--ffc-text);
}

a {
  color: var(--ffc-link);
}

.app-shell {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: var(--ffc-bg);
}

.app-header {
  position: sticky;
  top: 0;
  z-index: 10;
  backdrop-filter: blur(10px);
  background: var(--ffc-header-bg);
  border-bottom: 1px solid var(--ffc-border);
}

.app-header .container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 12px;
  padding-top: 16px;
  padding-bottom: 16px;
}

.app-brand {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
}

.app-header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: auto;
}

.theme-toggle {
  appearance: none;
  background: none;
  border: 1px solid var(--ffc-border);
  border-radius: 999px;
  padding: 6px 14px;
  font: inherit;
  color: inherit;
  cursor: pointer;
  transition: background 0.2s ease;
}

.theme-toggle:hover,
.theme-toggle:focus-visible {
  background: rgba(15, 98, 254, 0.08);
  outline: none;
}

.app-main {
  flex: 1;
  padding: 24px 0 48px;
}

.container {
  width: 100%;
  max-width: 1120px;
  margin: 0 auto;
  padding: 0 16px;
}

.grid,
.row {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.col {
  flex: 1 1 0;
  min-width: 0;
}

.card {
  background: var(--ffc-surface);
  border: 1px solid var(--ffc-border);
  border-radius: var(--ffc-radius);
  padding: 16px;
  margin: 16px 0;
}

h1 {
  margin: 0 0 8px;
}

label {
  display: block;
  margin: 10px 0 6px;
}

select,
input[type=email],
input[type=number] {
  width: 100%;
  padding: 10px;
  border: 1px solid var(--ffc-border);
  border-radius: 8px;
  background: var(--ffc-surface);
  color: inherit;
}

.row {
  align-items: center;
}

.btn {
  display: inline-block;
  background: #111;
  color: #fff;
  border-radius: 10px;
  padding: 10px 14px;
  text-decoration: none;
  border: 0;
  cursor: pointer;
}

[data-theme="dark"] .btn {
  background: #f5f7fa;
  color: #111418;
}

.muted {
  color: var(--ffc-muted);
}

.warn {
  background: #fff7e6;
  border: 1px solid #ffd591;
  color: #8a5d1e;
}

[data-theme="dark"] .warn {
  background: rgba(255, 188, 87, 0.15);
  border-color: rgba(255, 188, 87, 0.4);
  color: #ffddab;
}

.note {
  background: #eef7ff;
  border: 1px solid #b8defc;
}

[data-theme="dark"] .note {
  background: rgba(106, 166, 255, 0.15);
  border-color: rgba(106, 166, 255, 0.35);
}

.app-footer {
  background: var(--ffc-footer-bg);
  border-top: 1px solid var(--ffc-border);
  padding: 24px 0;
  color: var(--ffc-muted);
}

.app-footer nav {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  font-size: 0.9rem;
}

.app-footer a {
  color: inherit;
  text-decoration: none;
}

.app-footer a:hover,
.app-footer a:focus-visible {
  text-decoration: underline;
}

@media (min-width: 720px) {
  .app-main {
    padding-top: 40px;
  }
}
</style>
</head>
<body>
<div class="app-shell">
  <header class="app-header" role="banner">
    <div class="container">
      <p class="app-brand">Fan Feed Calendar</p>
      <div class="app-header-actions">
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle theme">Toggle theme</button>
      </div>
    </div>
  </header>

  <main class="app-main" role="main">
    <div class="container">
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
          <a class="btn" href="admin_fetch.php?league=<?= urlencode($league) ?>&season=<?= urlencode($season) ?>">
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
    </div>
  </main>

  <footer class="app-footer" role="contentinfo">
    <div class="container">
      <nav aria-label="Helpful links">
        <a href="#">Privacy</a>
        <a href="#">Cookies</a>
        <a href="#">Help</a>
      </nav>
    </div>
  </footer>
</div>

<script>
(function () {
  const storageKey = 'ffc-theme';
  const root = document.documentElement;
  const toggle = document.querySelector('[data-theme-toggle]');
  let storage;

  try {
    storage = window.localStorage;
  } catch (err) {
    storage = null;
  }

  const applyTheme = (theme) => {
    root.setAttribute('data-theme', theme);
    if (toggle) {
      toggle.setAttribute('aria-pressed', theme === 'dark');
      toggle.textContent = theme === 'dark' ? 'Use light theme' : 'Use dark theme';
    }
  };

  const getPreferredTheme = () => {
    const stored = storage ? storage.getItem(storageKey) : null;
    if (stored === 'light' || stored === 'dark') {
      return stored;
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  };

  applyTheme(getPreferredTheme());

  if (toggle) {
    toggle.addEventListener('click', () => {
      const current = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      if (storage) {
        storage.setItem(storageKey, current);
      }
      applyTheme(current);
    });
  }
})();
</script>
</body>
</html>
