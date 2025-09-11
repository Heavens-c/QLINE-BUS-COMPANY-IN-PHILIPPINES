<?php
// admin/dashboard.php
require __DIR__ . '/../php_includes/connection.php';
require __DIR__ . '/_header.php'; // includes session + UI shell

// Auto-detect base paths (works even if moved)
$scriptDir = str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')); // e.g. /.../admin
$adminBase = rtrim($scriptDir, '/');
$rootBase  = rtrim(dirname($adminBase), '/');

$slideUrl   = $adminBase . '/slide.php';
$auditUrl   = $adminBase . '/audit_trail.php';
$aboutUrl   = $adminBase . '/about.php';
$publicUrl  = $rootBase  . '/index.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Default values (in case tables/columns don’t exist yet)
$totalMembers = 0;
$adminCount   = null; // null => role column might not exist
$loginsToday  = 0;
$failedToday  = 0;

// --- KPI: total members
try {
  if ($res = $con->query("SELECT COUNT(*) AS c FROM members")) {
    $row = $res->fetch_assoc();
    $totalMembers = (int)($row['c'] ?? 0);
    $res->close();
  }
} catch (Throwable $t) { /* ignore; keep default */ }

// --- KPI: admin count (if role column exists)
try {
  if ($res = $con->query("SELECT COUNT(*) AS c FROM members WHERE role='admin'")) {
    $row = $res->fetch_assoc();
    $adminCount = (int)($row['c'] ?? 0);
    $res->close();
  } else {
    $adminCount = null;
  }
} catch (Throwable $t) {
  $adminCount = null; // role column likely missing
}

// --- KPI: logins today
try {
  $stmt = $con->prepare("SELECT COUNT(*) AS c FROM audit_log WHERE action='login_success' AND DATE(created_at)=CURDATE()");
  if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $loginsToday = (int)($row['c'] ?? 0);
    $stmt->close();
  }
} catch (Throwable $t) { /* ignore */ }

// --- KPI: failed logins today
try {
  $stmt = $con->prepare("SELECT COUNT(*) AS c FROM audit_log WHERE action='login_failed' AND DATE(created_at)=CURDATE()");
  if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $failedToday = (int)($row['c'] ?? 0);
    $stmt->close();
  }
} catch (Throwable $t) { /* ignore */ }

// --- Recent audit logs
$recentLogs = [];
try {
  $stmt = $con->prepare("SELECT id, email, action, ip_address, created_at FROM audit_log ORDER BY id DESC LIMIT 10");
  if ($stmt) {
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $recentLogs[] = $r;
    $stmt->close();
  }
} catch (Throwable $t) { /* ignore */ }

// --- About quick preview
$about = ['title' => 'About Us', 'p1' => '', 'p2' => '', 'updated_at' => null];
try {
  if ($res = $con->query("SELECT title, p1, p2, updated_at FROM about_page WHERE id=1")) {
    if ($res->num_rows > 0) {
      $about = $res->fetch_assoc();
    }
    $res->close();
  }
} catch (Throwable $t) { /* ignore; likely table not created yet */ }
?>

