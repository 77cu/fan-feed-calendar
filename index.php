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
<html lang="en" data-theme="light">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Prototype</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Google Tag Manager placeholder -->
<style>
:root {
  color-scheme: light;
  --bg-base: #0e1320;
  --bg-elevated: #12192a;
  --surface: #ffffff;
  --surface-muted: #f3f6fb;
  --border-soft: rgba(15, 23, 42, 0.12);
  --border-strong: rgba(15, 23, 42, 0.18);
  --text-primary: #101828;
  --text-secondary: #475467;
  --text-inverse: #f8fafc;
  --accent: #2f62ff;
  --accent-strong: #1031c6;
  --shadow-lg: 0 24px 60px rgba(15, 23, 42, 0.16);
  --radius-lg: 24px;
  --radius-md: 16px;
  --radius-sm: 12px;
  --space-xs: 0.5rem;
  --space-sm: 0.75rem;
  --space-md: 1rem;
  --space-lg: 1.5rem;
  --space-xl: 2rem;
  --container-max: 1200px;
  font-family: "Inter", "SF Pro Text", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

[data-theme="dark"] {
  color-scheme: dark;
  --bg-base: #05070d;
  --bg-elevated: #070b15;
  --surface: #0f172a;
  --surface-muted: #111c34;
  --border-soft: rgba(148, 163, 184, 0.24);
  --border-strong: rgba(148, 163, 184, 0.32);
  --text-primary: #e2e8f0;
  --text-secondary: #cbd5f5;
  --text-inverse: #0b1120;
  --accent: #93b4ff;
  --accent-strong: #dbe4ff;
  --shadow-lg: 0 24px 70px rgba(15, 23, 42, 0.55);
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  min-height: 100vh;
  background: linear-gradient(135deg, rgba(15,23,42,.92), rgba(2,6,23,0.88)), var(--bg-base);
  color: var(--text-primary);
  font-family: inherit;
  display: flex;
  flex-direction: column;
}

a {
  color: inherit;
}

a:hover,
a:focus {
  color: var(--accent);
}

:focus-visible {
  outline: 3px solid var(--accent);
  outline-offset: 3px;
}

.app-header {
  position: sticky;
  top: 0;
  z-index: 20;
  backdrop-filter: blur(14px);
  background: rgba(5, 11, 26, 0.85);
  color: var(--text-inverse);
  border-bottom: 1px solid rgba(148, 163, 184, 0.18);
}

.app-header .shell,
.app-main .shell,
.app-footer .shell {
  max-width: var(--container-max);
  margin: 0 auto;
  padding: var(--space-md) var(--space-lg);
}

.top-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-lg);
}

.brand {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  text-decoration: none;
}

.brand-mark {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
  display: grid;
  place-items: center;
  color: #fff;
  font-weight: 700;
  letter-spacing: 0.5px;
  box-shadow: 0 18px 40px rgba(37, 99, 235, 0.35);
}

.brand-text {
  display: flex;
  flex-direction: column;
  font-weight: 600;
  line-height: 1.2;
}

.brand-text span {
  font-size: 0.75rem;
  font-weight: 500;
  opacity: 0.8;
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
}

.nav-btn {
  appearance: none;
  border: 1px solid rgba(148, 163, 184, 0.38);
  background: rgba(15, 23, 42, 0.35);
  color: var(--text-inverse);
  border-radius: 999px;
  padding: 0.4rem 0.95rem;
  font-size: 0.875rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: background 0.3s ease, border 0.3s ease, transform 0.3s ease;
}

.nav-btn svg {
  width: 18px;
  height: 18px;
}

.nav-btn:hover {
  background: rgba(37, 99, 235, 0.3);
  border-color: rgba(37, 99, 235, 0.6);
}

.nav-btn:active {
  transform: translateY(1px);
}

.app-main {
  flex: 1;
  width: 100%;
  color: var(--text-primary);
}

.hero {
  margin-top: clamp(2rem, 8vw, 5rem);
  margin-bottom: clamp(2rem, 6vw, 4rem);
  color: var(--text-inverse);
}

