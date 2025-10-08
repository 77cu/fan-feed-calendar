<?php
// cron_fetch.php — background aggregation task
// Fetches all leagues, teams and fixtures for configured seasons.
declare(strict_types=1);

error_reporting(E_ALL);

require __DIR__ . '/lib_api.php';

$c = cfg();
$seasons = $c['seasons_to_fetch'] ?? [$c['default_season'] ?? '2023'];
$tz = (string)($c['api_timezone'] ?? 'UTC');
$fixturesDir = (string)$c['fixtures_dir'];

$summary = [
    'started_at' => gmdate('c'),
    'seasons' => [],
];

try {
    foreach ($seasons as $season) {
        $season = (string)$season;
        if ($season === '') {
            continue;
        }

        $seasonData = [
            'season' => $season,
            'leagues' => [],
        ];

        $leagues = fetch_leagues($season);
        $leagueFile = $fixturesDir . "/leagues_{$season}.json";

        file_put_contents($leagueFile, json_encode([
            'generated_at' => gmdate('c'),
            'season' => $season,
            'league_count' => count($leagues),
            'leagues' => $leagues,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        foreach ($leagues as $league) {
            $leagueId = (string)($league['id'] ?? '');
            if ($leagueId === '') {
                continue;
            }

            $teams = fetch_teams($leagueId, $season);
            $fixtures = fetch_fixtures($leagueId, $season, null, $tz);

            $teamsFile = $fixturesDir . "/teams_{$leagueId}_{$season}.json";
            $fixturesFile = $fixturesDir . "/fixtures_{$leagueId}_{$season}.json";

            file_put_contents($teamsFile, json_encode([
                'generated_at' => gmdate('c'),
                'league' => $leagueId,
                'season' => $season,
                'teams' => $teams,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            file_put_contents($fixturesFile, json_encode([
                'generated_at' => gmdate('c'),
                'league' => $leagueId,
                'season' => $season,
                'fixtures' => $fixtures,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $seasonData['leagues'][] = [
                'id' => $leagueId,
                'name' => $league['name'] ?? '',
                'team_count' => count($teams),
                'fixture_count' => count($fixtures),
            ];
        }

        $seasonData['league_count'] = count($seasonData['leagues']);
        $summary['seasons'][] = $seasonData;
    }

    $summary['completed_at'] = gmdate('c');
    $summary['ok'] = true;
} catch (Throwable $e) {
    $summary['completed_at'] = gmdate('c');
    $summary['ok'] = false;
    $summary['error'] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
