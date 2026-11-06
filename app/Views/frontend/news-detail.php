<?php
// app/Views/frontend/news-detail.php
?>
<section class="section" style="padding-top:48px;">
    <div class="container-nour">
        <a href="<?= e(url('news')) ?>" class="text-muted-n small" style="display:inline-block;margin-bottom:16px;">← كل
            الأخبار</a>
        <article
            style="max-width:800px;margin:0 auto;background:#fff;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);">
            <?php if ($article['cover_image']): ?>
            <img src="<?= e($article['cover_image']) ?>" alt="" style="width:100%;aspect-ratio:16/9;object-fit:cover;">
            <?php endif; ?>
            <div style="padding:32px;">
                <div class="project-cat" style="margin-bottom:8px;">
                    <?= e($article['category_name'] ?? '') ?> —
                    <?= dt($article['published_at'] ?: $article['created_at'], 'Y-m-d') ?>
                </div>
                <h1 class="h1" style="color:var(--c-primary-dark);margin-bottom:16px;">
                    <?= e($article['title']) ?>
                </h1>
                <?php if ($article['excerpt']): ?>
                <p class="lead-ar" style="font-weight:600;color:var(--c-text);margin-bottom:20px;">
                    <?= e($article['excerpt']) ?>
                </p>
                <?php endif; ?>
                <div class="lead-ar">
                    <?= $article['content'] /* sanitized */ ?>
                </div>
            </div>
        </article>
    </div>
</section>