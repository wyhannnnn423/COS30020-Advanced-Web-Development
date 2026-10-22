<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/admin_common.php';
require_role('admin');   // Req-1: admins only, otherwise redirect to login.php

$db       = get_db();
$statuses = ['pending', 'approved', 'rejected'];

$form_errors = [];
$mode        = null;
$form        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        if (is_ajax()) {
            json_out(['ok' => false, 'message' => 'Your session expired. Please reload the page.'], 403);
        }
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_activities.php');
    }

    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'approve' || $action === 'reject') {
        set_status($db, 'activity_table', $id, $action === 'approve' ? 'approved' : 'rejected');
        if (is_ajax()) {
            json_out(['ok' => true, 'id' => $id, 'status' => $action === 'approve' ? 'approved' : 'rejected']);
        }
        flash('ok', 'Registration #' . $id . ' ' . ($action === 'approve' ? 'approved.' : 'rejected.'));
        redirect_to('manage_activities.php');
    }

    if ($action === 'delete') {
        $del = $db->prepare('DELETE FROM activity_table WHERE id = ?');
        $del->bind_param('i', $id);
        $del->execute();
        flash('ok', 'Registration deleted.');
        redirect_to('manage_activities.php');
    }

    if ($action === 'save') {
        $form = [
            'id'             => $id,
            'email'          => trim($_POST['email'] ?? ''),
            'first_name'     => trim($_POST['first_name'] ?? ''),
            'last_name'      => trim($_POST['last_name'] ?? ''),
            'activity_title' => trim($_POST['activity_title'] ?? ''),
            'date'           => trim($_POST['date'] ?? ''),
            'time'           => trim($_POST['time'] ?? ''),
            'contact_number' => trim($_POST['contact_number'] ?? ''),
            'status'         => trim($_POST['status'] ?? ''),
        ];
        $mode = $id > 0 ? 'edit' : 'add';

        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL) || strlen($form['email']) > 50) {
            $form_errors[] = 'A valid email is required (max 50 characters).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['first_name'])) {
            $form_errors[] = 'First name is required (letters and spaces, max 50).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['last_name'])) {
            $form_errors[] = 'Last name is required (letters and spaces, max 50).';
        }
        if ($form['activity_title'] === '' || mb_strlen($form['activity_title']) > 100) {
            $form_errors[] = 'Activity is required (max 100 characters).';
        }
        $d = DateTime::createFromFormat('Y-m-d', $form['date']);
        if (!$d || $d->format('Y-m-d') !== $form['date']) {
            $form_errors[] = 'Please enter a valid date.';
        }
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $form['time'])) {
            $form_errors[] = 'Please enter a valid time (HH:MM).';
        }
        if (!preg_match('/^[0-9+\- ]{7,15}$/', $form['contact_number'])) {
            $form_errors[] = 'Contact number must be 7 to 15 digits (+, - and spaces allowed).';
        }
        if (!in_array($form['status'], $statuses, true)) {
            $form_errors[] = 'Please choose a status.';
        }

        if (empty($form_errors)) {
            $time = $form['time'] . ':00';
            try {
                if ($id === 0) {
                    $stmt = $db->prepare(
                        'INSERT INTO activity_table (email, first_name, last_name, `date`, `time`, contact_number, status, activity_title)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('ssssssss', $form['email'], $form['first_name'], $form['last_name'], $form['date'],
                        $time, $form['contact_number'], $form['status'], $form['activity_title']);
                    $stmt->execute();
                    flash('ok', 'Registration added.');
                } else {
                    $stmt = $db->prepare(
                        'UPDATE activity_table SET email = ?, first_name = ?, last_name = ?, `date` = ?, `time` = ?,
                                contact_number = ?, status = ?, activity_title = ? WHERE id = ?'
                    );
                    $stmt->bind_param('ssssssssi', $form['email'], $form['first_name'], $form['last_name'], $form['date'],
                        $time, $form['contact_number'], $form['status'], $form['activity_title'], $id);
                    $stmt->execute();
                    flash('ok', 'Registration updated.');
                }
                redirect_to('manage_activities.php');
            } catch (mysqli_sql_exception $e) {
                $form_errors[] = 'Could not save the registration. Please try again.';
            }
        }
    }
} elseif (isset($_GET['add'])) {
    $mode = 'add';
    $form = ['id' => 0, 'status' => 'pending'];
} elseif (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM activity_table WHERE id = ?');
    $eid  = (int)$_GET['edit'];
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $form = $stmt->get_result()->fetch_assoc();
    if ($form) {
        $mode = 'edit';
        $form['time'] = substr((string)$form['time'], 0, 5);   // HH:MM for the time input
    } else {
        flash('err', 'That registration no longer exists.');
        redirect_to('manage_activities.php');
    }
}

