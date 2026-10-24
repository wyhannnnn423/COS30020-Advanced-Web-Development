<?php
// includes/config.hosting.example.php
// For putting the site on a web host (for example InfinityFree).
//   1. Copy this file and name the copy  config.hosting.php  (in the same includes folder).
//   2. Fill in the details your host shows under MySQL Databases.
//   3. Upload config.hosting.php to the host only. Do NOT put it in the zip you submit.

define('DB_HOST', 'sqlXXX.infinityfree.com');   // "MySQL Host Name" from the control panel
define('DB_USER', 'if0_XXXXXXXX');              // "MySQL User Name"
define('DB_PASS', 'your-database-password');
define('DB_NAME', 'if0_XXXXXXXX_gundam_hub');   // the full database name, with the prefix

// The password of admin@swin.edu.my on the public site. Choose your own, because anyone who
// finds the site could otherwise log in as admin with the default "admin".
define('ADMIN_SEED_PASSWORD', 'choose-a-strong-password');

// Set to true ONLY while something does not work: the error page then shows MySQL's own message.
// Set it back to false afterwards.
define('DB_DEBUG', false);
