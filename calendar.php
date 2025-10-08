<?php
declare(strict_types=1);
$c = require __DIR__ . '/config.php';

$defaultLeague = preg_replace('/\D/', '', $c['default_league'] ?? '39');
$defaultSeason = preg_replace('/\D/', '', $c['default_season'] ?? '2023');

$league = isset($_GET['league']) ? preg_replace('/\D/', '', (string)$_GET['league']) : $defaultLeague;
$season = isset($_GET['season']) ? preg_replace('/\D/', '', (string)$_GET['season']) : $defaultSeason;
if ($league === '') {
    $league = $defaultLeague;
}
if ($season === '') {
    $season = $defaultSeason;
}

$fixturesPath = sprintf('data/fixtures/fixtures_%s_%s.json', $league, $season);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Fan Feed Calendar – Read-only Fixtures</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
:root {
    color-scheme: light dark;
    --page-max-width: 960px;
    --space-2: 4px;
    --space-3: 8px;
    --space-4: 12px;
    --space-5: 16px;
    --space-6: 20px;
    --space-7: 24px;
    --space-8: 32px;
    --radius-s: 10px;
    --radius-m: 16px;
    --shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.12);
    --bg-page: #f5f7fa;
    --bg-card: #ffffff;
    --bg-soft: rgba(15, 23, 42, 0.04);
    --text-main: #0f172a;
    --text-muted: #475569;
    --border-soft: rgba(15, 23, 42, 0.12);
    --accent: #2563eb;
    --chip-default: #475569;
    --chip-text: #ffffff;
    --league-premier-league: #37003c;
    --league-fa-cup: #064635;
    --league-champions-league: #0039a6;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    background-color: var(--bg-page);
    color: var(--text-main);
}
@media (prefers-color-scheme: dark) {
    :root {
        --bg-page: #0f172a;
        --bg-card: #16213b;
        --bg-soft: rgba(255, 255, 255, 0.08);
        --text-main: #f8fafc;
        --text-muted: #cbd5f5;
        --border-soft: rgba(255, 255, 255, 0.14);
        --chip-text: #f8fafc;
        --shadow-soft: 0 10px 30px rgba(2, 6, 23, 0.35);
    }
}
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    background-color: var(--bg-page);
}
a {
    color: inherit;
}
.site-header {
    backdrop-filter: blur(14px);
    background: rgba(255, 255, 255, 0.84);
    border-bottom: 1px solid var(--border-soft);
    position: sticky;
    top: 0;
    z-index: 20;
}
@media (prefers-color-scheme: dark) {
    .site-header {
        background: rgba(15, 23, 42, 0.86);
    }
    .month-grid .cell.is-muted,
    .weekday-name {
        background: rgba(255, 255, 255, 0.04);
    }
}
.header-inner {
    max-width: var(--page-max-width);
    margin: 0 auto;
    padding: var(--space-5) var(--space-5);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-5);
}
.brand {
    font-weight: 700;
    letter-spacing: 0.02em;
}
nav {
    display: flex;
    gap: var(--space-4);
}
nav a {
    padding: 10px 16px;
    border-radius: var(--radius-s);
    text-decoration: none;
    font-weight: 500;
    background: transparent;
    border: 1px solid transparent;
    min-height: 44px;
    display: inline-flex;
    align-items: center;
}
nav a[aria-current="page"] {
    background: var(--bg-soft);
    border-color: var(--border-soft);
}
main {
    max-width: var(--page-max-width);
    margin: 0 auto;
    padding: var(--space-7) var(--space-5) var(--space-8);
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}
.hero h1 {
    margin: 0 0 var(--space-3);
    font-size: clamp(1.7rem, 3vw, 2.4rem);
}
.hero p {
    margin: 0;
    color: var(--text-muted);
    font-size: 1rem;
}
.controls {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
}
.view-toggle {
    display: inline-flex;
    padding: 4px;
    border-radius: var(--radius-s);
    background: var(--bg-soft);
    gap: 4px;
}
.view-toggle button {
    min-width: 120px;
    padding: 12px 18px;
    border-radius: calc(var(--radius-s) - 2px);
    border: none;
    background: transparent;
    color: var(--text-muted);
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
    min-height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.view-toggle button.is-active {
    background: var(--bg-card);
    color: var(--text-main);
    box-shadow: var(--shadow-soft);
}
.view-toggle button:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 2px;
}
.calendar-wrapper {
    background: var(--bg-card);
    border-radius: var(--radius-m);
    box-shadow: var(--shadow-soft);
    border: 1px solid var(--border-soft);
    overflow: hidden;
}
.view-panel[hidden] {
    display: none;
}
.section-heading {
    padding: var(--space-6) var(--space-6) var(--space-5);
    border-bottom: 1px solid var(--border-soft);
}
.section-heading h2 {
    margin: 0;
    font-size: 1.25rem;
}
.fixture-group {
    display: flex;
    flex-direction: column;
    gap: var(--space-5);
    padding: var(--space-5) var(--space-6) var(--space-6);
}
.day-block {
    display: flex;
    flex-direction: column;
}
.day-block + .day-block {
    border-top: 1px solid var(--border-soft);
}
.fixture-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-5);
    padding: var(--space-5) var(--space-6) var(--space-6);
}
.fixture-card {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-m);
    padding: var(--space-5);
    background: var(--bg-card);
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
}
.fixture-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
}
.fixture-title {
    font-size: 1.05rem;
    font-weight: 600;
    margin: 0;
}
.league-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--chip-text);
}
.fixture-meta {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
    font-size: 0.95rem;
    color: var(--text-muted);
}
.meta-line {
    display: flex;
    gap: 8px;
    align-items: center;
}
.status-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    background: var(--bg-soft);
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-main);
}
.include-toggle {
    align-self: flex-start;
    border: 1px solid var(--border-soft);
    background: var(--bg-soft);
    color: var(--text-main);
    border-radius: 999px;
    padding: 10px 18px;
    min-height: 44px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, border 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.include-toggle[data-state="included"] {
    background: rgba(37, 99, 235, 0.16);
    border-color: rgba(37, 99, 235, 0.55);
    color: var(--accent);
}
.include-toggle:focus-visible {
    outline: 3px solid var(--accent);
    outline-offset: 2px;
}
.month-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    border-top: 1px solid var(--border-soft);
    border-left: 1px solid var(--border-soft);
}
.month-grid .cell {
    min-height: 110px;
    padding: var(--space-4);
    border-right: 1px solid var(--border-soft);
    border-bottom: 1px solid var(--border-soft);
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
    background: var(--bg-card);
}
.month-grid .cell.is-muted {
    background: rgba(15, 23, 42, 0.03);
}
.month-grid .cell header {
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: var(--text-muted);
}
.month-grid .cell ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.month-grid .cell li {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    padding: 6px 8px;
    border-radius: var(--radius-s);
    background: var(--bg-soft);
}
.weekday-row {
    display: contents;
}
.weekday-name {
    padding: var(--space-4);
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
    background: rgba(15, 23, 42, 0.03);
    font-weight: 600;
    border-right: 1px solid var(--border-soft);
    border-bottom: 1px solid var(--border-soft);
}
.weekday-name:last-child {
    border-right: none;
}

