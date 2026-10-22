<?php
// includes/admin_common.php
// Small helpers shared by the admin pages: CSRF protection, flash messages, status buttons.

function csrf_token(): string
{
    start_session_once();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

// Every admin POST (approve, delete, save...) must carry the token from the page it came from.
function csrf_valid(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [$type, $message];
}

function render_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        [$type, $message] = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="flash flash-' . ($type === 'ok' ? 'ok' : 'err') . '">' . htmlspecialchars($message) . '</div>';
    }
}

// Approve / Reject buttons. pending: both on, approved: Approve off, rejected: Reject off.
function status_buttons(int $id, string $status): string
{
    $approve_off = ($status === 'approved') ? ' disabled' : '';
    $reject_off  = ($status === 'rejected') ? ' disabled' : '';
    return '<form method="POST" class="inline-form status-form">' . csrf_field()
         . '<input type="hidden" name="id" value="' . $id . '">'
         . '<button type="submit" name="action" value="approve" class="btn-sm btn-ok"' . $approve_off . '>Approve</button> '
         . '<button type="submit" name="action" value="reject" class="btn-sm btn-warn"' . $reject_off . '>Reject</button>'
         . '</form>';
}

function delete_button(int $id, string $what): string
{
    return '<form method="POST" class="inline-form" onsubmit="return confirm(\'Delete this ' . $what . '? This cannot be undone.\');">'
         . csrf_field()
         . '<input type="hidden" name="id" value="' . $id . '">'
         . '<button type="submit" name="action" value="delete" class="btn-sm btn-danger">Delete</button></form>';
}

// Only these tables may have their status changed through set_status().
function set_status(mysqli $db, string $table, int $id, string $status): bool
{
    $allowed = ['community_table', 'activity_table', 'smart_feature_table'];
    if (!in_array($table, $allowed, true) || !in_array($status, ['approved', 'rejected', 'pending'], true)) {
        return false;
    }
    $stmt = $db->prepare("UPDATE `$table` SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    return true;
}

function redirect_to(string $page): void
{
    header('Location: ' . $page);
    exit;
}


// ------------------------------------------------------------------ AJAX support
// js/admin.js sends approve/reject clicks with the header "X-Requested-With: fetch"
// and expects JSON back. Without JavaScript the same forms still work as normal posts.

function is_ajax(): bool
{
    return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';
}

function json_out(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// ------------------------------------------------------- search + pagination
const ADMIN_PER_PAGE = 6;

// Reads ?q= (search text), ?status= (filter) and ?page= from the URL.
function admin_list_params(array $allowed_filter = []): array
{
    $q = trim($_GET['q'] ?? '');
    if (mb_strlen($q) > 50) {
        $q = mb_substr($q, 0, 50);
    }
    $filter = $_GET['status'] ?? '';
    if (!in_array($filter, $allowed_filter, true)) {
        $filter = '';
    }
    $page = max(1, (int)($_GET['page'] ?? 1));
    return [$q, $filter, $page];
}

// Fetches one page of rows. $from, $select, $columns, $filter_col and $order are fixed
// strings written in the calling page; only $q, $filter and $page come from the visitor,
// and those always go through bound parameters.
function admin_fetch_page(mysqli $db, string $from, string $select, array $columns, string $filter_col,
                          string $order, string $q, string $filter, int $page, ?int &$total): array
{
    $parts  = [];
    $types  = '';
    $params = [];

    if ($q !== '') {
        $like = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $q) . '%';
        $ors  = [];
        foreach ($columns as $c) {
            $ors[]    = "$c LIKE ? ESCAPE '!'";
            $types   .= 's';
            $params[] = $like;
        }
        $parts[] = '(' . implode(' OR ', $ors) . ')';
    }
    if ($filter !== '') {
        $parts[]  = "$filter_col = ?";
        $types   .= 's';
        $params[] = $filter;
    }
    $where = $parts ? ' WHERE ' . implode(' AND ', $parts) : '';

    // how many rows match in total
    $stmt = $db->prepare("SELECT COUNT(*) FROM $from$where");
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();

    $pages  = max(1, (int)ceil($total / ADMIN_PER_PAGE));
    $page   = min($page, $pages);
    $offset = ($page - 1) * ADMIN_PER_PAGE;

    $stmt = $db->prepare("SELECT $select FROM $from$where ORDER BY $order LIMIT ? OFFSET ?");
    $per = ADMIN_PER_PAGE;
    $stmt->bind_param($types . 'ii', ...array_merge($params, [$per, $offset]));
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Search box and filter drop-down above a table.
function render_admin_search(string $page_file, string $q, string $filter, array $filter_options, string $filter_label = 'Status'): void
{
    ?>
    <form method="GET" action="<?= htmlspecialchars($page_file) ?>" class="admin-search">
        <input type="search" name="q" placeholder="Search..." maxlength="50" value="<?= htmlspecialchars($q) ?>">
        <?php if (!empty($filter_options)): ?>
        <select name="status">
            <option value="">All <?= htmlspecialchars(strtolower($filter_label)) ?></option>
            <?php foreach ($filter_options as $value => $label): ?>
                <option value="<?= htmlspecialchars((string)$value) ?>" <?= ($filter === (string)$value) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <button type="submit" class="btn-sm">Search</button>
        <?php if ($q !== '' || $filter !== ''): ?>
            <a href="<?= htmlspecialchars($page_file) ?>" class="btn-sm">Clear</a>
        <?php endif; ?>
    </form>
    <?php
}

// "Showing 1-6 of 8" and Previous / 1 2 3 / Next links that keep the search and filter.
function render_pager(string $page_file, int $total, int $page, string $q, string $filter): void
{
    $pages = max(1, (int)ceil($total / ADMIN_PER_PAGE));
    $page  = min($page, $pages);
    $from  = $total === 0 ? 0 : ($page - 1) * ADMIN_PER_PAGE + 1;
    $to    = min($total, $page * ADMIN_PER_PAGE);

    $link = function (int $p) use ($page_file, $q, $filter): string {
        $query = array_filter(['q' => $q, 'status' => $filter, 'page' => $p > 1 ? $p : null], fn($v) => $v !== '' && $v !== null);
        return htmlspecialchars($page_file . ($query ? '?' . http_build_query($query) : ''));
    };
    ?>
    <div class="pager">
        <span class="pager-info">Showing <?= $from ?>&ndash;<?= $to ?> of <?= $total ?></span>
        <?php if ($pages > 1): ?>
        <nav class="pager-links" aria-label="Pages">
            <?php if ($page > 1): ?><a href="<?= $link($page - 1) ?>" class="btn-sm">&laquo; Previous</a><?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="<?= $link($i) ?>" class="btn-sm<?= $i === $page ? ' pager-current' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?><a href="<?= $link($page + 1) ?>" class="btn-sm">Next &raquo;</a><?php endif; ?>
        </nav>
        <?php endif; ?>
    </div>
    <?php
}
