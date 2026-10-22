<?php
// includes/setup.php
// Creates all five tables (only if they don't exist) and seeds dummy data
// (only into tables that are still empty). Called automatically by get_db().
//
// Columns marked "EXTRA" are not in the brief; they are needed to keep
// Assignment 1 features working. Remove them here if the lecturer says no.

function setup_database(mysqli $db): void
{
    // ---------------------------------------------------------------- user_table
    $db->query("CREATE TABLE IF NOT EXISTS user_table (
        email         VARCHAR(50)  NOT NULL,
        first_name    VARCHAR(50)  NOT NULL,
        last_name     VARCHAR(50)  NOT NULL,
        dob           DATE         NULL,
        gender        VARCHAR(6)   NOT NULL,
        hometown      VARCHAR(50)  NOT NULL,
        profile_image VARCHAR(100) NULL,
        resume_path   VARCHAR(100) NULL,            -- EXTRA: supporting document (Task 3.3)
        PRIMARY KEY (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ------------------------------------------------------------- account_table
    $db->query("CREATE TABLE IF NOT EXISTS account_table (
        email             VARCHAR(50)  NOT NULL,
        password          VARCHAR(255) NOT NULL,
        type              VARCHAR(5)   NOT NULL,
        security_question VARCHAR(30)  NULL,        -- EXTRA: keeps forgot_password.php working
        security_answer   VARCHAR(255) NULL,        -- EXTRA: stored as a hash
        PRIMARY KEY (email),
        CONSTRAINT fk_account_user FOREIGN KEY (email)
            REFERENCES user_table (email)
            ON UPDATE CASCADE ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // -------------------------------------------------------- smart_feature_table
    $db->query("CREATE TABLE IF NOT EXISTS smart_feature_table (
        id               INT          NOT NULL AUTO_INCREMENT,
        item_name        VARCHAR(50)  NOT NULL,
        item_category    VARCHAR(50)  NOT NULL,
        item_image       VARCHAR(100) NULL,
        description_file VARCHAR(100) NULL,
        status           VARCHAR(10)  NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ------------------------------------------------------------ activity_table
    $db->query("CREATE TABLE IF NOT EXISTS activity_table (
        id             INT          NOT NULL AUTO_INCREMENT,
        email          VARCHAR(50)  NOT NULL,
        first_name     VARCHAR(50)  NOT NULL,
        last_name      VARCHAR(50)  NOT NULL,
        `date`         DATE         NULL,
        `time`         TIME         NULL,
        contact_number VARCHAR(15)  NULL,
        status         VARCHAR(10)  NOT NULL,
        activity_title VARCHAR(100) NULL,           -- EXTRA: which workshop/screening was booked
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ----------------------------------------------------------- community_table
    $db->query("CREATE TABLE IF NOT EXISTS community_table (
        id                 INT          NOT NULL AUTO_INCREMENT,
        first_name         VARCHAR(50)  NOT NULL,
        last_name          VARCHAR(50)  NOT NULL,
        contribution_image VARCHAR(100) NULL,
        related_activity   VARCHAR(50)  NOT NULL,
        status             VARCHAR(10)  NOT NULL,
        title              VARCHAR(100) NULL,       -- EXTRA: A1 post title
        caption            TEXT         NULL,       -- EXTRA: A1 post caption
        tag                VARCHAR(30)  NULL,       -- EXTRA: A1 filter category
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // ------------------------------------------------------------ order_table (EXTRA)
    // A sixth table, not in the brief: it backs the shop checkout (QR payment, proof upload, tracking).
    $db->query("CREATE TABLE IF NOT EXISTS order_table (
        id              INT           NOT NULL AUTO_INCREMENT,
        email           VARCHAR(50)   NOT NULL,
        product_id      VARCHAR(40)   NOT NULL,
        product_name    VARCHAR(100)  NOT NULL,
        unit_price      DECIMAL(10,2) NOT NULL,
        quantity        INT           NOT NULL,
        recipient_name  VARCHAR(100)  NOT NULL,
        phone           VARCHAR(15)   NOT NULL,
        address         VARCHAR(200)  NOT NULL,
        city            VARCHAR(50)   NOT NULL,
        postcode        VARCHAR(10)   NOT NULL,
        state           VARCHAR(30)   NOT NULL,
        payment_proof   VARCHAR(100)  NULL,
        status          VARCHAR(20)   NOT NULL,
        tracking_number VARCHAR(40)   NULL,
        created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // -------------------------------------------------------- product_stock (EXTRA)
    // Stock and on/off switch for the products listed in data_products.php.
    $db->query("CREATE TABLE IF NOT EXISTS product_stock (
        product_id VARCHAR(40) NOT NULL,
        stock      INT         NOT NULL DEFAULT 10,
        available  TINYINT(1)  NOT NULL DEFAULT 1,
        PRIMARY KEY (product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // If these tables already existed from an older version (CREATE TABLE IF NOT EXISTS skips
    // them), add any column that is missing instead of crashing with "Unknown column".
    ensure_column($db, 'user_table',      'resume_path',       'VARCHAR(100) NULL');
    ensure_column($db, 'account_table',   'security_question', 'VARCHAR(30) NULL');
    ensure_column($db, 'account_table',   'security_answer',   'VARCHAR(255) NULL');
    ensure_column($db, 'activity_table',  'activity_title',    'VARCHAR(100) NULL');
    ensure_column($db, 'community_table', 'title',             'VARCHAR(100) NULL');
    ensure_column($db, 'community_table', 'caption',           'TEXT NULL');
    ensure_column($db, 'community_table', 'tag',               'VARCHAR(30) NULL');

    seed_users($db);
    seed_smart_features($db);
    seed_activities($db);
    seed_community($db);
    seed_orders($db);
    seed_product_stock($db);
}

// Adds $column to $table when it is not there yet. Names are hard-coded in this file.
function ensure_column(mysqli $db, string $table, string $column, string $definition): void
{
    $stmt = $db->prepare(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?'
    );
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ((int)$count === 0) {
        $db->query("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

function table_is_empty(mysqli $db, string $table): bool
{
    // $table is always a hard-coded name from this file, never user input.
    $row = $db->query("SELECT COUNT(*) AS c FROM `$table`")->fetch_assoc();
    return (int)$row['c'] === 0;
}

function seed_users(mysqli $db): void
{
    if (!table_is_empty($db, 'user_table')) {
        return;
    }

    // email, first, last, dob, gender, hometown, profile photo, plain password, type
    // Every dummy account also gets the security question/answer that registration asks for
    // (question "first_pet", answer "gundam").
    $users = [
        ['admin@swin.edu.my',         'Admin',    'Admin', null,         'Male', 'Kuching',         null,                       'admin',     'admin'],
        ['andrewkuan@swin.edu.my',    'Andrew',   'Kuan',  '2006-07-31', 'Male', 'Gelugor',         null,                       'User@1234', 'user'],
        ['dariusho@swin.edu.my',      'Darius',   'Ho',    '2006-02-26', 'Male', 'Semenyih',        null,                       'User@1234', 'user'],
        ['jerichoting@swin.edu.my',   'Jericho',  'Ting',  '2006-04-23', 'Male', 'Miri',            null,                       'User@1234', 'user'],
        ['jonathanlau@swin.edu.my',   'Jonathan', 'Lau',   '2006-07-18', 'Male', 'Iskandar Puteri', null,                       'User@1234', 'user'],
        ['haskelyu@swin.edu.my',      'Haskel',   'Yu',    '2006-02-14', 'Male', 'Bangi',           null,                       'User@1234', 'user'],
        ['brandonchan@swin.edu.my',   'Brandon',  'Chan',  '2006-12-11', 'Male', 'Kuching',         null,                       'User@1234', 'user'],
        ['yanhanwong@swin.edu.my',    'Yan Han',  'Wong',  '2006-04-23', 'Male', 'Kuching',         'img/avatars/your-photo.jpg', 'User@1234', 'user'],
    ];

    $insUser = $db->prepare(
        'INSERT INTO user_table (email, first_name, last_name, dob, gender, hometown, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $insAcc = $db->prepare(
        'INSERT INTO account_table (email, password, type, security_question, security_answer) VALUES (?, ?, ?, ?, ?)'
    );

    $db->begin_transaction();
    foreach ($users as [$email, $first, $last, $dob, $gender, $town, $photo, $plain, $type]) {
        $insUser->bind_param('sssssss', $email, $first, $last, $dob, $gender, $town, $photo);
        $insUser->execute();

        $hash = password_hash($plain, PASSWORD_DEFAULT);
        $q    = 'first_pet';
        $a    = password_hash('gundam', PASSWORD_DEFAULT);
        $insAcc->bind_param('sssss', $email, $hash, $type, $q, $a);
        $insAcc->execute();
    }
    $db->commit();
}

function seed_smart_features(mysqli $db): void
{
    if (!table_is_empty($db, 'smart_feature_table')) {
        return;
    }

    // name, category, image, pdf, status
    $rows = [
        ['RX-78-2 Gundam', 'Entry Grade', 'img/kits/eg-rx78-2.jpg', 'smart_feature_description/eg-rx78-2.pdf', 'approved'],
        ['Wing Gundam', 'Entry Grade', 'img/kits/eg-wing.jpg', 'smart_feature_description/eg-wing.pdf', 'approved'],
        ['Nu Gundam', 'Entry Grade', 'img/kits/eg-nu.jpg', 'smart_feature_description/eg-nu.pdf', 'approved'],
        ['SDCS RX-78-2 Gundam', 'SD', 'img/kits/sd-cs-rx78.jpg', 'smart_feature_description/sd-cs-rx78.pdf', 'approved'],
        ['Mighty Strike Freedom Gundam', 'High Grade', 'img/kits/hg-freedom.jpg', 'smart_feature_description/hg-freedom.pdf', 'approved'],
        ['Destiny Gundam', 'High Grade', 'img/kits/hg-destiny.jpg', 'smart_feature_description/hg-destiny.pdf', 'approved'],
        ['Infinite Justice Gundam', 'High Grade', 'img/kits/hg-justice.jpg', 'smart_feature_description/hg-justice.pdf', 'approved'],
        ['Gundam Exia Repair II', 'High Grade', 'img/kits/hg-exia-r2.jpg', 'smart_feature_description/hg-exia-r2.pdf', 'pending'],
        ['O Gundam', 'High Grade', 'img/kits/hg-o.jpg', 'smart_feature_description/hg-o.pdf', 'rejected'],
        ['Gundam Aerial', 'Full Mechanics', 'img/kits/fm-aerial.jpg', 'smart_feature_description/fm-aerial.pdf', 'approved'],
        ['Gundam Epyon', 'Real Grade', 'img/kits/rg-epyon.jpg', 'smart_feature_description/rg-epyon.pdf', 'approved'],
        ['Wing Gundam Zero', 'Real Grade', 'img/kits/rg-wing.jpg', 'smart_feature_description/rg-wing.pdf', 'approved'],
        ['Nightingale', 'Re/100', 'img/kits/re-nightingale.jpg', 'smart_feature_description/re-nightingale.pdf', 'approved'],
        ['Ex-S Gundam', 'Master Grade', 'img/kits/mg-ex-s.jpg', 'smart_feature_description/mg-ex-s.pdf', 'approved'],
        ['Unleashed RX-78-2 Gundam', 'Perfect Grade', 'img/kits/pg-unleashed.jpg', 'smart_feature_description/pg-unleashed.pdf', 'approved'],
    ];

    $stmt = $db->prepare(
        'INSERT INTO smart_feature_table (item_name, item_category, item_image, description_file, status) VALUES (?, ?, ?, ?, ?)'
    );
    foreach ($rows as [$name, $cat, $img, $pdf, $status]) {
        $stmt->bind_param('sssss', $name, $cat, $img, $pdf, $status);
        $stmt->execute();
    }
}

function seed_activities(mysqli $db): void
{
    if (!table_is_empty($db, 'activity_table')) {
        return;
    }

    // email, first, last, date, time, contact, status, title
    $rows = [
        ['andrewkuan@swin.edu.my',  'Andrew',   'Kuan', '2026-12-05', '10:00:00', '0166176318',  'approved', 'Workshop: Basic Assembly: Clean Builds Without Paint'],
        ['dariusho@swin.edu.my',    'Darius',   'Ho',   '2026-12-05', '14:00:00', '01155060558', 'pending',  'Workshop: Panel Lining: Tools & Technique'],
        ['jerichoting@swin.edu.my', 'Jericho',  'Ting', '2026-12-12', '10:00:00', '0178081683',  'rejected', 'Workshop: Decal & Sticker Application'],
        ['jonathanlau@swin.edu.my', 'Jonathan', 'Lau',  '2026-12-12', '14:00:00', '0128476627',  'approved', 'Workshop: Basic Touch-Up with Gundam Markers'],
        ['haskelyu@swin.edu.my',    'Haskel',   'Yu',   '2026-12-05', '14:00:00', '01165132677', 'pending',  'Workshop: Panel Lining: Tools & Technique'],
        ['brandonchan@swin.edu.my', 'Brandon',  'Chan', '2026-12-05', '10:00:00', '0146330320',  'approved', 'Workshop: Basic Assembly: Clean Builds Without Paint'],
        ['yanhanwong@swin.edu.my',  'Yan Han',  'Wong', '2026-12-12', '14:00:00', '01110823685', 'pending',  'Workshop: Basic Touch-Up with Gundam Markers'],
        ['yanhanwong@swin.edu.my',  'Yan Han',  'Wong', '2026-12-05', '10:00:00', '01110823685', 'approved', 'Workshop: Basic Assembly: Clean Builds Without Paint'],
    ];

    $stmt = $db->prepare(
        'INSERT INTO activity_table (email, first_name, last_name, `date`, `time`, contact_number, status, activity_title)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($rows as [$email, $first, $last, $date, $time, $contact, $status, $title]) {
        $stmt->bind_param('ssssssss', $email, $first, $last, $date, $time, $contact, $status, $title);
        $stmt->execute();
    }
}

function seed_community(mysqli $db): void
{
    if (!table_is_empty($db, 'community_table')) {
        return;
    }

    // first, last, image, related_activity, status, title, caption, tag
    $rows = [
        ['Yan Han', 'Wong', 'img/community/build_01.jpg',      'Basic Assembly',            'approved',
            'First Gunpla work: water decaling',
            'Trying out water decaling on HGUC Zeta Gundam. Took a while to get the placement right so the decals did not sit crooked.',
            'Workshop'],
        ['Andrew',  'Kuan', 'img/community/build_02.jpg',      'Basic Assembly',            'approved',
            'HGBF Wing Gundam Zero Honoo: Basic Build',
            'First time building a Gunpla kit. Took about 4 hours, still a work in progress. Planning to add panel lining next.',
            'Gunpla Build'],
        ['Darius',  'Ho',   'img/community/discussion_01.jpg', 'Series Guide',              'approved',
            'Tomino writes his ginger characters surprisingly well',
            'Been rewatching the UC timeline and noticed most of the red-haired characters are some of the best-written in the series.',
            'Anime Discussion'],
        ['Jericho', 'Ting', 'img/community/game_01.jpg',       'SD Gundam Battle Alliance', 'approved',
            'Clutch win in SD Gundam Battle Alliance',
            'Down to my last bit of HP, landed the beam saber finisher just before the Zaku could recover.',
            'Game Highlight'],
    ];

    $stmt = $db->prepare(
        'INSERT INTO community_table (first_name, last_name, contribution_image, related_activity, status, title, caption, tag)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($rows as [$first, $last, $img, $related, $status, $title, $caption, $tag]) {
        $stmt->bind_param('ssssssss', $first, $last, $img, $related, $status, $title, $caption, $tag);
        $stmt->execute();
    }
}

function seed_orders(mysqli $db): void
{
    if (!table_is_empty($db, 'order_table')) {
        return;
    }

    // email, product id, name, price, qty, recipient, phone, address, city, postcode, state, proof, status, tracking
    $rows = [
        ['yanhanwong@swin.edu.my', 'eg-rx78-2',  'EG RX-78-2 Gundam',               40.00, 1, 'Yan Han Wong',  '01110823685', 'Jalan Hup Kee',                           'Kuching',         '93350', 'Sarawak',         'payment_proofs/demo-proof.png', 'shipped',          'MY1234567890'],
        ['yanhanwong@swin.edu.my', 'hg-freedom', 'HG Mighty Strike Freedom Gundam', 95.00, 1, 'Yan Han Wong',  '01110823685', 'Jalan Hup Kee',                           'Kuching',         '93350', 'Sarawak',         'payment_proofs/demo-proof.png', 'pending',          null],
        ['andrewkuan@swin.edu.my', 'hg-exia-r2', 'HG Gundam Exia R2',               56.00, 2, 'Andrew Kuan',   '0166176318',  'Jalan Universiti',                        'Gelugor',         '11700', 'Penang',          null,                            'awaiting_payment', null],
        ['dariusho@swin.edu.my',   'sd-cs-rx78', 'SDCS RX-78-2 Gundam',             35.00, 1, 'Darius Ho',     '01155060558', 'Jalan Broga',                             'Semenyih',        '43500', 'Selangor',        'payment_proofs/demo-proof.png', 'paid',             null],
        ['jerichoting@swin.edu.my', 'rg-epyon', 'RG Gundam Epyon',              147.00, 1, 'Jericho Ting',  '0178081683',  'Kuala Baram',                             'Miri',            '98009', 'Sarawak',         'payment_proofs/demo-proof.png', 'packed',           null],
        ['jonathanlau@swin.edu.my','hg-justice', 'HG Infinite Justice Gundam',      98.00, 1, 'Jonathan Lau',  '0128476627',  'Jalan Eko Botani 3, Taman Eko Botani',    'Iskandar Puteri', '79100', 'Johor',           'payment_proofs/demo-proof.png', 'shipped',          'MY9876543210'],
        ['haskelyu@swin.edu.my',   'eg-wing',    'EG Wing Gundam',                  42.00, 1, 'Haskel Yu',     '01165132677', 'Lingkungan Ilmu',                         'Bangi',           '43600', 'Selangor',        'payment_proofs/demo-proof.png', 'delivered',        'MY5556667778'],
        ['brandonchan@swin.edu.my','fm-aerial',  'FM Gundam Aerial',               175.00, 1, 'Brandon Chan',  '0146330320',  'Jalan Simpang Tiga',                      'Kuching',         '93350', 'Sarawak',         'payment_proofs/demo-proof.png', 'pending',          null],
    ];

    $stmt = $db->prepare(
        'INSERT INTO order_table (email, product_id, product_name, unit_price, quantity, recipient_name, phone, address, city, postcode, state, payment_proof, status, tracking_number)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    foreach ($rows as [$email, $pid, $pname, $price, $qty, $rec, $phone, $addr, $city, $post, $state, $proof, $status, $track]) {
        $stmt->bind_param('sssdisssssssss', $email, $pid, $pname, $price, $qty, $rec, $phone, $addr, $city, $post, $state, $proof, $status, $track);
        $stmt->execute();
    }
}

function seed_product_stock(mysqli $db): void
{
    if (!table_is_empty($db, 'product_stock')) {
        return;
    }
    require_once __DIR__ . '/../data_products.php';

    // A few sample states so the shop and the admin page show something: sold out, low stock, switched off.
    $special = ['hg-o' => [0, 1], 'eg-wing' => [2, 1], 'mg-ex-s' => [5, 0]];

    $stmt = $db->prepare('INSERT INTO product_stock (product_id, stock, available) VALUES (?, ?, ?)');
    foreach (product_catalogue() as $list) {
        foreach ($list as $p) {
            [$stock, $available] = $special[$p['id']] ?? [10, 1];
            $stmt->bind_param('sii', $p['id'], $stock, $available);
            $stmt->execute();
        }
    }
}
