<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/admin_common.php';
require_once 'includes/stock.php';
require_once 'data_products.php';
require_role('admin');   // admins only, otherwise redirect to login.php

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_products.php');
    }

    $pid       = (string)($_POST['product_id'] ?? '');
    $action    = $_POST['action'] ?? '';
    $back      = 'manage_products.php' . (!empty($_POST['return']) ? '?' . preg_replace('/[^A-Za-z0-9_=&%.\-]/', '', (string)$_POST['return']) : '');

    // Only products that exist in the catalogue can be changed.
    if (find_product($pid) === null) {
        flash('err', 'Unknown product.');
        redirect_to($back);
    }

    if ($action === 'save') {
        $stock     = filter_var($_POST['stock'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 9999]]);
        $available = isset($_POST['available']) ? 1 : 0;

        if ($stock === false) {
            flash('err', 'Stock must be a whole number from 0 to 9999.');
        } else {
            $stmt = $db->prepare(
                'INSERT INTO product_stock (product_id, stock, available) VALUES (?, ?, ?)
                 ON DUPLICATE KEY UPDATE stock = VALUES(stock), available = VALUES(available)'
            );
            $stmt->bind_param('sii', $pid, $stock, $available);
            $stmt->execute();
            flash('ok', 'Saved: ' . trim(find_product($pid)['name']) . ' (stock ' . $stock . ', ' . ($available ? 'available' : 'switched off') . ').');
        }
    } elseif ($action === 'soldout') {
        $stmt = $db->prepare(
            'INSERT INTO product_stock (product_id, stock, available) VALUES (?, 0, 1)
             ON DUPLICATE KEY UPDATE stock = 0'
        );
        $stmt->bind_param('s', $pid);
        $stmt->execute();
        flash('ok', trim(find_product($pid)['name']) . ' marked as sold out.');
    }
    redirect_to($back);
}

// ---------------------------------------------------- list: search, filter, pages
$stock_map = fetch_stock_map($db);
$filters   = ['in' => 'In stock', 'low' => 'Low stock (3 or fewer)', 'out' => 'Sold out', 'off' => 'Switched off'];

$q      = trim($_GET['q'] ?? '');
$filter = $_GET['status'] ?? '';
$filter = isset($filters[$filter]) ? $filter : '';
$pg     = max(1, (int)($_GET['page'] ?? 1));

$all = [];
foreach (product_catalogue() as $type => $list) {
    foreach ($list as $p) {
        $p['type'] = $type;
        $p['st']   = stock_for($stock_map, $p['id']);
        $all[]     = $p;
    }
}

$matches = array_values(array_filter($all, function ($p) use ($q, $filter) {
    if ($q !== '' && stripos($p['name'] . ' ' . $p['id'] . ' ' . $p['type'], $q) === false) {
        return false;
    }
    $s = $p['st'];
    switch ($filter) {
        case 'in':  return $s['available'] && $s['stock'] > 3;
        case 'low': return $s['available'] && $s['stock'] > 0 && $s['stock'] <= 3;
        case 'out': return $s['stock'] <= 0;
        case 'off': return !$s['available'];
        default:    return true;
    }
}));

$total = count($matches);
$pages = max(1, (int)ceil($total / ADMIN_PER_PAGE));
$pg    = min($pg, $pages);
$rows  = array_slice($matches, ($pg - 1) * ADMIN_PER_PAGE, ADMIN_PER_PAGE);

// Where to come back to after saving (keeps the search, filter and page)
$return = http_build_query(array_filter(['q' => $q, 'status' => $filter, 'page' => $pg > 1 ? $pg : null], fn($v) => $v !== '' && $v !== null));

$page_title = 'Products';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Manage Products</h1>
    <p class="lead">Control what the shop can sell. Set how many are in stock, or switch a product off. Sold out and switched off products cannot be bought.</p>

    <?php render_flash(); ?>

    <?php render_admin_search('manage_products.php', $q, $filter, $filters, 'Stock'); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Photo</th><th>Product</th><th>Price</th><th>Status</th><th>Stock and availability</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="5">No products match.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $p): $s = $p['st']; ?>
                <tr>
                    <td><img class="thumb" src="<?= htmlspecialchars($p['image']) ?>" alt=""></td>
                    <td><?= htmlspecialchars(trim($p['name'])) ?><br><span class="form-hint"><?= htmlspecialchars($p['id']) ?></span></td>
                    <td><?= htmlspecialchars(trim($p['price'])) ?></td>
                    <td>
                        <?php if (!$s['available']): ?>
                            <span class="status-badge status-rejected">Switched off</span>
                        <?php elseif ($s['stock'] <= 0): ?>
                            <span class="status-badge status-rejected">Sold out</span>
                        <?php elseif ($s['stock'] <= 3): ?>
                            <span class="status-badge status-pending">Low: <?= (int)$s['stock'] ?></span>
                        <?php else: ?>
                            <span class="status-badge status-approved">In stock: <?= (int)$s['stock'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" class="inline-form stock-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                            <input type="hidden" name="return" value="<?= htmlspecialchars($return) ?>">
                            <input type="number" name="stock" min="0" max="9999" value="<?= (int)$s['stock'] ?>" aria-label="Stock">
                            <label class="check"><input type="checkbox" name="available" value="1" <?= $s['available'] ? 'checked' : '' ?>> On sale</label>
                            <button type="submit" name="action" value="save" class="btn-sm btn-ok">Save</button>
                            <button type="submit" name="action" value="soldout" class="btn-sm btn-warn">Sold out</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php render_pager('manage_products.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
