<?php
// app/Views/frontend/login.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div class="auth-box">
    <img src="assets/images/logo.png" height="100px" \>
    <h2 class="auth-title">تسجيل الدخول</h2>
    <p class="auth-subtitle">مرحباً بعودتك! سجل دخولك لإكمال تبرعك.</p>
    
    <?php if ($m = Session::getFlash('error')): ?>
        <div class="alert-error"><?= e($m) ?></div>
    <?php endif; ?>
    <?php if ($m = Session::getFlash('success')): ?>
        <div class="alert-error" style="background:#d1fae5; color:#065f46; border-color:#a7f3d0;"><?= e($m) ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?= e(url('login')) ?>">
        <?= CSRF::field() ?>
        <div class="input-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" placeholder="example@email.com" required autofocus>
            <i data-lucide="mail" class="icon"></i>
        </div>
        <div class="input-group" style="margin-bottom: 30px;">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            <i data-lucide="lock" class="icon"></i>
        </div>
        <button type="submit" class="btn-auth">تسجيل الدخول</button>
    </form>
    
    <div class="auth-footer">
        ليس لديك حساب؟ <a href="<?= e(url('register')) ?>">أنشئ حساباً جديداً</a>
    </div>
</div>