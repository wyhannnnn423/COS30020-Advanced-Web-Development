<?php
// includes/db.php
// One shared MySQL connection. On first use it also creates the tables and the dummy data
// (see setup.php).
//
// Usage on any page:   require_once 'includes/db.php';  $db = get_db();
//
// On your own computer (XAMPP) the defaults below work as they are, and the database is
// created for you. On a web host, put the host's details in includes/config.hosting.php
// (copy includes/config.hosting.example.php). That file is only needed on the host.

require_once __DIR__ . '/setup.php';

if (is_file(__DIR__ . '/config.hosting.php')) {
    require_once __DIR__ . '/config.hosting.php';
}
defined('DB_HOST') || define('DB_HOST', 'localhost');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
defined('DB_NAME') || define('DB_NAME', 'gundam_hub');
defined('DB_DEBUG') || define('DB_DEBUG', false);

function get_db(): mysqli
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    // Throw exceptions instead of printing raw MySQL errors to visitors.
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // ---- 1. connect
    try {
        try {
            // Normal case: the database already exists (always true on a web host).
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() !== 1049) {   // 1049 = unknown database
                throw $e;
            }
            // First run on XAMPP: create the database ourselves.
            $db = new mysqli(DB_HOST, DB_USER, DB_PASS);
            $db->query('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            $db->select_db(DB_NAME);
        }
        $db->set_charset('utf8mb4');
    } catch (mysqli_sql_exception $e) {
        db_fail('Could not connect to the database. Please check the details in includes/db.php or includes/config.hosting.php.', $e);
    }

    // ---- 2. make sure the tables and dummy data exist
    try {
        // One cheap query tells us whether the tables are already complete; only if not do we
        // create tables, add missing columns and fill in the dummy data.
        if (!schema_is_current($db)) {
            setup_database($db);
        }
    } catch (mysqli_sql_exception $e) {
        db_fail('Connected to the database, but creating the tables failed.', $e);
    }

    return $db;
}

// Stops the page with a short message. The real MySQL message is only shown when
// DB_DEBUG is switched on in config.hosting.php (turn it off again afterwards), and is
// always written to the server's error log.
function db_fail(string $message, mysqli_sql_exception $e): void
{
    error_log('Gundam Hub database error [' . $e->getCode() . ']: ' . $e->getMessage());
    http_response_code(500);
    exit($message . (DB_DEBUG ? ' [' . $e->getCode() . '] ' . htmlspecialchars($e->getMessage()) : ''));
}
