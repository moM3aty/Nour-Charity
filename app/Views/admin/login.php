<?php
// app/Views/admin/login.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div class="admin-auth-box">
    <h2 class="admin-auth-title">مرحباً بعودتك</h2>
    <p class="admin-auth-subtitle">قم بتسجيل الدخول للمتابعة</p>
    
    <?php if ($msg = Session::getFlash('error')): ?>
        <div class="alert-error"><?= e($msg) ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?= e(url('admin/login')) ?>">
        <?= CSRF::field() ?>
        <div class="input-group">
            <label>البريد الإلكتروني للإدارة</label>
            <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
            <i data-lucide="mail" class="icon"></i>
        </div>
        <div class="input-group" style="margin-bottom: 30px;">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            <i data-lucide="lock" class="icon"></i>
        </div>
        <button type="submit" class="btn-admin-auth">تسجيل الدخول</button>
    </form>
    
    <div style="text-align: center; margin-top: 25px; font-size: 12px; color: #64748b; font-weight: 700;">
        <i data-lucide="shield" style="width:14px; display:inline-block; vertical-align:middle; margin-left:5px;"></i>
        هذا النظام محمي ومراقب أمنياً
    </div>
</div>