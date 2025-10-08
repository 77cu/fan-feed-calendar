<?php
// make_ics.php — Fan Feed Calendar (stable)
// Generates an ICS from stored fixtures for the posted league/season and team.
declare(strict_types=1);
error_reporting(E_ALL);

$c = require __DIR__ . '/config.php';

// ---------- helpers ----------
function icsEscape(string $s): string {
    return str_replace(['\\', ';', ',', "\n"], ['\\\\', '\;', '\,', '\\n'], $s);
}
function foldLine(string $k, string $v): string {
    $l = $k . ':' . $v;
    $out = '';
    while (strlen($l) > 75) {
        $out .= substr($l, 0, 75) . "\r\n ";
        $l = substr($l, 75);
    }
    return $out . $l . "\r\n";
}
function fail(int $code, string $msg): void {
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    echo $msg;
    exit;
}

// ---------- input ----------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail(405, 'POST only');
}

$email   = trim($_POST['email'] ?? '');
$teamId  = isset($_POST['team_id']) ? (int)$_POST['team_id'] : 0;
$consent = isset($_POST['consent']);

// If "Add all" is ticked, we include past + future; otherwise we’ll limit to next N fixtures
$count = isset($_POST['all']) ? null : max(1, (int)($_POST['count'] ?? 5));

// Use posted league/season if present; otherwise fall back to config defaults
$league = preg_replace('/\D/', '', $_POST['league'] ?? ($c['default_league'] ?? '39'));
$season = preg_replace('/\D/', '', $_POST['season'] ?? ($c['default_season'] ?? '2023'));

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$teamId || !$consent) {
    fail(400, 'Missing or invalid fields.');
}

// ---------- load data ----------
$fixturesFile = $c['fixtures_dir'] . "/fixtures_{$league}_{$season}.json";
$teamsFile    = $c['fixtures_dir'] . "/teams_{$league}_{$season}.json";

if (!is_file($teamsFile))    fail(404, 'Teams file missing for the selected league/season.');
if (!is_file($fixturesFile)) fail(404, 'Fixtures file missing for the selected league/season.');

$fixturesData = json_decode(file_get_contents($fixturesFile), true);
$teamsData    = json_decode(file_get_contents($teamsFile), true);

if (!is_array($fixturesData) || !is_array($teamsData)) {
    fail(500, 'Invalid data files (JSON).');
}

$fixtures = $fixturesData['fixtures'] ?? [];
$teams    = $teamsData['teams'] ?? [];

// Map team id -> name; resolve selected team’s display name
$teamMap = [];
foreach ($teams as $t) {
    $tid = isset($t['id']) ? (int)$t['id'] : 0;
    if ($tid) $teamMap[$tid] = (string)($t['name'] ?? '');
}
$teamName = $teamMap[$teamId] ?? '';
if ($teamName === '') {
    fail(400, 'Unknown team for this league/season.');
}

// ---------- filter fixtures ----------
$now = time();

// First, isolate fixtures involving the chosen team (by name, per stored data shape)
$teamFixtures = array_values(array_filter($fixtures, function ($f) use ($teamName) {
    if (empty($f['id']) || empty($f['date'])) return false;
    $home = (string)($f['home'] ?? '');
    $away = (string)($f['away'] ?? '');
    return (strcasecmp($home, $teamName) === 0 || strcasecmp($away, $teamName) === 0);
}));

// If "Add all" ticked, include past + future; otherwise only future fixtures
if ($count === null) {
    $filtered = $teamFixtures;
} else {
    $filtered = array_values(array_filter($teamFixtures, function ($f) use ($now) {
        $ts = strtotime((string)$f['date']);
        return ($ts !== false && $ts >= $now);
    }));
}

// Sort ascending by kickoff
usort($filtered, fn($a, $b) => strtotime((string)$a['date']) <=> strtotime((string)$b['date']));

// Limit to “next N fixtures” if requested
if ($count !== null) {
    $filtered = array_slice($filtered, 0, $count);
}

if (!$filtered) {
    fail(404, 'No fixtures matched your selection. Tip: tick “Add all available fixtures” for historical seasons.');
}

// ---------- build ICS ----------
$nowUtc = gmdate('Ymd\THis\Z');

$buf  = "BEGIN:VCALENDAR\r\n";
$buf .= "VERSION:2.0\r\n";
$buf .= "PRODID:-//FanFeed//Fan Feed Calendar//EN\r\n";
$buf .= "CALSCALE:GREGORIAN\r\n";
$buf .= foldLine('X-WR-CALNAME', icsEscape("Fan Feed: $teamName"));
$buf .= foldLine('X-WR-TIMEZONE', 'UTC');

foreach ($filtered as $f) {
    // Defensive reads
    $fixtureId  = (string)($f['id'] ?? '');
    $iso        = (string)($f['date'] ?? '');
    $home       = (string)($f['home'] ?? '');
    $away       = (string)($f['away'] ?? '');
    $venue      = (string)($f['venue'] ?? '');
    $leagueName = (string)($f['league'] ?? 'Unknown');
    $round      = (string)($f['round'] ?? '');
    $status     = (string)($f['status'] ?? '');

    // Robust ISO8601 parse (handles “+01:00” cleanly)
    $dtLocal = DateTimeImmutable::createFromFormat(DateTime::ATOM, $iso);
    if (!$dtLocal) {
        // Fallback parser for any slightly different strings
        try { $dtLocal = new DateTimeImmutable($iso); } catch (Throwable $e) { $dtLocal = null; }
    }
    if (!$dtLocal) {
        // Skip any event we can't parse (prevents empty DTSTART blocks)
        continue;
    }

    // Emit in UTC for simplicity
    $dtUtc   = $dtLocal->setTimezone(new DateTimeZone('UTC'));
    $dtstart = $dtUtc->format('Ymd\THis\Z');
    $dtend   = $dtUtc->modify('+2 hours')->format('Ymd\THis\Z'); // football default duration: 2h

    $summary = trim(($home !== '' ? $home : 'Home') . ' vs ' . ($away !== '' ? $away : 'Away'));
    $uid     = ($fixtureId !== '' ? $fixtureId : md5($summary.$dtstart)) . '@fanfeed.local';

    $desc = "League: $leagueName";
    if ($round !== '')  $desc .= "\\nRound: $round";
    if ($status !== '') $desc .= "\\nStatus: $status";
    $desc .= "\\nSource: API-Football (prototype)";

    $buf .= "BEGIN:VEVENT\r\n";
    $buf .= foldLine('UID', $uid);
    $buf .= foldLine('DTSTAMP', $nowUtc);
    $buf .= foldLine('DTSTART', $dtstart);
    $buf .= foldLine('DTEND',   $dtend);
    $buf .= foldLine('SUMMARY', icsEscape($summary));
    if ($venue !== '') {
        $buf .= foldLine('LOCATION', icsEscape($venue));
    }
    $buf .= foldLine('DESCRIPTION', icsEscape($desc));
    $buf .= "END:VEVENT\r\n";
}

$buf .= "END:VCALENDAR\r\n";

// ---------- output ----------
$fname = 'fan-feed-' . preg_replace('/\s+/', '-', strtolower($teamName)) . "-fixtures.ics";
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $fname . '"');
echo $buf;
