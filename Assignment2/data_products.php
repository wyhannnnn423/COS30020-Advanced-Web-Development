<?php
// data_products.php: the shop catalogue, shared by product_list.php and the order pages.

function product_catalogue(): array
{
    return [
    // --- Model Kits ---
    'eg' => [
        ['id'=>'eg-wing', 'name'=>'EG Wing Gundam', 'image'=>'img/kits/eg-wing.jpg', 'hover_image'=>'img/kits/eg-wing-hover.jpg', 'price'=>'RM 42'],
        ['id'=>'eg-nu', 'name'=>'EG Nu Gundam', 'image'=>'img/kits/eg-nu.jpg', 'hover_image'=>'img/kits/eg-nu-hover.jpg', 'price'=>'RM 42'],
        ['id'=>'eg-rx78-2', 'name'=>'EG RX-78-2 Gundam', 'image'=>'img/kits/eg-rx78-2.jpg', 'hover_image'=>'img/kits/eg-rx78-2-hover.jpg', 'price'=>'RM     40'],
    ],
    'hg' => [
        ['id'=>'hg-freedom', 'name'=>'HG Mighty Strike Freedom Gundam', 'image'=>'img/kits/hg-freedom.jpg', 'hover_image'=>'img/kits/hg-freedom-hover.jpg', 'price'=>'RM 95'],
        ['id'=>'hg-destiny', 'name'=>'HG Destiny Gundam ', 'image'=>'img/kits/hg-destiny.jpg', 'hover_image'=>'img/kits/hg-destiny-hover.jpg', 'price'=>'RM 260'],
        ['id'=>'hg-justice', 'name'=>'HG Infinite Justice Gundam', 'image'=>'img/kits/hg-justice.jpg', 'hover_image'=>'img/kits/hg-justice-hover.jpg', 'price'=>'RM 98'],
        ['id'=>'hg-exia-r2', 'name'=>'HG Gundam Exia R2', 'image'=>'img/kits/hg-exia-r2.jpg', 'hover_image'=>'img/kits/hg-exia-r2-hover.jpg', 'price'=>'RM 56'],
        ['id'=>'hg-o', 'name'=>'HG O Gundam', 'image'=>'img/kits/hg-o.jpg', 'hover_image'=>'img/kits/hg-o-hover.jpg', 'price'=>'RM 56'],

    ],  
    'rg' => [
        ['id'=>'rg-epyon', 'name'=>'RG Gundam Epyon', 'image'=>'img/kits/rg-epyon.jpg', 'hover_image'=>'img/kits/rg-epyon-hover.jpg', 'price'=>'RM 147'],
        ['id'=>'rg-wing', 'name'=>'RG Wing Gundam Zero', 'image'=>'img/kits/rg-wing.jpg', 'hover_image'=>'img/kits/rg-wing-hover.jpg', 'price'=>'RM 148'],
    ],
    're100' => [
        ['id'=>'re-nightingale', 'name'=>'RE/100 Nightingale', 'image'=>'img/kits/re-nightingale.jpg', 'hover_image'=>'img/kits/re-nightingale-hover.jpg', 'price'=>'RM 299'],
    ],
    'fm' => [
        ['id'=>'fm-aerial', 'name'=>'FM Gundam Aerial', 'image'=>'img/kits/fm-aerial.jpg', 'hover_image'=>'img/kits/fm-aerial-hover.jpg', 'price'=>'RM 175'],
    ],
    'mg' => [
        ['id'=>'mg-ex-s', 'name'=>'MG Ex-S Gundam', 'image'=>'img/kits/mg-ex-s.jpg', 'hover_image'=>'img/kits/mg-ex-s-hover.jpg', 'price'=>'RM 460'],
    ],
    'pg' => [
        ['id'=>'pg-unleashed', 'name'=>'PG Unleashed RX-78-2 Gundam', 'image'=>'img/kits/pg-unleashed.jpg', 'hover_image'=>'img/kits/pg-unleashed-hover.jpg', 'price'=>'RM 1,000'],
    ],
    'sd' => [
        ['id'=>'sd-cs-rx78', 'name'=>'SDCS RX-78-2 Gundam', 'image'=>'img/kits/sd-cs-rx78.jpg', 'hover_image'=>'img/kits/sd-cs-rx78-hover.jpg', 'price'=>'RM 35'],
    ],

    // --- Figures ---
    'robot-spirits' => [
        ['id'=>'rs-rx78-anime', 'name'=>'The Robot Spirits RX-78-2 (A.N.I.M.E.)', 'image'=>'img/kits/rs-rx78-anime.jpg', 'hover_image'=>'img/kits/rs-rx78-anime-hover.jpg', 'price'=>'RM 240'],
    ],
    'metal-robot-spirits' => [
        ['id'=>'mrs-barbatos-lupus', 'name'=>'Metal Robot Spirits Barbatos Lupus Rex', 'image'=>'img/kits/mrs-barbatos-lupus.jpg', 'hover_image'=>'img/kits/mrs-barbatos-lupus-hover.jpg', 'price'=>'RM1100'],
    ],
    'metal-build' => [
        ['id'=>'mb-freedom', 'name'=>'Metal Build Freedom Gundam', 'image'=>'img/kits/mb-freedom.jpg', 'hover_image'=>'img/kits/mb-freedom-hover.jpg', 'price'=>'RM 1300'],
    ],
    'nxedge-style' => [
        ['id'=>'nx-xi', 'name'=>'NXEDGE STYLE Xi Gundam', 'image'=>'img/kits/nx-xi.jpg', 'hover_image'=>'img/kits/nx-xi-hover.jpg', 'price'=>'RM 200'],
        ['id'=>'nx-penelope', 'name'=>'NXEDGE STYLE Penelope ', 'image'=>'img/kits/nx-penelope.jpg', 'hover_image'=>'img/kits/nx-penelope-hover.jpg', 'price'=>'RM 200'],

    ],
    'gundam-universe' => [
        ['id'=>'gu-wing-zero', 'name'=>'Gundam Universe Gquuuuuux ', 'image'=>'img/kits/gu-gqux.jpg', 'hover_image'=>'img/kits/gu-gqux-hover.jpg', 'price'=>'RM 150'],
    ],
    'gffmc' => [
        ['id'=>'gffmc-wing-zero', 'name'=>'GFFMC Wing Gundam Snow White Prelude', 'image'=>'img/kits/gffmc-wing-zero.jpg', 'hover_image'=>'img/kits/gffmc-wing-zero-hover.jpg', 'price'=>'RM 1,350'],
    ],
    ];
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

// "RM 1,350" / "RM1100" / "RM     40"  ->  1350.00 / 1100.00 / 40.00
function product_price(array $product): float
{
    return (float)preg_replace('/[^0-9.]/', '', $product['price']);
}
