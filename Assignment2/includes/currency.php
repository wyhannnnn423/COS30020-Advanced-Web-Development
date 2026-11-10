<?php
// includes/currency.php: JPY -> MYR conversion for the shop.
// Kit prices are stored in Japanese yen (data_products.php). The live rate comes from the
// Frankfurter API (no key needed) and is cached for 1 hour. If the API cannot be reached,
// the last known rate is used, and failing that the fallback rate below.

const JPY_MYR_FALLBACK = 0.025;   // approximate rate, used only when no live or cached rate exists
const SHOP_MARKUP      = 1.0;     // 1.0 = straight conversion; 1.3 would add 30% (shipping, import, margin)
const RATE_CACHE_SECS  = 3600;

// 1 JPY in MYR, e.g. 0.0250
function get_jpy_to_myr(): float
{
    static $rate = null;          // one lookup per page load, however many prices are shown
    if ($rate !== null) {
        return $rate;
    }

    $cache = __DIR__ . '/rate_cache.json';
    $saved = file_exists($cache) ? json_decode(file_get_contents($cache), true) : null;

    // A fresh cached rate is good enough
    if (isset($saved['rate']) && time() - filemtime($cache) < RATE_CACHE_SECS) {
        return $rate = (float)$saved['rate'];
    }

    $res = false;
    if (function_exists('curl_init')) {
        $ch = curl_init('https://api.frankfurter.dev/v1/latest?base=JPY&symbols=MYR');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 3]);
        $res = curl_exec($ch);
        curl_close($ch);
    }
    $data = $res ? json_decode($res, true) : null;

    if (isset($data['rates']['MYR']) && $data['rates']['MYR'] > 0) {
        $rate = (float)$data['rates']['MYR'];
    } else {
        // API failed: keep the old rate (or the fallback) and rewrite the cache so we only
        // retry once an hour instead of making every page wait for the timeout.
        $rate = isset($saved['rate']) ? (float)$saved['rate'] : JPY_MYR_FALLBACK;
    }
    @file_put_contents($cache, json_encode(['rate' => $rate]));
    return $rate;
}

// 2980 -> 74.50 (exact conversion, 2 decimals)
function jpy_to_myr(int $jpy): float
{
    return round($jpy * get_jpy_to_myr(), 2);
}

// The shop price in whole ringgit, including the markup: 2000 -> 50
function shop_price_rm(int $jpy): float
{
    return max(1, round($jpy * get_jpy_to_myr() * SHOP_MARKUP));
}

// 2980 -> "¥2,980"
function format_yen(int $jpy): string
{
    return '¥' . number_format($jpy);
}

// Display helper: "RM 74.50 (¥2,980)"
function format_price(int $jpy): string
{
    return 'RM ' . number_format(jpy_to_myr($jpy), 2) . ' (' . format_yen($jpy) . ')';
}

// Short note for shop pages: "¥1 = RM 0.0250"
function rate_note(): string
{
    return '¥1 = RM ' . number_format(get_jpy_to_myr(), 4);
}
