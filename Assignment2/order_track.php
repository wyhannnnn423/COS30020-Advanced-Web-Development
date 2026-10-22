<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/orders.php';
require_role('user');

$db = get_db();
$id = (int)($_GET['id'] ?? 0);

$order = null;
if ($id > 0) {
    // Only the customer's own order can be opened.
    $q = $db->prepare('SELECT * FROM order_table WHERE id = ? AND email = ?');
    $q->bind_param('is', $id, $_SESSION['email']);
    $q->execute();
    $order = $q->get_result()->fetch_assoc();
}

$orders = [];
if (!$order) {
    $q = $db->prepare('SELECT * FROM order_table WHERE email = ? ORDER BY id DESC');
    $q->bind_param('s', $_SESSION['email']);
    $q->execute();
    $orders = $q->get_result()->fetch_all(MYSQLI_ASSOC);
}

$page_title = 'My Orders';
include 'header.php';
?>

<main class="page order-page">
<?php if ($order): ?>
    <?php $reached = ORDER_STEP_OF[$order['status']] ?? 0; ?>
    <a href="order_track.php" class="order-back">&larr; All orders</a>
    <h1>Order <?= htmlspecialchars(order_ref((int)$order['id'])) ?></h1>
    <p class="order-sub"><?= order_badge($order['status']) ?> &nbsp; Placed <?= htmlspecialchars(date('j M Y, g:i A', strtotime($order['created_at']))) ?></p>

    <ol class="order-steps">
        <?php foreach (ORDER_STEPS as $i => $label): ?>
            <li class="<?= ($i <= $reached && $order['status'] !== 'rejected') || ($order['status'] === 'rejected' && $i === 0) ? 'done' : '' ?><?= ($i === $reached) ? ' current' : '' ?>">
                <span class="dot"></span><?= htmlspecialchars($label) ?>
            </li>
        <?php endforeach; ?>
    </ol>

    <?php if ($order['status'] === 'rejected'): ?>
        <p class="form-errors">Your payment proof could not be verified. <a href="order_pay.php?id=<?= (int)$order['id'] ?>">Upload a new proof</a>.</p>
    <?php elseif ($order['status'] === 'awaiting_payment'): ?>
        <p class="order-note">We are waiting for your payment. <a href="order_pay.php?id=<?= (int)$order['id'] ?>">Pay now</a>.</p>
    <?php endif; ?>

    <?php if (!empty($order['tracking_number'])): ?>
        <p class="order-note">Tracking number: <strong><?= htmlspecialchars($order['tracking_number']) ?></strong></p>
    <?php endif; ?>

    <div class="order-detail-grid">
        <div class="profile-side">
            <h2>Item</h2>
            <p><?= htmlspecialchars($order['product_name']) ?> &times; <?= (int)$order['quantity'] ?></p>
            <p>Total: <strong><?= money(order_total($order)) ?></strong></p>
            <?php if (!empty($order['payment_proof']) && is_file($order['payment_proof'])): ?>
                <p><a href="<?= htmlspecialchars($order['payment_proof']) ?>" target="_blank">View my payment proof</a></p>
            <?php endif; ?>
        </div>
        <div class="profile-side">
            <h2>Deliver to</h2>
            <p><?= htmlspecialchars($order['recipient_name']) ?><br>
               <?= htmlspecialchars($order['phone']) ?><br>
               <?= htmlspecialchars($order['address']) ?><br>
               <?= htmlspecialchars($order['postcode'] . ' ' . $order['city']) ?>, <?= htmlspecialchars($order['state']) ?></p>
        </div>
    </div>

<?php else: ?>
    <h1>My Orders</h1>
    <p class="order-sub">Track your Gunpla orders here.</p>

    <?php if (empty($orders)): ?>
        <p class="bookings-empty">You have not ordered anything yet. <a href="series.php">Browse kits</a> to get started.</p>
    <?php else: ?>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Order</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= htmlspecialchars(order_ref((int)$o['id'])) ?></td>
                    <td><?= htmlspecialchars($o['product_name']) ?> &times; <?= (int)$o['quantity'] ?></td>
                    <td><?= money(order_total($o)) ?></td>
                    <td><?= order_badge($o['status']) ?></td>
                    <td><a href="order_track.php?id=<?= (int)$o['id'] ?>" class="btn-sm">Track</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
<?php endif; ?>
</main>

<?php include 'footer.php'; ?>
