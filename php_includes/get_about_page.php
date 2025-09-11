<?php
// php_includes/get_about_page.php
require_once __DIR__ . '/connection.php';
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$page = $con->query("SELECT * FROM about_page WHERE id=1")->fetch_assoc();
$vals = [];
$r = $con->query("SELECT * FROM about_values ORDER BY sort_order, id"); while ($row=$r->fetch_assoc()) $vals[]=$row;

$page_title = $page['page_title'] ?? 'About Us';
$subtitle   = $page['subtitle'] ?? '';
?>
<section class="about-hero" style="margin-top:2rem; text-align:center;">
  <h1><?= e($page_title) ?></h1>
  <?php if ($subtitle): ?><p class="text-muted"><?= e($subtitle) ?></p><?php endif; ?>
</section>

<section class="container" style="margin-top:2rem;">
  <div class="row g-4 align-items-start">
    <div class="col-md-6">
      <?php if (!empty($page['history_img'])): ?>
        <img src="<?= e($page['history_img']) ?>" alt="History" class="img-fluid rounded border">
      <?php endif; ?>
      <?php if (!empty($page['history_caption'])): ?>
        <small class="d-block text-muted mt-2"><?= nl2br(e($page['history_caption'])) ?></small>
      <?php endif; ?>
      <?php if (!empty($page['history_body'])): ?>
        <p class="mt-3"><?= nl2br(e($page['history_body'])) ?></p>
      <?php endif; ?>
    </div>

    <div class="col-md-6">
      <?php if (!empty($page['legacy_title'])): ?>
        <h5 class="mb-2"><?= e($page['legacy_title']) ?></h5>
      <?php endif; ?>
      <?php if (!empty($page['legacy_body'])): ?>
        <p><?= nl2br(e($page['legacy_body'])) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-4 mt-2">
    <div class="col-md-6">
      <div class="p-3 rounded border h-100">
        <h6 class="mb-2">Our Mission</h6>
        <p class="mb-0"><?= nl2br(e($page['mission'] ?? '')) ?></p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 rounded border h-100">
        <h6 class="mb-2">Our Vision</h6>
        <p class="mb-0"><?= nl2br(e($page['vision'] ?? '')) ?></p>
      </div>
    </div>
  </div>

  <div class="mt-4">
    <h5>Our Core Values</h5>
    <div class="row g-3">
      <?php foreach ($vals as $v): ?>
        <div class="col-md-3 col-sm-6">
          <div class="text-center p-3 rounded border h-100">
            <?php if ($v['icon_key']): ?>
              <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                   style="width:48px;height:48px;border:2px solid #f0b429;font-weight:700;">
                <?= e($v['icon_key']) ?>
              </div>
            <?php endif; ?>
            <div class="fw-semibold mt-2"><?= e($v['label']) ?></div>
            <small class="text-muted d-block"><?= e($v['description']) ?></small>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
