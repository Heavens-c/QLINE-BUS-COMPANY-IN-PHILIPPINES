<?php
// admin/_header.php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../php_includes/admin_only.php'; // blocks non-admins

/* Auto paths */
$scriptDir = str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '')); // /.../admin
$adminBase = rtrim($scriptDir, '/');
$rootBase  = rtrim(dirname($adminBase), '/');

/* URLs */
$dashboardUrl = $adminBase . '/dashboard.php';
$slideUrl     = $adminBase . '/slide.php';
$aboutUrl     = $adminBase . '/about.php';
$auditUrl     = $adminBase . '/audit_trail.php';
$publicUrl    = $rootBase  . '/index.php';
$logoutUrl    = $rootBase  . '/logout.php';

$current = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin • Dimple Star</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --aside-w: 260px; }
    body { min-height:100vh; background:#f8fafc; }
    .admin-wrap { display:flex; }
    .admin-aside {
      width: var(--aside-w);
      background:#0f172a; color:#fff; min-height:100vh; position:sticky; top:0;
    }
    .admin-aside .brand { font-weight:600; padding:1rem 1.25rem; border-bottom:1px solid rgba(255,255,255,.08); }
    .admin-aside a { color:#e5e7eb; text-decoration:none; }
    .admin-aside .nav-link { padding:.75rem 1.25rem; display:flex; gap:.5rem; align-items:center; border-left:3px solid transparent; }
    .admin-aside .nav-link.active, .admin-aside .nav-link:hover { background:#111827; border-left-color:#60a5fa; color:#fff; }
    .admin-main { flex:1; }
    .admin-topbar {
      display:flex; align-items:center; justify-content:space-between;
      padding:.75rem 1rem; border-bottom:1px solid #e5e7eb; background:#fff; position:sticky; top:0; z-index:50;
    }
    @media (max-width: 991px) {
      .admin-aside { position:fixed; left:-100%; transition:left .25s; z-index:1040; }
      .admin-aside.open { left:0; }
      .backdrop { position:fixed; inset:0; background:rgba(0,0,0,.35); display:none; z-index:1030; }
      .backdrop.show { display:block; }
    }
  </style>
</head>
<body>
<div class="backdrop" id="backdrop"></div>
<div class="admin-wrap">

  <aside class="admin-aside" id="aside">
    <div class="brand d-flex align-items-center justify-content-between">
      <span><i class="bi bi-speedometer2"></i> Admin Panel</span>
      <button class="btn btn-sm btn-outline-light d-lg-none" id="hideAside"><i class="bi bi-x-lg"></i></button>
    </div>
    <nav class="mt-2">
      <a href="<?= e($dashboardUrl) ?>" class="nav-link <?= $current==='dashboard.php'?'active':''; ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
      <a href="<?= e($slideUrl) ?>"     class="nav-link <?= $current==='slide.php'?'active':''; ?>"><i class="bi bi-sliders"></i> Slide Bar</a>
      <a href="<?= e($aboutUrl) ?>"     class="nav-link <?= $current==='about.php'?'active':''; ?>"><i class="bi bi-info-circle"></i> About Page</a>
      <a href="<?= e($auditUrl) ?>"     class="nav-link <?= $current==='audit_trail.php'?'active':''; ?>"><i class="bi bi-clipboard-data"></i> Audit Trail</a>
      <a href="<?= e($publicUrl) ?>"    class="nav-link"><i class="bi bi-house-door"></i> Public Site</a>
      <a href="<?= e($logoutUrl) ?>"    class="nav-link"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary d-lg-none" id="showAside"><i class="bi bi-list"></i></button>
        <strong>Welcome, <?= e($_SESSION['email'] ?? $_SESSION['admin_email'] ?? 'Admin'); ?></strong>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a class="btn btn-sm btn-outline-secondary" href="<?= e($auditUrl) ?>"><i class="bi bi-clipboard-data"></i> Audit</a>
        <a class="btn btn-sm btn-primary" href="<?= e($slideUrl) ?>"><i class="bi bi-sliders"></i> Slide Bar</a>
      </div>
    </div>
    <!-- Page content starts here -->

