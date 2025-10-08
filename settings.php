<!doctype html>
<html lang="en" data-page="settings">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Settings</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="assets/theme.js"></script>
<style>
:root {
    --ff-font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    --ff-radius-lg: 16px;
    --ff-radius-md: 12px;
    --ff-radius-sm: 999px;
    --ff-space-1: 0.5rem;
    --ff-space-2: 0.75rem;
    --ff-space-3: 1rem;
    --ff-space-4: 1.5rem;
    --ff-space-5: 2rem;
    --ff-width-content: min(720px, 100%);
    --ff-color-bg: #f8fafc;
    --ff-color-surface: #ffffff;
    --ff-color-border: #d0d7de;
    --ff-color-surface-alt: #f1f5f9;
    --ff-color-border-strong: #94a3b8;
    --ff-color-text: #0f172a;
    --ff-color-muted: #64748b;
    --ff-color-primary: #2563eb;
    --ff-color-primary-text: #ffffff;
    --ff-color-accent-soft: rgba(37, 99, 235, 0.12);
    --ff-color-switch-off: #cbd5f5;
    --ff-color-switch-on: #2563eb;
    --ff-color-divider: rgba(15, 23, 42, 0.08);
    --ff-shadow-card: 0 2px 8px rgba(15, 23, 42, 0.08);
}

[data-theme="dark"] {
    --ff-color-bg: #0b1220;
    --ff-color-surface: #111b2b;
    --ff-color-border: rgba(148, 163, 184, 0.35);
    --ff-color-surface-alt: rgba(148, 163, 184, 0.12);
    --ff-color-border-strong: rgba(148, 163, 184, 0.5);
    --ff-color-text: #e2e8f0;
    --ff-color-muted: #94a3b8;
    --ff-color-primary: #38bdf8;
    --ff-color-primary-text: #0f172a;
    --ff-color-accent-soft: rgba(56, 189, 248, 0.16);
    --ff-color-switch-off: rgba(148, 163, 184, 0.35);
    --ff-color-switch-on: #38bdf8;
    --ff-color-divider: rgba(148, 163, 184, 0.2);
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
.nav a:focus-visible,
.nav a[aria-current="page"] {
    background: var(--ff-color-accent-soft);
    color: var(--ff-color-text);
}

.card {
    background: var(--ff-color-surface);
    border-radius: var(--ff-radius-lg);
    border: 1px solid var(--ff-color-border);
    padding: var(--ff-space-3);
    margin: var(--ff-space-3) 0;
    box-shadow: var(--ff-shadow-card);
}

.card h2 {
    margin: 0 0 var(--ff-space-2);
    font-size: 1.2rem;
}

fieldset {
    border: 0;
    padding: 0;
    margin: 0;
}

.field {
    display: flex;
    flex-direction: column;
    gap: var(--ff-space-1);
    padding: var(--ff-space-2) 0;
    border-bottom: 1px solid var(--ff-color-divider);
}

.field:last-of-type {
    border-bottom: 0;
}

.field-label {
    font-weight: 600;
}

select {
    width: 100%;
    padding: 0.75rem 0.85rem;
    border-radius: var(--ff-radius-md);
    border: 1px solid var(--ff-color-border);
    background: var(--ff-color-surface);
    color: var(--ff-color-text);
    font-size: 1rem;
}

select:focus {
    outline: none;
    border-color: var(--ff-color-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
}

.segmented {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 4px;
    background: var(--ff-color-surface-alt, rgba(37, 99, 235, 0.08));
    border-radius: var(--ff-radius-md);
    padding: 4px;
}

.segmented input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.segmented label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.7rem 0.5rem;
    border-radius: var(--ff-radius-md);
    font-weight: 600;
    color: var(--ff-color-muted);
    transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}

.segmented input:checked + label {
    background: var(--ff-color-primary);
    color: var(--ff-color-primary-text);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
}

.toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--ff-space-2);
    flex-wrap: wrap;
}

