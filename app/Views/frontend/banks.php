<?php
// app/Views/frontend/banks.php
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>?v=<?= time() ?>">

<style>
    .bank-card-premium { background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 30px; transition: var(--transition); box-shadow: var(--shadow-sm); position: relative; overflow: hidden; }
    .bank-card-premium:hover { border-color: var(--primary-light); transform: translateY(-6px); box-shadow: var(--shadow-hover); }
    .bank-card-premium::before { content: ''; position: absolute; top: 0; right: 0; width: 6px; height: 100%; background: linear-gradient(180deg, var(--gold), #d97706); }
    .bank-name { font-size: 20px; font-weight: 900; color: var(--primary); margin-bottom: 25px; display: flex; align-items: center; gap: 10px; border-bottom: 1px dashed var(--border); padding-bottom: 15px; }
    .bank-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; }
    .bank-label { font-size: 14px; color: var(--text-muted); font-weight: 700; }
    .bank-value { font-family: monospace; font-size: 16px; font-weight: 800; color: var(--primary-light); background: #EAF2FF; padding: 6px 14px; border-radius: 8px; user-select: all; letter-spacing: 1px; }
</style>

<section class="section" style="padding-top:48px;">
    <div class="container-nour">
        <div class="section-title-wrap">
            <span class="section-subtitle">وسائل الدفع</span>
            <h1 class="section-title">طرق التبرع المتاحة</h1>
            <p class="section-desc">نستقبل تبرعاتكم عبر الحسابات البنكية الرسمية المعتمدة.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; margin-top: 50px;">
            <?php if (empty($banks)): ?>
                <div class="premium-panel" style="text-align:center; grid-column: 1/-1;">
                    <p class="text-muted">لم يتم إضافة حسابات بنكية بعد. يرجى التواصل مع الإدارة.</p>
                </div>
            <?php else: foreach ($banks as $b): ?>
                <div class="bank-card-premium">
                    <div class="bank-name">
                        <i data-lucide="landmark" style="color: var(--gold); width: 28px; height: 28px;"></i> 
                        <?= e($b['bank_name']) ?>
                    </div>
                    
                    <?php if (!empty($b['account_holder'])): ?>
                        <div class="bank-detail-row" style="border-bottom: 1px dashed #f1f5f9; padding-bottom: 15px; margin-bottom: 10px;">
                            <span class="bank-label">صاحب الحساب:</span>
                            <span style="font-weight:800; color:var(--primary);"><?= e($b['account_holder']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($b['iban'])): ?>
                        <div class="bank-detail-row">
                            <span class="bank-label">رقم الآيبان (IBAN):</span>
                            <span class="bank-value" dir="ltr"><?= e($b['iban']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($b['account_number'])): ?>
                        <div class="bank-detail-row">
                            <span class="bank-label">رقم الحساب:</span>
                            <span class="bank-value" dir="ltr"><?= e($b['account_number']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; endif; ?>
        </div>

        <div style="text-align: center; margin-top: 60px;">
            <a href="<?= e(url('donate')) ?>" class="btn-n btn-n-primary btn-n-lg" style="max-width: 300px;">
                انتقل لصفحة التبرع <i data-lucide="arrow-left" style="margin-right: 10px;"></i>
            </a>
        </div>
    </div>
</section>