<?php
// Reusable paint-weather indicator. Styles live in the main stylesheet (.weather-fab).
require_once __DIR__ . '/weather.php';
$w = getPaintWeather();
if ($w): ?>
<div class="weather-fab <?= $w['ok'] ? 'good' : 'bad' ?>" tabindex="0">
    <span class="weather-icon"><?= $w['icon'] ?></span>
    <div class="weather-tip">
        <strong><?= $w['ok'] ? 'Good day to spray paint!' : 'Not suitable for spray painting today' ?></strong><br>
        <?= $w['reason'] ?> · <?= $w['temp'] ?>°C · <?= $w['humidity'] ?>% humidity
    </div>
</div>
<?php endif; ?>