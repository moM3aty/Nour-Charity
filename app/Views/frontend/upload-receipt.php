<?php
// app/Views/frontend/upload-receipt.php
use App\Core\CSRF; use App\Core\Session;
?>
<link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>?v=<?= time() ?>">

<section class="section" style="padding-top:40px;">
    <div class="container-nour">
        
        <div class="premium-panel" style="max-width:650px; margin:0 auto;">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 70px; height: 70px; background: #EAF2FF; color: var(--primary-light); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i data-lucide="upload-cloud" style="width: 32px; height: 32px;"></i>
                </div>
                <h1 class="h2" style="color: var(--primary);">إرفاق إيصال التحويل</h1>
                <p class="lead-ar" style="font-size: 15px;">تبرع رقم: <code style="font-weight: 900; color: var(--primary-light);"><?= e($donation['donation_number']) ?></code> بقيمة <?= money((float)$donation['amount']) ?></p>
            </div>
            
            <?php if ($m = Session::getFlash('error')): ?>
                <div class="page-alert error"><i data-lucide="alert-circle"></i> <?= e($m) ?></div>
            <?php endif; ?>
            <?php if ($m = Session::getFlash('success')): ?>
                <div class="page-alert success"><i data-lucide="check-circle"></i> <?= e($m) ?></div>
            <?php endif; ?>

            <?php if ($existing): ?>
                <div class="page-alert info">
                    <i data-lucide="info"></i>
                    <span>تم إرفاق إيصال مسبقاً بتاريخ <?= dt($existing['uploaded_at'], 'Y-m-d H:i') ?>. يمكنك رفع ملف جديد لتحديثه.</span>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= e(url('donate/receipt/upload')) ?>" enctype="multipart/form-data">
                <?= CSRF::field() ?>
                <input type="hidden" name="ref" value="<?= e($donation['donation_number']) ?>">
                
                <div style="margin-bottom:25px;">
                    <label style="display: block; font-weight: 800; margin-bottom: 10px; color: var(--primary);">صورة الإيصال أو ملف التحويل (PDF/JPG/PNG)</label>
                    <div style="border: 2px dashed var(--border); padding: 30px 20px; border-radius: 16px; text-align: center; background: #f8fafc; transition: 0.3s;" id="uploadBox">
                        <i data-lucide="image" style="width: 36px; height: 36px; color: var(--text-muted); margin-bottom: 10px;"></i>
                        <input type="file" name="receipt" id="receiptFile" class="form-control" accept="image/*,application/pdf" required style="max-width: 300px; margin: 0 auto; background: #fff; border: 1px solid var(--border);">
                        <p style="font-size: 13px; color: var(--text-muted); margin-top: 15px;">الحد الأقصى للملف: 8 ميجابايت.</p>
                    </div>
                </div>
                
                <button type="submit" class="btn-n btn-n-primary btn-n-lg">إرسال وتأكيد الدفع</button>
            </form>
        </div>
    </div>
</section>