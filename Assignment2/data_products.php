<?php
// data_products.php: the shop catalogue, shared by product_list.php, the order pages and the
// admin product page.
//
// Prices are stored in Japanese yen (the official kit price) and converted to RM with the live
// exchange rate from includes/currency.php. The RM price is rounded to a whole ringgit.
//
// To add a product, add one line to the right group below:
//     kit('id', 'Name shown in the shop', 2980),
//   - id    : lower case, unique. The photos are img/kits/<id>.jpg and img/kits/<id>-hover.jpg.
//   - price : the price in YEN (2980 means "JPY 2,980"). The shop shows it in RM with the yen price beside it.
//   - A 4th value only if the photo has a different file name: kit('id', 'Name', 6000, 'other-name').
// The order of the lines is the order in the shop.

require_once __DIR__ . '/includes/currency.php';

// One product. Everything that can be worked out from the id is worked out here, once.
function kit(string $id, string $name, int $jpy, ?string $photo = null): array
{
    $photo = $photo ?? $id;
    $rm    = shop_price_rm($jpy);
    return [
        'id'          => $id,
        'name'        => $name,
        'image'       => "img/kits/$photo.jpg",
        'hover_image' => "img/kits/$photo-hover.jpg",
        'jpy'         => $jpy,                   // the price in yen (the stored price)
        'amount'      => $rm,                    // the converted RM number, used for ordering
        'price'       => format_rm($rm),         // the RM text, shown in the shop
        'price_jpy'   => format_yen($jpy),       // the yen text, shown beside it
    ];
}

// 80 -> "RM 80", 1500 -> "RM 1,500", 52.5 -> "RM 52.50"
function format_rm(float $amount): string
{
    return 'RM ' . number_format($amount, floor($amount) == $amount ? 0 : 2);
}

