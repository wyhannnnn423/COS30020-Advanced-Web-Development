<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/orders.php';
require_role('user');

$db = get_db();
$id = (int)($_GET['id'] ?? 0);

// A customer can only open their own order.
$q = $db->prepare('SELECT * FROM order_table WHERE id = ? AND email = ?');
$q->bind_param('is', $id, $_SESSION['email']);
$q->execute();
$order = $q->get_result()->fetch_assoc();

if (!$order) {
    header('Location: order_track.php');
    exit;
}
// Payment can be sent (or re-sent after a rejection) only in these two states.
if (!in_array($order['status'], ['awaiting_payment', 'rejected'], true)) {
    header('Location: order_track.php?id=' . $id);
    exit;
}

$errors = [];

if (post_too_large()) {
    $errors[] = 'The upload was too large for the server. The proof must be 5MB or less.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['proof']) || $_FILES['proof']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Please attach a screenshot or photo of your payment.';
    } else {
        $err  = '';
        $path = save_upload($_FILES['proof'], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'payment_proofs', $err);
        if ($err !== '') {
            $errors[] = 'Proof of payment: ' . $err;
        } else {
            $status = 'pending';
            $upd = $db->prepare('UPDATE order_table SET payment_proof = ?, status = ? WHERE id = ? AND email = ?');
            $upd->bind_param('ssis', $path, $status, $id, $_SESSION['email']);
            try {
                $upd->execute();
                delete_stored_file($order['payment_proof']);   // replace an earlier rejected proof
                header('Location: order_track.php?id=' . $id . '&proof_uploaded=1');
                exit;
            } catch (mysqli_sql_exception $e) {
                delete_stored_file($path);
                $errors[] = 'Could not save your proof. Please try again.';
            }
        }
    }
}

$page_title = 'Payment';
include 'header.php';
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
        <h1>Pay by QR</h1>
        <p class="auth-subtitle">Step 2 of 3: scan, pay the exact amount, then upload your proof.</p>

        <?php if ($order['status'] === 'rejected'): ?>
        <div class="form-errors"><ul><li>Your last proof could not be verified. Please upload a clearer one.</li></ul></div>
        <?php endif; ?>

        <div class="pay-box">
            <img src="img/payment/duitnow-qr.png" alt="Payment QR code" class="pay-qr">
            <div class="pay-info">
                <p><span>Order</span><strong><?= htmlspecialchars(order_ref((int)$order['id'])) ?></strong></p>
                <p><span>Item</span><strong><?= htmlspecialchars($order['product_name']) ?> &times; <?= (int)$order['quantity'] ?></strong></p>
                <p><span>Amount to pay</span><strong class="pay-amount"><?= money(order_total($order)) ?></strong></p>
                <p class="form-hint">Write <?= htmlspecialchars(order_ref((int)$order['id'])) ?> as the payment reference.</p>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <form action="order_pay.php?id=<?= (int)$order['id'] ?>" method="POST" enctype="multipart/form-data" class="auth-form">
            <label for="proof">Proof of Payment <span class="form-hint">(JPG or PNG, max 5MB)</span></label>
            <input type="file" name="proof" id="proof" accept=".jpg,.jpeg,.png,image/jpeg,image/png">

            <div class="auth-actions">
                <button type="submit" class="card-btn">Submit Proof</button>
                <a href="order_track.php" class="card-btn secondary-btn">Pay Later</a>
            </div>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>
