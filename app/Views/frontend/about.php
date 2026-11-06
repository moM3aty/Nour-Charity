<?php
// app/Views/frontend/about.php
use App\Models\PageSection;
$sec = (new PageSection())->forPage('about');
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">
<style>
    .about-hero { 
        background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); 
        padding: 100px 0 80px; 
        text-align: center; color: #fff; 
        border-radius: 0 0 50px 50px; 
        margin-bottom: 80px; position: relative; overflow: hidden; 
    }
    .about-hero::before { content:''; position:absolute; top:-50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius:50%; z-index:1; }
    .about-hero::after { content:''; position:absolute; bottom:-20%; left:-5%; width:300px; height:300px; background:radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius:50%; z-index:1; }
    .about-hero h1 { position:relative; z-index:2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .about-hero p { position:relative; z-index:2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
    
    .story-section { display: flex; flex-wrap: wrap; gap: 60px; align-items: center; margin-bottom: 100px; }
    .story-content { flex: 1; min-width: 300px; }
    .story-img-wrap { flex: 1; min-width: 300px; position: relative; }
    .story-img-wrap img { width: 100%; border-radius: 24px; box-shadow: var(--shadow-md); position: relative; z-index: 2; height:450px; object-fit:cover; }
    .story-img-wrap::before { content:''; position:absolute; top: -20px; left: -20px; width: 100%; height: 100%; border: 4px solid var(--gold); border-radius: 24px; z-index: 1; }
    
    .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 80px; }
    .value-card { background: #fff; padding: 40px 30px; border-radius: 24px; text-align: center; box-shadow: var(--shadow-sm); transition: var(--transition); border: 1px solid var(--border); }
    .value-card:hover { transform: translateY(-10px); box-shadow: var(--shadow-hover); border-color: var(--primary-light); }
    .value-icon { width: 80px; height: 80px; margin: 0 auto 25px; background: #EAF2FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary-light); transition: 0.3s; }
    .value-card:hover .value-icon { background: linear-gradient(135deg, #092A9E, #427BD4); color: #fff; transform: scale(1.1); }
</style>

<div class="about-hero">
    <div class="container-nour">
        <h1>من نحن</h1>
        <p>نور مخة العالمية.. دليلك لصناعة الأثر وتغيير حياة المحتاجين من خلال عمل مؤسسي شفاف وموثوق.</p>
    </div>
</div>

<section class="section" style="padding-top:0;">
    <div class="container-nour">
        
        <div class="story-section">
            <div class="story-content">
                <span style="color:var(--gold); font-weight:800; font-size:14px; letter-spacing:1px; margin-bottom:10px; display:block;">قصتنا</span>
                <h2 class="h2" style="color:var(--primary); margin-bottom:25px;">
                    <?= e($sec['story']['title'] ?? 'من فكرة إلى أثر مستدام') ?>
                </h2>
                <div class="lead-ar" style="line-height: 1.9; color: var(--text-main); font-size: 16px;">
                    <?= $sec['story']['content'] ?? '<p>انطلقت جمعية نور للأعمال الخيرية إيماناً منّا بأن العطاء لا يعرف حدوداً. نسعى جاهدين لبناء جسور من الأمل تمتد للمحتاجين في كل مكان.</p>' ?>
                </div>
            </div>
            <div class="story-img-wrap">
                <img src="<?= e($sec['story']['image'] ?? 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800') ?>" alt="قصة الجمعية">
            </div>
        </div>

        <div class="section-title-wrap" style="margin-bottom: 50px;">
            <h2 class="h2" style="color:var(--primary); text-align:center;">ركائزنا الأساسية</h2>
        </div>

        <div class="values-grid">
            <?php
            $cards = [
                ['vision', 'رؤيتنا', '<i data-lucide="eye" style="width:36px; height:36px;"></i>'],
                ['mission', 'رسالتنا', '<i data-lucide="rocket" style="width:36px; height:36px;"></i>'],
                ['values', 'قيمنا', '<i data-lucide="diamond" style="width:36px; height:36px;"></i>'],
            ];
            foreach ($cards as $c):
                $key = $c[0]; $label = $c[1]; $icon = $c[2];
            ?>
            <div class="value-card">
                <div class="value-icon">
                    <?= $icon ?>
                </div>
                <h3 class="h3" style="color:var(--primary); margin-bottom:15px;">
                    <?= e($sec[$key]['title'] ?? $label) ?>
                </h3>
                <div class="lead-ar" style="font-size: 15px; color: var(--text-muted); line-height: 1.7;">
                    <?= $sec[$key]['content'] ?? '<p>نسعى لتقديم الأفضل دائماً بما يخدم المجتمع.</p>' ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>