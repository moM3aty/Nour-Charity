<?php
// app/Views/frontend/login.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div class="auth-box">
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="<?= e(url('')) ?>" style="display: inline-block; transition: 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <img style="height:100px; object-fit:contain;" src="<?= e(\App\Core\Settings::get('site.logo', asset('images/logo.png'))) ?>" alt="Logo" onerror="this.src='https://placehold.co/150x50/061B52/FFF?text=Nour+Charity'">
        </a>
    </div>
    
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
        
        <div class="input-group" style="margin-bottom: 30px; position: relative;">
            <label>كلمة المرور</label>
            <!-- إضافة id="passwordField" للتحكم بالحقل -->
            <input type="password" name="password" id="passwordField" class="form-control" placeholder="••••••••" required style="padding-left: 45px;">
            <i data-lucide="lock" class="icon"></i>
            
            <!-- زر إظهار/إخفاء كلمة المرور -->
            <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; left: 15px; bottom: 12px; background: none; border: none; cursor: pointer; color: #64748b; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10;">
                <span id="eyeShow"><i data-lucide="eye" style="width: 20px;"></i></span>
                <span id="eyeHide" style="display: none;"><i data-lucide="eye-off" style="width: 20px;"></i></span>
            </button>
        </div>
        
        <button type="submit" class="btn-auth">تسجيل الدخول</button>
    </form>
    
    <div class="auth-footer">
        ليس لديك حساب؟ <a href="<?= e(url('register')) ?>">أنشئ حساباً جديداً</a>
    </div>
</div>

<script>
// سكربت التبديل بين إظهار وإخفاء كلمة المرور
function togglePasswordVisibility() {
    const passField = document.getElementById('passwordField');
    const eyeShow = document.getElementById('eyeShow');
    const eyeHide = document.getElementById('eyeHide');
    
    if (passField.type === 'password') {
        passField.type = 'text';
        eyeShow.style.display = 'none';
        eyeHide.style.display = 'inline-flex';
    } else {
        passField.type = 'password';
        eyeShow.style.display = 'inline-flex';
        eyeHide.style.display = 'none';
    }
}
</script>