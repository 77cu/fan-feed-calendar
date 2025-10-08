<?php
// index.php — Fan Feed Calendar (onboarding prototype)
declare(strict_types=1);
error_reporting(E_ALL);

$c = require __DIR__ . '/config.php';

$league = $c['default_league'] ?? '39';
$season = '2023';

$fixturesDir = $c['fixtures_dir'] ?? (__DIR__ . '/data/fixtures');
$teamsFile = $fixturesDir . "/teams_{$league}_{$season}.json";
$fixturesFile = $fixturesDir . "/fixtures_{$league}_{$season}.json";

$teams = [];
if (is_file($teamsFile)) {
    $data = json_decode((string)file_get_contents($teamsFile), true);
    if (isset($data['teams']) && is_array($data['teams'])) {
        $teams = $data['teams'];
    }
}

if (!$teams) {
    $teams = [
        'Arsenal',
        'Aston Villa',
        'Brighton & Hove Albion',
        'Chelsea',
        'Liverpool',
        'Manchester City',
        'Manchester United',
        'Newcastle United',
        'Tottenham Hotspur',
        'West Ham United'
    ];
}

$competitions = [];
if (is_file($fixturesFile)) {
    $fixturesData = json_decode((string)file_get_contents($fixturesFile), true);
    if (isset($fixturesData['fixtures']) && is_array($fixturesData['fixtures'])) {
        foreach ($fixturesData['fixtures'] as $fixture) {
            if (!empty($fixture['league'])) {
                $competitions[$fixture['league']] = $fixture['league'];
            }
        }
    }
}
$competitions = array_values($competitions);

if (!$competitions) {
    $competitions = [
        'Premier League',
        'FA Cup',
        'UEFA Champions League',
        'UEFA Europa League',
        'International Friendlies'
    ];
}

?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Fan Feed Calendar – Onboarding</title>
<style>
:root {
    color-scheme: light dark;
    --bg: #f5f7fb;
    --card: #fff;
    --primary: #111827;
    --accent: #4338ca;
    --accent-contrast: #fff;
    --muted: #6b7280;
    --border: #e5e7eb;
    --chip-bg: #eef2ff;
    --chip-border: #c7d2fe;
    --chip-text: #312e81;
    --chip-bg-active: #4338ca;
    --chip-text-active: #fff;
    --shadow: 0 18px 40px -24px rgba(15, 23, 42, 0.4);
}
* { box-sizing: border-box; }
body {
    font-family: 'Inter', system-ui, -apple-system, Segoe UI, sans-serif;
    margin: 0;
    background: var(--bg);
    color: var(--primary);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.header {
    padding: 32px 24px 8px;
    text-align: center;
}
.header h1 {
    margin: 0;
    font-size: clamp(28px, 4vw, 36px);
}
.header p {
    margin: 8px 0 0;
    color: var(--muted);
    font-size: 16px;
}
.container {
    width: min(960px, 100%);
    margin: 0 auto;
    padding: 0 24px 48px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.stepper {
    display: flex;
    justify-content: center;
    gap: 16px;
    margin: 0 auto 32px;
    flex-wrap: wrap;
}
.step-indicator {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    border-radius: 999px;
    background: rgba(67, 56, 202, 0.12);
    color: var(--accent);
    font-weight: 600;
}
.step-indicator span {
    background: var(--accent);
    color: var(--accent-contrast);
    display: grid;
    place-items: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-size: 14px;
}
.step-indicator[data-active="false"] {
    opacity: 0.5;
    background: rgba(148, 163, 184, 0.2);
    color: var(--muted);
}
.card {
    background: var(--card);
    border-radius: 24px;
    padding: clamp(24px, 4vw, 40px);
    box-shadow: var(--shadow);
    display: none;
    flex-direction: column;
    gap: 24px;
}
.card[data-active="true"] {
    display: flex;
}
.card h2 {
    margin: 0;
    font-size: clamp(22px, 3vw, 28px);
}
.card p.description {
    margin: 0;
    color: var(--muted);
}
.chip-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.chip {
    border: 1px solid var(--chip-border);
    padding: 10px 16px;
    border-radius: 999px;
    background: var(--chip-bg);
    color: var(--chip-text);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: background 0.2s, color 0.2s, transform 0.15s;
}
.chip[data-selected="true"] {
    background: var(--chip-bg-active);
    border-color: var(--chip-bg-active);
    color: var(--chip-text-active);
    transform: translateY(-2px);
}
.search-input {
    width: 100%;
    padding: 12px 16px;
    border-radius: 14px;
    border: 1px solid var(--border);
    font-size: 16px;
}
.selected-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.selected {
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 14px;
    color: var(--muted);
}
.selected strong {
    color: var(--primary);
}
.selected-pill {
    background: rgba(17, 24, 39, 0.08);
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 14px;
}
.placeholder {
    color: var(--muted);
    font-style: italic;
}
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
.tile-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}
.tile {
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: rgba(255,255,255,0.7);
    transition: border 0.2s, transform 0.2s;
    cursor: pointer;
}
.tile h3 {
    margin: 0;
    font-size: 18px;
}
.tile[data-selected="true"] {
    border-color: var(--accent);
    transform: translateY(-3px);
    box-shadow: 0 14px 30px -20px rgba(67, 56, 202, 0.6);
}
.tile button {
    align-self: flex-start;
    border: none;
    background: var(--accent);
    color: var(--accent-contrast);
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 600;
    cursor: pointer;
}
.preferences {
    display: grid;
    gap: 16px;
}
.preference-group {
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
}
.preference-group h4 {
    margin: 0 0 12px;
    font-size: 16px;
}
.preference-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    cursor: pointer;
}
.summary {
    background: rgba(67, 56, 202, 0.08);
    border-radius: 18px;
    padding: 16px;
    font-size: 15px;
    line-height: 1.6;
}
.summary strong {
    display: inline-block;
    min-width: 120px;
}
.actions {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
}
.button {
    border: none;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}
