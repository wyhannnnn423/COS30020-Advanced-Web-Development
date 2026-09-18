<?php
$page_title = 'Series Detail'; 
include 'header.php'; 
require_once 'data_series.php'; // Include centralized database array

// Retrieve item parameter safely using null coalescing operator
$id = $_GET['id'] ?? '';
$item = $items[$id] ?? null;

// Dynamically assign document title based on entry title
if ($item) {
    $page_title = $item['title'] . ' - Gundam Hub';
}
?>

<main class="detail-page">
<?php if ($item): ?>

    <!-- Graceful degradation: adds 'no-video' modifier class when video is empty -->
    <div class="detail-container <?= empty($item['video']) ? 'no-video' : '' ?>">
 
        <div class="detail-main-content">
 
            <!-- Render embedded iframe player only if video source exists -->
            <?php if (!empty($item['video'])): ?>
            <div class="detail-video">
                <iframe 
                    src="<?= htmlspecialchars($item['video']) ?>?autoplay=1&mute=1&modestbranding=1&rel=0" 
                    title="<?= htmlspecialchars($item['title']) ?> trailer"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <?php endif; ?>
 
            <div class="detail-info">
                <h1><?= htmlspecialchars($item['title']) ?></h1>
                
                <!-- 1. Anime timeline metadata -->
                <?php if ($item['type'] === 'anime'): ?>
                    <div class="meta-row">
                        <span class="meta format"><?= htmlspecialchars($item['format'] ?? 'Animation') ?></span>
                        <span class="meta"><?= htmlspecialchars($item['year'] ?? '') ?></span>
                        <span class="meta"><?= htmlspecialchars($item['era'] ?? '') ?></span>
                    </div>
                <!-- 2. Video game metadata -->
                <?php elseif ($item['type'] === 'game'): ?>
                    <div class="meta-row">
                        <span class="meta format"><?= htmlspecialchars($item['genre'] ?? 'Game') ?></span>
                    </div>
              
                <?php endif; ?>

                <p class="detail-caption"><?= htmlspecialchars($item['desc'] ?? '') ?></p>

                <!-- Production stills gallery: renders only if gallery paths exist -->
                <?php if (!empty($item['gallery'])): ?>
                    <section class="detail-gallery-section">
                        <h3 class="gallery-title">Production Stills &amp; Gallery</h3>
                        <div class="detail-gallery">
                            <?php foreach ($item['gallery'] as $img): ?>
                                <div class="gallery-card">
                                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($item['title']) ?> still">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Call-to-action buttons dynamically tailored to item type -->
                <div class="detail-actions">
                    <?php if ($item['type'] === 'anime'): ?>
                    <a href="activity_reg.php?activity=screening-<?php echo urlencode($id); ?>&from=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="card-btn">Book Screening</a>
                    <?php elseif ($item['type'] === 'game'): ?>
                        <a href="activity_reg.php?activity=session-<?= urlencode($id) ?>" class="card-btn">Join a Session</a>
                    <?php endif; ?>
                    
                    <!-- Return anchor link back to the catalog position -->
                    <a href="series.php#<?= urlencode($id) ?>" class="card-btn card-btn-secondary">Back to Guide</a>
                </div>
            </div>
 
        </div>
 
        <!-- Sidebar poster visual element -->
        <div class="detail-sidebar">
            <div class="detail-photo">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            </div>
        </div>
 
    </div>
 
<?php else: ?>
 
    <!-- 404 Fallback state -->
    <div class="detail-not-found">
        <h1>Item not found</h1>
        <p>This entry may have been removed or the link is incorrect.</p>
        <a href="series.php" class="card-btn">Back to Series Guide</a>
    </div>
 
<?php endif; ?>
</main>

<?php include 'footer.php'; ?>