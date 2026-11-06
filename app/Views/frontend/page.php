<?php
// app/Views/frontend/page.php
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    /* تصميم الهيرو الموحد للصفحات الثابتة */
    .page-hero-unified { background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); padding: 100px 0 80px; text-align: center; color: #fff; border-radius: 0 0 50px 50px; position: relative; overflow: hidden; margin-bottom: 60px; }
    .page-hero-unified::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }

    /* ========================================================
       حاوية الـ Rich Text: تستعيد التنسيقات التي مسحها الـ CSS العام 
       ======================================================== */
    .rich-text-container {
        font-size: 16px;
        line-height: 2.2;
        color: var(--text-main);
    }
    
    /* استعادة الفقرات */
    .rich-text-container p { margin-bottom: 1.5rem; }
    
    /* استعادة الخط العريض */
    .rich-text-container strong, 
    .rich-text-container b { font-weight: 900; color: var(--primary); }
    
    /* استعادة العناوين */
    .rich-text-container h1, .rich-text-container h2, 
    .rich-text-container h3, .rich-text-container h4, 
    .rich-text-container h5, .rich-text-container h6 {
        color: var(--primary);
        font-weight: 900;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    
    /* استعادة القوائم المنقطة والمرقمة */
    .rich-text-container ul, 
    .rich-text-container ol {
        margin-bottom: 1.5rem;
        padding-right: 2.5rem; /* إزاحة لليمين لدعم اللغة العربية */
    }
    .rich-text-container ul { list-style: disc outside !important; }
    .rich-text-container ol { list-style: decimal outside !important; }
    .rich-text-container li { margin-bottom: 0.5rem; display: list-item !important; }
    
    /* استعادة الروابط والصور والاقتباسات */
    .rich-text-container a { color: var(--primary-light); text-decoration: underline; font-weight: 700; }
    .rich-text-container a:hover { color: var(--gold-hover); }
    .rich-text-container img { max-width: 100%; height: auto; border-radius: 16px; margin: 1.5rem 0; box-shadow: var(--shadow-sm); }
    
    .rich-text-container blockquote {
        border-right: 4px solid var(--gold);
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        margin: 2rem 0;
        font-weight: 700;
        color: var(--primary);
        font-size: 1.1rem;
    }
    
    /* استعادة الجداول */
    .rich-text-container table { width: 100%; border-collapse: collapse; margin: 2rem 0; background: #fff; }
    .rich-text-container th, .rich-text-container td { border: 1px solid var(--border); padding: 15px; text-align: right; }
    .rich-text-container th { background: #f8fafc; font-weight: 900; color: var(--primary); }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1><?= e($page['title'] ?? $title) ?></h1>
    </div>
</div>

<section class="section" style="padding-top:0; min-height: 50vh; padding-bottom: 80px;">
    <div class="container-nour">
        <div class="premium-panel" style="max-width:900px; margin:0 auto; padding: 50px;">
            <div class="rich-text-container">
                <?= $page['content'] ?? '<p style="text-align:center; color:#64748b; font-weight:700;">سيتم إضافة المحتوى قريباً.</p>' ?>
            </div>
        </div>
    </div>
</section>