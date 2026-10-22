<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/admin_common.php';
require_role('admin');   // admins only, otherwise redirect to login.php

$db       = get_db();
$my_email = $_SESSION['email'];

$form_errors = [];
$mode        = null;
$form        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_accounts.php');
    }

    $action = $_POST['action'] ?? '';

    // ------------------------------------------------------------------ delete
    if ($action === 'delete') {
        $target = trim($_POST['email'] ?? '');

        if (strcasecmp($target, $my_email) === 0) {
            flash('err', 'You cannot delete the account you are logged in with.');
            redirect_to('manage_accounts.php');
        }

        $q = $db->prepare('SELECT profile_image, resume_path FROM user_table WHERE email = ?');
        $q->bind_param('s', $target);
        $q->execute();
        $files = $q->get_result()->fetch_assoc();

        try {
            // Remove the login first, then the profile. (ON DELETE CASCADE would also do the
            // first step; doing both makes the rule obvious.)
            $db->begin_transaction();
            $d1 = $db->prepare('DELETE FROM account_table WHERE email = ?');
            $d1->bind_param('s', $target);
            $d1->execute();
            $d2 = $db->prepare('DELETE FROM user_table WHERE email = ?');
            $d2->bind_param('s', $target);
            $d2->execute();
            $db->commit();

            if ($files) {
                delete_stored_file($files['profile_image']);
                delete_stored_file($files['resume_path']);
            }
            flash('ok', 'Account deleted from user_table and account_table.');
        } catch (mysqli_sql_exception $e) {
            $db->rollback();
            flash('err', 'Could not delete the account.');
        }
        redirect_to('manage_accounts.php');
    }

    // -------------------------------------------------------------- add / edit
    if ($action === 'save') {
        $orig = trim($_POST['orig_email'] ?? '');   // empty when adding
        $form = [
            'orig_email' => $orig,
            'email'      => trim($_POST['email'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name'] ?? ''),
            'dob'        => trim($_POST['dob'] ?? ''),
            'gender'     => trim($_POST['gender'] ?? ''),
            'hometown'   => trim($_POST['hometown'] ?? ''),
            'type'       => trim($_POST['type'] ?? ''),
        ];
        $password = $_POST['password'] ?? '';
        $mode     = ($orig === '') ? 'add' : 'edit';

        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) || strlen($form['email']) > 50) {
            $form_errors[] = 'A valid email is required (max 50 characters).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['first_name'])) {
            $form_errors[] = 'First name is required (letters and spaces, max 50).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['last_name'])) {
            $form_errors[] = 'Last name is required (letters and spaces, max 50).';
        }
        if ($form['dob'] !== '') {
            $d = DateTime::createFromFormat('Y-m-d', $form['dob']);
            if (!$d || $d->format('Y-m-d') !== $form['dob'] || $d > new DateTime('today')) {
                $form_errors[] = 'Please enter a valid date of birth.';
            }
        }
        if (!in_array($form['gender'], ['Female', 'Male'], true)) {
            $form_errors[] = 'Please choose a gender.';
        }
        if ($form['hometown'] === '' || mb_strlen($form['hometown']) > 50) {
            $form_errors[] = 'Hometown is required (max 50 characters).';
        }
        if (!in_array($form['type'], ['user', 'admin'], true)) {
            $form_errors[] = 'Please choose an account type.';
        }
        if ($mode === 'edit' && strcasecmp($orig, $my_email) === 0 && $form['type'] !== 'admin') {
            $form_errors[] = 'You cannot remove your own admin rights.';
        }
        if ($mode === 'add' && $password === '') {
            $form_errors[] = 'A password is required for a new account.';
        }
        if ($password !== '' && (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password))) {
            $form_errors[] = 'Password must be at least 8 characters with a number and a symbol.';
        }

        // Email must be unused (unless it is this account's own email).
        if (empty($form_errors) && ($mode === 'add' || strcasecmp($form['email'], $orig) !== 0)) {
            $chk = $db->prepare('SELECT 1 FROM user_table WHERE email = ?');
            $chk->bind_param('s', $form['email']);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) {
                $form_errors[] = 'That email is already used by another account.';
            }
        }

        if (empty($form_errors)) {
            $dob = ($form['dob'] === '') ? null : $form['dob'];
            try {
                $db->begin_transaction();

                if ($mode === 'add') {
                    $u = $db->prepare('INSERT INTO user_table (email, first_name, last_name, dob, gender, hometown) VALUES (?, ?, ?, ?, ?, ?)');
                    $u->bind_param('ssssss', $form['email'], $form['first_name'], $form['last_name'], $dob, $form['gender'], $form['hometown']);
                    $u->execute();

                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $a = $db->prepare('INSERT INTO account_table (email, password, type) VALUES (?, ?, ?)');
                    $a->bind_param('sss', $form['email'], $hash, $form['type']);
                    $a->execute();
                    $msg = 'Account added.';
                } else {
                    // A changed email is carried into account_table by the ON UPDATE CASCADE rule.
                    $u = $db->prepare('UPDATE user_table SET email = ?, first_name = ?, last_name = ?, dob = ?, gender = ?, hometown = ? WHERE email = ?');
                    $u->bind_param('sssssss', $form['email'], $form['first_name'], $form['last_name'], $dob, $form['gender'], $form['hometown'], $orig);
                    $u->execute();

                    $a = $db->prepare('UPDATE account_table SET type = ? WHERE email = ?');
                    $a->bind_param('ss', $form['type'], $form['email']);
                    $a->execute();

                    if ($password !== '') {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $p = $db->prepare('UPDATE account_table SET password = ? WHERE email = ?');
                        $p->bind_param('ss', $hash, $form['email']);
                        $p->execute();
                    }
                    if (strcasecmp($orig, $my_email) === 0) {
                        $_SESSION['email']      = $form['email'];
                        $_SESSION['first_name'] = $form['first_name'];
                    }
                    $msg = 'Account updated.';
                }

                $db->commit();
                flash('ok', $msg);
                redirect_to('manage_accounts.php');
            } catch (mysqli_sql_exception $e) {
                $db->rollback();
                $form_errors[] = ($e->getCode() === 1062)
                    ? 'That email is already used by another account.'
                    : 'Could not save the account. Please try again.';
            }
        }
    }
} elseif (isset($_GET['add'])) {
    $mode = 'add';
    $form = ['orig_email' => '', 'type' => 'user'];
} elseif (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT u.*, a.type FROM user_table u JOIN account_table a ON a.email = u.email WHERE u.email = ?');
    $e    = (string)$_GET['edit'];
    $stmt->bind_param('s', $e);
    $stmt->execute();
    $form = $stmt->get_result()->fetch_assoc();
    if ($form) {
        $mode = 'edit';
        $form['orig_email'] = $form['email'];
    } else {
        flash('err', 'That account no longer exists.');
        redirect_to('manage_accounts.php');
    }
}