[$q, $filter, $pg] = admin_list_params(['pending', 'approved', 'rejected']);
$total = 0;
$rows  = admin_fetch_page($db, 'activity_table', '*', ['email', 'first_name', 'last_name', 'activity_title', 'contact_number'],
                          'status', 'id', $q, $filter, $pg, $total);

$page_title = 'Activity Registrations';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Manage Activity Registrations</h1>
    <p class="lead">Review workshop and screening bookings. Approve or reject them, or edit and delete entries.</p>

    <?php render_flash(); ?>

    <?php if ($mode !== null): ?>
    <form method="POST" class="admin-form">
        <h2><?= $mode === 'add' ? 'Add Registration' : 'Edit Registration #' . (int)$form['id'] ?></h2>

        <?php if (!empty($form_errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($form_errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int)($form['id'] ?? 0) ?>">

        <div class="grid">
            <label>Email
                <input type="email" name="email" maxlength="50" value="<?= htmlspecialchars($form['email'] ?? '') ?>"></label>
            <label>First Name
                <input type="text" name="first_name" maxlength="50" value="<?= htmlspecialchars($form['first_name'] ?? '') ?>"></label>
            <label>Last Name
                <input type="text" name="last_name" maxlength="50" value="<?= htmlspecialchars($form['last_name'] ?? '') ?>"></label>
            <label>Activity
                <input type="text" name="activity_title" maxlength="100" value="<?= htmlspecialchars($form['activity_title'] ?? '') ?>"></label>
            <label>Date
                <input type="date" name="date" value="<?= htmlspecialchars($form['date'] ?? '') ?>"></label>
            <label>Time
                <input type="time" name="time" value="<?= htmlspecialchars($form['time'] ?? '') ?>"></label>
            <label>Contact Number
                <input type="text" name="contact_number" maxlength="15" value="<?= htmlspecialchars($form['contact_number'] ?? '') ?>"></label>
            <label>Status
                <select name="status">
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s ?>" <?= (($form['status'] ?? 'pending') === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select></label>
        </div>

        <div class="form-buttons">
            <button type="submit" class="card-btn">Save</button>
            <a href="manage_activities.php" class="card-btn secondary-btn">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <div class="admin-toolbar">
        <a href="manage_activities.php?add=1" class="card-btn">+ Add Registration</a>
    </div>
    <?php endif; ?>

    <?php render_admin_search('manage_activities.php', $q, $filter, ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Activity</th><th>Date</th><th>Time</th><th>Contact</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="9">No registrations yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['activity_title'] ?? '') ?></td>
                    <td><?= $r['date'] ? htmlspecialchars($r['date']) : '-' ?></td>
                    <td><?= $r['time'] ? htmlspecialchars(substr($r['time'], 0, 5)) : '-' ?></td>
                    <td><?= htmlspecialchars($r['contact_number'] ?? '') ?></td>
                    <td><span class="status-badge status-<?= htmlspecialchars($r['status']) ?>"><?= htmlspecialchars(ucfirst($r['status'])) ?></span></td>
                    <td>
                        <div class="actions">
                            <?= status_buttons((int)$r['id'], $r['status']) ?>
                            <a href="manage_activities.php?edit=<?= (int)$r['id'] ?>" class="btn-sm">Edit</a>
                            <?= delete_button((int)$r['id'], 'registration') ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php render_pager('manage_activities.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
