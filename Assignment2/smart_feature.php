<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
require_once 'includes/planner.php';
require_role('user');   // the Kit Recommender is for logged-in users

$db = get_db();

// ------------------------------------------------------------------ Build Planner
$skill  = $_GET['skill'] ?? '';
$finish = $_GET['finish'] ?? '';
$budget = $_GET['budget'] ?? '';
$planned = isset($_GET['plan']);
$plan_errors = [];
$plan_results = [];

if ($planned) {
    if (!isset(SKILLS[$skill])) {
        $plan_errors[] = 'Please choose your skill level.';
    }
    if (!isset(FINISHES[$finish])) {
        $plan_errors[] = 'Please choose a finish.';
    }
    if (!is_numeric($budget) || $budget < 20 || $budget > 5000) {
        $plan_errors[] = 'Budget must be between RM 20 and RM 5000.';
    }

    if (empty($plan_errors)) {
        // 1. Which grades suit this skill level and fit the budget (kit + supplies)?
        $fit = [];
        foreach (grades_for_skill($skill) as $grade) {
            $calc = plan_for_grade($grade, $skill, $finish);
            if ($calc['total'] <= (float)$budget) {
                $fit[$grade] = $calc;
            }
        }

        // 2. Which APPROVED kits in smart_feature_table belong to those grades?
        if (!empty($fit)) {
            $grades       = array_keys($fit);
            $placeholders = implode(',', array_fill(0, count($grades), '?'));
            $stmt = $db->prepare(
                "SELECT item_name, item_category, item_image, description_file
                 FROM smart_feature_table
                 WHERE status = 'approved' AND item_category IN ($placeholders)
                 ORDER BY item_category, item_name"
            );
            $stmt->bind_param(str_repeat('s', count($grades)), ...$grades);
            $stmt->execute();
            foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
                $row['calc'] = $fit[$row['item_category']];
                $plan_results[] = $row;
            }
            // Most fitting first: closest to the full budget without going over
            usort($plan_results, fn($a, $b) => $b['calc']['total'] <=> $a['calc']['total']);
        }
    }
}

// ------------------------------------------------------------------ Quick finder
$cat_rows   = $db->query("SELECT DISTINCT item_category FROM smart_feature_table WHERE status = 'approved' ORDER BY item_category")
                 ->fetch_all(MYSQLI_ASSOC);
$categories = array_column($cat_rows, 'item_category');

$q        = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');
$searched = isset($_GET['search']);
$results  = [];

if ($searched) {
    // Only approved kits may ever be shown. LIKE wildcards typed by the visitor are escaped.
    $sql    = "SELECT item_name, item_category, item_image, description_file
               FROM smart_feature_table WHERE status = 'approved'";
    $types  = '';
    $params = [];

    if ($q !== '') {
        $sql     .= " AND item_name LIKE ? ESCAPE '!'";
        $types   .= 's';
        $params[] = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $q) . '%';
    }
    if ($category !== '') {
        $sql     .= ' AND item_category = ?';
        $types   .= 's';
        $params[] = $category;
    }
    $sql .= ' ORDER BY item_category, item_name';

    $stmt = $db->prepare($sql);
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Small helper: one result card
function kit_card(array $r, ?array $calc = null): void
{
    ?>
    <article class="smart-card">
        <?php if (!empty($r['item_image']) && is_file($r['item_image'])): ?>
            <img src="<?= htmlspecialchars($r['item_image']) ?>" alt="<?= htmlspecialchars($r['item_name']) ?>">
        <?php endif; ?>
        <div class="smart-card-body">
            <h3><?= htmlspecialchars($r['item_name']) ?></h3>
            <span class="post-tag"><?= htmlspecialchars($r['item_category']) ?></span>

            <?php if ($calc): ?>
            <ul class="plan-list">
                <li><strong>Build time</strong> about <?= $calc['hours'] ?> h</li>
                <li><strong>Kit</strong> RM <?= number_format($calc['kit_rm'], 2) ?> (typical)</li>
                <?php if ($calc['markers']): ?><li><strong>Panel line marker</strong> &times; <?= $calc['markers'] ?></li><?php endif; ?>
                <?php if ($calc['primer']): ?><li><strong>Primer</strong> about <?= $calc['primer'] ?> ml</li><?php endif; ?>
                <?php if ($calc['paint']): ?><li><strong>Paint</strong> about <?= $calc['paint'] ?> ml</li><?php endif; ?>
                <?php if ($calc['topcoat']): ?><li><strong>Matte topcoat</strong> about <?= $calc['topcoat'] ?> ml</li><?php endif; ?>
                <li class="plan-total"><strong>Estimated total</strong> RM <?= number_format($calc['total'], 2) ?></li>
            </ul>
            <?php endif; ?>

            <?php if (!empty($r['description_file']) && is_file($r['description_file'])): ?>
                <a href="<?= htmlspecialchars($r['description_file']) ?>" download class="card-btn">Download PDF</a>
            <?php endif; ?>
        </div>
    </article>
    <?php
}

