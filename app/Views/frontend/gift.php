<?php
// app/Views/frontend/gift.php
use App\Core\CSRF; use App\Core\Session;
?>
<link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>?v=<?= time() ?>">
<style>
    .page-hero-unified { background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); padding: 100px 0 80px; text-align: center; color: #fff; border-radius: 0 0 50px 50px; position: relative; overflow: hidden; margin-bottom: 60px; }
    .page-hero-unified::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .page-hero-unified p { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>أهدِ من تحب تبرعاً</h1>
        <p>أهدِ ثواب التبرع لشخص عزيز، وسنرسل له بطاقة إهداء مخصصة.</p>
    </div>
</div>

        <div class="premium-panel" style="max-width: 800px; margin: 0 auto;">
            
            <?php if ($m = Session::getFlash('error')): ?>
                <div class="page-alert error"><i data-lucide="alert-circle"></i> <?= e($m) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= e(url('donate')) ?>" class="form-grid mt-2">
                <?= CSRF::field() ?>
                <input type="hidden" name="is_gift" value="1">

                <div class="form-full">
                    <label>المشروع المراد التبرع له</label>
                    <select class="form-select" name="project_id" required style="font-size: 16px;">
                        <option value="0">صدقة عامة / مشروع خيري مفتوح</option>
                        <?php foreach ($projects as $p): ?>
                            <option value="<?= (int)$p['id'] ?>"><?= e($p['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label>مبلغ الإهداء (ر.س)</label>
                    <input type="number" class="form-control" name="amount" min="1" required placeholder="100" style="font-weight: 800; color: var(--primary-light);">
                </div>
                <div>
                    <label>تصميم البطاقة</label>
                    <select class="form-select" name="card_design">
                        <option value="elegant">أنيق وراقي (افتراضي)</option>
                        <option value="classic">كلاسيكي إسلامي</option>
                        <option value="warm">دافئ ومعبر</option>
                    </select>
                </div>

                <hr class="form-full" style="border:0; border-top: 1px dashed var(--border); margin: 15px 0;">
                
                <h4 class="form-full" style="color: var(--primary); font-weight: 800;">بيانات المُهدَى إليه</h4>
                <div>
                    <label>اسم المستلم (المُهدَى إليه)</label>
                    <input type="text" class="form-control" name="recipient_name" required placeholder="مثال: والدتي الغالية">
                </div>
                <div>
                    <label>بريد المستلم (لإرسال البطاقة إلكترونياً)</label>
                    <input type="email" class="form-control" name="recipient_email" placeholder="اختياري">
                </div>
                <div class="form-full">
                    <label>رسالة شخصية ستكتب على البطاقة</label>
                    <textarea class="form-control" name="gift_message" rows="3" placeholder="اكتب عبارة تلامس القلب..."></textarea>
                </div>

                <hr class="form-full" style="border:0; border-top: 1px dashed var(--border); margin: 15px 0;">

                <h4 class="form-full" style="color: var(--primary); font-weight: 800;">بياناتك الشخصية</h4>
                <div>
                    <label>اسمك (المرسل)</label>
                    <input type="text" class="form-control" name="name" required placeholder="اسمك الكريم">
                </div>
                <div>
                    <label>رقم هاتفك</label>
                    <input type="text" class="form-control" name="phone" required placeholder="05xxxxxxxx">
                </div>
                <div class="form-full">
                    <label>بريدك الإلكتروني (لاستلام إيصال الدفع)</label>
                    <input type="email" class="form-control" name="email" required placeholder="example@email.com">
                </div>
                
                <div class="form-full mt-3">
                    <button type="submit" class="btn-n btn-n-primary btn-n-lg">متابعة للدفع وإنشاء البطاقة <i data-lucide="arrow-left" style="margin-right: 8px;"></i></button>
                </div>
            </form>
        </div>
    </div>
