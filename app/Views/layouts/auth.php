<?php
// app/Views/layouts/auth.php
use App\Core\Settings;
$siteName = Settings::get('site.name_ar', 'نور مخة العالمية');
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'تسجيل الدخول') ?> - <?= e($siteName) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', sans-serif; }
    body { display: flex; min-height: 100vh; background: #fff; overflow-x: hidden; }
    
    /* القسم الأيسر: الصورة */
    .auth-image {
        flex: 1;
        background: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1200') center/cover no-repeat;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        text-align: center;
        color: #fff;
    }
    .auth-image::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(6,27,82,0.9) 0%, rgba(9,42,158,0.6) 100%);
        z-index: 1;
    }
    .auth-image-content { position: relative; z-index: 2; max-width: 500px; }
    .auth-image-content h2 { font-size: 2.5rem; font-weight: 900; margin-bottom: 20px; line-height: 1.3; }
    .auth-image-content p { font-size: 1.1rem; opacity: 0.9; line-height: 1.8; }

    /* القسم الأيمن: الفورم */
    .auth-form-side {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
        background: #F8FAFC;
        position: relative;
    }
    .auth-box {
        width: 100%;
        max-width: 420px;
        background: #fff;
        padding: 50px 40px;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(6, 27, 82, 0.08);
        border: 1px solid #e2e8f0;
        text-align: center;
    }
    .auth-logo {
        width: 60px; height: 60px;
        background: linear-gradient(135deg, #092A9E, #061B52);
        color: #F4C978;
        border-radius: 16px;
        display: flex; justify-content: center; align-items: center;
        font-size: 28px; font-weight: 900; margin-bottom: 25px;
        box-shadow: 0 10px 20px rgba(6,27,82,0.2);
    }
    .auth-title { font-size: 26px; font-weight: 900; color: #061B52; margin-bottom: 8px; }
    .auth-subtitle { font-size: 14px; color: #64748b; font-weight: 600; margin-bottom: 30px; }
    
    .input-group { margin-bottom: 20px; position: relative; }
    .input-group label { display: block; font-size: 13px; font-weight: 800; color: #061B52; margin-bottom: 8px; }
    .input-group .icon { position: absolute; right: 15px; bottom: 15px; color: #94a3b8; width: 20px; }
    .form-control {
        width: 100%; padding: 14px 45px 14px 15px;
        border: 1.5px solid #cbd5e1; border-radius: 12px;
        font-size: 14px; font-family: inherit; font-weight: 600;
        transition: 0.3s; outline: none; background: #f8fafc;
    }
    .form-control:focus { border-color: #F4C978; background: #fff; box-shadow: 0 0 0 4px rgba(244,201,120,0.15); }
    
    .btn-auth {
        width: 100%; padding: 16px;
        background: linear-gradient(135deg, #F4C978, #d97706);
        color: #061B52; font-weight: 900; font-size: 16px;
        border: none; border-radius: 12px; cursor: pointer;
        transition: 0.3s; box-shadow: 0 10px 20px rgba(244,201,120,0.3);
    }
    .btn-auth:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(244,201,120,0.4); }
    
    .auth-footer { text-align: center; margin-top: 25px; font-size: 14px; font-weight: 700; color: #64748b; }
    .auth-footer a { color: #092A9E; text-decoration: none; font-weight: 800; }
    .auth-footer a:hover { text-decoration: underline; }
    
    .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 10px; font-size: 14px; font-weight: 700; margin-bottom: 20px; text-align: center; border: 1px solid #fecaca; }

    @media (max-width: 992px) {
        body { flex-direction: column; }
        .auth-image { display: none; }
        .auth-form-side { min-height: 100vh; padding: 20px; background: #fff; }
        .auth-box { box-shadow: none; border: none; padding: 20px; }
    }
</style>
</head>
<body>
    <!-- الجزء الأيسر (الصورة) -->
    <div class="auth-image">
        <div class="auth-image-content">
            <h2>معاً نصنع الأمل<br>ونضيء حياة الآخرين</h2>
            <p>انضم إلينا في رحلة العطاء، وساهم في تغيير حياة المحتاجين من خلال منصة آمنة وشفافة.</p>
        </div>
    </div>

    <!-- الجزء الأيمن (الفورم) -->
    <div class="auth-form-side">
        <?= $content ?>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>