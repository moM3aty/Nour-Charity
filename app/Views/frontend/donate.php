<?php
// app/Views/frontend/donate.php
use App\Core\CSRF; use App\Core\Session; 
?>
<link rel="stylesheet" href="<?= e(asset('css/donate.css')) ?>?v=<?= time() ?>">
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<section class="donate-section" style="padding: 60px 0 100px; background: #f8fafc; min-height: 100vh;">
    <div class="container-nour">
        <div class="section-title-wrap">
            <span class="section-subtitle">عطاء يلامس القلوب</span>
            <h1 class="section-title">تبرعك يصنع الأمل</h1>
            <p class="section-desc">خطوة بسيطة منك تعني الكثير لهم. اختر المبلغ وساهم الآن.</p>
        </div>

        <?php if ($m = Session::getFlash('error')): ?>
            <div class="premium-panel" style="max-width:800px; margin:0 auto 30px; background:#fee2e2; border-color:#fca5a5; padding: 20px;">
                <div style="display:flex; align-items:center; gap:10px; color:#991b1b; font-weight:800; font-size:16px;">
                    <i data-lucide="alert-circle"></i> <?= e($m) ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('donate')) ?>" class="donate-layout" style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; max-width: 1100px; margin: 0 auto;">
            <?= CSRF::field() ?>
            <input type="hidden" name="project_id" value="<?= (int)($project['id'] ?? 0) ?>">
            <input type="hidden" name="campaign_id" value="<?= (int)($campaign['id'] ?? 0) ?>">
            
            <!-- Main Form Card -->
            <div class="premium-panel" style="padding: 40px;">
                
                <!-- 1. Amount Selection -->
                <div style="margin-bottom: 40px;">
                    <h3 style="font-size: 20px; font-weight: 900; color: var(--primary); margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                        <span style="width: 32px; height: 32px; background: #EAF2FF; color: var(--primary-light); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 16px;">1</span> 
                        حدد قيمة التبرع
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 12px; margin-bottom: 25px;">
                        <?php foreach ($presets as $index => $amt): ?>
                            <button type="button" class="amount-btn <?= $index === 1 ? 'active' : '' ?>" onclick="setAmt(<?= $amt ?>, this)"
                                    style="background: #fff; border: 2px solid var(--border); border-radius: 16px; padding: 15px; font-weight: 900; font-size: 18px; color: var(--primary); cursor: pointer; transition: 0.3s; display: flex; flex-direction: column; align-items: center;">
                                <?= $amt ?> <span style="font-size: 13px; font-weight: 700; opacity: 0.7;">ر.س</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 800; color: var(--primary); margin-bottom: 10px;">أو أدخل مبلغاً آخر (ر.س)</label>
                        <div style="position: relative;">
                            <i data-lucide="banknote" style="position: absolute; right: 15px; top: 16px; color: var(--text-muted); width: 22px;"></i>
                            <input type="number" name="amount" id="customAmt" value="<?= $presets[1] ?? 100 ?>" required min="1" class="form-control" style="padding-right: 45px; font-size: 18px;">
                        </div>
                    </div>
                </div>

                <!-- 2. Donor Info -->
                <div style="margin-bottom: 40px;">
                    <h3 style="font-size: 20px; font-weight: 900; color: var(--primary); margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                        <span style="width: 32px; height: 32px; background: #EAF2FF; color: var(--primary-light); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 16px;">2</span> 
                        بيانات المتبرع
                    </h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" value="<?= e($donor['name'] ?? '') ?>" required placeholder="الاسم الكريم">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">رقم الهاتف</label>
                            <input type="text" name="phone" class="form-control" value="<?= e($donor['phone'] ?? '') ?>" required placeholder="05xxxxxxxx">
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 14px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">البريد الإلكتروني (لإرسال الإيصال)</label>
                        <input type="email" name="email" class="form-control" value="<?= e($donor['email'] ?? '') ?>" required placeholder="example@email.com">
                    </div>
                    
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid var(--border);">
                        <input type="checkbox" name="is_anonymous" value="1" style="width: 20px; height: 20px; accent-color: var(--primary);">
                        <span style="font-weight: 700; font-size: 14px; color: var(--primary);">أرغب بأن يكون تبرعي مجهولاً (فاعل خير)</span>
                    </label>
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div style="position: sticky; top: 100px;">
                <div class="premium-panel" style="background: var(--primary-grad); color: #fff; border: none; overflow: hidden; position: relative;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%;"></div>
                    
                    <h3 style="font-size: 22px; font-weight: 900; color: var(--gold); margin-bottom: 25px; position: relative; z-index: 2;">ملخص التبرع</h3>
                    
                    <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 700; margin-bottom: 15px; position: relative; z-index: 2;">
                        <span style="color: #cbd5e1;">التوجيه:</span>
                        <strong style="color: #fff;"><?= e($project['title'] ?? ($campaign['title'] ?? 'تبرع عام')) ?></strong>
                    </div>
                    
                    <hr style="border: 0; border-top: 1px dashed rgba(255,255,255,0.2); margin: 20px 0; position: relative; z-index: 2;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; position: relative; z-index: 2;">
                        <span style="font-size: 16px; font-weight: 700; color: #cbd5e1;">الإجمالي:</span>
                        <div style="font-size: 36px; font-weight: 900; color: var(--gold); line-height: 1;"><span id="summaryAmt"><?= $presets[1] ?? 100 ?></span> <small style="font-size: 16px;">ر.س</small></div>
                    </div>
                    
                    <button type="submit" class="btn-n" style="width: 100%; background: var(--gold); color: var(--primary); font-size: 16px; padding: 18px; border-radius: 16px; position: relative; z-index: 2; box-shadow: 0 10px 20px rgba(244,201,120,0.2);">
                        تأكيد التبرع والانتقال للدفع <i data-lucide="arrow-left" style="margin-right: 8px;"></i>
                    </button>
                    
                    <div style="text-align: center; margin-top: 20px; font-size: 13px; font-weight: 700; color: #cbd5e1; display: flex; justify-content: center; align-items: center; gap: 6px; position: relative; z-index: 2;">
                        <i data-lucide="shield-check" style="width: 16px; color: var(--gold);"></i> معاملاتك آمنة ومشفرة 100%
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    /* Styling for the JS interaction */
    .amount-btn.active { background: var(--primary) !important; border-color: var(--primary) !important; color: #fff !important; box-shadow: 0 8px 20px rgba(6,27,82,0.2); }
    .amount-btn.active span { color: var(--gold) !important; opacity: 1 !important;}
    .amount-btn:hover:not(.active) { border-color: var(--primary-light) !important; transform: translateY(-3px); }
</style>

<script>
    function setAmt(val, btn) {
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('customAmt').value = val;
        document.getElementById('summaryAmt').innerText = val;
    }
    document.getElementById('customAmt').addEventListener('input', function() {
        document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
        const val = this.value || 0;
        document.getElementById('summaryAmt').innerText = val;
    });
</script>