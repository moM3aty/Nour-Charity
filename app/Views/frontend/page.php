<?php
// app/Views/frontend/page.php
?>
<section class="section" style="padding-top:48px;">
    <div class="container-nour">
        <div class="section-head">
            <h1 class="section-title">
                <?= e($page['title'] ?? $title) ?>
            </h1>
        </div>
        <div style="max-width:800px;margin:0 auto;background:#fff;border-radius:var(--radius-lg);padding:32px;">
            <?= $page['content'] ?? '<p class="text-muted-n">سيتم إضافة المحتوى قريباً.</p>' ?>
        </div>
    </div>
</section>