<?php
// php_includes/get_about_page.php
require_once __DIR__ . '/connection.php';
if (!function_exists('e')) {
    function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

$page = $con->query("SELECT * FROM about_page WHERE id=1")->fetch_assoc();
$vals = [];
$r = $con->query("SELECT * FROM about_values ORDER BY sort_order, id");
if ($r) {
    while ($row = $r->fetch_assoc()) {
        $vals[] = $row;
    }
}

$page_title = $page['page_title'] ?? 'About Us';
$subtitle   = $page['subtitle'] ?? 'Serving the Philippines with reliable transportation since 1993';
?>

<!-- Page Header -->
<section class="content-grid">
    <div class="text-center" style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1rem;"><?= e($page_title) ?></h1>
        <?php if ($subtitle): ?>
            <p style="font-size: 1.125rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
                <?= e($subtitle) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- History Section -->
<section class="content-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><?= e($page['history_title'] ?? 'Our History') ?></h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; align-items: start;">
                <div>
                    <?php if (!empty($page['history_img'])): ?>
                        <img src="<?= e($page['history_img']) ?>" alt="History Image" style="width: 100%; border-radius: var(--radius-md);">
                    <?php endif; ?>
                    <?php if (!empty($page['history_caption'])): ?>
                        <p style="margin-top: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                            <?= nl2br(e($page['history_caption'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if (!empty($page['legacy_title'])): ?>
                        <h3 style="color: var(--primary-color); margin-bottom: 1rem;"><?= e($page['legacy_title']) ?></h3>
                    <?php endif; ?>
                    <?php if (!empty($page['history_body'])): ?>
                        <p style="line-height: 1.8; margin-bottom: 1rem; color: var(--text-secondary);">
                            <?= nl2br(e($page['history_body'])) ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($page['legacy_body'])): ?>
                        <p style="line-height: 1.8; color: var(--text-primary);">
                            <?= nl2br(e($page['legacy_body'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="content-grid" style="margin-top: 2rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white;">
                <h3 class="card-title" style="color: white; margin: 0;">Our Mission</h3>
            </div>
            <div class="card-body">
                <p style="font-size: 1.125rem; line-height: 1.7; color: var(--text-primary); margin: 0;">
                    <?= nl2br(e($page['mission'] ?? '')) ?>
                </p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-color), #d97706); color: white;">
                <h3 class="card-title" style="color: white; margin: 0;">Our Vision</h3>
            </div>
            <div class="card-body">
                <p style="font-size: 1.125rem; line-height: 1.7; color: var(--text-primary); margin: 0;">
                    <?= nl2br(e($page['vision'] ?? '')) ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<?php if (!empty($vals)): ?>
<section class="content-grid" style="margin-top: 2rem;">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Our Core Values</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                <?php foreach ($vals as $v): ?>
                    <div style="text-align: center;">
                        <?php if ($v['icon_key']): ?>
                            <div style="width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem; font-weight: bold;">
                                <?= e($v['icon_key']) ?>
                            </div>
                        <?php endif; ?>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;"><?= e($v['label']) ?></h4>
                        <p style="color: var(--text-secondary); margin: 0;"><?= e($v['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
