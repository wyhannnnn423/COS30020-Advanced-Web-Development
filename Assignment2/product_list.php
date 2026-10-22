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

require_once 'data_products.php';   // $products, shared with the order pages
require_once 'includes/db.php';
require_once 'includes/stock.php';
$stock_map = fetch_stock_map(get_db());   // stock and availability set by the admin

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
        <?php $st = stock_for($stock_map, $product['id']); $label = stock_label($st); $buyable = is_buyable($st); ?>
        <div class="product-card<?= $buyable ? '' : ' is-out' ?>">
            <div class="product-thumb">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="img-default">
                
                <?php if (!empty($product['hover_image'])): ?>
                <img src="<?php echo htmlspecialchars($product['hover_image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?> Alt View" 
                     class="img-hover">
                <?php endif; ?>

                <?php if ($label !== ''): ?>
                    <span class="stock-badge <?= $buyable ? 'stock-badge-low' : 'stock-badge-out' ?>"><?= htmlspecialchars($label) ?></span>
                <?php endif; ?>
            </div>  
           <div class="product-card-body">
                <h3><?= htmlspecialchars($product['name']); ?></h3>
                <span class="product-price"><?= htmlspecialchars($product['price']); ?></span>
                <?php if ($buyable): ?>
                    <a href="order.php?id=<?= urlencode($product['id']) ?>" class="card-btn">Buy</a>
                <?php else: ?>
                    <span class="card-btn card-btn-disabled">Not available</span>
                <?php endif; ?>
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