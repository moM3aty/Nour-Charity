<?php
// app/Views/frontend/register.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div class="auth-box">
  <img src="assets/images/logo.png" height="100px" \>
    <h2 class="auth-title">إنشاء حساب جديد</h2>
    <p class="auth-subtitle">انضم إلينا وابدأ رحلة الخير والعطاء.</p>
    
    <?php if ($m = Session::getFlash('error')): ?>
        <div class="alert-error"><?= e($m) ?></div>
    <?php endif; ?>
    
    <form method="post" action="<?= e(url('register')) ?>">
        <?= CSRF::field() ?>
        <div class="input-group">
            <label>الاسم الكامل</label>
            <input type="text" name="name" class="form-control" placeholder="محمد أحمد" required>
            <i data-lucide="user" class="icon"></i>
        </div>
        <div class="input-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
            <i data-lucide="mail" class="icon"></i>
        </div>
        <div class="input-group" style="margin-bottom: 30px;">
            <label>كلمة المرور (6 أحرف على الأقل)</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
            <i data-lucide="lock" class="icon"></i>
        </div>
        <button type="submit" class="btn-auth">إنشاء الحساب</button>
    </form>
    
    <div class="auth-footer">
        لديك حساب مسبقاً؟ <a href="<?= e(url('login')) ?>">قم بتسجيل الدخول</a>
    </div>
</div>