<?php
// app/Views/frontend/project-detail.php
$pct = ((float)$project['target_amount'] > 0) ? min(100, round(((float)$project['raised_amount'] / (float)$project['target_amount']) * 100)) : 0;
$img =$project['cover_image'] ?: 'https://placehold.co/1200x600/F4F7FA/061B52?text=Nour';
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    .detail-layout { display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start; margin-bottom: 80px; }
    .detail-main { flex: 1; min-width: 60%; }
    .detail-image-panel { padding: 0; overflow: hidden; margin-bottom: 30px; border-radius: 24px; box-shadow: var(--shadow-sm); }
    .detail-cover-img { width: 100%; height: 500px; object-fit: cover; }
    
    .detail-badge { display: inline-block; background: #EAF2FF; color: var(--primary-light); padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 900; margin-bottom: 15px;}
    .detail-title { color: var(--primary); margin-bottom: 15px; font-size: 2.2rem; font-weight: 900; line-height: 1.4; }
    .detail-rich-text { line-height: 2; font-size: 16px; color: var(--text-main); }
    .detail-rich-text img { border-radius: 16px; margin: 15px 0; }
    
    .detail-sidebar { width: 380px; position: sticky; top: 100px; }
    .detail-sidebar-title { color: var(--primary); margin-bottom: 25px; font-weight: 900; text-align: center; font-size: 22px; }
    .detail-target-box { background: #f8fafc; padding: 25px; border-radius: 16px; margin-bottom: 25px; text-align: center; border: 1px solid var(--border); }
    .detail-target-label { font-size: 15px; color: var(--text-muted); margin-bottom: 5px; font-weight: 700; }
    .detail-target-amount { font-size: 32px; font-weight: 900; color: var(--primary); }
    .detail-stats-row { display: flex; justify-content: space-between; font-size: 15px; font-weight: 900; margin-bottom: 12px; }
    
    @media (max-width: 992px) {
        .detail-sidebar { width: 100%; position: static; }
        .detail-cover-img { height: 350px; }
    }
</style>

<section class="section" style="padding-top:40px;">
    <div class="container-nour">
        <a href="<?= e(url('projects')) ?>" style="display:inline-block; margin-bottom: 20px; color: var(--text-muted); font-weight: 800; transition: 0.3s;">
            <i data-lucide="arrow-right" style="width: 16px; vertical-align: middle;"></i> عودة للمشاريع
        </a>
        
        <div class="detail-layout">
            <div class="detail-main">
                <div class="detail-image-panel">
                    <img src="<?= e($img) ?>" alt="<?= e($project['title']) ?>" class="detail-cover-img">
                </div>
                <div class="premium-panel">
                    <span class="detail-badge"><?= e($project['category_name'] ?? 'مشروع خيري') ?></span>
                    <h1 class="detail-title"><?= e($project['title']) ?></h1>
                    <p class="lead-ar" style="margin-bottom: 30px;"><?= e($project['short_description']) ?></p>
                    
                    <hr style="border:0; border-top: 1px dashed var(--border); margin: 30px 0;">
                    
                    <div class="detail-rich-text">
                        <?= $project['description'] ?>
                    </div>
                </div>
            </div>

            <div class="detail-sidebar">
                <div class="premium-panel" style="padding: 30px;">
                    <h3 class="detail-sidebar-title">شارك في الأجر</h3>
                    <div class="detail-target-box">
                        <div class="detail-target-label">الهدف المالي للمشروع</div>
                        <div class="detail-target-amount"><?= money((float)$project['target_amount']) ?></div>
                    </div>
                    
                    <div style="margin-bottom: 35px;">
                        <div class="detail-stats-row">
                            <span style="color: var(--primary-light);">جمعنا: <?= money((float)$project['raised_amount']) ?></span>
                            <span style="color: var(--gold-hover);"><?= $pct ?>%</span>
                        </div>
                        <div class="progress-bar-n" style="height: 14px; border-radius: 7px; background: #e2e8f0; overflow: hidden;">
                            <div class="progress-fill" style="width: <?= $pct ?>%; border-radius: 7px; height: 100%; background: linear-gradient(90deg, #092A9E, #F4C978);"></div>
                        </div>
                    </div>
                    
                    <a href="<?= e(url('donate?project=' . (int)$project['id'])) ?>" class="btn-n btn-n-primary btn-n-lg" style="margin-bottom: 15px;">
                        <i data-lucide="credit-card" style="width:20px;"></i> تبرع للمشروع الآن
                    </a>
                    
                    <button class="btn-n btn-n-outline btn-n-lg" onclick="window.nourAddToCart({id:<?= $project['id'] ?>, title:'<?= e(addslashes($project['title'])) ?>', amount: 100})">
                        <i data-lucide="shopping-cart" style="width:20px;"></i> أضف للسلة (100 ر.س)
                    </button>
                    
                    <div style="margin-top: 25px; text-align: center; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <a href="<?= e(url('gift')) ?>" style="color: var(--primary-light); font-size: 15px; font-weight: 800; text-decoration: none; display: flex; justify-content: center; align-items: center; gap: 8px;">
                            <i data-lucide="gift" style="width: 18px;"></i> أهدِ هذا التبرع لمن تحب
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>