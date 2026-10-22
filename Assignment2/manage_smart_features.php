<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/upload.php';
require_once 'includes/admin_common.php';
require_role('admin');   // admins only, otherwise redirect to login.php

$db = get_db();

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
    } elseif ($action === 'delete') {
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
    }
    redirect_to('manage_smart_features.php');
}

[$q, $filter, $pg] = admin_list_params(['pending', 'approved', 'rejected']);
$total = 0;
$rows  = admin_fetch_page($db, 'smart_feature_table', '*', ['item_name', 'item_category'],
                          'status', "FIELD(status, 'pending', 'approved', 'rejected'), id", $q, $filter, $pg, $total);

$page_title = 'Kit Submissions';
include 'header_admin.php';
?>

<main class="admin-page">
    <h1>Kit Submissions</h1>
    <p class="lead">Kits contributed by users. Only approved kits can appear in the Kit Recommender.</p>

    <?php render_flash(); ?>

    <?php render_admin_search('manage_smart_features.php', $q, $filter, ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']); ?>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Photo</th><th>Name</th><th>Category</th><th>PDF</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="7">No kits yet.</td></tr>
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
