<?php
// app/Views/frontend/news-detail.php
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    /* الهيرو الموحد مخصص لتفاصيل المقال */
    .article-hero { background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); padding: 100px 0 140px; text-align: center; color: #fff; border-radius: 0 0 50px 50px; position: relative; overflow: hidden; }
    .article-hero::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .article-hero::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    
    .article-meta-badge { position: relative; z-index: 2; display: inline-block; background: rgba(244, 201, 120, 0.15); color: var(--gold); padding: 6px 18px; border-radius: 20px; font-size: 13px; font-weight: 800; margin-bottom: 20px; border: 1px solid rgba(244, 201, 120, 0.3); backdrop-filter: blur(5px); }
    .article-title { position: relative; z-index: 2; font-size: 2.5rem; font-weight: 900; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.2); max-width: 900px; margin: 0 auto 20px; }
    .article-date { position: relative; z-index: 2; font-size: 14px; opacity: 0.8; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }

    /* حاوية الـ Rich Text لضمان ظهور التنسيقات (نقاط، مسافات، عناوين) */
    .rich-text-container { font-size: 16px; line-height: 2.2; color: var(--text-main); }
    .rich-text-container p { margin-bottom: 1.5rem; }
    .rich-text-container strong, .rich-text-container b { font-weight: 900; color: var(--primary); }
    .rich-text-container h1, .rich-text-container h2, .rich-text-container h3, .rich-text-container h4 { color: var(--primary); font-weight: 900; margin-top: 2rem; margin-bottom: 1rem; line-height: 1.4; }
    .rich-text-container ul, .rich-text-container ol { margin-bottom: 1.5rem; padding-right: 2.5rem; }
    .rich-text-container ul { list-style: disc outside !important; }
    .rich-text-container ol { list-style: decimal outside !important; }
    .rich-text-container li { margin-bottom: 0.5rem; display: list-item !important; }
    .rich-text-container a { color: var(--primary-light); text-decoration: underline; font-weight: 700; }
    .rich-text-container a:hover { color: var(--gold-hover); }
    .rich-text-container img { max-width: 100%; height: auto; border-radius: 16px; margin: 1.5rem 0; box-shadow: var(--shadow-sm); display: block; }
    .rich-text-container blockquote { border-right: 4px solid var(--gold); background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin: 2rem 0; font-weight: 700; color: var(--primary); font-size: 1.1rem; }
    .rich-text-container table { width: 100%; border-collapse: collapse; margin: 2rem 0; background: #fff; }
    .rich-text-container th, .rich-text-container td { border: 1px solid var(--border); padding: 15px; text-align: right; }
    .rich-text-container th { background: #f8fafc; font-weight: 900; color: var(--primary); }
</style>

<div class="article-hero">
    <div class="container-nour">
        <span class="article-meta-badge"><?= e($article['category_name'] ?? 'أخبار الجمعية') ?></span>
        <h1 class="article-title"><?= e($article['title']) ?></h1>
        <div class="article-date">
            <i data-lucide="calendar" style="width: 16px;"></i> 
            <?= dt($article['published_at'] ?: $article['created_at'], 'Y-m-d') ?>
        </div>
    </div>
</div>

<section style="padding-bottom: 100px; position: relative; z-index: 10; margin-top: -60px;">
    <div class="container-nour">
        
        <!-- زر العودة -->
        <div style="max-width: 950px; margin: 0 auto 20px; text-align: right;">
            <a href="<?= e(url('news')) ?>" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px); border: 1px solid var(--border); color: var(--primary); padding: 10px 20px; border-radius: 12px; font-weight: 800; font-size: 14px; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: 0.3s;" onmouseover="this.style.background='#fff'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.transform='none';">
                <i data-lucide="arrow-right" style="width: 16px;"></i> العودة للمركز الإعلامي
            </a>
        </div>

        <!-- بطاقة الخبر -->
        <article style="max-width: 950px; margin: 0 auto; background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(6, 27, 82, 0.08); border: 1px solid #e2e8f0;">
            
            <?php if ($article['cover_image']): ?>
            <!-- تم تعديل طريقة عرض الصورة لتظهر كاملة -->
            <div style="width: 100%; background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: center;">
                <img src="<?= e($article['cover_image']) ?>" alt="<?= e($article['title']) ?>" style="max-width: 100%; height: auto; max-height: 600px; object-fit: contain; display: inline-block;">
            </div>
            <?php endif; ?>

            <div style="padding: 40px 50px;">
                
                <?php if ($article['excerpt']): ?>
                <div style="font-size: 18px; font-weight: 800; color: var(--primary-light); margin-bottom: 30px; line-height: 1.8; padding-right: 20px; border-right: 4px solid var(--gold); background: #f8fafc; padding: 20px; border-radius: 12px 0 0 12px;">
                    <?= e($article['excerpt']) ?>
                </div>
                <?php endif; ?>

                <div class="rich-text-container">
                    <?= $article['content'] /* النص من المحرر */ ?>
                </div>

               

            </div>
        </article>
        
    </div>
</section>