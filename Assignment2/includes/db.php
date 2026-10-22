<?php
// includes/db.php
// One shared MySQL connection. On first use it also creates the database,
// the five tables and the dummy data (see setup.php).
//
// Usage on any page:   require_once 'includes/db.php';  $db = get_db();

require_once __DIR__ . '/setup.php';

// ---- Change these if your MySQL server uses different credentials ----
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'gundam_hub';
// ----------------------------------------------------------------------

function get_db(): mysqli
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    // Throw exceptions instead of printing raw MySQL errors to visitors.
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS);
        $db->set_charset('utf8mb4');
        $db->query('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $db->select_db(DB_NAME);
        setup_database($db);
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        exit('Could not connect to the database. Please check the credentials in includes/db.php.');
    }

    return $db;
}
