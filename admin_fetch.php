<?php
// admin_fetch.php
declare(strict_types=1);
error_reporting(E_ALL);

require __DIR__ . '/lib_api.php';
$c = cfg();

$league = $_GET['league'] ?? $c['default_league'];
$season = $_GET['season'] ?? $c['default_season'];

try {
    // 1) Teams list for dropdown
    $teams = fetch_teams($league, $season);

    // 2) All fixtures for the league (in local tz for readability)
    $fixtures = fetch_fixtures($league, $season, null, $c['api_timezone']);

    // Persist both
    $teamsFile    = $c['fixtures_dir'] . "/teams_{$league}_{$season}.json";
    $fixturesFile = $c['fixtures_dir'] . "/fixtures_{$league}_{$season}.json";

    @mkdir(dirname($teamsFile), 0775, true);
    file_put_contents($teamsFile, json_encode([
        'generated_at' => gmdate('c'),
        'league' => $league, 'season' => $season,
        'teams'  => $teams
    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    file_put_contents($fixturesFile, json_encode([
        'generated_at' => gmdate('c'),
        'league' => $league, 'season' => $season,
        'fixtures' => $fixtures
    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'ok' => true,
        'message' => 'Teams and fixtures saved.',
        'teams_file' => basename($teamsFile),
        'fixtures_file' => basename($fixturesFile),
        'team_count' => count($teams),
        'fixture_count' => count($fixtures)
    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
