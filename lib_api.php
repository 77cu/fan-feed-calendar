<?php
// lib_api.php
declare(strict_types=1);

function cfg(): array {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/config.php';
        @mkdir($cfg['cache_dir'], 0775, true);
        @mkdir($cfg['fixtures_dir'], 0775, true);
    }
    return $cfg;
}

function api_headers(): array {
    $c = cfg();
    return [
        'Accept: application/json',
        'x-apisports-key: ' . $c['api_key'], // API-Football v3 auth header
    ];
}

function api_get(string $path, array $query = [], ?int $ttl = null): array {
    $c = cfg();
    $ttl = $ttl ?? (int)$c['ttl_seconds'];
    $base = rtrim($c['base_url'], '/');
    $url  = $base . '/' . ltrim($path, '/');
    if ($query) $url .= '?' . http_build_query($query);

    $cacheFile = $c['cache_dir'] . '/' . sha1($url) . '.json';
    if (is_file($cacheFile) && (time() - filemtime($cacheFile) < $ttl)) {
        $j = file_get_contents($cacheFile);
        $d = json_decode($j, true);
        if (is_array($d)) return $d;
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => api_headers(),
        CURLOPT_TIMEOUT        => 20,
    ]);
    $body = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($body === false) throw new RuntimeException("HTTP error: $err");
    $data = json_decode($body, true);
    if (!is_array($data)) throw new RuntimeException("Bad JSON from API");

    // Cache whatever we got to stay polite under free-tier limits
    file_put_contents($cacheFile, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

    if ($code === 429) throw new RuntimeException("Rate limit hit (429). Try after midnight UTC.");

    return $data;
}

/** Fetch teams for a league/season (for dropdown) */
function fetch_teams(string $league, string $season): array {
    $resp = api_get('/teams', ['league' => $league, 'season' => $season]);
    $out = [];
    foreach ($resp['response'] ?? [] as $row) {
        $t = $row['team'] ?? null;
        if (!$t) continue;
        $out[] = ['id' => (int)$t['id'], 'name' => $t['name']];
    }
    usort($out, fn($a,$b)=>strcmp($a['name'],$b['name']));
    return $out;
}

/** Fetch fixtures for a league/season (optionally limited to team id) */
function fetch_fixtures(string $league, string $season, ?int $teamId, string $tz): array {
    $params = ['league'=>$league, 'season'=>$season, 'timezone'=>$tz];
    if ($teamId) $params['team'] = $teamId;
    $resp = api_get('/fixtures', $params);
    $rows = [];
    foreach ($resp['response'] ?? [] as $fx) {
        $fixture = $fx['fixture'] ?? [];
        $league  = $fx['league']  ?? [];
        $teams   = $fx['teams']   ?? [];
        $venue   = $fixture['venue'] ?? [];

        $rows[] = [
            'id'        => $fixture['id'] ?? null,
            'date'      => $fixture['date'] ?? null, // ISO 8601 (tz per request)
            'status'    => $fixture['status']['short'] ?? null,
            'league'    => $league['name'] ?? null,
            'round'     => $league['round'] ?? null,
            'home'      => $teams['home']['name'] ?? null,
            'away'      => $teams['away']['name'] ?? null,
            'venue'     => trim(($venue['name'] ?? '') . (empty($venue['city']) ? '' : ', '.$venue['city'])),
        ];
    }
    return $rows;
}
