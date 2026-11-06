<?php
// app/Views/frontend/assistance.php
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
        <h1>نحن هنا لدعمك</h1>
        <p>نستقبل طلبات المساعدة بسرية تامة واهتمام بالغ.</p>
    </div>
</div>


        <div class="premium-panel" style="max-width: 800px; margin: 0 auto;">
            
            <?php if ($m = Session::getFlash('success')): ?>
                <div class="page-alert success"><i data-lucide="check-circle"></i> <?= e($m) ?></div>
            <?php endif; ?>
            <?php if ($m = Session::getFlash('error')): ?>
                <div class="page-alert error"><i data-lucide="alert-circle"></i> <?= e($m) ?></div>
            <?php endif; ?>

            <div class="page-alert info">
                <i data-lucide="shield-check" style="width:24px; flex-shrink:0;"></i>
                <span><strong>سرية المعلومات:</strong> كافة البيانات المرفقة هنا تُعامل بسرية تامة ولا يُطلع عليها سوى لجنة البحث المختصة بالجمعية.</span>
            </div>

            <form method="post" action="<?= e(url('assistance')) ?>" enctype="multipart/form-data" class="form-grid mt-4">
                <?= CSRF::field() ?>
                
                <div>
                    <label>الاسم الكامل (رب الأسرة)</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div>
                    <label>رقم الهاتف للتواصل</label>
                    <input type="text" class="form-control" name="phone" required>
                </div>
                <div>
                    <label>المدينة / الحي</label>
                    <input type="text" class="form-control" name="location" required>
                </div>
                <div>
                    <label>نوع المساعدة المطلوبة</label>
                    <select class="form-select" name="category" required>
                        <option value="">اختر...</option>
                        <option>مساعدة مالية مقطوعة</option>
                        <option>مساعدة غذائية (سلال)</option>
                        <option>رعاية صحية وعلاج</option>
                        <option>سداد إيجار سكن</option>
                        <option>كفالة أيتام</option>
                        <option>أخرى</option>
                    </select>
                </div>
                
                <div class="form-full">
                    <label>شرح تفصيلي للحالة والحاجة</label>
                    <textarea class="form-control" name="description" rows="5" required minlength="20" placeholder="يرجى توضيح وضع الأسرة وسبب طلب المساعدة بالتفصيل..."></textarea>
                </div>
                
                <div class="form-full">
                    <label>المستندات الداعمة (إثبات حالة، تقارير طبية، فواتير - بحد أقصى 5 ملفات)</label>
                    <input type="file" class="form-control" name="documents[]" multiple accept="image/*,application/pdf" style="padding: 12px 20px; background: #fff;">
                    <div style="font-size: 12px; color: var(--text-muted); margin-top: 5px;">(الصيغ المدعومة: الصور و PDF)</div>
                </div>

                <div class="form-full mt-3">
                    <button type="submit" class="btn-n btn-n-primary btn-n-lg">إرسال الطلب للجنة البحث</button>
                </div>
            </form>
        </div>
    </div>
