<?php
// admin/about.php  — full About editor (history, legacy, mission, vision, core values)
require __DIR__ . '/../php_includes/connection.php';
require __DIR__ . '/_header.php';
require_once __DIR__ . '/../php_includes/audit.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ---- Create tables if missing ----
$con->query("
CREATE TABLE IF NOT EXISTS about_page (
  id TINYINT PRIMARY KEY,
  page_title    VARCHAR(191) NOT NULL,
  subtitle      VARCHAR(191) NULL,
  history_title VARCHAR(191) NULL,
  history_img   VARCHAR(255) NULL,
  history_caption TEXT NULL,
  history_body  TEXT NULL,
  legacy_title  VARCHAR(191) NULL,
  legacy_body   TEXT NULL,
  mission       TEXT NULL,
  vision        TEXT NULL,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");
$con->query("
CREATE TABLE IF NOT EXISTS about_values (
  id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  label       VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  icon_key    VARCHAR(32) NULL,
  sort_order  TINYINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Ensure seed row in about_page
$exists = $con->query("SELECT id FROM about_page WHERE id=1");
if ($exists && $exists->num_rows === 0) {
  $ins = $con->prepare("INSERT INTO about_page
   (id, page_title, subtitle, history_title, history_img, history_caption, history_body, legacy_title, legacy_body, mission, vision)
   VALUES (1, 'About Us', 'Serving the Philippines with reliable transportation since 1993',
           'Our History', 'images/about/history.jpg',
           'Photo caption…', 'Short history text…',
           'A Legacy of Service', 'Legacy paragraph…',
           'Mission text…', 'Vision text…')");
  $ins->execute(); $ins->close();
}
// Ensure 4 core values exist
$haveVals = $con->query("SELECT COUNT(*) c FROM about_values")->fetch_assoc();
if ((int)$haveVals['c'] < 4) {
  $defaults = [
    ['Safety First',  'Your safety is our top priority in every journey.',         'S', 10],
    ['Reliability',   'Consistent and dependable service you can count on.',       'R', 20],
    ['Comfort',       'Modern amenities for a pleasant travel experience.',        'C', 30],
    ['Excellence',    'Striving for the highest standards in everything we do.',   'E', 40],
  ];
  $ins = $con->prepare("INSERT INTO about_values (label, description, icon_key, sort_order) VALUES (?,?,?,?)");
  foreach ($defaults as $d) { $ins->bind_param("sssi", $d[0], $d[1], $d[2], $d[3]); $ins->execute(); }
  $ins->close();
}

// CSRF token
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$notice = "";

// ---- Save handler ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    $notice = '<div class="alert alert-danger">Invalid CSRF token.</div>';
  } else {
    // About page fields
    $fields = [
      'page_title','subtitle','history_title','history_img','history_caption','history_body',
      'legacy_title','legacy_body','mission','vision'
    ];
    $vals = [];
    foreach ($fields as $f) { $vals[$f] = trim($_POST[$f] ?? ''); }

    // Update about_page
    $sql = "UPDATE about_page SET
      page_title=?, subtitle=?, history_title=?, history_img=?, history_caption=?, history_body=?,
      legacy_title=?, legacy_body=?, mission=?, vision=? WHERE id=1";
    $upd = $con->prepare($sql);
    $upd->bind_param(
      "ssssssssss",
      $vals['page_title'], $vals['subtitle'], $vals['history_title'], $vals['history_img'],
      $vals['history_caption'], $vals['history_body'], $vals['legacy_title'], $vals['legacy_body'],
      $vals['mission'], $vals['vision']
    );
    $ok1 = $upd->execute(); $upd->close();

    // Update core values
    $ok2 = true;
    if (!empty($_POST['value_id'])) {
      for ($i=0; $i<count($_POST['value_id']); $i++) {
        $vid  = (int)$_POST['value_id'][$i];
        $lbl  = trim($_POST['value_label'][$i] ?? '');
        $desc = trim($_POST['value_desc'][$i] ?? '');
        $icon = trim($_POST['value_icon'][$i] ?? '');
        $ord  = (int)($_POST['value_order'][$i] ?? 0);

        // upsert
        if ($vid > 0) {
          $u = $con->prepare("UPDATE about_values SET label=?, description=?, icon_key=?, sort_order=? WHERE id=?");
          $u->bind_param("sssii", $lbl, $desc, $icon, $ord, $vid);
          $ok2 = $ok2 && $u->execute(); $u->close();
        } else if ($lbl !== '' && $desc !== '') {
          $iStmt = $con->prepare("INSERT INTO about_values (label, description, icon_key, sort_order) VALUES (?,?,?,?)");
          $iStmt->bind_param("sssi", $lbl, $desc, $icon, $ord);
          $ok2 = $ok2 && $iStmt->execute(); $iStmt->close();
        }
      }
    }

    if ($ok1 && $ok2) {
      audit_log($con, $_SESSION['email'] ?? $_SESSION['admin_email'] ?? 'admin', 'about_update');
      $notice = '<div class="alert alert-success">Saved! About page updated.</div>';
    } else {
      $notice = '<div class="alert alert-danger">Save failed. Please try again.</div>';
    }
  }
}

// ---- Load for form ----
$page = $con->query("SELECT * FROM about_page WHERE id=1")->fetch_assoc();
$values = [];
$r = $con->query("SELECT * FROM about_values ORDER BY sort_order, id");
while ($row = $r->fetch_assoc()) $values[] = $row;
?>
<div class="container-fluid py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0"><i class="bi bi-info-circle"></i> Edit About Page</h2>
    <?php if (!empty($page['updated_at'])): ?><small class="text-muted">Last updated: <?= e($page['updated_at']) ?></small><?php endif; ?>
  </div>

  <?= $notice ?>

  <form method="post" class="row g-4">
    <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">

    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header"><strong>Header</strong></div>
        <div class="card-body">
          <label class="form-label">Page Title</label>
          <input name="page_title" class="form-control mb-3" value="<?= e($page['page_title']) ?>" maxlength="191" required>

          <label class="form-label">Subtitle / Tagline</label>
          <input name="subtitle" class="form-control" value="<?= e($page['subtitle']) ?>" maxlength="191">
        </div>
      </div>

      <div class="card shadow-sm mt-3">
        <div class="card-header"><strong>Mission & Vision</strong></div>
        <div class="card-body">
          <label class="form-label">Mission</label>
          <textarea name="mission" rows="4" class="form-control mb-3"><?= e($page['mission']) ?></textarea>

          <label class="form-label">Vision</label>
          <textarea name="vision" rows="4" class="form-control"><?= e($page['vision']) ?></textarea>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header"><strong>History & Legacy</strong></div>
        <div class="card-body">
          <label class="form-label">History Title</label>
          <input name="history_title" class="form-control mb-3" value="<?= e($page['history_title']) ?>">

          <label class="form-label">History Image URL</label>
          <input name="history_img" class="form-control mb-3" value="<?= e($page['history_img']) ?>" placeholder="images/about/history.jpg">

          <label class="form-label">History Caption (under image)</label>
          <textarea name="history_caption" rows="2" class="form-control mb-3"><?= e($page['history_caption']) ?></textarea>

          <label class="form-label">History Body (left column text if any)</label>
          <textarea name="history_body" rows="3" class="form-control mb-3"><?= e($page['history_body']) ?></textarea>

          <div class="row">
            <div class="col-6">
              <label class="form-label">Legacy Title</label>
              <input name="legacy_title" class="form-control" value="<?= e($page['legacy_title']) ?>">
            </div>
            <div class="col-12 mt-3">
              <label class="form-label">Legacy Body</label>
              <textarea name="legacy_body" rows="5" class="form-control"><?= e($page['legacy_body']) ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mt-3">
        <div class="card-header d-flex justify-content-between">
          <strong>Core Values (up to 6)</strong>
          <small class="text-muted">Use order 10,20,30…</small>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:60px;">Order</th>
                  <th style="width:75px;">Icon</th>
                  <th>Label</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($values as $v): ?>
                  <tr>
                    <td><input type="number" class="form-control form-control-sm" name="value_order[]" value="<?= (int)$v['sort_order'] ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="value_icon[]" value="<?= e($v['icon_key']) ?>" placeholder="S / R / C / E"></td>
                    <td><input type="text" class="form-control form-control-sm" name="value_label[]" value="<?= e($v['label']) ?>"></td>
                    <td><input type="text" class="form-control form-control-sm" name="value_desc[]" value="<?= e($v['description']) ?>"></td>
                  </tr>
                  <input type="hidden" name="value_id[]" value="<?= (int)$v['id'] ?>">
                <?php endforeach; ?>

                <!-- empty row for optional new value -->
                <tr>
                  <td><input type="number" class="form-control form-control-sm" name="value_order[]" value="99"></td>
                  <td><input type="text" class="form-control form-control-sm" name="value_icon[]" value=""></td>
                  <td><input type="text" class="form-control form-control-sm" name="value_label[]" value=""></td>
                  <td><input type="text" class="form-control form-control-sm" name="value_desc[]" value=""></td>
                </tr>
                <input type="hidden" name="value_id[]" value="0">
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-3">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Save All</button>
      </div>
    </div>
  </form>
</div>

<?php require __DIR__ . '/_footer.php'; ?>
