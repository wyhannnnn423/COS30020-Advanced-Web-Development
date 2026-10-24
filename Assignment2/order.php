<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/orders.php';
require_once 'includes/stock.php';
require_once 'data_products.php';
require_role('user');   // customers must be logged in before they can order

$db      = get_db();
$product = find_product((string)($_GET['id'] ?? $_POST['id'] ?? ''));

$errors = [];
$stock  = $product ? stock_for(fetch_stock_map($db), $product['id']) : null;
$buyable = $stock !== null && is_buyable($stock);
$form   = ['quantity' => '1', 'recipient_name' => '', 'phone' => '', 'address' => '', 'city' => '', 'postcode' => '', 'state' => ''];

if ($product) {
    // Pre-fill: the name from the account, the rest from the customer's previous order (if any).
    $q = $db->prepare('SELECT first_name, last_name FROM user_table WHERE email = ?');
    $q->bind_param('s', $_SESSION['email']);
    $q->execute();
    $me = $q->get_result()->fetch_assoc();
    $form['recipient_name'] = $me ? trim($me['first_name'] . ' ' . $me['last_name']) : '';

    $q = $db->prepare('SELECT phone, address, city, postcode, state FROM order_table WHERE email = ? ORDER BY id DESC LIMIT 1');
    $q->bind_param('s', $_SESSION['email']);
    $q->execute();
    if ($last = $q->get_result()->fetch_assoc()) {
        $form = array_merge($form, $last);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $buyable) {
        foreach ($form as $k => $_) {
            $form[$k] = trim($_POST[$k] ?? '');
        }

        $qty = (int)$form['quantity'];
        $max_qty = min(5, $stock['stock']);
        if ($qty < 1 || $qty > $max_qty) {
            $errors[] = 'Quantity must be between 1 and ' . $max_qty . '.';
        }
        if (!preg_match("/^[A-Za-z .'@\\-]{2,100}$/", $form['recipient_name'])) {
            $errors[] = 'Please enter the recipient name (letters only).';
        }
        if (!preg_match('/^[0-9+\- ]{8,15}$/', $form['phone'])) {
            $errors[] = 'Please enter a valid phone number (8 to 15 digits).';
        }
        if ($form['address'] === '' || mb_strlen($form['address']) > 200) {
            $errors[] = 'Please enter the delivery address (max 200 characters).';
        }
        if ($form['city'] === '' || mb_strlen($form['city']) > 50) {
            $errors[] = 'Please enter the city (max 50 characters).';
        }
        if (!preg_match('/^[0-9]{5}$/', $form['postcode'])) {
            $errors[] = 'Postcode must be 5 digits.';
        }
        if (!in_array($form['state'], MY_STATES, true)) {
            $errors[] = 'Please choose a state.';
        }

        if (empty($errors)) {
            // Price and name come from the catalogue on the server, never from the form.
            $price  = product_price($product);
            $name   = trim($product['name']);
            $status = 'awaiting_payment';
            $ins = $db->prepare(
                'INSERT INTO order_table (email, product_id, product_name, unit_price, quantity, recipient_name, phone, address, city, postcode, state, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $ins->bind_param('sssdisssssss', $_SESSION['email'], $product['id'], $name, $price, $qty,
                $form['recipient_name'], $form['phone'], $form['address'], $form['city'], $form['postcode'], $form['state'], $status);
            try {
                // Take the stock and create the order together: either both happen or neither does.
                $db->begin_transaction();

                $ensure = $db->prepare('INSERT IGNORE INTO product_stock (product_id, stock, available) VALUES (?, ?, 1)');
                $default = DEFAULT_STOCK;
                $ensure->bind_param('si', $product['id'], $default);
                $ensure->execute();

                $take = $db->prepare('UPDATE product_stock SET stock = stock - ? WHERE product_id = ? AND available = 1 AND stock >= ?');
                $take->bind_param('isi', $qty, $product['id'], $qty);
                $take->execute();
                if ($take->affected_rows !== 1) {
                    $db->rollback();
                    $errors[] = 'Sorry, there is not enough stock left for that quantity.';
                } else {
                    $ins->execute();
                    $order_id = $db->insert_id;
                    $db->commit();
                    header('Location: order_pay.php?id=' . $order_id);
                    exit;
                }
            } catch (mysqli_sql_exception $e) {
                $db->rollback();
                $errors[] = 'Could not place the order. Please try again.';
            }
        }
    }
}

$page_title = 'Order';
include 'header.php';
?>

<main class="auth-page">
    <img src="img/auth/register.jpg" alt="" class="auth-page-bg">
    <div class="auth-page-overlay"></div>

    <div class="auth-card auth-card-single">
<?php if (!$product): ?>
        <h1>Product not found</h1>
        <p class="auth-subtitle">We could not find that product.</p>
        <a href="series.php" class="card-btn">Back to Series Guide</a>
<?php elseif (!$buyable): ?>
        <h1>Not available</h1>
        <p class="auth-subtitle"><?= htmlspecialchars(trim($product['name'])) ?> is <?= $stock['available'] ? 'sold out' : 'not available right now' ?>. Please check back later.</p>
        <a href="javascript:history.back()" class="card-btn">Back</a>
<?php else: ?>
        <h1>Checkout</h1>
        <p class="auth-subtitle">Step 1 of 3: delivery details. Next you pay by QR and upload your proof of payment.</p>

        <div class="order-summary">
            <?php if (product_has_photo($product)): ?><img src="<?= htmlspecialchars($product['image']) ?>" alt=""><?php endif; ?>
            <div>
                <strong><?= htmlspecialchars(trim($product['name'])) ?></strong>
                <span><?= money(product_price($product)) ?> each<?= stock_label($stock) !== '' ? ' &middot; ' . htmlspecialchars(stock_label($stock)) : '' ?></span>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <form action="order.php?id=<?= urlencode($product['id']) ?>" method="POST" class="auth-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" min="1" max="<?= min(5, $stock['stock']) ?>" value="<?= htmlspecialchars($form['quantity']) ?>">

            <label for="recipient_name">Recipient Name</label>
            <input type="text" name="recipient_name" id="recipient_name" maxlength="100" value="<?= htmlspecialchars($form['recipient_name']) ?>">

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" maxlength="15" value="<?= htmlspecialchars($form['phone']) ?>">

            <label for="address">Delivery Address</label>
            <input type="text" name="address" id="address" maxlength="200" value="<?= htmlspecialchars($form['address']) ?>">

            <label for="city">City</label>
            <input type="text" name="city" id="city" maxlength="50" value="<?= htmlspecialchars($form['city']) ?>">

            <label for="postcode">Postcode</label>
            <input type="text" name="postcode" id="postcode" maxlength="5" value="<?= htmlspecialchars($form['postcode']) ?>">

            <label for="state">State</label>
            <select name="state" id="state">
                <option value="">-- Select a State --</option>
                <?php foreach (MY_STATES as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= ($form['state'] === $s) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="auth-actions">
                <button type="submit" class="card-btn">Continue to Payment</button>
                <a href="javascript:history.back()" class="card-btn secondary-btn">Back</a>
            </div>
        </form>
<?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>
