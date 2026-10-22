<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/admin_common.php';
require_role('admin');   // Req-1: admins only, otherwise redirect to login.php

$db       = get_db();
$tags     = ['Workshop', 'Gunpla Build', 'Anime Discussion', 'Game Highlight'];
$statuses = ['pending', 'approved', 'rejected'];

$form_errors = [];
$mode        = null;   // 'add' or 'edit' when the form is open
$form        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        if (is_ajax()) {
            json_out(['ok' => false, 'message' => 'Your session expired. Please reload the page.'], 403);
        }
        flash('err', 'Your session expired. Please try again.');
        redirect_to('manage_community.php');
    }

    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);

    if ($action === 'approve' || $action === 'reject') {
        set_status($db, 'community_table', $id, $action === 'approve' ? 'approved' : 'rejected');
        if (is_ajax()) {
            json_out(['ok' => true, 'id' => $id, 'status' => $action === 'approve' ? 'approved' : 'rejected']);
        }
        flash('ok', 'Contribution #' . $id . ' ' . ($action === 'approve' ? 'approved.' : 'rejected.'));
        redirect_to('manage_community.php');
    }

    if ($action === 'delete') {
        $q = $db->prepare('SELECT contribution_image FROM community_table WHERE id = ?');
        $q->bind_param('i', $id);
        $q->execute();
        $old = $q->get_result()->fetch_assoc();

        $del = $db->prepare('DELETE FROM community_table WHERE id = ?');
        $del->bind_param('i', $id);
        $del->execute();
        if ($old) {
            delete_stored_file($old['contribution_image']);
        }
        flash('ok', 'Contribution deleted.');
        redirect_to('manage_community.php');
    }

    if ($action === 'save') {
        $form = [
            'id'               => $id,
            'first_name'       => trim($_POST['first_name'] ?? ''),
            'last_name'        => trim($_POST['last_name'] ?? ''),
            'related_activity' => trim($_POST['related_activity'] ?? ''),
            'title'            => trim($_POST['title'] ?? ''),
            'caption'          => trim($_POST['caption'] ?? ''),
            'tag'              => trim($_POST['tag'] ?? ''),
            'status'           => trim($_POST['status'] ?? ''),
        ];
        $mode = $id > 0 ? 'edit' : 'add';

        if (post_too_large()) {
            $form_errors[] = 'The upload was too large for the server (photos must be 5MB or less).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['first_name'])) {
            $form_errors[] = 'First name is required (letters and spaces, max 50).';
        }
        if (!preg_match('/^[A-Za-z ]{1,50}$/', $form['last_name'])) {
            $form_errors[] = 'Last name is required (letters and spaces, max 50).';
        }
        if ($form['related_activity'] === '' || mb_strlen($form['related_activity']) > 50) {
            $form_errors[] = 'Related activity is required (max 50 characters).';
        }
        if (mb_strlen($form['title']) > 100) {
            $form_errors[] = 'Title must be 100 characters or fewer.';
        }
        if (!in_array($form['tag'], $tags, true)) {
            $form_errors[] = 'Please choose a category.';
        }
        if (!in_array($form['status'], $statuses, true)) {
            $form_errors[] = 'Please choose a status.';
        }

        $new_image = null;
        if (empty($form_errors)) {
            $err = '';
            $new_image = save_upload($_FILES['image'] ?? [], ['jpg', 'jpeg', 'png'], 5 * 1048576, 'img/user_uploads', $err);
            if ($err !== '') {
                $form_errors[] = 'Photo: ' . $err;
            }
        }

        if (empty($form_errors)) {
            try {
                if ($id === 0) {
                    $stmt = $db->prepare(
                        'INSERT INTO community_table (first_name, last_name, contribution_image, related_activity, status, title, caption, tag)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                    );
                    $stmt->bind_param('ssssssss', $form['first_name'], $form['last_name'], $new_image,
                        $form['related_activity'], $form['status'], $form['title'], $form['caption'], $form['tag']);
                    $stmt->execute();
                    flash('ok', 'Contribution added.');
                } else {
                    $cur = $db->prepare('SELECT contribution_image FROM community_table WHERE id = ?');
                    $cur->bind_param('i', $id);
                    $cur->execute();
                    $current = $cur->get_result()->fetch_assoc();
                    $image   = $new_image ?? ($current['contribution_image'] ?? null);

                    $stmt = $db->prepare(
                        'UPDATE community_table SET first_name = ?, last_name = ?, contribution_image = ?, related_activity = ?,
                                status = ?, title = ?, caption = ?, tag = ? WHERE id = ?'
                    );
                    $stmt->bind_param('ssssssssi', $form['first_name'], $form['last_name'], $image,
                        $form['related_activity'], $form['status'], $form['title'], $form['caption'], $form['tag'], $id);
                    $stmt->execute();
                    if ($new_image !== null && $current) {
                        delete_stored_file($current['contribution_image']);
                    }
                    flash('ok', 'Contribution updated.');
                }
                redirect_to('manage_community.php');
            } catch (mysqli_sql_exception $e) {
                delete_stored_file($new_image);
                $form_errors[] = 'Could not save the contribution. Please try again.';
            }
        }
    }
} elseif (isset($_GET['add'])) {
    $mode = 'add';
    $form = ['id' => 0, 'status' => 'pending'];
} elseif (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM community_table WHERE id = ?');
    $eid  = (int)$_GET['edit'];
    $stmt->bind_param('i', $eid);
    $stmt->execute();
    $form = $stmt->get_result()->fetch_assoc();
    if ($form) {
        $mode = 'edit';
    } else {
        flash('err', 'That contribution no longer exists.');
        redirect_to('manage_community.php');
    }
}