function product_catalogue_raw(): array
{
    return [
    'eg' => [
        kit('eg-wing', 'EG Wing Gundam', 1100),
        kit('eg-nu', 'EG Nu Gundam', 1100),
        kit('eg-rx78-2', 'EG RX-78-2 Gundam', 770),
    ],
    'hg' => [
        kit('hg-freedom', 'HG Mighty Strike Freedom Gundam', 2970),
        kit('hg-destiny', 'HG 1/144 Destiny Gundam Spec II & Zeus Silhouette', 7920),
        kit('hg-justice', 'HG Infinite Justice Gundam', 2420),
        kit('hg-exia-r2', 'HG Gundam Exia R2', 1320),
        kit('hg-o', 'HG O Gundam', 1320),
        kit('hg-00-seven-sword', 'HG Gundam 00 Seven Sword/G', 1760),
        kit('hg-lightning-bw', 'HGBF Gundam Lightning Black Warrior', 1980),
        kit('hg-kamiki-burning', 'HGBF Kamiki Burning Gundam', 1980),
        kit('hg-sengoku-astray', 'HGBF Sengoku Astray Gundam', 1980),
        kit('hg-astray-no-name', 'HGBD Gundam Astray No-Name', 1980),
        kit('hg-00-sky', 'HGBD Gundam 00 Sky (Higher Than Sky Phase)', 2420),
        kit('hg-wing-zero-honoo', 'HGBF Wing Gundam Zero Honoo', 1980),
        kit('hguc-super-gundam', 'HGUC Super Gundam', 2200),
        kit('hguc-zeta', 'HGUC Zeta Gundam', 1980),
        kit('hguc-chars-zaku', 'HGUC Char\'s Zaku II', 1760),
        kit('hguc-banshee', 'HGUC Unicorn Gundam 02 Banshee (Destroy Mode)', 1980),
        kit('hguc-mk2', 'HGUC RX-178 Gundam Mk-II (A.E.U.G.)', 1650),
        kit('hguc-dijeh', 'HGUC Dijeh', 2090),
        kit('hguc-v2-assault-buster', 'HGUC V2 Assault Buster Gundam', 2200),
        kit('hguc-ground-type', 'HGUC RX-79[G] Gundam Ground Type', 1870),
        kit('hg-double-x', 'HGAW Gundam Double X', 1870),
        kit('hgac-deathscythe-hell', 'HGAC Gundam Deathscythe Hell', 2420),
        kit('hg-altron', 'HGAC Altron Gundam', 2640),
        kit('hg-load-astray-omega', 'HGCE Load Astray Ω', 2640),
        kit('hg-uraven', 'HGBD:R Uraven Gundam', 1760),
        kit('hg-flauros', 'HGIBO Gundam Flauros', 1540),
        kit('hg-grimgerde', 'HGIBO Gundam Grimgerde', 1320),
    ],
    'rg' => [
        kit('rg-epyon', 'RG Gundam Epyon', 4620),
        kit('rg-wing', 'RG Wing Gundam Zero (TV Ver.)', 4620),
        kit('rg-exia', 'RG Gundam Exia', 2750),
        kit('rg-hi-nu', 'RG Hi-Nu Gundam', 4950),
        kit('rg-impulse', 'RG Impulse Gundam', 3300),
        kit('rg-god', 'RG God Gundam', 3850),
        kit('rg-banshee-norn', 'RG Unicorn Gundam 02 Banshee Norn', 4400),
        kit('rg-crossbone-x1', 'RG Crossbone Gundam X1', 2750),
    ],
    're100' => [
        kit('re-nightingale', 'RE/100 Nightingale', 8800),
    ],
    'fm' => [
        kit('fm-aerial', 'FM Gundam Aerial', 4180),
    ],
    'mg' => [
        kit('mg-ex-s', 'MG Ex-S Gundam', 13200),
        kit('mg-deathscythe-hell-ew', 'MG Gundam Deathscythe Hell EW', 4950),
        kit('mg-zz-ver-ka', 'MG ZZ Gundam Ver.Ka', 6600),
        kit('mg-full-armor-ver-ka', 'MG Full Armor Gundam Ver.Ka', 7700),
        kit('mg-f91', 'MG Gundam F91 Ver.2.0', 4400),
        kit('mg-vidar', 'MG Gundam Vidar', 6600),
        kit('mg-wing-zero-ew-ver-ka', 'MG Wing Gundam Zero EW Ver.Ka', 6050),
        kit('mg-mk-v', 'MG Gundam Mk-V', 8800),
    ],
    'pg' => [
        kit('pg-unleashed', 'PG Unleashed RX-78-2 Gundam', 27500),
    ],
    'sd' => [
        kit('sd-cs-rx78', 'SDCS RX-78-2 Gundam', 880),
        kit('sd-cs-zeta', 'SDCS Zeta Gundam', 1100),
        kit('sd-00-diver-ace', 'SD Gundam 00 Diver Ace', 1100),
        kit('sd-cs-phoenix', 'SDCS Phoenix Gundam', 1540),
        kit('sd-cs-banshee-set', 'SDCS Unicorn Gundam 02 Banshee (Destroy Mode) & Banshee Norn Parts Set', 2200),
        kit('sd-bd-ex-valkylander', 'SDBD:R EX Valkylander', 1430),
        kit('sd-liu-bei', 'SD Gundam Sangoku Soketsuden Liu Bei Unicorn Gundam', 660),
        kit('sd-guan-yu-yun-chang-v', 'SD Gundam Sangoku Soketsuden Guan Yu Yun Chang V Gundam', 660),
        kit('sd-zhang-fei-god', 'SD Gundam Sangoku Soketsuden Zhang Fei God Gundam', 660),
        kit('sd-gan-ning-crossbone', 'SD Gundam Sangoku Soketsuden Gan Ning Crossbone Gundam', 770),
        kit('sd-sima-yi-destiny', 'SD Gundam Sangoku Soketsuden Sima Yi Destiny Gundam', 1100),
    ],
    'robot-spirits' => [
        kit('rs-rx78-anime', 'The Robot Spirits RX-78-2 (A.N.I.M.E.)', 6600),
        kit('rs-crossbone-x1-fc', 'The Robot Spirits Crossbone Gundam X1 Full Cloth', 5500),
        kit('rs-hyaku-shiki', 'The Robot Spirits Hyaku Shiki', 7150),
        kit('rs-exia', 'The Robot Spirits Gundam Exia', 3080),
        kit('rs-age1-spallow', 'The Robot Spirits Gundam AGE-1 Spallow', 3850),
        kit('rs-gm-sniper-ii', 'The Robot Spirits GM Sniper II (ver. A.N.I.M.E.)', 6600),
        kit('rs-virsago-cb', 'The Robot Spirits Virsago Chest Break', 6050),
        kit('rs-00-qant-trans-am', 'The Robot Spirits 00 Qan[T] (Trans-Am Ver.)', 4400),
    ],
    'metal-robot-spirits' => [
        kit('mrs-barbatos-lupus', 'Metal Robot Spirits Barbatos Lupus Rex', 14300),
    ],
    'metal-build' => [
        kit('mb-freedom', 'Metal Build Freedom Gundam Concept 2', 26400),
    ],
    'nxedge-style' => [
        kit('nx-xi', 'NXEDGE STYLE Xi Gundam', 6160),
        kit('nx-penelope', 'NXEDGE STYLE Penelope', 7480),
    ],
    'gundam-universe' => [
        kit('gu-wing-zero', 'Gundam Universe Gquuuuuux', 5500, 'gu-gqux'),
    ],
    'gffmc' => [
        kit('gffmc-wing-zero', 'GFFMC Wing Gundam Snow White Prelude', 29700),
    ],
    ];
}


// The catalogue as the pages use it. A hover photo whose file does not exist is dropped,
// so that product simply has no hover effect.
function product_catalogue(): array
{
    $catalogue = product_catalogue_raw();
    foreach ($catalogue as $type => $list) {
        foreach ($list as $i => $p) {
            if (!is_file($p['hover_image'])) {
                unset($catalogue[$type][$i]['hover_image']);
            }
        }
    }
    return $catalogue;
}

// True when the product's main photo file exists. The shop only lists products that have a
// photo, so a kit appears as soon as its picture is saved in img/kits/.
function product_has_photo(array $p): bool
{
    return is_file($p['image']);
}

// Kept as a plain variable too, because product_list.php loops over it.
$products = product_catalogue();

// Finds one product by id. Returns the product array (with its list key in 'type') or null.
function find_product(string $id): ?array
{
    foreach (product_catalogue() as $type => $list) {
        foreach ($list as $p) {
            if ($p['id'] === $id) {
                $p['type'] = $type;
                return $p;
            }
        }
    }
    return null;
}

// The price as a number, for working out an order total.
function product_price(array $product): float
{
    return (float)$product['amount'];
}