.button.primary {
    background: var(--accent);
    color: var(--accent-contrast);
}
.button.secondary {
    background: rgba(15, 23, 42, 0.08);
    color: var(--primary);
}
.button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
footer {
    text-align: center;
    padding: 24px;
    font-size: 14px;
    color: var(--muted);
}
@media (max-width: 640px) {
    .card {
        border-radius: 18px;
    }
    .stepper {
        gap: 8px;
    }
    .step-indicator {
        padding: 10px 14px;
        font-size: 14px;
    }
}
</style>
</head>
<body>
<div class="header">
  <h1>Set up your Fan Feed</h1>
  <p>Pick the competitions and teams you love, then connect your calendar in three simple steps.</p>
</div>
<div class="container">
  <div class="stepper" role="navigation" aria-label="Onboarding steps">
    <div class="step-indicator" data-step="0" data-active="true"><span>1</span>Competitions</div>
    <div class="step-indicator" data-step="1" data-active="false"><span>2</span>Teams</div>
    <div class="step-indicator" data-step="2" data-active="false"><span>3</span>Calendar</div>
  </div>

  <section class="card" data-step="0" data-active="true" aria-labelledby="step-competitions">
    <div>
      <h2 id="step-competitions">Which competitions do you follow?</h2>
      <p class="description">Choose all leagues and tournaments you never want to miss. You can edit this later.</p>
    </div>
    <div class="chip-grid" id="competition-grid"></div>
    <div class="selected" aria-live="polite">
      <strong>Selected:</strong>
      <div class="selected-list" id="selected-competitions"></div>
    </div>
  </section>

  <section class="card" data-step="1" data-active="false" aria-labelledby="step-teams">
    <div>
      <h2 id="step-teams">Tell us your favourite teams</h2>
      <p class="description">Search and select as many clubs as you want. These will tailor your match feed.</p>
    </div>
    <label for="team-search" class="sr-only">Search for a team</label>
    <input id="team-search" class="search-input" type="search" placeholder="Search teams…" autocomplete="off">
    <div class="chip-grid" id="team-grid"></div>
    <div>
      <strong>Selected teams:</strong>
      <div class="selected-list" id="selected-teams"></div>
    </div>
  </section>

  <section class="card" data-step="2" data-active="false" aria-labelledby="step-calendar">
    <div>
      <h2 id="step-calendar">Connect your calendar</h2>
      <p class="description">Sync fixtures instantly or approve each one before it lands in your diary.</p>
    </div>
    <div class="tile-grid" id="integration-grid">
      <article class="tile" data-integration="google" tabindex="0">
        <h3>Google Calendar</h3>
        <p>Add a live calendar that updates automatically on every device.</p>
        <button type="button">Connect</button>
      </article>
      <article class="tile" data-integration="microsoft" tabindex="0">
        <h3>Microsoft Outlook</h3>
        <p>Sync fixtures to Outlook or Teams with a single connection.</p>
        <button type="button">Connect</button>
      </article>
      <article class="tile" data-integration="ics" tabindex="0">
        <h3>Personal .ics feed</h3>
        <p>Generate a private link that you can import to any calendar app.</p>
        <button type="button">Get link</button>
      </article>
    </div>
    <div class="preferences">
      <div class="preference-group" role="radiogroup" aria-labelledby="pref-sync">
        <h4 id="pref-sync">How should we add fixtures?</h4>
        <label><input type="radio" name="sync-mode" value="auto" checked> Add matches automatically</label>
        <label><input type="radio" name="sync-mode" value="approve"> Ask me to approve each match</label>
      </div>
      <div class="preference-group" aria-labelledby="pref-reminders">
        <h4 id="pref-reminders">Reminders</h4>
        <label><input type="checkbox" name="reminder" value="15" checked> 15 minutes before kick-off</label>
        <label><input type="checkbox" name="reminder" value="60"> 1 hour before kick-off</label>
        <label><input type="checkbox" name="reminder" value="1440"> Morning of matchday</label>
      </div>
    </div>
    <div class="summary" id="summary"></div>
  </section>

  <div class="actions">
    <button class="button secondary" type="button" id="back-button">Back</button>
    <button class="button primary" type="button" id="next-button">Next</button>
  </div>