.hero-card {
  background: linear-gradient(135deg, rgba(37,99,235,0.95), rgba(59,130,246,0.9));
  border-radius: var(--radius-lg);
  padding: clamp(1.75rem, 5vw, 2.75rem);
  box-shadow: var(--shadow-lg);
  display: grid;
  gap: var(--space-md);
}

.hero-card h1 {
  margin: 0;
  font-size: clamp(2rem, 6vw, 2.8rem);
  font-weight: 700;
}

.hero-card p {
  margin: 0;
  font-size: 1.05rem;
  line-height: 1.6;
  max-width: 46ch;
}

.layout-centered {
  max-width: min(var(--container-max), 960px);
  margin: 0 auto;
}

.layout-full {
  max-width: var(--container-max);
  margin: 0 auto;
}

.layout-two-col {
  display: grid;
  gap: var(--space-xl);
}

@media (min-width: 960px) {
  .layout-two-col {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
  }
}

.grid {
  display: grid;
}

.gap-sm {
  gap: var(--space-sm);
}

.gap-md {
  gap: var(--space-md);
}

.gap-lg {
  gap: var(--space-lg);
}

.gap-xl {
  gap: var(--space-xl);
}

.sm\:grid-cols-2 {
  grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 640px) {
  .sm\:grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.md\:grid-cols-2 {
  grid-template-columns: repeat(1, minmax(0, 1fr));
}

@media (min-width: 768px) {
  .md\:grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.card {
  background: var(--surface);
  border-radius: var(--radius-lg);
  padding: clamp(1.5rem, 4vw, 2.25rem);
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--border-soft);
  display: grid;
  gap: var(--space-md);
}

.card h2 {
  margin: 0;
  font-size: 1.4rem;
}

.card h3 {
  margin: 0;
  font-size: 1.15rem;
}

.card p {
  margin: 0;
  color: var(--text-secondary);
  line-height: 1.6;
}

.card-note {
  background: var(--surface-muted);
  border-color: rgba(59, 130, 246, 0.25);
  color: var(--text-primary);
}

.card-note strong {
  color: var(--text-primary);
}

.card-warn {
  border-color: rgba(249, 115, 22, 0.35);
  background: rgba(251, 191, 36, 0.12);
  color: var(--text-primary);
}

.form-grid {
  display: grid;
  gap: var(--space-md);
}

label {
  font-weight: 600;
  color: var(--text-primary);
}

input[type="email"],
input[type="number"],
select {
  appearance: none;
  width: 100%;
  padding: 0.75rem 0.9rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border-strong);
  background: var(--surface-muted);
  color: var(--text-primary);
  font-size: 1rem;
  transition: border 0.3s ease, box-shadow 0.3s ease, background 0.3s ease;
}

input[type="email"]:focus,
input[type="number"]:focus,
select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 4px rgba(47, 98, 255, 0.18);
  background: #fff;
}

input[type="checkbox"] {
  width: 20px;
  height: 20px;
  border-radius: 6px;
  border: 1px solid var(--border-strong);
  appearance: none;
  display: grid;
  place-items: center;
  background: var(--surface);
  transition: background 0.3s ease, border 0.3s ease;
}

input[type="checkbox"]:checked {
  background: var(--accent);
  border-color: transparent;
}

input[type="checkbox"]:checked::after {
  content: '';
  width: 8px;
  height: 12px;
  border: 2px solid var(--text-inverse);
  border-top: 0;
  border-left: 0;
  transform: rotate(45deg);
}

.checkbox-row {
  display: flex;
  gap: var(--space-sm);
  align-items: flex-start;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.85rem 1.4rem;
  border-radius: 999px;
  border: none;
  cursor: pointer;
  font-weight: 600;
  font-size: 1rem;
  text-decoration: none;
  transition: transform 0.2s ease, box-shadow 0.3s ease, background 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #2563eb, #4f46e5);
  color: #fff;
  box-shadow: 0 18px 40px rgba(37, 99, 235, 0.35);
}

