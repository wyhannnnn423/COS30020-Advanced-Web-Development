<?php 
$page_title = 'Product List'; 
include 'header.php'; 

$type_names = [
    // Grades
    'eg' => 'Entry Grade (EG)',
    'hg' => 'High Grade (HG)',
    'rg' => 'Real Grade (RG)',
    're100' => 'RE/100 (Reborn-One Hundred)',
    'fm' => 'Full Mechanics (FM)',
    'mg' => 'Master Grade (MG)',
    'pg' => 'Perfect Grade (PG)',
    'sd' => 'SD (Super Deformed)',
    // Figures
    'robot-spirits' => 'The Robot Spirits',
    'metal-robot-spirits' => 'Metal Robot Spirits',
    'metal-build' => 'Metal Build',
    'nxedge-style' => 'NXEDGE STYLE',
    'gundam-universe' => 'Gundam Universe (GU)',
    'gffmc' => 'Gundam Fix Figuration Metal Composite',
];

$banners = [
    'eg'                   => 'img/banner/banner-eg.jpg',
    'hg'                   => 'img/banner/banner-hg.jpg',
    'rg'                   => 'img/banner/banner-rg.jpg',
    're100'                => 'img/banner/banner-re100.jpg',
    'fm'                   => 'img/banner/banner-fm.jpg',
    'mg'                   => 'img/banner/banner-mg.jpg',
    'pg'                   => 'img/banner/banner-pg.jpg',
    'sd'                   => 'img/banner/banner-sd.jpg',
    'robot-spirits'        => 'img/banner/banner-robot-spirits.jpg',
    'metal-robot-spirits'  => 'img/banner/banner-metal-robot-spirits.jpg',
    'metal-build'          => 'img/banner/banner-metal-build.jpg',
    'nxedge-style'         => 'img/banner/banner-nxedge-style.jpg',
    'gundam-universe'      => 'img/banner/banner-gundam-universe.jpg',
    'gffmc'                => 'img/banner/banner-gffmc.jpg',
];

$products = [
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
        ['id'=>'nx-hi-nu', 'name'=>'NXEDGE STYLE Xi Gundam', 'image'=>'img/kits/nx-xi.jpg', 'hover_image'=>'img/kits/nx-xi-hover.jpg', 'price'=>'RM 200'],
        ['id'=>'nx-hi-nu', 'name'=>'NXEDGE STYLE Penelope ', 'image'=>'img/kits/nx-penelope.jpg', 'hover_image'=>'img/kits/nx-penelope-hover.jpg', 'price'=>'RM 200'],

    ],
    'gundam-universe' => [
        ['id'=>'gu-wing-zero', 'name'=>'Gundam Universe Gquuuuuux ', 'image'=>'img/kits/gu-gqux.jpg', 'hover_image'=>'img/kits/gu-gqux-hover.jpg', 'price'=>'RM 150'],
    ],
    'gffmc' => [
        ['id'=>'gffmc-wing-zero', 'name'=>'GFFMC Wing Gundam Snow White Prelude', 'image'=>'img/kits/gffmc-wing-zero.jpg', 'hover_image'=>'img/kits/gffmc-wing-zero-hover.jpg', 'price'=>'RM 1,350'],
    ],
];

$type = $_GET['type'] ?? '';
$type_name = $type_names[$type] ?? null;
$current_banner = $banners[$type] ?? 'img/banner/Menu.jpg';
$list = $products[$type] ?? [];

$figure_types = ['robot-spirits', 'metal-robot-spirits', 'metal-build', 'nxedge-style', 'gundam-universe', 'gffmc'];
$back_anchor = in_array($type, $figure_types, true) ? 'cat-collector-figures' : 'cat-plastic-models';
?>

<section class="menu-screen" style="min-height: 45vh; height: 45vh;">
    <img src="<?php echo htmlspecialchars($current_banner); ?>" 
         alt="<?php echo htmlspecialchars($type_name ? $type_name : 'Banner'); ?>" 
         class="menu-bg" 
         style="min-height: 45vh;"
         onerror="this.src='img/banner/Menu.jpg';">
    <div class="menu-overlay"></div>
    <div class="menu-wordmark" style="bottom: 2rem;">
        <?php echo $type_name ? htmlspecialchars($type_name) : 'Product List'; ?>
    </div>
</section>

<main class="page products-page">
<?php if ($type_name && count($list) > 0): ?>

    <div class="products-grid">
        <?php foreach ($list as $product): ?>
        <div class="product-card">
            <div class="product-thumb">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="img-default">
                
                <?php if (!empty($product['hover_image'])): ?>
                <img src="<?php echo htmlspecialchars($product['hover_image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?> Alt View" 
                     class="img-hover">
                <?php endif; ?>
            </div>  
           <div class="product-card-body">
                <h3><?= htmlspecialchars($product['name']); ?></h3>
                <span class="product-price"><?= htmlspecialchars($product['price']); ?></span>
                <!-- Disabled button placeholder reserved for Assignment 2 -->
                <button type="button" 
                        class="card-btn btn-disabled" 
                        disabled 
                        title="Order feature coming in Assignment 2">
                    Buy (Assignment 2)
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
    <div class="detail-not-found">
        <h1>No products found</h1>
        <p>This category is being stocked. Check back soon.</p>
    </div>
<?php endif; ?>

    <div class="page-action-footer">
        <a href="series.php#<?php echo $back_anchor; ?>" class="card-btn card-btn-outline">Back to Series Guide</a>
    </div>

    
</main>

<?php include 'footer.php'; ?>