<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/admin_common.php';
require_once 'includes/planner.php';
require_role('admin');   // admins only, otherwise redirect to login.php

$db         = get_db();
$statuses   = ['pending', 'approved', 'rejected'];
$categories = array_keys(GRADES);   // same grades the Build Planner understands

$form_errors = [];
$mode        = null;   // 'add' or 'edit' while the form is open
$form        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        if (is_ajax()) {
            json_out(['ok' => false, 'message' => 'Your session expired. Please reload the page.'], 403);
        }
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_smart_features.php');
    }

    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'approve' || $action === 'reject') {
        set_status($db, 'smart_feature_table', $id, $action === 'approve' ? 'approved' : 'rejected');
        if (is_ajax()) {
            json_out(['ok' => true, 'id' => $id, 'status' => $action === 'approve' ? 'approved' : 'rejected']);
        }
        flash('ok', 'Kit #' . $id . ' ' . ($action === 'approve' ? 'approved.' : 'rejected.'));
        redirect_to('manage_smart_features.php');
    }

    if ($action === 'delete') {
        $q = $db->prepare('SELECT item_image, description_file FROM smart_feature_table WHERE id = ?');
        $q->bind_param('i', $id);
        $q->execute();
        $old = $q->get_result()->fetch_assoc();

        $del = $db->prepare('DELETE FROM smart_feature_table WHERE id = ?');
        $del->bind_param('i', $id);
        $del->execute();
        if ($old) {
            delete_stored_file($old['item_image']);
            delete_stored_file($old['description_file']);
        }
        flash('ok', 'Kit deleted.');
        redirect_to('manage_smart_features.php');
    }

    if ($action === 'save') {
        $form = [
            'id'            => $id,
            'item_name'     => trim($_POST['item_name'] ?? ''),
            'item_category' => trim($_POST['item_category'] ?? ''),
            'status'        => trim($_POST['status'] ?? ''),
        ];
        $mode = $id > 0 ? 'edit' : 'add';

        if (post_too_large()) {
            $form_errors[] = 'The upload was too large for the server (PDF 7MB or less, photo 5MB or less).';
        }
        if ($form['item_name'] === '' || mb_strlen($form['item_name']) > 50) {
            $form_errors[] = 'Kit name is required (max 50 characters).';
        }
        if (!in_array($form['item_category'], $categories, true)) {
            $form_errors[] = 'Please choose a category.';
        }
        if (!in_array($form['status'], $statuses, true)) {
            $form_errors[] = 'Please choose a status.';
        }
        if ($mode === 'add' && (empty($_FILES['description_file']) || $_FILES['description_file']['error'] === UPLOAD_ERR_NO_FILE)) {
            $form_errors[] = 'Please attach a PDF description for a new kit.';
        }

        $new_pdf = null;
        $new_img = null;
        if (empty($form_errors)) {
            $err = '';
            $new_pdf = save_upload($_FILES['description_file'] ?? [], ['pdf'], 7 * 1048576, 'smart_feature_description', $err);
            if ($err !== '') {
                $form_errors[] = 'PDF description: ' . $err;
            }
            $err = '';
            $new_img = save_upload($_FILES['item_image'] ?? [], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'img/user_uploads', $err);
            if ($err !== '') {
                $form_errors[] = 'Photo: ' . $err;
            }
        }

        if (empty($form_errors)) {
            try {
                if ($id === 0) {
                    $stmt = $db->prepare(
                        'INSERT INTO smart_feature_table (item_name, item_category, item_image, description_file, status) VALUES (?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('sssss', $form['item_name'], $form['item_category'], $new_img, $new_pdf, $form['status']);
                    $stmt->execute();
                    flash('ok', 'Kit added.');
                } else {
                    $cur = $db->prepare('SELECT item_image, description_file FROM smart_feature_table WHERE id = ?');
                    $cur->bind_param('i', $id);
                    $cur->execute();
                    $current = $cur->get_result()->fetch_assoc();
                    $img = $new_img ?? ($current['item_image'] ?? null);
                    $pdf = $new_pdf ?? ($current['description_file'] ?? null);

                    $stmt = $db->prepare(
                        'UPDATE smart_feature_table SET item_name = ?, item_category = ?, item_image = ?, description_file = ?, status = ? WHERE id = ?'
                    );
                    $stmt->bind_param('sssssi', $form['item_name'], $form['item_category'], $img, $pdf, $form['status'], $id);
                    $stmt->execute();

                    // Remove the files that were just replaced.
                    if ($current) {
                        if ($new_img !== null) { delete_stored_file($current['item_image']); }
                        if ($new_pdf !== null) { delete_stored_file($current['description_file']); }
                    }
                    flash('ok', 'Kit updated.');
                }
                redirect_to('manage_smart_features.php');
            } catch (mysqli_sql_exception $e) {
                delete_stored_file($new_pdf);
                delete_stored_file($new_img);
                $form_errors[] = 'Could not save the kit. Please try again.';
            }
        } else {
            delete_stored_file($new_pdf);
            delete_stored_file($new_img);
        }
    }
} elseif (isset($_GET['add'])) {
    $mode = 'add';
    $form = ['id' => 0, 'status' => 'approved'];   // admin-added kits are approved by default
} elseif (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM smart_feature_table WHERE id = ?');
    $eid  = (int)$_GET['edit'];
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $form = $stmt->get_result()->fetch_assoc();
    if ($form) {
        $mode = 'edit';
    } else {
        flash('err', 'That kit no longer exists.');
        redirect_to('manage_smart_features.php');
    }
}

