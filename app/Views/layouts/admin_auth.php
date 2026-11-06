<?php
// app/Views/layouts/admin_auth.php
use App\Core\Settings;
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>لوحة التحكم - <?= e($title ?? 'دخول الإدارة') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', sans-serif; }
    body { display: flex; min-height: 100vh; background: #061B52; overflow-x: hidden; }
    
    /* القسم الأيسر: صورة إدارية */
    .admin-auth-image {
        flex: 1;
        background: url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=1200') center/cover no-repeat;
        position: relative;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        padding: 40px; text-align: center; color: #fff;
    }
    .admin-auth-image::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(6,27,82,0.95) 0%, rgba(6,27,82,0.7) 100%);
        z-index: 1;
    }
    .admin-auth-content { position: relative; z-index: 2; max-width: 450px; }
    .admin-auth-content h2 { font-size: 2.2rem; font-weight: 900; margin-bottom: 15px; }
    .admin-auth-content p { font-size: 1.1rem; opacity: 0.8; line-height: 1.8; }
    .admin-logo-large { width: 90px; height: 90px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 24px; display: flex; justify-content: center; align-items: center; margin: 0 auto 25px; border: 1px solid rgba(255,255,255,0.2); }
    .admin-logo-large i { width: 40px; height: 40px; color: #F4C978; }

    /* القسم الأيمن: فورم الأدمن */
    .admin-auth-form-side {
        flex: 1; display: flex; justify-content: center; align-items: center;
        padding: 40px; background: #061B52; position: relative;
    }
    /* خلفية متحركة للفورم */
    .admin-auth-form-side::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle at center, rgba(66,123,212,0.15) 0%, transparent 60%);
        animation: rotate 20s linear infinite; z-index: 1;
    }
    @keyframes rotate { 100% { transform: rotate(360deg); } }
    
    .admin-auth-box {
        width: 100%; max-width: 420px;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        padding: 50px 40px; border-radius: 24px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        position: relative; z-index: 2;
    }
    .admin-auth-title { font-size: 24px; font-weight: 900; color: #fff; margin-bottom: 8px; }
    .admin-auth-subtitle { font-size: 14px; color: #94a3b8; font-weight: 600; margin-bottom: 30px; }
    
    .input-group { margin-bottom: 20px; position: relative; text-align: right; }
    .input-group label { display: block; font-size: 13px; font-weight: 700; color: #cbd5e1; margin-bottom: 8px; }
    .input-group .icon { position: absolute; right: 15px; bottom: 15px; color: #64748b; width: 20px; }
    .form-control {
        width: 100%; padding: 14px 45px 14px 15px;
        border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;
        font-size: 14px; font-family: inherit; font-weight: 600;
        transition: 0.3s; outline: none; background: rgba(0,0,0,0.2); color: #fff;
    }
    .form-control:focus { border-color: #F4C978; background: rgba(0,0,0,0.4); box-shadow: 0 0 0 4px rgba(244,201,120,0.1); }
    
    .btn-admin-auth {
        width: 100%; padding: 16px;
        background: linear-gradient(135deg, #F4C978, #d97706);
        color: #061B52; font-weight: 900; font-size: 16px;
        border: none; border-radius: 12px; cursor: pointer;
        transition: 0.3s; box-shadow: 0 10px 20px rgba(244,201,120,0.2);
    }
    .btn-admin-auth:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(244,201,120,0.3); }
    
    .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.5); color: #fca5a5; padding: 12px; border-radius: 10px; font-size: 14px; margin-bottom: 20px; font-weight: 700; text-align: center; }

    @media (max-width: 992px) {
        body { flex-direction: column; }
        .admin-auth-image { display: none; }
        .admin-auth-form-side { min-height: 100vh; padding: 20px; }
    }
</style>
</head>
<body>
    <div class="admin-auth-image">
        <div class="admin-auth-content">
            <div class="admin-logo-large"><i data-lucide="shield-check"></i></div>
            <h2>لوحة تحكم الإدارة</h2>
            <p>منصة آمنة ومشفرة لإدارة تبرعات وحملات الجمعية. الدخول مقتصر على المصرح لهم فقط.</p>
        </div>
    </div>

    <div class="admin-auth-form-side">
        <?= $content ?>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>