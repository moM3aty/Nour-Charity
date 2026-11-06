<?php
// app/Views/frontend/volunteers.php
use App\Core\CSRF; use App\Core\Session;
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
    .opp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 60px; }
    .opp-card { background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 30px; transition: var(--transition); box-shadow: var(--shadow-sm); position: relative; overflow: hidden; }
    .opp-card:hover { transform: translateY(-5px); border-color: var(--primary-light); box-shadow: var(--shadow-md); }
    .opp-card::before { content: ''; position: absolute; top: 0; right: 0; width: 4px; height: 100%; background: var(--gold); }
    .opp-title { font-size: 20px; font-weight: 900; color: var(--primary); margin-bottom: 15px; }
    .opp-desc { color: var(--text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.7; }
    .opp-meta { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 800; color: var(--primary-light); background: #EAF2FF; padding: 8px 15px; border-radius: 12px; display: inline-flex; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-full { grid-column: 1 / -1; }
    @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>التطوّع معنا</h1>
        <p>وقتك وخبرتك يصنعان فرقاً حقيقياً في مجتمعك. كن جزءاً من صناعة الأمل.</p>
    </div>
</div>

<section class="section" style="padding-top:0; padding-bottom: 80px;">
    <div class="container-nour">
        
        <?php if ($opportunities): ?>
        <div class="section-title-wrap" style="text-align: right; margin-bottom: 30px;">
            <h2 class="h2" style="color: var(--primary);">الفرص التطوعية المتاحة</h2>
        </div>
        <div class="opp-grid">
            <?php foreach ($opportunities as $o): ?>
            <div class="opp-card">
                <h3 class="opp-title"><?= e($o['title']) ?></h3>
                <p class="opp-desc"><?= e($o['description']) ?></p>
                <?php if ($o['location']): ?>
                <div class="opp-meta">
                    <i data-lucide="map-pin" style="width: 16px;"></i> <?= e($o['location']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="premium-panel" style="max-width: 900px; margin: 0 auto; padding: 50px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <div style="width: 70px; height: 70px; background: #EAF2FF; color: var(--primary-light); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                    <i data-lucide="user-plus" style="width: 32px; height: 32px;"></i>
                </div>
                <h3 class="h3" style="color: var(--primary);">نموذج طلب التطوع</h3>
                <p style="color: var(--text-muted); font-weight: 600;">نسعد بانضمامك لفريقنا، يرجى تعبئة البيانات بدقة.</p>
            </div>
            
            <?php if ($m = Session::getFlash('success')): ?>
                <div style="background:#d1fae5; color:#065f46; padding:15px; border-radius:12px; font-weight:800; margin-bottom:20px; text-align:center;"><?= e($m) ?></div>
            <?php endif; ?>
            <?php if ($m = Session::getFlash('error')): ?>
                <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:12px; font-weight:800; margin-bottom:20px; text-align:center;"><?= e($m) ?></div>
            <?php endif; ?>

            <form method="post" action="<?= e(url('volunteers')) ?>" enctype="multipart/form-data" class="form-grid">
                <?= CSRF::field() ?>
                <!-- اختيار الفرصة التطوعية -->
                <div class="form-full">
                    <label style="display:block; font-weight:900; color:var(--primary); margin-bottom:8px;">الفرصة التطوعية المتقدم عليها</label>
                    <select class="form-select" name="opportunity_id" style="font-weight: 800; color: var(--primary-light);">
                        <option value="">تطوع عام (بدون تحديد فرصة)</option>
                        <?php foreach ($opportunities as $o): ?>
                            <option value="<?= (int)$o['id'] ?>"><?= e($o['title']) ?> - <?= e($o['location']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">الاسم الكامل</label>
                    <input type="text" class="form-control" name="name" required placeholder="محمد أحمد">
                </div>
                <div>
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">البريد الإلكتروني</label>
                    <input type="email" class="form-control" name="email" required placeholder="example@mail.com">
                </div>
                <div>
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">رقم الهاتف</label>
                    <input type="text" class="form-control" name="phone" required placeholder="05xxxxxxxx">
                </div>
                <div>
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">المدينة / المنطقة</label>
                    <input type="text" class="form-control" name="city" placeholder="الرياض">
                </div>
                
                <div class="form-full">
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">المهارات والخبرات (اختياري)</label>
                    <textarea class="form-control" name="skills" rows="3" placeholder="أخبرنا عن مهاراتك التي يمكن أن تفيد الجمعية..."></textarea>
                </div>
                <div class="form-full">
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">مجالات الاهتمام بالتطوع</label>
                    <textarea class="form-control" name="interests" rows="2" placeholder="أمثلة: التصميم، التنظيم الميداني، إدخال البيانات..."></textarea>
                </div>
                <div class="form-full">
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">الوقت المتاح للتطوع</label>
                    <input type="text" class="form-control" name="availability" placeholder="مثال: نهاية الأسبوع، 5 ساعات أسبوعياً">
                </div>
                
                <div class="form-full">
                    <label style="display:block; font-weight:800; color:var(--primary); margin-bottom:8px;">السيرة الذاتية (اختياري - PDF/DOC)</label>
                    <div style="border: 2px dashed var(--border); padding: 20px; border-radius: 16px; text-align: center; background: #f8fafc;">
                        <i data-lucide="file-text" style="color: var(--text-muted); margin-bottom: 10px;"></i>
                        <input type="file" class="form-control" name="cv" accept=".pdf,.doc,.docx" style="max-width: 300px; margin: 0 auto; background: #fff;">
                    </div>
                </div>

                <div class="form-full mt-4">
                    <button type="submit" class="btn-n btn-n-primary btn-n-lg">تقديم طلب التطوع <i data-lucide="send" style="margin-right: 8px;"></i></button>
                </div>
            </form>
        </div>
    </div>
</section>