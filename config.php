<?php
// config.php
declare(strict_types=1);

return [
    // API-Football v3 base:
    'base_url' => 'https://v3.football.api-sports.io',
    // Put your API key here:
    'api_key'  => 'a8ad039c1223f602da71626d8f8259db',

    // Data dirs
    'cache_dir'    => __DIR__ . '/data/cache',
    'fixtures_dir' => __DIR__ . '/data/fixtures',

    // Defaults for quick tests
    'default_league' => '39',   // Premier League (example)
    'default_season' => '2023', // Season year (free plan supports 2023 data)
    // The API accepts timezone to localise fixture.date; we’ll still write UTC to ICS.
    'api_timezone'   => 'Europe/London',

    // Cache TTL to avoid burning free-plan calls
    'ttl_seconds' => 3600,

    // Seasons to include in the background fetch task (cron_fetch.php)
    'seasons_to_fetch' => ['2023'],
];
