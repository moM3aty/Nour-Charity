<?php
// app/Views/frontend/gallery.php
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
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; max-width: 1200px; margin: 0 auto 60px; }
    .gallery-item { position: relative; border-radius: 16px; overflow: hidden; aspect-ratio: 1 / 1; cursor: pointer; box-shadow: var(--shadow-sm); background: #f8fafc; }
    .gallery-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .gallery-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(6, 27, 82, 0.8), transparent); opacity: 0; transition: 0.4s; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px; }
    .gallery-item:hover .gallery-img { transform: scale(1.1); }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    .gallery-title { color: #fff; font-weight: 800; font-size: 15px; margin: 0; transform: translateY(10px); transition: 0.4s; }
    .gallery-item:hover .gallery-title { transform: translateY(0); }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>معرض الصور</h1>
        <p>جانب من مشاريعنا وأعمالنا الميدانية التي تحققت بفضل دعمكم وعطائكم المستمر.</p>
    </div>
</div>

<section class="section" style="padding-top:0; min-height: 50vh;">
    <div class="container-nour">
        
        <?php if (!empty($items)): ?>
            <div class="gallery-grid">
                <?php foreach ($items as $item): ?>
                    <div class="gallery-item" onclick="if(typeof Swal !== 'undefined') { Swal.fire({imageUrl: '<?= e($item['path']) ?>', imageAlt: '<?= e($item['original_name']) ?>', showConfirmButton: false, customClass:{popup: 'bg-transparent'}, width: '80%', padding: 0, background: 'transparent'}); }">
                        <img src="<?= e($item['path']) ?>" alt="<?= e($item['original_name']) ?>" class="gallery-img" loading="lazy">
                        <div class="gallery-overlay">
                            <h4 class="gallery-title"><?= e(pathinfo($item['original_name'], PATHINFO_FILENAME)) ?></h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($pages > 1): ?>
                <div style="display: flex; justify-content: center; gap: 10px; margin-top: 40px; margin-bottom: 80px;">
                    <?php for ($i=1; $i<=$pages; $i++): ?>
                        <a href="?page=<?= $i ?>" 
                           style="width: 45px; height: 45px; display: flex; justify-content: center; align-items: center; border-radius: 12px; font-weight: 800; text-decoration: none; transition: 0.3s;
                                  <?= $i===($page) ? 'background: var(--primary); color: #fff; box-shadow: 0 5px 15px rgba(6,27,82,0.2);' : 'background: #fff; color: var(--primary); border: 1px solid var(--border);' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="premium-panel" style="text-align: center; padding: 80px 20px; max-width: 800px; margin: 0 auto 80px;">
                <i data-lucide="image" style="width: 80px; height: 80px; color: var(--border); margin-bottom: 20px;"></i>
                <h3 style="font-size: 24px; font-weight: 900; color: var(--primary); margin-bottom: 10px;">لا توجد صور حالياً</h3>
                <p style="color: var(--text-muted);">سيتم إضافة تغطيات المشاريع والصور قريباً.</p>
            </div>
        <?php endif; ?>
        
    </div>
</section>