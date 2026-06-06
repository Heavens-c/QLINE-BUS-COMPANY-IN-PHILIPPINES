<?php
// admin/audit_trail.php
require __DIR__ . '/../php_includes/connection.php';
require __DIR__ . '/_header.php';

$q      = trim($_GET['q'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = 15;
$off    = ($page - 1) * $per;

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Count
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $con->prepare("SELECT COUNT(*) c FROM audit_log WHERE email LIKE ? OR user_id LIKE ? OR action LIKE ? OR ip_address LIKE ?");
    $stmt->bind_param("ssss", $like, $like, $like, $like);
} else {
    $stmt = $con->prepare("SELECT COUNT(*) c FROM audit_log");
}
$stmt->execute();
$total = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
$stmt->close();

// Rows
if ($q !== '') {
    $stmt = $con->prepare("SELECT id, COALESCE(email, user_id, 'unknown') AS actor, action, ip_address, user_agent, created_at
                           FROM audit_log
                           WHERE email LIKE ? OR user_id LIKE ? OR action LIKE ? OR ip_address LIKE ?
                           ORDER BY id DESC
                           LIMIT ?, ?");
    $stmt->bind_param("ssssii", $like, $like, $like, $like, $off, $per);
} else {
    $stmt = $con->prepare("SELECT id, COALESCE(email, user_id, 'unknown') AS actor, action, ip_address, user_agent, created_at
                           FROM audit_log
                           ORDER BY id DESC
                           LIMIT ?, ?");
    $stmt->bind_param("ii", $off, $per);
}
$stmt->execute();
$res = $stmt->get_result();
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

$pages = max(1, (int)ceil($total / $per));
?>

<div class="container-fluid py-3">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0"><i class="bi bi-clipboard-data"></i> Audit Trail</h2>
    <form class="d-flex" method="get">
      <input class="form-control form-control-sm me-2" type="search" name="q" value="<?= e($q) ?>" placeholder="Search actor / action / IP…">
      <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-sm align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Actor</th>
            <th>Action</th>
            <th>IP</th>
            <th style="width:190px;">When</th>
            <th>User Agent</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$rows): ?>
            <tr><td colspan="6" class="text-center text-muted p-3">No records.</td></tr>
          <?php else: ?>
            <?php foreach ($rows as $r): ?>
              <?php
                $badge = 'secondary';
                if ($r['action'] === 'login_success') $badge = 'success';
                elseif ($r['action'] === 'login_failed') $badge = 'danger';
                elseif ($r['action'] === 'about_update') $badge = 'info';
              ?>
              <tr>
                <td class="text-muted">#<?= (int)$r['id'] ?></td>
                <td><?= e($r['actor']) ?></td>
                <td><span class="badge bg-<?= $badge ?>"><?= e($r['action']) ?></span></td>
                <td class="text-muted"><?= e($r['ip_address'] ?? '') ?></td>
                <td class="text-muted"><?= e($r['created_at'] ?? '') ?></td>
                <td class="text-truncate" style="max-width:420px;" title="<?= e($r['user_agent'] ?? '') ?>">
                  <?= e($r['user_agent'] ?? '') ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="text-muted small">Total: <?= number_format($total) ?></div>
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item <?= $page<=1?'disabled':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= max(1,$page-1) ?>">Prev</a></li>
          <?php for ($i=max(1,$page-2); $i<=min($pages,$page+2); $i++): ?>
            <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a></li>
          <?php endfor; ?>
          <li class="page-item <?= $page>=$pages?'disabled':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= min($pages,$page+1) ?>">Next</a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<?php require __DIR__ . '/_footer.php'; ?>