<div class="container-fluid py-3">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0"><i class="bi bi-grid-1x2-fill"></i> Dashboard</h2>
    <div class="d-flex gap-2">
      <a href="<?= e($slideUrl) ?>" class="btn btn-sm btn-primary"><i class="bi bi-sliders"></i> Slide Bar</a>
      <a href="<?= e($auditUrl) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-clipboard-data"></i> Audit Trail</a>
      <a href="<?= e($aboutUrl) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-info-circle"></i> Edit About</a>
      <a href="<?= e($publicUrl) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-house-door"></i> View Site</a>
    </div>
  </div>

  <!-- KPIs -->
  <div class="row g-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Total Members</div>
            <div class="fs-3 fw-semibold"><?= number_format($totalMembers) ?></div>
          </div>
          <i class="bi bi-people fs-1 text-primary"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Admins</div>
            <div class="fs-3 fw-semibold">
              <?= $adminCount === null ? '<span class="text-muted">N/A</span>' : number_format($adminCount) ?>
            </div>
            <?php if ($adminCount === null): ?>
              <small class="text-muted">Tip: add a <code>role</code> column to <code>members</code> (use values like <em>admin</em>, <em>user</em>).</small>
            <?php endif; ?>
          </div>
          <i class="bi bi-person-gear fs-1 text-success"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Logins Today</div>
            <div class="fs-3 fw-semibold"><?= number_format($loginsToday) ?></div>
          </div>
          <i class="bi bi-box-arrow-in-right fs-1 text-info"></i>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted">Failed Today</div>
            <div class="fs-3 fw-semibold <?= $failedToday > 0 ? 'text-danger' : '' ?>"><?= number_format($failedToday) ?></div>
          </div>
          <i class="bi bi-exclamation-triangle fs-1 text-danger"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Main grid: Recent Audit + About Preview -->
  <div class="row g-3 mt-1">
    <div class="col-lg-7">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <strong><i class="bi bi-clipboard-data"></i> Recent Audit Logs</strong>
          <a href="<?= e($auditUrl) ?>" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width:70px;">ID</th>
                  <th>Email</th>
                  <th>Action</th>
                  <th>IP</th>
                  <th style="width:180px;">When</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($recentLogs) === 0): ?>
                  <tr><td colspan="5" class="text-center text-muted p-3">No logs yet.</td></tr>
                <?php else: ?>
                  <?php foreach ($recentLogs as $log): ?>
                    <?php
                      $badge = 'secondary';
                      if ($log['action'] === 'login_success') $badge = 'success';
                      elseif ($log['action'] === 'login_failed') $badge = 'danger';
                      elseif ($log['action'] === 'about_update') $badge = 'info';
                    ?>
                    <tr>
                      <td class="text-muted">#<?= (int)$log['id'] ?></td>
                      <td><?= e($log['email'] ?? '') ?></td>
                      <td><span class="badge bg-<?= $badge ?>"><?= e($log['action']) ?></span></td>
                      <td class="text-muted"><?= e($log['ip_address'] ?? '') ?></td>
                      <td class="text-muted"><?= e($log['created_at'] ?? '') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer text-end">
          <a href="<?= e($auditUrl) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-journal-text"></i> Open Audit Trail</a>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <strong><i class="bi bi-info-circle"></i> About Preview</strong>
          <div class="d-flex align-items-center gap-2">
            <?php if (!empty($about['updated_at'])): ?>
              <small class="text-muted">Last updated: <?= e($about['updated_at']) ?></small>
            <?php endif; ?>
            <a href="<?= e($aboutUrl) ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i> Edit</a>
          </div>
        </div>
        <div class="card-body">
          <div id="about" class="about-section">
            <div class="about-content">
              <div class="about-text">
                <h4 class="mb-2"><?= e($about['title'] ?? 'About Us') ?></h4>
                <p class="mb-2"><?= nl2br(e($about['p1'] ?? '')) ?></p>
                <p class="mb-0"><?= nl2br(e($about['p2'] ?? '')) ?></p>
              </div>
            </div>
          </div>
          <?php if (empty($about['p1']) && empty($about['p2'])): ?>
            <div class="alert alert-info mt-3 mb-0">
              No About content yet. Click <strong>Edit</strong> to add your text.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row g-3 mt-1">
    <div class="col-md-4">
      <a class="card text-decoration-none shadow-sm h-100" href="<?= e($slideUrl) ?>">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-sliders fs-2"></i>
          <div>
            <div class="fw-semibold">Manage Slide Bar</div>
            <small class="text-muted">Shortcuts & actions</small>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a class="card text-decoration-none shadow-sm h-100" href="<?= e($auditUrl) ?>">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-clipboard-data fs-2"></i>
          <div>
            <div class="fw-semibold">Audit Trail</div>
            <small class="text-muted">Review recent activity</small>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a class="card text-decoration-none shadow-sm h-100" href="<?= e($aboutUrl) ?>">
        <div class="card-body d-flex align-items-center gap-3">
          <i class="bi bi-info-circle fs-2"></i>
          <div>
            <div class="fw-semibold">Edit About Page</div>
            <small class="text-muted">Update public content</small>
          </div>
        </div>
      </a>
    </div>
  </div>

</div>

<?php require __DIR__ . '/_footer.php'; ?>