.text-muted {
    color: var(--text-muted);
    font-size: 0.85rem;
}

@media (max-width: 640px) {
    .fixture-card {
        padding: var(--space-5);
    }
    .fixture-header {
        flex-direction: column;
        align-items: flex-start;
    }
    nav {
        width: 100%;
        justify-content: flex-end;
    }
    .month-grid .cell {
        min-height: 90px;
    }
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
</style>
</head>
<body>
<header class="site-header">
  <div class="header-inner">
    <div class="brand">Fan Feed</div>
    <nav aria-label="Primary">
      <a href="index.php">Generator</a>
      <a href="calendar.php" aria-current="page">Calendar</a>
    </nav>
  </div>
</header>
<main>
  <section class="hero">
    <h1>Fixtures calendar</h1>
    <p>Browse fixtures from stored JSON data. Switch between list and grid views to scan upcoming matches quickly.</p>
  </section>
  <div class="controls">
    <div class="view-toggle" role="tablist" aria-label="View mode">
      <button type="button" id="view-list" class="is-active" data-view="list" role="tab" aria-selected="true" aria-controls="list-view">List</button>
      <button type="button" id="view-month" data-view="month" role="tab" aria-selected="false" aria-controls="month-view">Month</button>
    </div>
    <div class="selection-info" data-selection-info></div>
  </div>
  <section class="calendar-wrapper">
    <div class="view-panel" id="list-view" role="tabpanel" aria-labelledby="view-list"></div>
    <div class="view-panel" id="month-view" role="tabpanel" aria-labelledby="view-month" hidden></div>
  </section>
</main>
<script>
(function() {
  const config = {
    fixturesUrl: <?php echo json_encode($fixturesPath, JSON_UNESCAPED_SLASHES); ?>,
    league: <?php echo json_encode($league, JSON_UNESCAPED_SLASHES); ?>,
    season: <?php echo json_encode($season, JSON_UNESCAPED_SLASHES); ?>
  };

  const leagueColorTokens = {
    'Premier League': 'var(--league-premier-league)',
    'FA Cup': 'var(--league-fa-cup)',
    'UEFA Champions League': 'var(--league-champions-league)'
  };

  const state = {
    fixtures: [],
    grouped: [],
    included: new Set(),
    view: 'list',
    monthAnchor: null
  };

  window.dataLayer = window.dataLayer || [];

  const listView = document.getElementById('list-view');
  const monthView = document.getElementById('month-view');
  const viewButtons = document.querySelectorAll('.view-toggle button');
  const selectionInfo = document.querySelector('[data-selection-info]');

  function formatDateHeading(date) {
    return new Intl.DateTimeFormat(undefined, {
      weekday: 'long',
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).format(date);
  }

  function formatTime(date) {
    return new Intl.DateTimeFormat(undefined, {
      hour: 'numeric',
      minute: '2-digit'
    }).format(date);
  }

  function getDayKey(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function colourForLeague(name) {
    return leagueColorTokens[name] || 'var(--chip-default)';
  }

  function buildFixtureCard(fixture) {
    const card = document.createElement('article');
    card.className = 'fixture-card';
    card.setAttribute('data-fixture-id', fixture.id);

    const header = document.createElement('div');
    header.className = 'fixture-header';

    const title = document.createElement('h3');
    title.className = 'fixture-title';
    title.textContent = `${fixture.home} vs ${fixture.away}`;

    const leagueChip = document.createElement('span');
    leagueChip.className = 'league-chip';
    leagueChip.style.backgroundColor = colourForLeague(fixture.league);
    leagueChip.textContent = fixture.league;

    header.appendChild(title);
    header.appendChild(leagueChip);

    const meta = document.createElement('div');
    meta.className = 'fixture-meta';

    const whenLine = document.createElement('div');
    whenLine.className = 'meta-line';
    whenLine.textContent = `${fixture.time} • ${fixture.dateLabel}`;

    const whereLine = document.createElement('div');
    whereLine.className = 'meta-line';
    whereLine.textContent = fixture.venue;

    const statusLine = document.createElement('div');
    statusLine.className = 'meta-line';
    const statusPill = document.createElement('span');
    statusPill.className = 'status-pill';
    statusPill.textContent = fixture.status;
    statusLine.appendChild(statusPill);

    meta.appendChild(whenLine);
    meta.appendChild(whereLine);
    meta.appendChild(statusLine);

    const toggle = document.createElement('button');
    toggle.className = 'include-toggle';
    toggle.type = 'button';
    const isIncluded = state.included.has(fixture.id);
    toggle.setAttribute('aria-pressed', String(isIncluded));
    toggle.dataset.state = isIncluded ? 'included' : 'excluded';
    toggle.textContent = isIncluded ? 'Included' : 'Excluded';

    toggle.addEventListener('click', function() {
      const isIncluded = toggle.dataset.state === 'included';
      const nextState = isIncluded ? 'excluded' : 'included';
      toggle.dataset.state = nextState;
      toggle.setAttribute('aria-pressed', String(nextState === 'included'));
      toggle.textContent = nextState === 'included' ? 'Included' : 'Excluded';

      if (nextState === 'included') {
        state.included.add(fixture.id);
      } else {
        state.included.delete(fixture.id);
      }
      updateSelectionInfo();

      window.dataLayer.push({
        event: nextState === 'included' ? 'fixture_included' : 'fixture_excluded',
        fixture_id: fixture.id,
        fixture_date: fixture.rawDate,
        home: fixture.home,
        away: fixture.away,
        league: fixture.league
      });
    });

    card.appendChild(header);
    card.appendChild(meta);
    card.appendChild(toggle);

    return card;
  }

  function updateSelectionInfo() {
    if (!selectionInfo) return;
    const total = state.fixtures.length;
    selectionInfo.textContent = `${state.included.size} of ${total} fixtures included`;
  }

  function renderListView() {
    listView.innerHTML = '';

    state.grouped.forEach(group => {
      const section = document.createElement('section');
      section.className = 'day-block';
      section.setAttribute('aria-labelledby', `day-${group.key}`);

      const headingWrap = document.createElement('div');
      headingWrap.className = 'section-heading';
      const heading = document.createElement('h2');
      heading.id = `day-${group.key}`;
      heading.textContent = formatDateHeading(group.date);
      headingWrap.appendChild(heading);

      const cardsContainer = document.createElement('div');
      cardsContainer.className = 'fixture-list';

      group.fixtures.forEach(fix => {
        cardsContainer.appendChild(buildFixtureCard(fix));
      });

      section.appendChild(headingWrap);
      section.appendChild(cardsContainer);

      listView.appendChild(section);
    });
    updateSelectionInfo();
  }

  function renderMonthView() {
    monthView.innerHTML = '';
    if (!state.monthAnchor) {
      monthView.textContent = 'No fixtures available for this month.';
      return;
    }

    const monthDate = state.monthAnchor;
    const anchorYear = monthDate.getFullYear();
    const anchorMonth = monthDate.getMonth();
    const start = new Date(anchorYear, anchorMonth, 1);
    const end = new Date(anchorYear, anchorMonth + 1, 0);

    const fixturesByDay = new Map();
    state.fixtures.forEach(f => {
      const date = f.dateObj;
      if (date.getFullYear() === anchorYear && date.getMonth() === anchorMonth) {
        const day = date.getDate();
        if (!fixturesByDay.has(day)) {
          fixturesByDay.set(day, []);
        }
        fixturesByDay.get(day).push(f);
      }
    });

    const monthLabel = monthDate.toLocaleString(undefined, { month: 'long', year: 'numeric' });
    const heading = document.createElement('div');
    heading.className = 'section-heading';
    const title = document.createElement('h2');
    title.textContent = monthLabel;
    heading.appendChild(title);
    monthView.appendChild(heading);

    const weekdays = [];
    for (let i = 0; i < 7; i++) {
      const date = new Date(2023, 0, i + 1);
      weekdays.push(new Intl.DateTimeFormat(undefined, { weekday: 'short' }).format(date));
    }

    const grid = document.createElement('div');
    grid.className = 'month-grid';

    weekdays.forEach(name => {
      const label = document.createElement('div');
      label.className = 'weekday-name';
      label.textContent = name;
      grid.appendChild(label);
    });

    const offset = start.getDay();
    for (let i = 0; i < offset; i++) {
      const cell = document.createElement('div');
      cell.className = 'cell is-muted';
      grid.appendChild(cell);
    }

    for (let day = 1; day <= end.getDate(); day++) {
      const cell = document.createElement('div');
      cell.className = 'cell';
      const header = document.createElement('header');
      const dayNum = document.createElement('span');
      dayNum.textContent = day;
      header.appendChild(dayNum);
      cell.appendChild(header);

      const list = document.createElement('ul');
      const fixtures = fixturesByDay.get(day) || [];
      fixtures.sort((a, b) => a.dateObj - b.dateObj);
      fixtures.forEach(fix => {
        const li = document.createElement('li');
        const chip = document.createElement('span');
        chip.className = 'league-chip';
        chip.style.backgroundColor = colourForLeague(fix.league);
        chip.textContent = fix.league;
        const label = document.createElement('span');
        label.textContent = `${fix.time} · ${fix.home} vs ${fix.away}`;
        li.appendChild(chip);
        li.appendChild(label);
        list.appendChild(li);
      });
      if (!fixtures.length) {
        const empty = document.createElement('li');
        empty.className = 'text-muted';
        empty.textContent = 'No fixtures';
        list.appendChild(empty);
      }
      cell.appendChild(list);
      grid.appendChild(cell);
    }

    const trailing = (7 - ((offset + end.getDate()) % 7)) % 7;
    for (let i = 0; i < trailing; i++) {
      const cell = document.createElement('div');
      cell.className = 'cell is-muted';
      grid.appendChild(cell);
    }

    monthView.appendChild(grid);
  }

  function switchView(view) {
    state.view = view;
    if (view === 'list') {
      listView.hidden = false;
      monthView.hidden = true;
    } else {
      listView.hidden = true;
      monthView.hidden = false;
      renderMonthView();
    }
    viewButtons.forEach(btn => {
      const isActive = btn.dataset.view === view;
      btn.classList.toggle('is-active', isActive);
      btn.setAttribute('aria-selected', String(isActive));
    });
  }

  viewButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const nextView = btn.dataset.view;
      if (nextView !== state.view) {
        switchView(nextView);
        window.dataLayer.push({
          event: 'calendar_view_toggled',
          view: nextView
        });
      }
    });
  });

  function hydrateFixtures(rawFixtures) {
    const parsed = rawFixtures.map(item => {
      const dateObj = new Date(item.date);
      const dayKey = getDayKey(dateObj);
      return {
        id: item.id,
        rawDate: item.date,
        dateObj,
        key: dayKey,
        dateLabel: new Intl.DateTimeFormat(undefined, { day: 'numeric', month: 'short' }).format(dateObj),
        time: formatTime(dateObj),
        status: item.status || 'TBC',
        home: item.home || 'Home',
        away: item.away || 'Away',
        league: item.league || 'League',
        venue: item.venue || 'Venue'
      };
    }).filter(item => !Number.isNaN(item.dateObj.getTime()));

    parsed.sort((a, b) => a.dateObj - b.dateObj);

    const grouped = [];
    parsed.forEach(f => {
      let group = grouped.find(g => g.key === f.key);
      if (!group) {
        group = { key: f.key, date: new Date(f.dateObj), fixtures: [] };
        grouped.push(group);
      }
      group.fixtures.push(f);
    });

    grouped.sort((a, b) => a.date - b.date);

    state.fixtures = parsed;
    state.grouped = grouped;
    state.monthAnchor = parsed.length ? new Date(parsed[0].dateObj.getFullYear(), parsed[0].dateObj.getMonth(), 1) : null;
    state.included = new Set(parsed.map(f => f.id));
    updateSelectionInfo();
  }

  function render() {
    if (state.view === 'list') {
      renderListView();
    } else {
      renderMonthView();
    }
  }

  async function init() {
    updateSelectionInfo();
    try {
      const res = await fetch(config.fixturesUrl);
      if (!res.ok) {
        throw new Error('Unable to load fixtures.');
      }
      const data = await res.json();
      const fixtures = Array.isArray(data.fixtures) ? data.fixtures : [];
      hydrateFixtures(fixtures);
      render();
    } catch (error) {
      listView.innerHTML = '<div class="fixture-group"><p>Unable to load fixtures for this league/season.</p></div>';
      monthView.innerHTML = '';
      console.error(error);
    }
  }

  init();
})();
</script>
</body>
</html>
