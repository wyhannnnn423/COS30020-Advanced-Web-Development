<?php
function getPaintWeather() {
    $cache = __DIR__ . '/weather_cache.json';
    if (file_exists($cache) && time() - filemtime($cache) < 3600) {
        return json_decode(file_get_contents($cache), true);
    }
    $url = 'https://api.open-meteo.com/v1/forecast?latitude=1.55&longitude=110.34&current=temperature_2m,relative_humidity_2m,precipitation';
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 3]);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = $res ? json_decode($res, true) : null;

    if (!isset($data['current'])) {
        return file_exists($cache) ? json_decode(file_get_contents($cache), true) : null;
    }
    $c = $data['current'];
    $raining = $c['precipitation'] > 0;
    $humid = $c['relative_humidity_2m'] > 80;
    $out = [
        'ok'       => !$raining && !$humid,
        'icon'     => $raining ? '🌧️' : ($humid ? '☁️' : '☀️'),
        'temp'     => round($c['temperature_2m']),
        'humidity' => $c['relative_humidity_2m'],
        'reason'   => $raining ? 'It is raining' : ($humid ? 'Humidity too high' : 'Low humidity, no rain'),
    ];
    file_put_contents($cache, json_encode($out));
    return $out;
}