.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 24px 50px rgba(37, 99, 235, 0.45);
}

.btn-primary:active {
  transform: translateY(1px);
}

.btn-secondary {
  background: rgba(15, 23, 42, 0.12);
  color: var(--text-primary);
  border: 1px solid var(--border-strong);
}

.btn-secondary:hover {
  background: rgba(37, 99, 235, 0.12);
  border-color: rgba(37, 99, 235, 0.4);
}

.secondary-text {
  color: var(--text-secondary);
  font-size: 0.95rem;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.35rem 0.75rem;
  font-size: 0.75rem;
  border-radius: 999px;
  background: rgba(148, 163, 184, 0.12);
  color: var(--text-secondary);
}

.app-footer {
  color: rgba(226, 232, 240, 0.9);
  background: rgba(5, 11, 26, 0.92);
  border-top: 1px solid rgba(148, 163, 184, 0.18);
}

.app-footer small {
  font-size: 0.8rem;
  color: rgba(226, 232, 240, 0.8);
}

@media (max-width: 640px) {
  .app-header .shell,
  .app-main .shell,
  .app-footer .shell {
    padding: var(--space-md);
  }

  .top-nav {
    flex-direction: column;
    align-items: stretch;
  }

  .nav-actions {
    justify-content: space-between;
  }

  .card {
    padding: var(--space-lg);
  }
}
</style>
</head>
<body>
<header class="app-header">
  <div class="shell">
    <nav class="top-nav" aria-label="Primary">
      <a class="brand" href="#">
        <span class="brand-mark" aria-hidden="true">FF</span>
        <span class="brand-text">
          Fan Feed
          <span>Calendar Prototype</span>
        </span>
      </a>
      <div class="nav-actions">
        <button class="nav-btn" type="button" data-action="toggle-theme">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79Z" />
          </svg>
          <span class="theme-label">Light</span>
        </button>
        <button class="nav-btn" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
          </svg>
          Account
        </button>
      </div>
    </nav>
  </div>
</header>
<main class="app-main">
  <div class="shell layout-centered">
    <section class="hero">
      <div class="hero-card">
        <span class="badge">Prototype access</span>
        <h1>Download fixtures tailored to your fandom</h1>
        <p>Fetch official league fixtures, fine-tune your favourite team selection and generate a premium .ics calendar that keeps fans in sync across every device.</p>
      </div>
    </section>

    <section class="layout-two-col">
      <article class="card card-note">
        <h2>Before you start</h2>
        <p><strong>Season data is limited to 2023</strong> while we’re working with the free-tier API. Once your admin fetch completes, you can return here to craft a bespoke calendar feed.</p>
      </article>

      <article class="card">
        <h2>Snapshot</h2>
        <div class="form-grid">
          <div>
            <span class="secondary-text">League</span>
            <p><strong>#<?= htmlspecialchars($league) ?></strong></p>
          </div>
          <div>
            <span class="secondary-text">Current season</span>
            <p><strong><?= htmlspecialchars($season) ?></strong></p>
          </div>
          <div>
            <span class="secondary-text">Teams loaded</span>
            <p><strong><?= $teams ? count($teams) : 0 ?></strong></p>
          </div>
        </div>
      </article>
    </section>

    <section class="layout-two-col" style="margin-top: var(--space-xl);">
      <article class="card">
        <h3>Step 1 · Fetch league data</h3>
        <p>Choose a season to pull in the latest league, team and fixture information.</p>
        <form class="form-grid" method="get" action="">
          <label for="season">Season year</label>
          <select id="season" name="season">
            <option value="2023" <?= $season==='2023'?'selected':'' ?>>2023</option>
            <option value="2022" <?= $season==='2022'?'selected':'' ?>>2022</option>
            <option value="2021" <?= $season==='2021'?'selected':'' ?>>2021</option>
          </select>
          <p><button class="btn btn-secondary" type="submit">Use this season</button></p>
        </form>
        <p class="secondary-text">After selecting a season, populate teams and fixtures with the admin fetch.</p>
        <p>
          <a class="btn btn-primary" href="admin_fetch.php?league=<?= urlencode($league) ?>&season=<?= urlencode($season) ?>">
            Fetch teams &amp; fixtures (<?= htmlspecialchars($season) ?>)
          </a>
        </p>
        <?php if (!$teams): ?>
          <p class="card-warn" style="padding: var(--space-md); border-radius: var(--radius-md);">
            No teams found for <?= htmlspecialchars($season) ?> yet. Run the fetch above, then reload this page.
          </p>
        <?php else: ?>
          <p class="secondary-text">Loaded <?= count($teams) ?> teams for <?= htmlspecialchars($season) ?>.</p>
        <?php endif; ?>
      </article>

      <article class="card">
        <h3>Step 2 · Generate your calendar file</h3>
        <p>Tailor the feed to your inbox and fandom. We’ll build a one-off .ics file for your preferred fixtures.</p>
        <form class="form-grid" method="post" action="make_ics.php">
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

          <div class="checkbox-row">
            <input id="all" type="checkbox" name="all" value="1">
            <label for="all">Add all available fixtures</label>
          </div>

          <div class="checkbox-row">
            <input id="consent" type="checkbox" name="consent" value="1" required>
            <label for="consent">I consent to you generating a one-off calendar file (.ics) with the fixtures I’ve selected.</label>
          </div>

          <p class="secondary-text">Using League <?= htmlspecialchars($league) ?> · Season <?= htmlspecialchars($season) ?>.</p>
          <button class="btn btn-primary" type="submit">Download my Fan Feed .ics</button>
        </form>
      </article>
    </section>
  </div>
