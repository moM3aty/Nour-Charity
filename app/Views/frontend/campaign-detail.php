<?php
// app/Views/frontend/campaign-detail.php
$pct = ((float)$campaign['goal_amount'] > 0) ? min(100, round(((float)$campaign['raised_amount'] / (float)$campaign['goal_amount']) * 100)) : 0;
$img = $campaign['image'] ?: 'https://placehold.co/1200x600/F4F7FA/061B52?text=Campaign';
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<style>
    .cd-layout { display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-start; margin-top: 20px; margin-bottom: 80px;}
    .cd-main { flex: 1; min-width: 60%; }
    .cd-img { width: 100%; height: 450px; object-fit: cover; border-radius: 24px; margin-bottom: 30px; box-shadow: var(--shadow-sm); }
    .cd-sidebar { width: 380px; position: sticky; top: 100px; }
    .cd-content-box { background: #fff; padding: 40px; border-radius: 24px; box-shadow: var(--shadow-md); border: 1px solid var(--border); }
    @media(max-width:992px){ .cd-sidebar {width: 100%; position:static;} }
</style>

<section class="section" style="padding-top:40px;">
    <div class="container-nour">
        <a href="<?= e(url('campaigns')) ?>" style="display:inline-block; margin-bottom: 20px; color: var(--text-muted); font-weight: 700;">← عودة للحملات</a>
        
        <div class="cd-layout">
            <div class="cd-main">
                <img src="<?= e($img) ?>" alt="<?= e($campaign['title']) ?>" class="cd-img">
                <div class="cd-content-box">
                    <span style="display: inline-block; background: #d1fae5; color: #059669; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 800; margin-bottom: 15px;">حملة نشطة</span>
                    <h1 class="h1" style="color: var(--primary); margin-bottom: 20px;"><?= e($campaign['title']) ?></h1>
                    <div style="line-height: 2; font-size: 16px; color: var(--text-main);">
                        <?= $campaign['description'] ?>
                    </div>
                </div>
            </div>

            <div class="cd-sidebar">
                <div class="cd-content-box" style="padding: 30px;">
                    <h3 style="color: var(--primary); margin-bottom: 25px; font-weight: 900; text-align: center;">مؤشر أداء الحملة</h3>
                    <div style="background: #f8fafc; padding: 25px 20px; border-radius: 16px; margin-bottom: 25px; text-align: center; border: 1px solid var(--border);">
                        <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 5px;">المبلغ المستهدف</div>
                        <div style="font-size: 32px; font-weight: 900; color: var(--primary);"><?= money((float)$campaign['goal_amount']) ?></div>
                    </div>
                    <div style="margin-bottom: 35px;">
                        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; margin-bottom: 12px;">
                            <span style="color: var(--primary-light);">تم جمع: <?= money((float)$campaign['raised_amount']) ?></span>
                            <span style="color: var(--gold);"><?= $pct ?>%</span>
                        </div>
                        <div class="progress-bar-n" style="height: 14px; border-radius: 7px; background: #e2e8f0;">
                            <div class="progress-fill" style="width: <?= $pct ?>%; border-radius: 7px;"></div>
                        </div>
                    </div>
                    <a href="<?= e(url('donate?campaign=' . (int)$campaign['id'])) ?>" class="btn-n btn-n-primary btn-n-lg">
                        <i data-lucide="heart" style="width:20px;"></i> تبرع للحملة الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>