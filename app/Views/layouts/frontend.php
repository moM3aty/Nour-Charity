<?php
// app/Views/layouts/frontend.php
use App\Core\Settings;
use App\Core\Auth;
use App\Core\Session;

$siteName = Settings::get('site.name_ar', 'نور الخيرية');
$title = ($title ?? '') ? ($title . ' ' . $siteName) : Settings::get('seo.default_title', $siteName);
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

function navActive(string $p) { 
    global $currentPath; 
    return (rtrim($currentPath,'/') === rtrim($p,'/') || (strlen($p) > 1 && str_starts_with($currentPath, $p))) ? 'active' : ''; 
}
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>?v=<?= time() ?>">
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .site-header {
        background: #ffffff !important;
        box-shadow: 0 10px 30px rgba(6, 27, 82, 0.05) !important;
        border-bottom: 2px solid var(--gold);
        position: sticky; top: 0; z-index: 1000;
    }
    .main-nav {
        display: flex; gap: 6px; align-items: center; justify-content: center; flex-wrap: wrap; padding: 10px 0;
    }
    .main-nav a {
        display: flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 800; color: var(--primary); padding: 10px 14px; border-radius: 12px; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); text-decoration: none; border: 1px solid transparent;
    }
    .main-nav a i { width: 16px; height: 16px; color: var(--gold-hover); transition: 0.3s; }
    .main-nav a:hover { background: #f8fafc; border-color: #e2e8f0; color: var(--primary-light); transform: translateY(-2px); }
    .main-nav a:hover i { color: var(--primary-light); }
    .main-nav a.active { background: rgba(9, 42, 158, 0.05); color: var(--primary-light); border-color: rgba(9, 42, 158, 0.1); }
    .main-nav a.active i { color: var(--primary-light); }
    
    .nav-volunteer-link { background: #fef3c7 !important; color: #d97706 !important; border-color: #fde68a !important; }
    .nav-volunteer-link i { color: #d97706 !important; }
    .nav-volunteer-link:hover { background: #fde68a !important; color: #92400e !important; }
    
    .nav-assistance-link { background: #e0e7ff !important; color: #3730a3 !important; border-color: #c7d2fe !important; }
    .nav-assistance-link i { color: #3730a3 !important; }
    .nav-assistance-link:hover { background: #c7d2fe !important; color: #312e81 !important; }
</style>
</head>
<body>

<header class="site-header">
  <div class="container">
    <div class="header-top">
      <a href="<?= e(url('/')) ?>" class="header-brand">
        <img src="<?= e(asset('images/logo.png')) ?>" alt="Logo" onerror="this.src='https://placehold.co/150x50/061B52/FFF?text=Nour+Charity'">
      </a>
      
      <div class="header-actions">
        <div class="header-contact">
          <i data-lucide="phone-call" style="width:18px;"></i>
          <span dir="ltr"><?= e(Settings::get('site.phone', '0500000000')) ?></span>
        </div>
        
        <div class="header-divider"></div>
        
        <a href="<?= e(url('cart')) ?>" class="header-icon-btn cart-btn">
          <i data-lucide="shopping-cart" style="width:22px;"></i>
          <span class="cart-badge">0</span>
        </a>
        
        <?php if (Auth::check()): ?>
          <a href="<?= e(url('profile')) ?>" class="header-icon-btn"><i data-lucide="user" style="width:20px;"></i> حسابي</a>
          <a href="<?= e(url('donate')) ?>" class="btn-donate-fast"><i data-lucide="heart" style="width:18px;"></i> تبرع الآن</a>
        <?php else: ?>
          <a href="<?= e(url('login')) ?>" class="header-icon-btn"><i data-lucide="user" style="width:20px;"></i> دخول</a>
          <a href="<?= e(url('login')) ?>" class="btn-donate-fast" style="background:#64748b; box-shadow:none;" onclick="if(typeof Swal !== 'undefined'){ Swal.fire({icon:'info', title:'تسجيل الدخول', text:'يجب تسجيل الدخول لتتمكن من التبرع.', confirmButtonColor:'#061B52'}); return false; } else { alert('يجب تسجيل الدخول أولاً.'); return false; }"><i data-lucide="heart" style="width:18px;"></i> سجل لتتبرع</a>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="header-nav-wrap">
      <nav class="main-nav">
        <a href="<?= e(url('/')) ?>" class="<?= navActive('/') ?>">
            <i data-lucide="home"></i> الرئيسية
        </a>
        <a href="<?= e(url('projects')) ?>" class="<?= navActive('/projects') ?>">
            <i data-lucide="layout-grid"></i> المشاريع
        </a>
        <a href="<?= e(url('campaigns')) ?>" class="<?= navActive('/campaigns') ?>">
            <i data-lucide="megaphone"></i> الإهداءات
        </a>
        <a href="<?= e(url('gallery')) ?>" class="<?= navActive('/gallery') ?>">
            <i data-lucide="image"></i> معرض الصور
        </a>
        <a href="<?= e(url('news')) ?>" class="<?= navActive('/news') ?>">
            <i data-lucide="newspaper"></i> المركز الإعلامي
        </a>
        <a href="<?= e(url('about')) ?>" class="<?= navActive('/about') ?>">
            <i data-lucide="info"></i> عن الجمعية
        </a>
        <a href="<?= e(url('contact')) ?>" class="<?= navActive('/contact') ?>">
            <i data-lucide="phone"></i> تواصل معنا
        </a>
        <a href="<?= e(url('volunteers')) ?>" class="nav-volunteer-link <?= navActive('/volunteers') ?>">
            <i data-lucide="users"></i> التطوع
        </a>
        <a href="<?= e(url('assistance')) ?>" class="nav-assistance-link <?= navActive('/assistance') ?>">
            <i data-lucide="hand-helping"></i> طلب مساعدة
        </a>
      </nav>
    </div>
  </div>
</header>

<main style="padding:0 0 20px 0; min-height: 70vh;">
  <?php if ($m = Session::getFlash('error')): ?>
    <div class="container" style="margin: 20px auto;">
      <div style="background:#fee2e2; color:#991b1b; padding:15px 20px; border-radius:12px; font-weight:800; display:flex; align-items:center; gap:10px; border: 1px solid #fecaca;">
        <i data-lucide="alert-circle"></i> <?= e($m) ?>
      </div>
    </div>
  <?php endif; ?>
  
  <?= $content ?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      
      <div class="footer-col">
        <h4>روابط سريعة</h4>
        <ul>
          <li><a href="<?= e(url('projects')) ?>">المشاريع الخيرية</a></li>
          <li><a href="<?= e(url('campaigns')) ?>">الحملات والإهداءات</a></li>
          <li><a href="<?= e(url('gallery')) ?>">معرض الصور</a></li>
          <li><a href="<?= e(url('banks')) ?>">وسائل التبرع البنكية</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>المركز والدعم</h4>
        <ul>
          <li><a href="<?= e(url('contact')) ?>">اتصل بنا</a></li>
          <li><a href="<?= e(url('about')) ?>">عن الجمعية</a></li>
          <li><a href="<?= e(url('privacy')) ?>">سياسة الخصوصية</a></li>
          <li><a href="<?= e(url('terms')) ?>">الشروط والأحكام</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>حساب المتبرع</h4>
        <ul>
          <li><a href="<?= e(url('profile')) ?>">سجل التبرعات</a></li>
          <li><a href="<?= e(url('login')) ?>">تسجيل الدخول</a></li>
          <li><a href="<?= e(url('register')) ?>">إنشاء حساب جديد</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>معلومات التواصل</h4>
        <ul>
          <li style="color:var(--gold); font-weight:800; display:flex; gap:10px; align-items:center; font-size:15px; margin-bottom: 12px;">
            <i data-lucide="mail" style="width:20px;"></i> <?= e(Settings::get('site.email', 'info@nourcharity.com')) ?>
          </li>
          <li style="color:var(--gold); font-weight:800; display:flex; gap:10px; align-items:center; font-size:15px; margin-bottom: 12px;" dir="ltr">
            <i data-lucide="phone" style="width:20px;"></i> <?= e(Settings::get('site.phone', '0500000000')) ?>
          </li>
          <li style="color:#cbd5e1; font-size: 14px; display:flex; gap:10px; align-items:flex-start; line-height: 1.6;">
            <i data-lucide="map-pin" style="width:20px; flex-shrink:0; color: var(--gold);"></i> <?= e(Settings::get('site.address', 'المملكة العربية السعودية')) ?>
          </li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <div class="f-payments">
        <span style="color:#fff; font-weight:800; font-size:14px;">Visa</span>
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" style="height:24px;">
        <span style="color:#fff; font-weight:800; font-size:14px;">Apple Pay</span>
      </div>
      
      <div class="f-socials">
        <?php if($ig = Settings::get('site.instagram')): ?>
            <a href="<?= e($ig) ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>
        <?php endif; ?>
        <?php if($tw = Settings::get('site.twitter')): ?>
            <a href="<?= e($tw) ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg></a>
        <?php endif; ?>
        <?php if($fb = Settings::get('site.facebook')): ?>
            <a href="<?= e($fb) ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a>
        <?php endif; ?>
      </div>
      
      <div class="f-copyright">
        © <?= date('Y') ?> منصة <?= e($siteName) ?> - جميع الحقوق محفوظة
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>window.APP_BASE = '<?= rtrim(url('/'), '/') ?>';</script>
<script src="<?= e(asset('js/app.js')) ?>?v=<?= time() ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    <?php if ($m = \App\Core\Session::getFlash('success')): ?>
        if(typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'success', title: 'تم بنجاح', text: '<?= e($m) ?>', confirmButtonColor: '#061B52', confirmButtonText: 'حسناً' });
        }
    <?php endif; ?>
});
</script>
</body>
</html>