</main>
<footer class="app-footer">
  <div class="shell">
    <small>© <?= date('Y') ?> Fan Feed. Built for calendar-first football fans.</small>
  </div>
</footer>
<script>
(function () {
  const root = document.documentElement;
  const storageKey = 'ffc-theme';
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  const storedTheme = window.localStorage.getItem(storageKey);
  if (storedTheme === 'light' || storedTheme === 'dark') {
    root.setAttribute('data-theme', storedTheme);
  } else {
    root.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
  }

  const themeButton = document.querySelector('[data-action="toggle-theme"]');
  const themeLabel = themeButton ? themeButton.querySelector('.theme-label') : null;

  function updateThemeLabel() {
    if (themeLabel) {
      const current = root.getAttribute('data-theme') || 'light';
      themeLabel.textContent = current.charAt(0).toUpperCase() + current.slice(1);
    }
  }

  updateThemeLabel();

  if (themeButton) {
    themeButton.addEventListener('click', function () {
      const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      const next = current === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      window.localStorage.setItem(storageKey, next);
      updateThemeLabel();
      window.ffPush && window.ffPush('theme_toggle', {
        theme_before: current,
        theme_after: next,
        page_path: window.location.pathname || null,
        site_theme: next
      });
    });
  }

  window.dataLayer = window.dataLayer || [];
  window.ffPush = function ffPush(eventName, payload) {
    const eventData = Object.assign({ event: eventName }, payload || {});
    const host = window.location.hostname;
    const isDev = !host || host === 'localhost' || host === '127.0.0.1' || host === '0.0.0.0' || host.endsWith('.local');
    if (isDev && typeof console !== 'undefined' && console.info) {
      console.info('[ffAnalytics]', eventData);
    }
    window.dataLayer.push(eventData);
    return eventData;
  };

  const pageViewPayload = {
    page_name: 'Fan Feed Calendar',
    page_title: document.title || null,
    page_path: window.location.pathname || null,
    page_url: window.location.href || null,
    page_type: 'app',
    page_category: 'calendar_tools',
    site_language: document.documentElement.lang || null,
    site_theme: root.getAttribute('data-theme') || 'light',
    user_id: null,
    ff_account_status: null
  };

  ffPush('page_view', pageViewPayload);
})();
</script>
</body>
</html>
