<?php
// app/Views/frontend/campaigns.php
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<style>
    /* تصميم الهيرو الموحد */
    .camp-hero {
        background: linear-gradient(135deg, #061B52 0%, #092A9E 100%);
        padding: 100px 0 80px;
        text-align: center; color: #fff;
        border-radius: 0 0 50px 50px;
        position: relative; overflow: hidden;
        margin-bottom: 60px;
    }
    .camp-hero::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .camp-hero::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .camp-hero h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .camp-hero p { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    
    .camp-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 80px;}
    .camp-card { background: #fff; border-radius: 24px; overflow: hidden; box-shadow: var(--shadow-sm); transition: var(--transition); border: 1px solid var(--border); display: flex; flex-direction: column; }
    .camp-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
    .camp-img { width: 100%; height: 240px; object-fit: cover; }
    .camp-body { padding: 30px; flex-grow: 1; display: flex; flex-direction: column; }
    .camp-badge { display: inline-flex; align-items: center; gap: 6px; background: #d1fae5; color: #059669; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 800; margin-bottom: 15px; align-self: flex-start; }
    .camp-title { font-size: 22px; font-weight: 900; color: var(--primary); margin-bottom: 15px; line-height: 1.4; }
    .camp-desc { color: var(--text-muted); font-size: 15px; line-height: 1.7; margin-bottom: 25px; flex-grow: 1; }
    .camp-stats { display: flex; justify-content: space-between; font-size: 14px; font-weight: 900; margin-bottom: 10px; }
</style>

<div class="camp-hero">
    <div class="container-nour">
        <h1>الحملات الخيرية والإغاثية</h1>
        <p>مبادرات وحملات عاجلة تنتظر مساهمتك لصناعة الفارق ومد يد العون.</p>
    </div>
</div>

<section class="section" style="padding-top:0;">
    <div class="container-nour">
        <?php if ($campaigns): ?>
        <div class="camp-grid">
            <?php foreach ($campaigns as $c):
                $pct = ((float)$c['goal_amount'] > 0) ? min(100, round(((float)$c['raised_amount'] / (float)$c['goal_amount']) * 100)) : 0;
                $img = $c['image'] ?: 'https://placehold.co/600x400/F4F7FA/061B52?text=Campaign';
            ?>
            <a href="<?= e(url('campaigns/' . $c['slug'])) ?>" style="text-decoration:none; color:inherit; display:contents;">
                <div class="camp-card">
                    <img src="<?= e($img) ?>" class="camp-img" alt="<?= e($c['title']) ?>">
                    <div class="camp-body">
                        <span class="camp-badge"><i data-lucide="activity" style="width:16px;"></i> حملة نشطة</span>
                        <h3 class="camp-title"><?= e($c['title']) ?></h3>
                        <div class="camp-desc"><?= e(mb_substr(strip_tags($c['description'] ?? ''), 0, 120)) ?>...</div>
                        
                        <div>
                            <div class="camp-stats">
                                <span style="color: var(--primary-light);">تم جمع <?= money((float)$c['raised_amount']) ?></span>
                                <span style="color: var(--gold-hover);"><?= $pct ?>%</span>
                            </div>
                            <div class="progress-bar-n" style="height: 8px; background:#f1f5f9; border-radius: 4px; overflow: hidden;">
                                <div class="progress-fill" style="width: <?= $pct ?>%; height: 100%; background: linear-gradient(90deg, #092A9E, #F4C978);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="premium-panel" style="text-align: center; padding: 80px 20px;">
            <i data-lucide="megaphone-off" style="width:80px; height:80px; color:var(--border); margin-bottom:20px;"></i>
            <h3 style="color: var(--primary); font-size: 24px; font-weight: 900;">لا توجد حملات نشطة حالياً.</h3>
            <p style="color: var(--text-muted);">يمكنك تصفح المشاريع الخيرية الدائمة المتاحة للتبرع.</p>
            <a href="<?= e(url('projects')) ?>" class="btn-n btn-n-primary mt-3">تصفح المشاريع</a>
        </div>
        <?php endif; ?>
    </div>
</section>