[$q, $filter, $pg] = admin_list_params(['user', 'admin']);
$total = 0;
$rows  = admin_fetch_page($db, 'user_table u LEFT JOIN account_table a ON a.email = u.email',
                          'u.email, u.first_name, u.last_name, u.gender, u.hometown, a.type',
                          ['u.email', 'u.first_name', 'u.last_name', 'u.hometown'],
                          'a.type', 'a.type, u.email', $q, $filter, $pg, $total);

$page_title = 'Manage Accounts';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Manage Users' Accounts</h1>
    <p class="lead">Add, edit and delete accounts. Deleting an account removes it from both user_table and account_table.</p>

    <?php render_flash(); ?>

    <?php if ($mode !== null): ?>
    <form method="POST" class="admin-form" autocomplete="off">
        <h2><?= $mode === 'add' ? 'Add Account' : 'Edit Account' ?></h2>

        <?php if (!empty($form_errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($form_errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="orig_email" value="<?= htmlspecialchars($form['orig_email'] ?? '') ?>">

        <div class="grid">
            <label>Email
                <input type="email" name="email" maxlength="50" value="<?= htmlspecialchars($form['email'] ?? '') ?>"></label>
            <label>First Name
                <input type="text" name="first_name" maxlength="50" value="<?= htmlspecialchars($form['first_name'] ?? '') ?>"></label>
            <label>Last Name
                <input type="text" name="last_name" maxlength="50" value="<?= htmlspecialchars($form['last_name'] ?? '') ?>"></label>
            <label>Date of Birth
                <input type="date" name="dob" value="<?= htmlspecialchars($form['dob'] ?? '') ?>"></label>
            <label>Gender
                <select name="gender">
                    <option value="">-- Select --</option>
                    <?php foreach (['Female', 'Male'] as $g): ?>
                        <option value="<?= $g ?>" <?= (($form['gender'] ?? '') === $g) ? 'selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label>Hometown
                <input type="text" name="hometown" maxlength="50" value="<?= htmlspecialchars($form['hometown'] ?? '') ?>"></label>
            <label>Account Type
                <select name="type">
                    <?php foreach (['user', 'admin'] as $t): ?>
                        <option value="<?= $t ?>" <?= (($form['type'] ?? 'user') === $t) ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label>Password <span class="form-hint"><?= $mode === 'edit' ? '(leave empty to keep the current one)' : '(min 8 characters, a number and a symbol)' ?></span>
                <input type="password" name="password" autocomplete="new-password"></label>
        </div>

        <div class="form-buttons">
            <button type="submit" class="card-btn">Save</button>
            <a href="manage_accounts.php" class="card-btn secondary-btn">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <div class="admin-toolbar">
        <a href="manage_accounts.php?add=1" class="card-btn">+ Add Account</a>
    </div>
    <?php endif; ?>

    <?php render_admin_search('manage_accounts.php', $q, $filter, ['user' => 'User', 'admin' => 'Admin'], 'Types'); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>Email</th><th>Name</th><th>Gender</th><th>Hometown</th><th>Type</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <?php $is_me = strcasecmp($r['email'], $my_email) === 0; ?>
                <tr>
                    <td><?= htmlspecialchars($r['email']) ?><?= $is_me ? ' <span class="form-hint">(you)</span>' : '' ?></td>
                    <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                    <td><?= htmlspecialchars($r['gender']) ?></td>
                    <td><?= htmlspecialchars($r['hometown']) ?></td>
                    <td><span class="type-badge <?= htmlspecialchars((string)$r['type']) ?>"><?= htmlspecialchars((string)$r['type']) ?></span></td>
                    <td>
                        <div class="actions">
                            <a href="manage_accounts.php?edit=<?= urlencode($r['email']) ?>" class="btn-sm">Edit</a>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Delete this account? This cannot be undone.');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="email" value="<?= htmlspecialchars($r['email']) ?>">
                                <button type="submit" name="action" value="delete" class="btn-sm btn-danger"<?= $is_me ? ' disabled title="You cannot delete your own account"' : '' ?>>Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php render_pager('manage_accounts.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