$page_title = 'Kit Recommender';
include 'header.php';
?>

<main class="page smart-page">
    <h1>Kit Recommender</h1>
    <p class="smart-lead">Tell us how you build and we will work out which kits suit you, how long they take and what supplies you need.</p>

    <!-- ===================== Build Planner ===================== -->
    <section class="smart-panel">
        <h2>Build Planner</h2>
        <form method="GET" class="smart-form">
            <label>Skill level
                <select name="skill">
                    <option value="">-- Select --</option>
                    <?php foreach (SKILLS as $key => $s): ?>
                        <option value="<?= $key ?>" <?= ($skill === $key) ? 'selected' : '' ?>><?= htmlspecialchars($s['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Finish
                <select name="finish">
                    <option value="">-- Select --</option>
                    <?php foreach (FINISHES as $key => $f): ?>
                        <option value="<?= $key ?>" <?= ($finish === $key) ? 'selected' : '' ?>><?= htmlspecialchars($f['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Budget (RM)
                <input type="number" name="budget" min="20" max="5000" step="10" placeholder="e.g. 150" value="<?= htmlspecialchars((string)$budget) ?>">
            </label>
            <button type="submit" name="plan" value="1" class="card-btn">Plan My Build</button>
        </form>

        <?php if ($planned): ?>
            <?php if (!empty($plan_errors)): ?>
                <div class="form-errors"><ul>
                    <?php foreach ($plan_errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul></div>
            <?php elseif (empty($plan_results)): ?>
                <p class="smart-empty">No matching data available. Try a higher budget or a simpler finish.</p>
            <?php else: ?>
                <p class="smart-count"><?= count($plan_results) ?> kit<?= count($plan_results) === 1 ? '' : 's' ?> fit your skill and budget. Numbers are estimates.</p>
                <div class="smart-grid">
                    <?php foreach ($plan_results as $r): kit_card($r, $r['calc']); endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <!-- ===================== Quick finder ===================== -->
    <section class="smart-panel">
        <h2>Find a Kit</h2>
        <form method="GET" class="smart-form">
            <input type="text" name="q" placeholder="Kit name, e.g. Freedom" maxlength="50" value="<?= htmlspecialchars($q) ?>">
            <select name="category">
                <option value="">All grades</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c) ?>" <?= ($category === $c) ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="search" value="1" class="card-btn">Find Kits</button>
        </form>

        <?php if ($searched): ?>
            <?php if (empty($results)): ?>
                <p class="smart-empty">No matching data available. Try a different name or grade.</p>
            <?php else: ?>
                <p class="smart-count"><?= count($results) ?> kit<?= count($results) === 1 ? '' : 's' ?> found</p>
                <div class="smart-grid">
                    <?php foreach ($results as $r): kit_card($r); endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <p class="smart-contribute">Know a kit that is missing? <a href="smart_feature_add.php">Contribute one</a>.</p>
</main>

<?php include 'footer.php'; ?>