.switch {
    position: relative;
    display: inline-flex;
    align-items: center;
    width: 46px;
    height: 26px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.switch-track {
    position: absolute;
    inset: 0;
    border-radius: var(--ff-radius-sm);
    background: var(--ff-color-switch-off);
    transition: background 0.2s ease;
}

.switch-thumb {
    position: absolute;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #ffffff;
    top: 2px;
    left: 2px;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
}

.switch input:checked + .switch-track {
    background: var(--ff-color-switch-on);
}

.switch input:checked + .switch-track + .switch-thumb {
    transform: translateX(20px);
}

.field-description {
    color: var(--ff-color-muted);
    font-size: 0.95rem;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.875rem;
    padding: 0.3rem 0.65rem;
    border-radius: var(--ff-radius-sm);
    background: var(--ff-color-accent-soft);
    color: var(--ff-color-text);
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
    <a href="index.php">Home</a>
    <a href="settings.php" aria-current="page">Settings</a>
  </nav>
  <h1>Settings</h1>
  <p>Manage preferences that personalise your Fan Feed experience.</p>
</header>

<main>
  <section class="card" aria-labelledby="calendar-preferences">
    <div class="status-pill" role="status">
      <span aria-hidden="true">⚙️</span>
      Preferences sync locally
    </div>
    <h2 id="calendar-preferences">Calendar preferences</h2>
    <form id="settings-form">
      <fieldset>
        <div class="field">
          <span class="field-label">Reminder notifications</span>
          <select id="reminders" name="reminders">
            <option value="off">Off</option>
            <option value="10">10 minutes before</option>
            <option value="15">15 minutes before</option>
            <option value="30">30 minutes before</option>
            <option value="60">60 minutes before</option>
          </select>
          <p class="field-description">Choose how long before kick-off you’d like to be nudged.</p>
        </div>

        <div class="field">
          <span class="field-label">Event approval</span>
          <div class="segmented" role="radiogroup" aria-label="Auto add or approve events">
            <div>
              <input type="radio" id="auto-add" name="autoMode" value="auto">
              <label for="auto-add">Auto-add</label>
            </div>
            <div>
              <input type="radio" id="approve-first" name="autoMode" value="approve">
              <label for="approve-first">Approve first</label>
            </div>
          </div>
          <p class="field-description">Auto-add puts fixtures straight into your calendar; approve first lets you review each one.</p>
        </div>

        <div class="field">
          <div class="toggle-row">
            <div>
              <span class="field-label">Show odds</span>
              <p class="field-description" id="odds-description">Include bookmaker odds alongside fixtures where available.</p>
            </div>
            <label class="switch" for="show-odds">
              <input type="checkbox" id="show-odds" aria-describedby="odds-description">
              <span class="switch-track" aria-hidden="true"></span>
              <span class="switch-thumb" aria-hidden="true"></span>
            </label>
          </div>
        </div>

        <div class="field">
          <span class="field-label">Theme</span>
          <div class="segmented" role="radiogroup" aria-label="Theme preference">
            <div>
              <input type="radio" id="theme-light" name="theme" value="light">
              <label for="theme-light">Light</label>
            </div>
            <div>
              <input type="radio" id="theme-dark" name="theme" value="dark">
              <label for="theme-dark">Dark</label>
            </div>
            <div>
              <input type="radio" id="theme-auto" name="theme" value="auto">
              <label for="theme-auto">Auto</label>
            </div>
          </div>
          <p class="field-description">Auto adapts to your device setting. Changes apply instantly.</p>
        </div>
      </fieldset>
    </form>
  </section>
</main>

<script>
(function () {
  function resolveStorage() {
    try {
      const testKey = '__fanfeed__test__';
      window.localStorage.setItem(testKey, '1');
      window.localStorage.removeItem(testKey);
      return window.localStorage;
    } catch (error) {
      const noop = function () {};
      return {
        getItem: function () { return null; },
        setItem: noop,
        removeItem: noop
      };
    }
  }

  const storage = resolveStorage();
  const settingsForm = document.getElementById('settings-form');
  const reminderSelect = document.getElementById('reminders');
  const autoModeRadios = settingsForm.querySelectorAll('input[name="autoMode"]');
  const showOddsCheckbox = document.getElementById('show-odds');
  const themeRadios = settingsForm.querySelectorAll('input[name="theme"]');

  const DEFAULTS = {
    reminders: 'off',
    autoMode: 'auto',
    showOdds: true,
    theme: (window.fanfeedTheme && window.fanfeedTheme.getPreference()) || 'auto'
  };

  function readBool(key, fallback) {
    const stored = storage.getItem(key);
    if (stored === null) return fallback;
    return stored === '1' || stored === 'true';
  }

  let currentSettings = {
    reminders: storage.getItem('fanfeed_reminders') || DEFAULTS.reminders,
    autoMode: storage.getItem('fanfeed_autoMode') || DEFAULTS.autoMode,
    showOdds: readBool('fanfeed_showOdds', DEFAULTS.showOdds),
    theme: storage.getItem('fanfeed_theme') || DEFAULTS.theme
  };

  function hydrateForm() {
    reminderSelect.value = currentSettings.reminders;
    autoModeRadios.forEach((radio) => {
      radio.checked = radio.value === currentSettings.autoMode;
    });
    showOddsCheckbox.checked = currentSettings.showOdds;
    let matchedTheme = false;
    themeRadios.forEach((radio) => {
      const isMatch = radio.value === currentSettings.theme;
      radio.checked = isMatch;
      matchedTheme = matchedTheme || isMatch;
    });
    if (!matchedTheme) {
      const autoRadio = settingsForm.querySelector('input[name="theme"][value="auto"]');
      if (autoRadio) autoRadio.checked = true;
    }
  }

  function pushAnalytics(updatedSettings) {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
      event: 'settings_changed',
      reminders: updatedSettings.reminders,
      autoAddMode: updatedSettings.autoMode,
      showOdds: updatedSettings.showOdds,
      theme: updatedSettings.theme
    });
  }

  function persist(nextSettings) {
    storage.setItem('fanfeed_reminders', nextSettings.reminders);
    storage.setItem('fanfeed_autoMode', nextSettings.autoMode);
    storage.setItem('fanfeed_showOdds', nextSettings.showOdds ? '1' : '0');
    if (window.fanfeedTheme && typeof window.fanfeedTheme.setPreference === 'function') {
      window.fanfeedTheme.setPreference(nextSettings.theme);
    } else {
      storage.setItem('fanfeed_theme', nextSettings.theme);
    }
  }

  let isInitialising = true;

  function saveSettings(partial) {
    const nextSettings = Object.assign({}, currentSettings, partial);
    const hasChanged =
      nextSettings.reminders !== currentSettings.reminders ||
      nextSettings.autoMode !== currentSettings.autoMode ||
      nextSettings.showOdds !== currentSettings.showOdds ||
      nextSettings.theme !== currentSettings.theme;

    if (!hasChanged) {
      return;
    }

    currentSettings = nextSettings;
    persist(nextSettings);

    if (!isInitialising) {
      pushAnalytics(nextSettings);
    }
  }

  hydrateForm();
  isInitialising = false;

  reminderSelect.addEventListener('change', function (event) {
    saveSettings({ reminders: event.target.value });
  });

  autoModeRadios.forEach((radio) => {
    radio.addEventListener('change', function (event) {
      if (event.target.checked) {
        saveSettings({ autoMode: event.target.value });
      }
    });
  });

  showOddsCheckbox.addEventListener('change', function (event) {
    saveSettings({ showOdds: event.target.checked });
  });

  themeRadios.forEach((radio) => {
    radio.addEventListener('change', function (event) {
      if (event.target.checked) {
        saveSettings({ theme: event.target.value });
      }
    });
  });

  window.addEventListener('fanfeed-theme-change', function (event) {
    const preference = event.detail && event.detail.preference;
    if (typeof preference === 'string' && preference !== currentSettings.theme) {
      currentSettings.theme = preference;
      storage.setItem('fanfeed_theme', preference);
      hydrateForm();
    }
  });
})();
</script>
</body>
</html>
