<?php
// app/Views/frontend/contact.php
use App\Core\CSRF; use App\Core\Session; use App\Core\Settings;
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    /* تصميم الهيرو الموحد */
    .page-hero-unified {
        background: linear-gradient(135deg, #061B52 0%, #092A9E 100%);
        padding: 100px 0 80px;
        text-align: center; color: #fff;
        border-radius: 0 0 50px 50px;
        position: relative; overflow: hidden;
        margin-bottom: 60px;
    }
    .page-hero-unified::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .page-hero-unified p { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }

    /* بقية ستايلات الصفحة */
    .contact-layout { display: flex; flex-wrap: wrap; gap: 40px; max-width: 1100px; margin: 0 auto; }
    .contact-info-panel { flex: 1; min-width: 320px; background: var(--primary-grad); color: #fff; padding: 50px 40px; border-radius: 24px; position: relative; overflow: hidden; box-shadow: var(--shadow-md); }
    .contact-info-panel::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; }
    .contact-item { display: flex; align-items: flex-start; gap: 18px; margin-bottom: 35px; font-size: 16px; position: relative; z-index: 2; }
    .contact-item-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.1); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; border-radius: 14px; color: var(--gold); flex-shrink: 0; border: 1px solid rgba(255,255,255,0.2); }
    .contact-form-panel { flex: 1.5; min-width: 350px; padding: 40px; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-full { grid-column: 1 / -1; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } .contact-layout { flex-direction: column-reverse; } }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>نسعد بتواصلك معنا</h1>
        <p>فريقنا متواجد دائماً للرد على أسئلتك واستفساراتك بصدر رحب.</p>
    </div>
</div>

<section class="section" style="padding-top:0; padding-bottom: 100px;">
    <div class="container-nour">
        <div class="contact-layout premium-panel" style="padding: 20px;">
            
            <!-- معلومات التواصل -->
            <div class="contact-info-panel">
                <h3 style="font-size: 26px; font-weight: 900; margin-bottom: 40px; position: relative; z-index: 2;">معلومات الاتصال</h3>
                
                <?php if ($p = Settings::get('site.phone')): ?>
                <div class="contact-item">
                    <div class="contact-item-icon"><i data-lucide="phone"></i></div>
                    <div>
                        <div style="opacity: 0.8; font-size: 14px; margin-bottom: 6px;">رقم الهاتف</div>
                        <div style="font-weight: 900; font-size: 18px; letter-spacing: 1px;" dir="ltr"><?= e($p) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($w = Settings::get('site.whatsapp')): ?>
                <div class="contact-item">
                    <div class="contact-item-icon"><i data-lucide="message-circle"></i></div>
                    <div>
                        <div style="opacity: 0.8; font-size: 14px; margin-bottom: 6px;">واتساب</div>
                        <div style="font-weight: 900; font-size: 18px; letter-spacing: 1px;" dir="ltr"><?= e($w) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($em = Settings::get('site.email')): ?>
                <div class="contact-item">
                    <div class="contact-item-icon"><i data-lucide="mail"></i></div>
                    <div>
                        <div style="opacity: 0.8; font-size: 14px; margin-bottom: 6px;">البريد الإلكتروني</div>
                        <div style="font-weight: 800; font-size: 17px;"><?= e($em) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($ad = Settings::get('site.address')): ?>
                <div class="contact-item">
                    <div class="contact-item-icon"><i data-lucide="map-pin"></i></div>
                    <div>
                        <div style="opacity: 0.8; font-size: 14px; margin-bottom: 6px;">العنوان</div>
                        <div style="font-weight: 700; font-size: 16px; line-height: 1.6;"><?= e($ad) ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- نموذج المراسلة -->
            <div class="contact-form-panel">
                <h3 style="font-size: 26px; font-weight: 900; color: var(--primary); margin-bottom: 10px;">أرسل رسالة</h3>
                <p style="color: var(--text-muted); font-weight: 600; margin-bottom: 30px;">قم بتعبئة النموذج أدناه وسنقوم بالرد عليك في أقرب وقت.</p>
                
                <?php if ($m = Session::getFlash('success')): ?>
                    <div style="background:#d1fae5; color:#065f46; padding:15px; border-radius:12px; font-weight:800; margin-bottom:20px;"><i data-lucide="check-circle" style="vertical-align:middle;"></i> <?= e($m) ?></div>
                <?php endif; ?>
                <?php if ($m = Session::getFlash('error')): ?>
                    <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:12px; font-weight:800; margin-bottom:20px;"><i data-lucide="alert-circle" style="vertical-align:middle;"></i> <?= e($m) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('contact')) ?>" class="form-grid">
                    <?= CSRF::field() ?>
                    
                    <div>
                        <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">الاسم الكامل</label>
                        <input type="text" class="form-control" name="name" required placeholder="الاسم الكريم">
                    </div>
                    <div>
                        <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="email" required placeholder="example@mail.com">
                    </div>
                    <div>
                        <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">رقم الهاتف (اختياري)</label>
                        <input type="text" class="form-control" name="phone" placeholder="05xxxxxxxx">
                    </div>
                    <div>
                        <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">موضوع الرسالة</label>
                        <input type="text" class="form-control" name="subject" placeholder="استفسار عام">
                    </div>
                    <div class="form-full">
                        <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">الرسالة</label>
                        <textarea class="form-control" name="message" rows="5" required placeholder="اكتب تفاصيل رسالتك هنا..."></textarea>
                    </div>
                    <div class="form-full mt-3">
                        <button type="submit" class="btn-n btn-n-primary btn-n-lg">إرسال الرسالة <i data-lucide="send" style="width:20px; margin-right:8px;"></i></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>