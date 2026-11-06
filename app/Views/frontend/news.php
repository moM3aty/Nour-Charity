<?php
// app/Views/frontend/news.php
?>
<style>
    /* تصميم الهيرو الموحد */
    .page-hero-unified { background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); padding: 100px 0 80px; text-align: center; color: #fff; border-radius: 0 0 50px 50px; position: relative; overflow: hidden; margin-bottom: 60px; }
    .page-hero-unified::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .page-hero-unified p { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }

    /* شبكة الأخبار */
    .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 80px; }
    .news-card { background: #fff; border-radius: 20px; overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow-sm); transition: 0.3s; display: block; text-decoration: none; }
    .news-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
    .news-img-wrap { height: 220px; overflow: hidden; position: relative; }
    .news-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .news-card:hover .news-img { transform: scale(1.05); }
    .news-badge { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; z-index: 2; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>المركز الإعلامي</h1>
        <p>آخر أخبار ومستجدات الجمعية وتغطيات المشاريع.</p>
    </div>
</div>

<section class="section" style="padding-top:0; min-height: 50vh;">
    <div class="container-nour">
        <?php if (!empty($data['items'])): ?>
            <div class="news-grid">
                <?php foreach ($data['items'] as $a): ?>
                    <a href="<?= e(url('news/' . $a['slug'])) ?>" class="news-card">
                        <div class="news-img-wrap">
                            <img src="<?= e($a['cover_image'] ?: 'https://placehold.co/600x400/061B52/ffffff?text=News') ?>" class="news-img" alt="<?= e($a['title']) ?>">
                            <span class="news-badge"><?= e($a['category_name'] ?? 'أخبار') ?></span>
                        </div>
                        <div style="padding: 25px;">
                            <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; margin-bottom: 10px;">
                                <i data-lucide="calendar" style="width:14px; vertical-align:middle;"></i> <?= dt($a['published_at'], 'Y-m-d') ?>
                            </div>
                            <h3 style="font-size: 18px; font-weight: 900; color: var(--primary); margin-bottom: 15px; line-height: 1.5;"><?= e($a['title']) ?></h3>
                            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 0;">
                                <?= e(mb_substr($a['excerpt'] ?? strip_tags($a['content']), 0, 100)) ?>...
                            </p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <?php if (($data['pages'] ?? 0) > 1): ?>
                <div style="display: flex; justify-content: center; gap: 10px;">
                    <?php for ($i=1; $i<=$data['pages']; $i++): ?>
                        <a href="?page=<?= $i ?>" style="width: 45px; height: 45px; display: flex; justify-content: center; align-items: center; border-radius: 12px; font-weight: 800; text-decoration: none; <?= $i===($data['page']??1) ? 'background: var(--primary); color: #fff; box-shadow: 0 5px 15px rgba(6,27,82,0.2);' : 'background: #fff; color: var(--primary); border: 1px solid var(--border);' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div style="text-align: center; padding: 80px 20px; background: #fff; border-radius: 20px; border: 1px dashed var(--border); max-width: 800px; margin: 0 auto;">
                <i data-lucide="newspaper" style="width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 20px; opacity: 0.5;"></i>
                <h3 style="font-size: 20px; font-weight: 900; color: var(--primary);">لا توجد أخبار منشورة بعد</h3>
                <p style="color: var(--text-muted); margin-top: 10px;">سيتم إضافة الأخبار وتغطيات المشاريع قريباً.</p>
            </div>
        <?php endif; ?>
    </div>
</section>