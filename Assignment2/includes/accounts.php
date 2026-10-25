<?php
// includes/accounts.php: things that must follow a user when their email changes.

// user_table and account_table are linked by a foreign key (ON UPDATE CASCADE), but the
// bookings, orders and community posts only store the email as text, so they are moved here.
function move_user_records(mysqli $db, string $old_email, string $new_email): void
{
    if (strcasecmp($old_email, $new_email) === 0) {
        return;
    }
    foreach (['activity_table', 'order_table', 'community_table'] as $table) {   // fixed names
        $stmt = $db->prepare("UPDATE `$table` SET email = ? WHERE email = ?");
        $stmt->bind_param('ss', $new_email, $old_email);
        $stmt->execute();
    }
}

// A public page about a member (member.php) is addressed by this token, not by the email,
// so a visitor can open a poster's page without ever seeing their email address.
// The same value is worked out inside MySQL with SHA2(LOWER(email), 256).
function member_token(string $email): string
{
    return hash('sha256', strtolower($email));
}
