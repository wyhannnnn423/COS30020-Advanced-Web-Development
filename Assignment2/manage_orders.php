<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/admin_common.php';
require_once 'includes/orders.php';
require_role('admin');   // admins only, otherwise redirect to login.php

$db = get_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_orders.php');
    }

    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'update') {
        $status   = $_POST['status'] ?? '';
        $tracking = trim($_POST['tracking_number'] ?? '');

        if (!isset(ORDER_STATUSES[$status])) {
            flash('err', 'Unknown status.');
        } elseif (mb_strlen($tracking) > 40) {
            flash('err', 'Tracking number must be 40 characters or fewer.');
        } elseif ($status === 'shipped' && $tracking === '') {
            flash('err', 'Please enter a tracking number before marking an order as shipped.');
        } else {
            $track = ($tracking === '') ? null : $tracking;
            $upd = $db->prepare('UPDATE order_table SET status = ?, tracking_number = ? WHERE id = ?');
            $upd->bind_param('ssi', $status, $track, $id);
            $upd->execute();
            flash('ok', 'Order ' . order_ref($id) . ' updated.');
        }
    } elseif ($action === 'delete') {
        $q = $db->prepare('SELECT payment_proof, product_id, quantity, status FROM order_table WHERE id = ?');
        $q->bind_param('i', $id);
        $q->execute();
        $old = $q->get_result()->fetch_assoc();

        $del = $db->prepare('DELETE FROM order_table WHERE id = ?');
        $del->bind_param('i', $id);
        $del->execute();
        if ($old) {
            delete_stored_file($old['payment_proof']);
            // An order that never left the shop gives its stock back.
            if (!in_array($old['status'], ['shipped', 'delivered'], true)) {
                $back = $db->prepare('UPDATE product_stock SET stock = stock + ? WHERE product_id = ?');
                $back->bind_param('is', $old['quantity'], $old['product_id']);
                $back->execute();
            }
        }
        flash('ok', 'Order deleted.');
    }
    redirect_to('manage_orders.php');
}

[$q, $filter, $pg] = admin_list_params(array_keys(ORDER_STATUSES));
$total = 0;
$rows  = admin_fetch_page($db, 'order_table', '*', ['email', 'recipient_name', 'product_name', 'tracking_number', 'city'],
                          'status',
                          "FIELD(status, 'pending', 'awaiting_payment', 'paid', 'packed', 'shipped', 'delivered', 'rejected'), id DESC",
                          $q, $filter, $pg, $total);

$page_title = 'Orders';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Manage Orders</h1>
    <p class="lead">Check each payment proof, then move the order along: confirm payment, pack, ship with a tracking number, deliver.</p>

    <?php render_flash(); ?>

    <?php render_admin_search('manage_orders.php', $q, $filter, ORDER_STATUSES); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Order</th><th>Customer</th><th>Item</th><th>Total</th><th>Deliver to</th><th>Proof</th><th>Status</th><th>Update</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="8">No orders yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $o): ?>
                <tr>
                    <td><?= htmlspecialchars(order_ref((int)$o['id'])) ?></td>
                    <td><?= htmlspecialchars($o['recipient_name']) ?><br><span class="form-hint"><?= htmlspecialchars($o['email']) ?></span></td>
                    <td><?= htmlspecialchars($o['product_name']) ?> &times; <?= (int)$o['quantity'] ?></td>
                    <td><?= money(order_total($o)) ?></td>
                    <td><?= htmlspecialchars($o['address'] . ', ' . $o['postcode'] . ' ' . $o['city'] . ', ' . $o['state']) ?><br>
                        <span class="form-hint"><?= htmlspecialchars($o['phone']) ?></span></td>
                    <td>
                        <?php if (!empty($o['payment_proof']) && is_file($o['payment_proof'])): ?>
                            <a href="<?= htmlspecialchars($o['payment_proof']) ?>" target="_blank"><img class="thumb" src="<?= htmlspecialchars($o['payment_proof']) ?>" alt="Payment proof"></a>
                        <?php else: ?>-<?php endif; ?>
                    </td>
                    <td><?= order_badge($o['status']) ?></td>
                    <td>
                        <form method="POST" class="inline-form order-update">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
                            <select name="status">
                                <?php foreach (ORDER_STATUSES as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= ($o['status'] === $value) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="tracking_number" maxlength="40" placeholder="Tracking no." value="<?= htmlspecialchars($o['tracking_number'] ?? '') ?>">
                            <button type="submit" name="action" value="update" class="btn-sm btn-ok">Save</button>
                        </form>
                        <?= delete_button((int)$o['id'], 'order') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php render_pager('manage_orders.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
