<?php
// includes/bookings.php: a user's own activity registrations (from activity_table).
// Used on both profile.php and activities.php.

function fetch_my_bookings(mysqli $db, string $email): array
{
    $stmt = $db->prepare('SELECT activity_title, `date`, `time`, status FROM activity_table WHERE email = ? ORDER BY `date`, `time`');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function render_bookings_table(array $bookings, bool $scroll = false): void
{
    if (empty($bookings)) {
        echo '<p class="bookings-empty">You have not registered for anything yet. <a href="activities.php">Browse activities</a> to book a workshop.</p>';
        return;
    }
    ?>
    <div class="table-wrap<?= $scroll ? ' table-scroll' : '' ?>">
        <table class="data-table">
            <thead>
                <tr><th>Activity</th><th>Date</th><th>Time</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['activity_title'] ?? 'Activity') ?></td>
                    <td><?= $b['date'] ? htmlspecialchars(date('D j M Y', strtotime($b['date']))) : '-' ?></td>
                    <td><?= $b['time'] ? htmlspecialchars(date('g:i A', strtotime($b['time']))) : '-' ?></td>
                    <td><span class="status-badge status-<?= htmlspecialchars($b['status']) ?>"><?= htmlspecialchars(ucfirst($b['status'])) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