[$q, $filter, $pg] = admin_list_params(['pending', 'approved', 'rejected']);
$total = 0;
$rows  = admin_fetch_page($db, 'community_table', '*', ['first_name', 'last_name', 'title', 'related_activity', 'tag'],
                          'status', 'id', $q, $filter, $pg, $total);

$page_title = 'Community Contributions';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Manage Community Contributions</h1>
    <p class="lead">Add, edit, delete and approve or reject what users show on the Community page. Only approved entries are visible to users.</p>

    <?php render_flash(); ?>

    <?php if ($mode !== null): ?>
    <form method="POST" enctype="multipart/form-data" class="admin-form">
        <h2><?= $mode === 'add' ? 'Add Contribution' : 'Edit Contribution #' . (int)$form['id'] ?></h2>

        <?php if (!empty($form_errors)): ?>
        <div class="form-errors"><ul>
            <?php foreach ($form_errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul></div>
        <?php endif; ?>

        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int)($form['id'] ?? 0) ?>">

        <div class="grid">
            <label>First Name
                <input type="text" name="first_name" maxlength="50" value="<?= htmlspecialchars($form['first_name'] ?? '') ?>"></label>
            <label>Last Name
                <input type="text" name="last_name" maxlength="50" value="<?= htmlspecialchars($form['last_name'] ?? '') ?>"></label>
            <label>Related Activity
                <input type="text" name="related_activity" maxlength="50" value="<?= htmlspecialchars($form['related_activity'] ?? '') ?>"></label>
            <label>Title
                <input type="text" name="title" maxlength="100" value="<?= htmlspecialchars($form['title'] ?? '') ?>"></label>
            <label>Category
                <select name="tag">
                    <option value="">-- Select --</option>
                    <?php foreach ($tags as $t): ?>
                        <option value="<?= htmlspecialchars($t) ?>" <?= (($form['tag'] ?? '') === $t) ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label>Status
                <select name="status">
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s ?>" <?= (($form['status'] ?? 'pending') === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select></label>
            <label class="wide">Caption
                <textarea name="caption" rows="3"><?= htmlspecialchars($form['caption'] ?? '') ?></textarea></label>
            <label class="wide">Photo <span class="form-hint">(JPG or PNG, max 5MB<?= $mode === 'edit' ? '; leave empty to keep the current photo' : '' ?>)</span>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,image/jpeg,image/png"></label>
        </div>

        <div class="form-buttons">
            <button type="submit" class="card-btn">Save</button>
            <a href="manage_community.php" class="card-btn secondary-btn">Cancel</a>
        </div>
    </form>
    <?php else: ?>
    <div class="admin-toolbar">
        <a href="manage_community.php?add=1" class="card-btn">+ Add Contribution</a>
    </div>
    <?php endif; ?>

    <?php render_admin_search('manage_community.php', $q, $filter, ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Photo</th><th>Name</th><th>Title</th><th>Related Activity</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="7">No contributions yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td>
                        <?php if (!empty($r['contribution_image']) && is_file($r['contribution_image'])): ?>
                            <img class="thumb" src="<?= htmlspecialchars($r['contribution_image']) ?>" alt="">
                        <?php else: ?>-<?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($r['first_name'] . ' ' . $r['last_name']) ?></td>
                    <td><?= htmlspecialchars($r['title'] ?? '') ?></td>
                    <td><?= htmlspecialchars($r['related_activity']) ?></td>
                    <td><span class="status-badge status-<?= htmlspecialchars($r['status']) ?>"><?= htmlspecialchars(ucfirst($r['status'])) ?></span></td>
                    <td>
                        <div class="actions">
                            <?= status_buttons((int)$r['id'], $r['status']) ?>
                            <a href="manage_community.php?edit=<?= (int)$r['id'] ?>" class="btn-sm">Edit</a>
                            <?= delete_button((int)$r['id'], 'contribution') ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php render_pager('manage_community.php', $total, $pg, $q, $filter); ?>
</main>

<?php include 'footer.php'; ?>