[$q, $filter, $pg] = admin_list_params(['pending', 'approved', 'rejected']);
$total = 0;
$rows  = admin_fetch_page($db, 'smart_feature_table', '*', ['item_name', 'item_category'],
                          'status', "FIELD(status, 'pending', 'approved', 'rejected'), id", $q, $filter, $pg, $total);

$page_title = 'Kit Submissions';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Kits</h1>
    <p class="lead">Add kits yourself, or review the ones users contributed. Only approved kits can appear in the Kit Recommender.</p>

    <?php render_flash(); ?>

    <?php if ($mode !== null): ?>
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <h2><?= $mode === 'add' ? 'Add Kit' : 'Edit Kit #' . (int)$form['id'] ?></h2>

        <?php if (!empty($form_errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($form_errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int)($form['id'] ?? 0) ?>">

        <div class="grid">
            <label>Kit Name
                <input type="text" name="item_name" maxlength="50" value="<?= htmlspecialchars($form['item_name'] ?? '') ?>"></label>
            <label>Category
                <select name="item_category">
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= (($form['item_category'] ?? '') === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label>Status
                <select name="status">
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s ?>" <?= (($form['status'] ?? 'approved') === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label>Photo <span class="form-hint">(optional, JPG or PNG, max 5MB<?= $mode === 'edit' ? '; empty keeps the current one' : '' ?>)</span>
                <input type="file" name="item_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png"></label>
            <label class="wide">PDF Description <span class="form-hint">(<?= $mode === 'add' ? 'required' : 'empty keeps the current one' ?>, PDF only, max 7MB)</span>
                <input type="file" name="description_file" accept=".pdf,application/pdf"></label>
        </div>

        <div class="form-buttons">
            <button type="submit" class="card-btn">Save</button>
            <a href="manage_smart_features.php" class="card-btn secondary-btn">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <div class="admin-toolbar">
        <a href="manage_smart_features.php?add=1" class="card-btn">+ Add Kit</a>
    </div>
    <?php endif; ?>

    <?php render_admin_search('manage_smart_features.php', $q, $filter, ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Photo</th><th>Name</th><th>Category</th><th>PDF</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="7">No kits found.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td>
                        <?php if (!empty($r['item_image']) && is_file($r['item_image'])): ?>
                            <img class="thumb" src="<?= htmlspecialchars($r['item_image']) ?>" alt="">
                        <?php else: ?>-<?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($r['item_name']) ?></td>
                    <td><?= htmlspecialchars($r['item_category']) ?></td>
                    <td>
                        <?php if (!empty($r['description_file']) && is_file($r['description_file'])): ?>
                            <a href="<?= htmlspecialchars($r['description_file']) ?>" target="_blank">View PDF</a>
                        <?php else: ?>-<?php endif; ?>
                    </td>
                    <td><span class="status-badge status-<?= htmlspecialchars($r['status']) ?>"><?= htmlspecialchars(ucfirst($r['status'])) ?></span></td>
                    <td>
                        <div class="actions">
                            <?= status_buttons((int)$r['id'], $r['status']) ?>
                            <a href="manage_smart_features.php?edit=<?= (int)$r['id'] ?>" class="btn-sm">Edit</a>
                            <?= delete_button((int)$r['id'], 'kit') ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php render_pager('manage_smart_features.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