</div>
<footer>
  &copy; <?= date('Y') ?> Fan Feed Calendar – Prototype onboarding experience.
</footer>

<script>
(function(){
  const competitions = <?php echo json_encode($competitions, JSON_UNESCAPED_UNICODE); ?>;
  const teams = <?php echo json_encode($teams, JSON_UNESCAPED_UNICODE); ?>;

  const stateKey = 'ffc-onboarding-state-v1';
  const startedKey = 'ffc-onboarding-started';
  const defaultState = {
    step: 0,
    competitions: [],
    teams: [],
    integration: null,
    syncMode: 'auto',
    reminders: ['15'],
  };

  const dataLayerPush = (event, detail = {}) => {
    window.dataLayer = window.dataLayer || [];
    const payload = { event, ...detail };
    window.dataLayer.push(payload);
    console.log('[dataLayer]', payload);
  };

  const loadState = () => {
    try {
      const raw = sessionStorage.getItem(stateKey);
      if (raw) {
        const parsed = JSON.parse(raw);
        return { ...defaultState, ...parsed };
      }
    } catch (err) {
      console.warn('Unable to load state', err);
    }
    return { ...defaultState };
  };

  const saveState = () => {
    sessionStorage.setItem(stateKey, JSON.stringify(state));
  };

  const state = loadState();

  if (!sessionStorage.getItem(startedKey)) {
    dataLayerPush('onboarding_started', { step: state.step + 1 });
    sessionStorage.setItem(startedKey, '1');
  }

  const stepper = document.querySelectorAll('.step-indicator');
  const cards = document.querySelectorAll('.card');
  const backButton = document.getElementById('back-button');
  const nextButton = document.getElementById('next-button');
  const competitionGrid = document.getElementById('competition-grid');
  const selectedCompetitions = document.getElementById('selected-competitions');
  const teamGrid = document.getElementById('team-grid');
  const teamSearch = document.getElementById('team-search');
  const selectedTeams = document.getElementById('selected-teams');
  const tiles = document.querySelectorAll('.tile');
  const summary = document.getElementById('summary');

  const renderStepper = () => {
    stepper.forEach((el) => {
      const isActive = Number(el.dataset.step) === state.step;
      el.dataset.active = isActive;
    });
    cards.forEach((card) => {
      const isActive = Number(card.dataset.step) === state.step;
      card.dataset.active = isActive;
    });
    backButton.disabled = state.step === 0;
    nextButton.textContent = state.step === 2 ? 'Finish setup' : 'Next';
    updateSummary();
  };

  const createChip = (label, value, collection) => {
    const chip = document.createElement('button');
    chip.type = 'button';
    chip.className = 'chip';
    chip.dataset.value = value;
    chip.dataset.selected = collection.includes(value);
    chip.textContent = label;
    chip.addEventListener('click', () => {
      const selected = chip.dataset.selected === 'true';
      if (selected) {
        chip.dataset.selected = 'false';
        const idx = collection.indexOf(value);
        if (idx >= 0) collection.splice(idx, 1);
      } else {
        chip.dataset.selected = 'true';
        collection.push(value);
        if (collection === state.competitions) {
          dataLayerPush('competition_selected', { competition_name: label });
        }
        if (collection === state.teams) {
          dataLayerPush('team_selected', { team_name: label });
        }
      }
      saveState();
      refreshSelections();
      updateButtons();
    });
    return chip;
  };

  const refreshSelections = () => {
    selectedCompetitions.innerHTML = '';
    if (state.competitions.length === 0) {
      const placeholder = document.createElement('span');
      placeholder.className = 'placeholder';
      placeholder.textContent = 'Pick at least one competition to continue.';
      selectedCompetitions.appendChild(placeholder);
    } else {
      state.competitions.forEach((value) => {
        const pill = document.createElement('span');
        pill.className = 'selected-pill';
        pill.textContent = value;
        selectedCompetitions.appendChild(pill);
      });
    }

    selectedTeams.innerHTML = '';
    if (state.teams.length === 0) {
      const placeholder = document.createElement('span');
      placeholder.className = 'placeholder';
      placeholder.textContent = 'Search above and add a team.';
      selectedTeams.appendChild(placeholder);
    } else {
      state.teams.forEach((value) => {
        const pill = document.createElement('span');
        pill.className = 'selected-pill';
        pill.textContent = value;
        selectedTeams.appendChild(pill);
      });
    }

    tiles.forEach((tile) => {
      tile.dataset.selected = tile.dataset.integration === state.integration;
    });
  };

  const updateButtons = () => {
    if (state.step === 0) {
      nextButton.disabled = state.competitions.length === 0;
    } else if (state.step === 1) {
      nextButton.disabled = state.teams.length === 0;
    } else {
      nextButton.disabled = !state.integration;
    }
  };

  const updateSummary = () => {
    if (state.step !== 2) {
      summary.textContent = '';
      return;
    }
    const lines = [
      `<div><strong>Competitions:</strong> ${state.competitions.length ? state.competitions.join(', ') : 'None selected'}</div>`,
      `<div><strong>Teams:</strong> ${state.teams.length ? state.teams.join(', ') : 'None selected'}</div>`,
      `<div><strong>Integration:</strong> ${state.integration ? state.integration.charAt(0).toUpperCase() + state.integration.slice(1) : 'Choose an option'}</div>`,
      `<div><strong>Sync mode:</strong> ${state.syncMode === 'auto' ? 'Add automatically' : 'Request approval'}</div>`,
      `<div><strong>Reminders:</strong> ${state.reminders.length ? state.reminders.map((min) => reminderCopy[min] || min + ' mins').join(', ') : 'No reminders'}</div>`
    ];
    summary.innerHTML = lines.join('');
  };

  competitions.forEach((name) => {
    const chip = createChip(name, name, state.competitions);
    competitionGrid.appendChild(chip);
  });

  const teamChips = teams.map((team) => {
    const name = typeof team === 'string' ? team : team.name;
    const chip = createChip(name, name, state.teams);
    teamGrid.appendChild(chip);
    return { name: name.toLowerCase(), element: chip };
  });

  teamSearch.addEventListener('input', () => {
    const query = teamSearch.value.trim().toLowerCase();
    teamChips.forEach(({ name, element }) => {
      const match = !query || name.includes(query);
      element.style.display = match ? '' : 'none';
    });
  });

  const reminderCopy = {
    '15': '15 minutes before',
    '60': '1 hour before',
    '1440': 'Morning of matchday'
  };

  document.querySelectorAll('input[name="sync-mode"]').forEach((input) => {
    if (input.value === state.syncMode) {
      input.checked = true;
    }
    input.addEventListener('change', () => {
      state.syncMode = input.value;
      saveState();
      updateSummary();
    });
  });

  document.querySelectorAll('input[name="reminder"]').forEach((input) => {
    if (state.reminders.includes(input.value)) {
      input.checked = true;
    }
    input.addEventListener('change', () => {
      if (input.checked) {
        if (!state.reminders.includes(input.value)) {
          state.reminders.push(input.value);
        }
      } else {
        state.reminders = state.reminders.filter((value) => value !== input.value);
      }
      saveState();
      updateSummary();
    });
  });

  tiles.forEach((tile) => {
    const integration = tile.dataset.integration;
    const selectIntegration = () => {
      state.integration = integration;
      saveState();
      refreshSelections();
      updateButtons();
      updateSummary();
      dataLayerPush('integration_connect_clicked', { integration });
    };
    tile.addEventListener('click', selectIntegration);
    tile.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        selectIntegration();
      }
    });
    const button = tile.querySelector('button');
    if (button) {
      button.addEventListener('click', (event) => {
        event.stopPropagation();
        selectIntegration();
      });
    }
  });

  backButton.addEventListener('click', () => {
    if (state.step > 0) {
      state.step -= 1;
      saveState();
      renderStepper();
      updateButtons();
    }
  });

  nextButton.addEventListener('click', () => {
    if (state.step < 2) {
      state.step += 1;
      saveState();
      renderStepper();
      updateButtons();
    } else {
      dataLayerPush('onboarding_completed', {
        competitions: state.competitions,
        teams: state.teams,
        integration: state.integration,
        sync_mode: state.syncMode,
        reminders: state.reminders
      });
      nextButton.disabled = true;
      nextButton.textContent = 'You’re all set!';
      setTimeout(() => {
        nextButton.disabled = false;
        nextButton.textContent = 'Finish setup';
      }, 2000);
    }
  });

  refreshSelections();
  renderStepper();
  updateButtons();
})();
</script>
</body>
</html>
