<?php
// app/Views/layouts/admin.php
use App\Core\Auth;
use App\Core\CSRF;

$user = Auth::user();
$unread = (new \App\Models\Notification())->unreadCount($user['id']);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

function isActive($segment) { global $path; return str_contains($path, $segment) ? 'active' : ''; }
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="<?= CSRF::token() ?>">
    <title><?= e($title ?? 'لوحة التحكم') ?> — إدارة المنصة</title>
    
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>window.APP_BASE = '<?= rtrim(e(url('/')), '/') ?>';</script>
</head>
<body>
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon"><i data-lucide="shield-check" style="width:20px;"></i></div> 
            <span>إدارة المنصة</span>
        </div>
        <div class="sidebar-menu">
            
            <div class="sidebar-heading">الرئيسية</div>
            <a href="<?= e(url('admin/dashboard')) ?>" class="sidebar-link <?= isActive('dashboard') ?>"><i data-lucide="pie-chart" style="width:16px;"></i> لوحة القيادة</a>
            <a href="<?= e(url('admin/reports')) ?>" class="sidebar-link <?= isActive('reports') ?>"><i data-lucide="bar-chart-2" style="width:16px;"></i> التقارير المالية</a>
            
            <div class="sidebar-heading">التبرعات والمشاريع</div>
            <a href="<?= e(url('admin/donations')) ?>" class="sidebar-link <?= isActive('donations') ?>"><i data-lucide="heart-handshake" style="width:16px;"></i> سجل التبرعات</a>
            <a href="<?= e(url('admin/projects')) ?>" class="sidebar-link <?= isActive('projects') ?>"><i data-lucide="folder-kanban" style="width:16px;"></i> إدارة المشاريع</a>
            <a href="<?= e(url('admin/campaigns')) ?>" class="sidebar-link <?= isActive('campaigns') ?>"><i data-lucide="megaphone" style="width:16px;"></i> الحملات الإغاثية</a>
            
            <div class="sidebar-heading">التفاعل والمجتمع</div>
            <a href="<?= e(url('admin/volunteers')) ?>" class="sidebar-link <?= isActive('volunteers') ?>"><i data-lucide="users" style="width:16px;"></i> طلبات التطوع</a>
            <a href="<?= e(url('admin/assistance')) ?>" class="sidebar-link <?= isActive('assistance') ?>"><i data-lucide="hand-helping" style="width:16px;"></i> طلبات المساعدة</a>
            <a href="<?= e(url('admin/contact')) ?>" class="sidebar-link <?= isActive('contact') ?>"><i data-lucide="message-square" style="width:16px;"></i> البريد الوارد</a>
            
            <div class="sidebar-heading">المحتوى والإعدادات</div>
            <a href="<?= e(url('admin/articles')) ?>" class="sidebar-link <?= isActive('articles') ?>"><i data-lucide="newspaper" style="width:16px;"></i> المركز الإعلامي</a>
            <a href="<?= e(url('admin/media')) ?>" class="sidebar-link <?= isActive('media') ?>"><i data-lucide="image" style="width:16px;"></i> مكتبة الوسائط</a>
            <a href="<?= e(url('admin/pages')) ?>" class="sidebar-link <?= isActive('pages') ?>"><i data-lucide="file-text" style="width:16px;"></i> الصفحات الثابتة</a>
            <a href="<?= e(url('admin/settings')) ?>" class="sidebar-link <?= isActive('settings') ?>"><i data-lucide="settings" style="width:16px;"></i> إعدادات الموقع</a>
            <a href="<?= e(url('admin/users')) ?>" class="sidebar-link <?= isActive('users') ?>"><i data-lucide="shield" style="width:16px;"></i> مدراء النظام</a>
            
            <a href="<?= e(url('admin/logout')) ?>" class="sidebar-link sidebar-logout" style="margin-top:10px;"><i data-lucide="log-out" style="width:16px;"></i> خروج</a>
        </div>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1 class="topbar-title"><?= e($title ?? 'لوحة التحكم') ?></h1>
            <div class="topbar-actions">
                <a href="<?= e(url('/')) ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i data-lucide="external-link" style="width:14px;"></i> للموقع</a>
                <div class="user-profile-widget" style="display: flex;gap: 5px;align-items: center;">
                    <div class="user-avatar"><?= mb_substr($user['name'] ?? 'A', 0, 1) ?></div>
                    <div class="user-info">
                        <div class="user-name"><?= e($user['name'] ?? 'مدير') ?></div>
                    </div>
                </div>
            </div>
        </header>
        <div class="admin-content"><?= $content ?></div>
    </main>

    <style>
        .admin-sidebar { width: 240px; font-size: 13px; }
        .sidebar-brand { padding: 15px 20px; font-size: 18px; }
        .sidebar-menu { padding: 10px 15px; gap: 2px; }
        .sidebar-link { padding: 10px 15px; border-radius: 8px; font-size: 13.5px; }
        .sidebar-heading { font-size: 10.5px; margin: 12px 15px 4px; }
        .admin-main { margin-right: 240px; }
        .admin-topbar { height: 70px; padding: 0 25px; }
        .user-avatar { width: 35px; height: 35px; background: var(--gold); color: #000; border-radius: 8px; display: flex; justify-content: center; align-items: center; font-weight: 900; }
        .user-name { font-size: 13px; font-weight: 800; color: var(--sidebar-bg); }
    </style>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>