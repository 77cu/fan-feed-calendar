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
  --ff-color-bg: #f5f6f8;
  --ff-color-surface: #ffffff;
  --ff-color-border: #d0d7de;
  --ff-color-text: #1f2328;
  --ff-color-text-muted: #5c6670;
  --ff-color-accent: #1253c0;
  --ff-color-accent-contrast: #ffffff;
  --ff-color-input-bg: #ffffff;
  --ff-color-input-border: #c3cad4;
  --ff-color-note-bg: #e8f2ff;
  --ff-color-note-border: #9cc4ff;
  --ff-color-warn-bg: #fff4e5;
  --ff-color-warn-border: #f0b429;
  --ff-radius-card: 12px;
  --ff-radius-input: 8px;
  --ff-radius-pill: 999px;
  --ff-shadow-card: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.theme-dark {
  --ff-color-bg: #0f172a;
  --ff-color-surface: #1e293b;
  --ff-color-border: #2e3a4f;
  --ff-color-text: #f8fafc;
  --ff-color-text-muted: #cbd5f5;
  --ff-color-accent: #4f83ff;
  --ff-color-accent-contrast: #0b1120;
  --ff-color-input-bg: #101a2b;
  --ff-color-input-border: #34415b;
  --ff-color-note-bg: #1d3a75;
  --ff-color-note-border: #3e63c3;
  --ff-color-warn-bg: #3b2a12;
  --ff-color-warn-border: #f6ad55;
  --ff-shadow-card: 0 1px 2px rgba(2, 6, 23, 0.4);
}

body {
  font-family: system-ui, -apple-system, Segoe UI, Arial, sans-serif;
  max-width: 720px;
  margin: 40px auto;
  padding: 0 16px 48px;
  line-height: 1.45;
  background: var(--ff-color-bg);
  color: var(--ff-color-text);
  transition: background 0.25s ease, color 0.25s ease;
  color-scheme: light;
}

body.theme-dark {
  color-scheme: dark;
}

.page-header {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 12px;
}

.theme-toggle {
  display: inline-flex;
  gap: 6px;
  padding: 4px;
  border-radius: var(--ff-radius-pill);
  background: var(--ff-color-surface);
  background: color-mix(in srgb, var(--ff-color-surface) 75%, transparent);
  border: 1px solid var(--ff-color-border);
}

.theme-toggle button {
  font: inherit;
  background: transparent;
  border: 0;
  padding: 4px 10px;
  border-radius: var(--ff-radius-pill);
  color: var(--ff-color-text-muted);
  cursor: pointer;
  transition: background 0.2s ease, color 0.2s ease;
}

.theme-toggle button:hover,
.theme-toggle button:focus-visible {
  background: rgba(79, 131, 255, 0.16);
  background: color-mix(in srgb, var(--ff-color-accent) 15%, transparent);
  color: var(--ff-color-text);
  outline: none;
}

.theme-toggle button.active {
  background: var(--ff-color-accent);
  color: var(--ff-color-accent-contrast);
  font-weight: 600;
}

h1 {
  margin: 0 0 8px;
}

.card {
  background: var(--ff-color-surface);
  border: 1px solid var(--ff-color-border);
  border-radius: var(--ff-radius-card);
  padding: 16px;
  margin: 16px 0;
  box-shadow: var(--ff-shadow-card);
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
  border: 1px solid var(--ff-color-input-border);
  border-radius: var(--ff-radius-input);
  background: var(--ff-color-input-bg);
  color: var(--ff-color-text);
  transition: border-color 0.2s ease, background 0.2s ease;
}

select:focus,
input[type=email]:focus,
input[type=number]:focus {
  border-color: var(--ff-color-accent);
  outline: none;
  box-shadow: 0 0 0 3px rgba(79, 131, 255, 0.25);
  box-shadow: 0 0 0 3px color-mix(in srgb, var(--ff-color-accent) 25%, transparent);
}

.row {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn {
  display: inline-block;
  background: var(--ff-color-accent);
  color: var(--ff-color-accent-contrast);
  border-radius: 10px;
  padding: 10px 14px;
  text-decoration: none;
  border: 0;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.15s ease;
}

.btn:hover,
.btn:focus-visible {
  background: #2a64d4;
  background: color-mix(in srgb, var(--ff-color-accent) 85%, #ffffff 15%);
  outline: none;
}

.btn:active {
  transform: translateY(1px);
}

.muted {
  color: var(--ff-color-text-muted);
}

.warn {
  background: var(--ff-color-warn-bg);
  border: 1px solid var(--ff-color-warn-border);
  border-radius: var(--ff-radius-card);
}

.note {
  background: var(--ff-color-note-bg);
  border: 1px solid var(--ff-color-note-border);
  border-radius: var(--ff-radius-card);
}

@media (max-width: 600px) {
  body {
    margin: 24px auto;
    padding: 0 16px 40px;
  }

  .theme-toggle {
    font-size: 0.9rem;
  }
}
</style>
</head>
<body>
<header class="page-header">
  <div class="theme-toggle" role="group" aria-label="Theme toggle">
    <button type="button" data-theme-option="light">Light</button>
    <button type="button" data-theme-option="dark">Dark</button>
    <button type="button" data-theme-option="auto">Auto</button>
  </div>
</header>
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

<script>
(function () {
  const STORAGE_KEY = 'fanfeed_theme';
  const body = document.body;
  const buttons = document.querySelectorAll('[data-theme-option]');

  const valid = new Set(['light', 'dark', 'auto']);

  function safeGet(key) {
    try {
      return window.localStorage.getItem(key);
    } catch (err) {
      return null;
    }
  }

  function safeSet(key, value) {
    try {
      window.localStorage.setItem(key, value);
    } catch (err) {
      /* noop */
    }
  }

  function systemPrefersDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function resolveTheme(mode) {
    if (mode === 'dark' || mode === 'light') {
      return mode;
    }
    return systemPrefersDark() ? 'dark' : 'light';
  }

  function applyMode(mode) {
    const theme = resolveTheme(mode);
    body.classList.toggle('theme-dark', theme === 'dark');
    buttons.forEach((btn) => {
      btn.classList.toggle('active', btn.dataset.themeOption === mode);
      btn.setAttribute('aria-pressed', btn.dataset.themeOption === mode ? 'true' : 'false');
    });
  }

  function readStored() {
    const saved = safeGet(STORAGE_KEY);
    return valid.has(saved) ? saved : 'auto';
  }

  let currentMode = readStored();
  applyMode(currentMode);

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const mode = btn.dataset.themeOption;
      if (!valid.has(mode)) {
        return;
      }
      currentMode = mode;
      safeSet(STORAGE_KEY, mode);
      applyMode(mode);
    });
  });

  if (window.matchMedia) {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const handle = () => {
      if (currentMode === 'auto') {
        applyMode('auto');
      }
    };
    if (typeof mq.addEventListener === 'function') {
      mq.addEventListener('change', handle);
    } else if (typeof mq.addListener === 'function') {
      mq.addListener(handle);
    }
  }
})();
</script>
</body>
</html>
