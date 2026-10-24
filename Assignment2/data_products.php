<?php
// data_products.php: the shop catalogue, shared by product_list.php, the order pages and the
// admin product page.
//
// To add a product, add one line to the right group below:
//     kit('id', 'Name shown in the shop', 80),
//   - id    : lower case, unique. The photos are img/kits/<id>.jpg and img/kits/<id>-hover.jpg.
//   - price : a number in RM (80 or 52.5), shown as "RM 80" / "RM 52.50".
//   - A 4th value only if the photo has a different file name: kit('id', 'Name', 150, 'other-name').
// The order of the lines is the order in the shop.

// One product. Everything that can be worked out from the id is worked out here, once.
function kit(string $id, string $name, float $price, ?string $photo = null): array
{
    $photo = $photo ?? $id;
    return [
        'id'          => $id,
        'name'        => $name,
        'image'       => "img/kits/$photo.jpg",
        'hover_image' => "img/kits/$photo-hover.jpg",
        'amount'      => $price,                 // the number, used for ordering
        'price'       => format_rm($price),      // the text, shown in the shop
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
        kit('eg-wing', 'EG Wing Gundam', 50),
        kit('eg-nu', 'EG Nu Gundam', 50),
        kit('eg-rx78-2', 'EG RX-78-2 Gundam', 50),
    ],
    'hg' => [
        kit('hg-freedom', 'HG Mighty Strike Freedom Gundam', 100),
        kit('hg-destiny', 'HG Destiny Gundam', 260),
        kit('hg-justice', 'HG Infinite Justice Gundam', 100),
        kit('hg-exia-r2', 'HG Gundam Exia R2', 60),
        kit('hg-o', 'HG O Gundam', 60),
        kit('hg-00-seven-sword', 'HG Gundam 00 Seven Sword/G', 80),
        kit('hg-lightning-bw', 'HGBF Gundam Lightning Black Warrior', 80),
        kit('hg-kamiki-burning', 'HGBF Kamiki Burning Gundam', 80),
        kit('hg-sengoku-astray', 'HGBF Sengoku Astray Gundam', 80),
        kit('hg-astray-no-name', 'HGBD Gundam Astray No-Name', 80),
        kit('hg-00-sky', 'HGBD Gundam 00 Sky (Higher Than Sky Phase)', 80),
        kit('hg-wing-zero-honoo', 'HGBF Wing Gundam Zero Honoo', 80),
        kit('hguc-super-gundam', 'HGUC Super Gundam', 80),
        kit('hguc-zeta', 'HGUC Zeta Gundam', 80),
        kit('hguc-chars-zaku', 'HGUC Char\'s Zaku II', 80),
        kit('hguc-banshee', 'HGUC Unicorn Gundam 02 Banshee (Destroy Mode)', 80),
        kit('hguc-mk2', 'HGUC RX-178 Gundam Mk-II (A.E.U.G.)', 80),
        kit('hguc-dijeh', 'HGUC Dijeh', 80),
        kit('hguc-v2-assault-buster', 'HGUC V2 Assault Buster Gundam', 80),
        kit('hguc-ground-type', 'HGUC RX-79[G] Gundam Ground Type', 80),
        kit('hg-double-x', 'HGAW Gundam Double X', 80),
        kit('hgac-deathscythe-hell', 'HGAC Gundam Deathscythe Hell', 100),
        kit('hg-altron', 'HGAC Altron Gundam', 100),
        kit('hg-load-astray-omega', 'HGCE Load Astray Ω', 100),
        kit('hg-uraven', 'HGBD:R Uraven Gundam', 80),
        kit('hg-flauros', 'HGIBO Gundam Flauros', 60),
        kit('hg-grimgerde', 'HGIBO Gundam Grimgerde', 80),
    ],
    'rg' => [
        kit('rg-epyon', 'RG Gundam Epyon', 150),
        kit('rg-wing', 'RG Wing Gundam Zero', 150),
        kit('rg-exia', 'RG Gundam Exia', 150),
        kit('rg-hi-nu', 'RG Hi-Nu Gundam', 200),
        kit('rg-impulse', 'RG Impulse Gundam', 150),
        kit('rg-god', 'RG God Gundam', 150),
        kit('rg-banshee-norn', 'RG Unicorn Gundam 02 Banshee Norn', 150),
        kit('rg-crossbone-x1', 'RG Crossbone Gundam X1', 150),
    ],
    're100' => [
        kit('re-nightingale', 'RE/100 Nightingale', 300),
    ],
    'fm' => [
        kit('fm-aerial', 'FM Gundam Aerial', 200),
    ],
    'mg' => [
        kit('mg-ex-s', 'MG Ex-S Gundam', 600),
        kit('mg-deathscythe-hell-ew', 'MG Gundam Deathscythe Hell EW', 160),
        kit('mg-zz-ver-ka', 'MG ZZ Gundam Ver.Ka', 200),
        kit('mg-full-armor-ver-ka', 'MG Full Armor Gundam Ver.Ka', 250),
        kit('mg-f91', 'MG Gundam F91 Ver.2.0', 150),
        kit('mg-vidar', 'MG Gundam Vidar', 160),
        kit('mg-wing-zero-ew-ver-ka', 'MG Wing Gundam Zero EW Ver.Ka', 200),
        kit('mg-mk-v', 'MG Gundam Mk-V', 300),
    ],
    'pg' => [
        kit('pg-unleashed', 'PG Unleashed RX-78-2 Gundam', 1500),
    ],
    'sd' => [
        kit('sd-cs-rx78', 'SDCS RX-78-2 Gundam', 40),
        kit('sd-cs-zeta', 'SDCS Zeta Gundam', 40),
        kit('sd-00-diver-ace', 'SD Gundam 00 Diver Ace', 40),
        kit('sd-cs-phoenix', 'SDCS Phoenix Gundam', 70),
        kit('sd-cs-banshee-set', 'SDCS Unicorn Gundam 02 Banshee (Destroy Mode) & Banshee Norn Parts Set', 70),
        kit('sd-bd-ex-valkylander', 'SDBD:R EX Valkylander', 100),
        kit('sd-liu-bei', 'SD Gundam Sangoku Soketsuden Liu Bei Unicorn Gundam', 30),
        kit('sd-guan-yu-yun-chang-v', 'SD Gundam Sangoku Soketsuden Guan Yu Yun Chang V Gundam', 30),
        kit('sd-zhang-fei-god', 'SD Gundam Sangoku Soketsuden Zhang Fei God Gundam', 30),
        kit('sd-gan-ning-crossbone', 'SD Gundam Sangoku Soketsuden Gan Ning Crossbone Gundam', 30),
        kit('sd-sima-yi-destiny', 'SD Gundam Sangoku Soketsuden Sima Yi Destiny Gundam', 50),
    ],

        // --- Figures ---
    'robot-spirits' => [
        kit('rs-rx78-anime', 'The Robot Spirits RX-78-2 (A.N.I.M.E.)', 250),
        kit('rs-crossbone-x1-fc', 'The Robot Spirits Crossbone Gundam X1 Full Cloth', 250),
        kit('rs-hyaku-shiki', 'The Robot Spirits Hyaku Shiki', 250),
        kit('rs-exia', 'The Robot Spirits Gundam Exia', 250),
        kit('rs-age1-spallow', 'The Robot Spirits Gundam AGE-1 Spallow', 100),
        kit('rs-gm-sniper-ii', 'The Robot Spirits GM Sniper II', 250),
        kit('rs-virsago-cb', 'The Robot Spirits Virsago Chest Break', 250),
        kit('rs-00-qant-trans-am', 'The Robot Spirits 00 Qan[T] (Trans-Am Ver.)', 300),
    ],
    'metal-robot-spirits' => [
        kit('mrs-barbatos-lupus', 'Metal Robot Spirits Barbatos Lupus Rex', 1000),
    ],
    'metal-build' => [
        kit('mb-freedom', 'Metal Build Freedom Gundam', 1500),
    ],
    'nxedge-style' => [
        kit('nx-xi', 'NXEDGE STYLE Xi Gundam', 200),
        kit('nx-penelope', 'NXEDGE STYLE Penelope', 200),
    ],
    'gundam-universe' => [
        kit('gu-wing-zero', 'Gundam Universe Gquuuuuux', 150, 'gu-gqux'),
    ],
    'gffmc' => [
        kit('gffmc-wing-zero', 'GFFMC Wing Gundam Snow White Prelude', 1500),
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
