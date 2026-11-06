<?php
// app/Views/frontend/projects.php
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
        margin-bottom: -50px; /* لدمج الفلتر */
    }
    .page-hero-unified::before { content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified::after { content: ''; position: absolute; bottom: -20%; left: -5%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; z-index: 1; }
    .page-hero-unified h1 { position: relative; z-index: 2; font-size: 3rem; font-weight: 900; margin-bottom: 15px; text-shadow: 0 4px 15px rgba(0,0,0,0.2); }
    .page-hero-unified p { position: relative; z-index: 2; font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }

    /* بقية ستايلات الصفحة */
    .filter-wrapper { position: relative; z-index: 10; max-width: 1000px; margin: 0 auto 50px; }
    .filter-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 24px; padding: 25px; box-shadow: 0 20px 40px rgba(6,27,82,0.1); display: flex; gap: 20px; flex-wrap: wrap; align-items: center; border: 1px solid rgba(226, 232, 240, 0.8); }
    .filter-input-group { flex: 1; min-width: 250px; position: relative; }
    .filter-input-group i { position: absolute; right: 20px; top: 18px; color: var(--text-muted); width: 22px; }
    .filter-control { width: 100%; padding: 16px 50px 16px 20px; border: 1.5px solid var(--border); border-radius: 16px; font-family: inherit; font-size: 15px; font-weight: 700; color: var(--primary); background: #f8fafc; transition: var(--transition); outline: none; }
    .filter-control:focus { border-color: var(--gold); background: #fff; box-shadow: 0 0 0 4px rgba(244,201,120,0.15); }
    .btn-filter { background: var(--primary); color: #fff; border: none; border-radius: 16px; padding: 16px 40px; font-family: inherit; font-size: 16px; font-weight: 800; cursor: pointer; transition: var(--transition); white-space: nowrap; }
    .btn-filter:hover { background: var(--primary-light); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(6,27,82,0.2); }
    
    .projects-container { max-width: 1240px; margin: 0 auto; padding: 0 20px 100px; }
    .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
    .project-box { background: #fff; border-radius: 24px; overflow: hidden; box-shadow: var(--shadow-sm); border: 1px solid var(--border); display: flex; flex-direction: column; transition: var(--transition); position: relative; }
    .project-box:hover { transform: translateY(-10px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
    .project-img-wrap { position: relative; overflow: hidden;}
    .project-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .project-box:hover .project-img-wrap img { transform: scale(1.08); }
    .project-cat-badge { position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); backdrop-filter: blur(5px); color: var(--primary); padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 900; z-index: 2; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .project-body { padding: 25px; flex-grow: 1; display: flex; flex-direction: column; }
    .project-title { font-size: 20px; font-weight: 900; color: var(--primary); margin-bottom: 12px; line-height: 1.4; }
    .project-desc { font-size: 14px; color: var(--text-muted); margin-bottom: 25px; flex-grow: 1; line-height: 1.7; }
    .progress-wrap { margin-bottom: 25px; }
    .progress-stats { display: flex; justify-content: space-between; font-size: 14px; font-weight: 900; margin-bottom: 8px; }
    .progress-stats .raised { color: var(--primary-light); }
    .progress-stats .pct { color: var(--gold-hover); }
    .progress-track { height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; }
    .progress-bar { height: 100%; background: linear-gradient(90deg, #092A9E, #F4C978); border-radius: 4px; }
    .project-actions { display: grid; grid-template-columns: 1fr 1.5fr; gap: 10px; }
    .btn-details { text-align: center; border: 2px solid var(--border); color: var(--primary); padding: 12px; border-radius: 12px; font-weight: 800; font-size: 14px; transition: var(--transition); }
    .btn-details:hover { border-color: var(--primary-light); background: #f8fafc; color: var(--primary-light); }
    .btn-donate { text-align: center; background: var(--primary-grad); color: #fff; padding: 12px; border-radius: 12px; font-weight: 800; font-size: 14px; display: flex; justify-content: center; align-items: center; gap: 8px; transition: var(--transition); border: none; cursor: pointer; }
    .btn-donate:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(9,42,158,0.3); color: #fff; }
</style>

<div class="page-hero-unified">
    <div class="container-nour">
        <h1>مشاريعنا الخيرية</h1>
        <p>استكشف فرص العطاء وكن سبباً في تغيير حياة إنسان. كل تبرع يصنع فرقاً حقيقياً.</p>
    </div>
</div>

<!-- Filter -->
<div class="container-nour">
    <div class="filter-wrapper">
        <form method="get" class="filter-card">
            <div class="filter-input-group">
                <i data-lucide="search"></i>
                <input type="text" name="q" class="filter-control" placeholder="ابحث عن مشروع بالاسم..." value="<?= e($search ?? '') ?>">
            </div>
            <div class="filter-input-group">
                <i data-lucide="filter"></i>
                <select name="cat" class="filter-control" style="cursor: pointer;">
                    <option value="">كل التصنيفات</option>
                    <?php foreach ($categories as$c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ($categoryId ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-filter">تصفية النتائج</button>
        </form>
    </div>
</div>

<!-- Grid -->
<div class="projects-container">
    <div class="projects-grid">
        <?php if (!empty($projectsData['items'])): ?>
            <?php foreach ($projectsData['items'] as$p): 
                $pct = ((float)$p['target_amount'] > 0) ? min(100, round(((float)$p['raised_amount'] / (float)$p['target_amount']) * 100)) : 0;
                $img = !empty($p['cover_image']) ?$p['cover_image'] : 'https://placehold.co/600x400/F4F7FA/061B52?text=Nour';
            ?>
                <div class="project-box">
                    <div class="project-img-wrap">
                        <span class="project-cat-badge"><?= e($p['category_name'] ?? 'عام') ?></span>
                        <img src="<?= e($img) ?>" alt="<?= e($p['title']) ?>">
                    </div>
                    <div class="project-body">
                        <h3 class="project-title"><?= e($p['title']) ?></h3>
                        <p class="project-desc"><?= e(mb_substr(strip_tags($p['short_description'] ?? ''), 0, 90)) ?>...</p>
                        
                        <div class="progress-wrap">
                            <div class="progress-stats">
                                <span class="raised">جمعنا <?= money((float)$p['raised_amount']) ?></span>
                                <span class="pct"><?= $pct ?>%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-bar" style="width: <?= $pct ?>%"></div>
                            </div>
                        </div>

                        <div class="project-actions">
                            <a href="<?= e(url('projects/' . $p['slug'])) ?>" class="btn-details">التفاصيل</a>
                            <?php if (\App\Core\Auth::check()): ?>
                                <a href="<?= e(url('donate?project=' . (int)$p['id'])) ?>" class="btn-donate">
                                    <i data-lucide="heart" style="width: 16px;"></i> تبرع الآن
                                </a>
                            <?php else: ?>
                                <a href="<?= e(url('login')) ?>" class="btn-donate" onclick="if(typeof Swal !== 'undefined'){ Swal.fire({icon:'info', title:'تسجيل الدخول', text:'يجب تسجيل الدخول لتتمكن من التبرع.', confirmButtonColor:'#061B52'}); return false; } else { alert('يجب تسجيل الدخول أولاً.'); return false; }">
                                    <i data-lucide="heart" style="width: 16px;"></i> سجل لتتبرع
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="premium-panel" style="grid-column: 1 / -1; text-align: center; padding: 80px 20px;">
                <i data-lucide="folder-search" style="width: 80px; height: 80px; color: var(--border); margin-bottom: 20px;"></i>
                <h3 style="font-size: 24px; font-weight: 900; color: var(--primary); margin-bottom: 10px;">لا توجد مشاريع مطابقة لبحثك</h3>
                <p style="color: var(--text-muted); margin-bottom: 25px;">جرب البحث بكلمات أخرى أو تصفح كل التصنيفات.</p>
                <a href="<?= e(url('projects')) ?>" class="btn-n btn-n-primary">عرض كل المشاريع</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (($projectsData['pages'] ?? 0) > 1): ?>
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 60px;">
            <?php for ($i=1; $i<=$projectsData['pages'];$i++): ?>
                <a href="?page=<?= $i ?>&q=<?= urlencode($search ?? '') ?>&cat=<?= $categoryId ?? 0 ?>" 
                   style="width: 45px; height: 45px; display: flex; justify-content: center; align-items: center; border-radius: 12px; font-weight: 800; transition: 0.3s;
                          <?= $i===($projectsData['page']??1) ? 'background: var(--primary); color: #fff; box-shadow: 0 5px 15px rgba(6,27,82,0.2);' : 'background: #fff; color: var(--primary); border: 1px solid var(--border);' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>