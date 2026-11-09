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
    <link rel="icon" href="<?= e(\App\Core\Settings::get('site.favicon', asset('images/logo.png'))) ?>" type="image/png">
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
        <!-- عرض اللوجو هنا بدلاً من النص -->
        <div class="sidebar-brand" style="display: flex; justify-content: center; padding: 20px 10px; background: rgba(0,0,0,0.1);">
            <a href="<?= e(url('admin/dashboard')) ?>">
<img src="<?= e(\App\Core\Settings::get('site.logo', asset('images/logo.png'))) ?>" alt="Logo" style="max-height: 85px; object-fit: contain; border-radius:15px;"style="max-height: 55px; object-fit: contain;" onerror="this.src='https://placehold.co/150x50/061B52/FFF?text=Admin'">            </a>
        </div>
        
        <div class="sidebar-menu">
            <div class="sidebar-heading">نظرة عامة</div>
            <a href="<?= e(url('admin/dashboard')) ?>" class="sidebar-link <?= isActive('dashboard') ?>"><i data-lucide="pie-chart"></i> لوحة القيادة</a>
            <a href="<?= e(url('admin/reports')) ?>" class="sidebar-link <?= isActive('reports') ?>"><i data-lucide="bar-chart-2"></i> التقارير المالية</a>
            
            <div class="sidebar-heading">التبرعات والمشاريع</div>
            <a href="<?= e(url('admin/donations')) ?>" class="sidebar-link <?= isActive('donations') ?>"><i data-lucide="heart-handshake"></i> سجل التبرعات</a>
            <a href="<?= e(url('admin/projects')) ?>" class="sidebar-link <?= isActive('projects') ?>"><i data-lucide="folder-kanban"></i> إدارة المشاريع</a>
            <a href="<?= e(url('admin/campaigns')) ?>" class="sidebar-link <?= isActive('campaigns') ?>"><i data-lucide="megaphone"></i> الحملات الإغاثية</a>
            <a href="<?= e(url('admin/categories')) ?>" class="sidebar-link <?= isActive('categories') ?>"><i data-lucide="tags"></i> تصنيفات المشاريع</a>
            
            <div class="sidebar-heading">المجتمع والطلبات</div>
            <a href="<?= e(url('admin/volunteers')) ?>" class="sidebar-link <?= isActive('volunteers') ?>"><i data-lucide="users"></i> التطوع والفرص</a>
            <a href="<?= e(url('admin/assistance')) ?>" class="sidebar-link <?= isActive('assistance') ?>"><i data-lucide="hand-helping"></i> طلبات المساعدة</a>
            <a href="<?= e(url('admin/contact')) ?>" class="sidebar-link <?= isActive('contact') ?>"><i data-lucide="message-square"></i> البريد الوارد</a>
            
            <div class="sidebar-heading">المحتوى والواجهة</div>
            <a href="<?= e(url('admin/articles')) ?>" class="sidebar-link <?= isActive('articles') ?>"><i data-lucide="newspaper"></i> المركز الإعلامي</a>
            <a href="<?= e(url('admin/media')) ?>" class="sidebar-link <?= isActive('media') ?>"><i data-lucide="image"></i> مكتبة الوسائط</a>
            <a href="<?= e(url('admin/hero')) ?>" class="sidebar-link <?= isActive('hero') ?>"><i data-lucide="monitor-play"></i> سلايدر الرئيسية</a>
            <a href="<?= e(url('admin/pages')) ?>" class="sidebar-link <?= isActive('pages') ?>"><i data-lucide="file-text"></i> الصفحات الثابتة</a>
            <a href="<?= e(url('admin/partners')) ?>" class="sidebar-link <?= isActive('partners') ?>"><i data-lucide="briefcase"></i> شركاء النجاح</a>
            
            <div class="sidebar-heading">الإعدادات والنظام</div>
            <a href="<?= e(url('admin/banks')) ?>" class="sidebar-link <?= isActive('banks') ?>"><i data-lucide="landmark"></i> الحسابات البنكية</a>
            <a href="<?= e(url('admin/users')) ?>" class="sidebar-link <?= isActive('users') ?>"><i data-lucide="shield"></i> مدراء النظام</a>
            <a href="<?= e(url('admin/audit')) ?>" class="sidebar-link <?= isActive('audit') ?>"><i data-lucide="activity"></i> سجل التدقيق</a>
            <a href="<?= e(url('admin/settings')) ?>" class="sidebar-link <?= isActive('settings') ?>"><i data-lucide="settings"></i> إعدادات الموقع</a>
            
            <div style="margin-top:10px;"></div>
            <a href="<?= e(url('admin/logout')) ?>" class="sidebar-link sidebar-logout"><i data-lucide="log-out"></i> تسجيل الخروج</a>
            <div style="margin-bottom:20px;"></div>
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
        .admin-sidebar { width: 240px; }
        .sidebar-brand { font-size: 18px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-menu { padding: 10px; gap: 2px; overflow-y: auto; height: calc(100vh - 95px); }
        .sidebar-menu::-webkit-scrollbar { width: 5px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .sidebar-menu::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        .sidebar-link { padding: 8px 15px; border-radius: 8px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; }
        .sidebar-link i { width: 16px; height: 16px; }
        .sidebar-heading { font-size: 11px; margin: 15px 12px 6px; }
        .admin-main { margin-right: 240px; }
        .admin-topbar { height: 70px; padding: 0 25px; }
        .user-avatar { width: 35px; height: 35px; background: var(--gold); color: #000; border-radius: 8px; display: flex; justify-content: center; align-items: center; font-weight: 900; }
        .user-name { font-size: 13px; font-weight: 800; color: var(--sidebar-bg); }

        /* ================= تصميم التنبيهات (Ultra Premium) ================= */
        
        /* خلفية ضبابية راقية للنافذة */
        .chic-backdrop {
            background: rgba(6, 27, 82, 0.25) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }

        /* النافذة المنبثقة (Modal) */
        .chic-swal-popup {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(25px) !important;
            -webkit-backdrop-filter: blur(25px) !important;
            border-radius: 32px !important;
            padding: 3.5em 2em 3em !important;
            box-shadow: 0 40px 80px rgba(6, 27, 82, 0.15) !important;
            border: 1px solid rgba(255,255,255,0.8) !important;
            font-family: 'Cairo', sans-serif !important;
        }
        
        .chic-swal-title { color: #061B52 !important; font-weight: 900 !important; font-size: 26px !important; margin-bottom: 10px !important; }
        .chic-swal-content { color: #64748b !important; font-weight: 600 !important; font-size: 15px !important; line-height: 1.7 !important; }
        .chic-swal-icon { border-width: 0 !important; width: 80px !important; height: 80px !important; margin: 0 auto 25px !important; background: #fee2e2 !important; color: #ef4444 !important; border-radius: 50% !important; }
        .swal2-icon.swal2-warning { border-color: transparent !important; color: #ef4444 !important; }
        
        /* الأزرار الدائرية (Pill-shaped) */
        .chic-swal-actions { gap: 12px !important; margin-top: 35px !important; width: 100% !important; display: flex !important; justify-content: center !important; }
        
        .chic-btn-confirm {
            background: #ef4444 !important;
            color: #fff !important;
            border-radius: 100px !important;
            padding: 16px 35px !important;
            font-family: 'Cairo', sans-serif !important;
            font-weight: 900 !important;
            font-size: 15px !important;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2) !important;
            transition: all 0.3s ease !important;
            flex: 1 !important;
            max-width: 180px !important;
            border: none !important;
        }
        .chic-btn-confirm:hover { transform: translateY(-3px) !important; box-shadow: 0 12px 25px rgba(239, 68, 68, 0.35) !important; }
        
        .chic-btn-cancel {
            background: #f1f5f9 !important;
            color: #475569 !important;
            border-radius: 100px !important;
            padding: 16px 35px !important;
            font-family: 'Cairo', sans-serif !important;
            font-weight: 800 !important;
            font-size: 15px !important;
            transition: all 0.3s ease !important;
            flex: 1 !important;
            max-width: 180px !important;
            border: none !important;
        }
        .chic-btn-cancel:hover { background: #e2e8f0 !important; color: #061B52 !important; }

        /* التنبيهات العائمة (Toasts - Apple Style) */
        .chic-toast {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            border-radius: 20px !important;
            padding: 16px 25px 16px 20px !important;
            box-shadow: 0 15px 35px rgba(6, 27, 82, 0.08) !important;
            font-family: 'Cairo', sans-serif !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
        }
        .chic-toast-title { font-weight: 800 !important; font-size: 14.5px !important; color: #061B52 !important; padding-right: 10px !important; }
        .swal2-timer-progress-bar { background: var(--gold) !important; height: 3px !important; border-radius: 3px; }
        
        /* أنيميشن الدخول والخروج */
        .swal-enter { animation: swal-slide-in 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) forwards !important; }
        .swal-leave { animation: swal-slide-out 0.3s ease-in forwards !important; }
        @keyframes swal-slide-in { 0% { opacity: 0; transform: scale(0.9) translateY(20px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
        @keyframes swal-slide-out { 0% { opacity: 1; transform: scale(1); } 100% { opacity: 0; transform: scale(0.95); } }
    </style>
    
    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', function() {
            // إعدادات الـ Toast (تنبيهات النجاح والخطأ)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-start',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                iconColor: '#10b981', // لون أيقونة النجاح
                customClass: { 
                    popup: 'chic-toast',
                    title: 'chic-toast-title'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            <?php if ($m = \App\Core\Session::getFlash('success')): ?>
                Toast.fire({ icon: 'success', title: '<?= e($m) ?>' });
            <?php endif; ?>
            
            <?php if ($m = \App\Core\Session::getFlash('error')): ?>
                Toast.fire({ icon: 'error', title: '<?= e($m) ?>', iconColor: '#ef4444' });
            <?php endif; ?>

            // رسائل التأكيد للحذف (Modals)
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                let originalMsg = form.getAttribute('onsubmit');
                let confirmText = originalMsg.match(/'([^']+)'/);
                let message = confirmText ? confirmText[1] : "هل أنت متأكد من تنفيذ هذا الإجراء؟";
                
                form.removeAttribute('onsubmit');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'تأكيد الإجراء',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، تأكيد',
                        cancelButtonText: 'تراجع',
                        buttonsStyling: false,
                        showClass: { popup: 'swal-enter' },
                        hideClass: { popup: 'swal-leave' },
                        customClass: {
                            backdrop: 'chic-swal-backdrop',
                            popup: 'chic-swal-popup',
                            title: 'chic-swal-title',
                            htmlContainer: 'chic-swal-content',
                            actions: 'chic-swal-actions',
                            confirmButton: 'chic-btn-confirm',
                            cancelButton: 'chic-btn-cancel',
                            icon: 'chic-swal-icon'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
<!-- استدعاء محرر النصوص الغني (النسخة المجانية بالكامل من Cloudflare) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    
    <style>
        /* إخفاء شريط الإشعارات احتياطياً */
        .tox-notifications-container { display: none !important; }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.rich-editor')) {
                tinymce.init({
                    selector: '.rich-editor',
                    language: 'ar',
                    directionality: 'rtl',
                    height: 500,
                    plugins: 'lists link image media table code wordcount',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code',
                    content_style: 'body { font-family: "Cairo", sans-serif; font-size: 16px; line-height: 1.8; }',
                    branding: false,
                    promotion: false
                });
            }
        });
    </script>
</body>
</html>