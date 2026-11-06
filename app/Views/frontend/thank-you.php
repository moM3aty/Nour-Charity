<?php
// app/Views/frontend/thank-you.php
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<style>
    .bank-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 25px; margin-bottom: 15px; position: relative; overflow: hidden; text-align: right; }
    .bank-card::before { content: ''; position: absolute; top: 0; right: 0; width: 4px; height: 100%; background: var(--gold); }
    .bank-name { font-size: 18px; font-weight: 900; color: var(--primary); margin-bottom: 15px; display: flex; align-items: center; gap: 8px; }
    .bank-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #f1f5f9; }
    .bank-value { font-family: monospace; font-size: 15px; font-weight: 800; color: var(--primary-light); background: #f8fafc; padding: 4px 10px; border-radius: 6px; user-select: all; }
</style>

<section class="section" style="padding-top:60px; padding-bottom:100px;">
    <div class="container-nour">
        <div class="premium-panel" style="max-width:750px; margin:0 auto; text-align:center; padding-top: 50px;">
            <div style="width: 80px; height: 80px; background: #d1fae5; color: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="check" style="width: 40px; height: 40px;"></i>
            </div>
            
            <h1 class="h2" style="color:var(--primary); margin-bottom:15px;">جزاك الله خيراً وتقبل منك!</h1>
            <p class="lead-ar" style="margin-bottom: 30px;">تم تسجيل طلب التبرع بنجاح. لإتمام العملية، يرجى إيداع المبلغ في أحد حساباتنا البنكية ثم رفع الإيصال.</p>

            <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 16px; padding: 25px; text-align: right; margin-bottom: 30px;">
                <div style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--border);">
                    <span style="color: var(--text-muted); font-weight: 800;">الرقم المرجعي</span>
                    <code style="color:var(--primary-light); font-weight:900; font-size: 16px; background: #EAF2FF; padding: 4px 10px; border-radius: 6px;">#<?= e($donation['donation_number']) ?></code>
                </div>
                <div style="display:flex; justify-content:space-between; padding:12px 0; border-bottom:1px solid var(--border);">
                    <span style="color: var(--text-muted); font-weight: 800;">المبلغ الإجمالي</span>
                    <strong style="font-size: 18px; color: var(--gold-hover); font-weight: 900;"><?= money((float)$donation['amount']) ?></strong>
                </div>
                <div style="display:flex; justify-content:space-between; padding:12px 0;">
                    <span style="color: var(--text-muted); font-weight: 800;">التوجيه</span>
                    <span style="font-weight: 700; color: var(--primary);"><?= e($donation['project_title'] ?? 'تبرع عام / سلة تبرعات') ?></span>
                </div>
            </div>

            <div style="display:flex; gap:15px; margin-bottom: 40px; flex-wrap: wrap;">
                <a href="<?= e(url('donate/receipt/upload?ref=' . urlencode($donation['donation_number']))) ?>" class="btn-n btn-n-primary" style="flex:1;">رفع إيصال التحويل <i data-lucide="upload-cloud" style="margin-right: 8px;"></i></a>
                <a href="<?= e(url('donate/receipt?ref=' . urlencode($donation['donation_number']))) ?>" target="_blank" class="btn-n btn-n-outline" style="flex:1;">طباعة ملخص الطلب <i data-lucide="printer" style="margin-right: 8px;"></i></a>
            </div>

            <h3 class="h3" style="margin-bottom:20px; color: var(--primary);">حساباتنا البنكية المعتمدة</h3>
            <?php foreach ($banks as$b): ?>
                <div class="bank-card">
                    <div class="bank-name"><i data-lucide="landmark" style="color: var(--gold);"></i> <?= e($b['bank_name']) ?></div>
                    <?php if (!empty($b['account_holder'])): ?>
                        <div class="bank-detail-row"><span style="font-weight:800; color:var(--text-muted);">صاحب الحساب:</span><span style="font-weight:900;color:var(--primary);"><?= e($b['account_holder']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($b['account_number'])): ?>
                        <div class="bank-detail-row"><span style="font-weight:800; color:var(--text-muted);">رقم الحساب:</span><span class="bank-value" dir="ltr"><?= e($b['account_number']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($b['iban'])): ?>
                        <div class="bank-detail-row"><span style="font-weight:800; color:var(--text-muted);">رقم الآيبان:</span><span class="bank-value" dir="ltr"><?= e($b['iban']) ?></span></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- تصفير السلة فور الوصول لهذه الصفحة -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        localStorage.removeItem('nour_cart');
        const badge = document.querySelector('.cart-badge');
        if (badge) badge.textContent = '0';
    });
</script>