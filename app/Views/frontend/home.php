<?php
// app/Views/frontend/home.php
use App\Models\Project;
use App\Models\Category;

$localImages = [
    'quran'   => asset('images/1.avif'), 
    'orphans' => asset('images/2.avif'),
    'zakat'   => asset('images/3.avif'), 
    'food'    => asset('images/4.avif'), 
];

function getWaterBottleSvg() {
    $defs = '<defs><linearGradient id="waterGrad" x1="0%" y1="0%" x2="0%" y2="100%"><stop offset="0%" stop-color="#092A9E" /><stop offset="45%" stop-color="#092A9E" /><stop offset="85%" stop-color="#F4C978" /><stop offset="100%" stop-color="#F4C978" /></linearGradient></defs>';
    $path = '
        <path d="M6 2h4"/>
        <path d="M7 2v3c-2 1.5-3 3-3 5v9a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-9c0-2-1-3.5-3-5V2"/>
        <path d="M4 14h8"/>
        <path d="M14.5 11h7l-.85 8.5A1.5 1.5 0 0 1 19.15 21h-2.3a1.5 1.5 0 0 1-1.5-1.5L14.5 11z"/>
        <path d="M14.9 15h6.2"/> 
        <path d="M18 5c0 0-1.5 1.8-1.5 2.8a1.5 1.5 0 0 0 3 0c0-1-1.5-2.8-1.5-2.8z"/>
    ';
    $style = 'stroke="url(#waterGrad)" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"';
    return '<svg class="cat-svg-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">' . $defs . '<g ' . $style . '>' . $path . '</g></svg>';
}
?>
<style>
    .heroSwiper { width: 100%; height: 90vh; min-height: 650px; border-radius: 0 0 60px 60px; overflow: hidden; margin-bottom: 70px; box-shadow: var(--shadow-lg); position: relative; background: #000; }
    .hero-bg-img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 1; transform: scale(1.05); transition: transform 10s cubic-bezier(0.25, 0.46, 0.45, 0.94), filter 1s; filter: brightness(0.8); }
    .swiper-slide-active .hero-bg-img { transform: scale(1.15); filter: brightness(1); }
    .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(6, 27, 82, 0.95) 0%, rgba(9, 42, 158, 0.4) 50%, rgba(0, 0, 0, 0.2) 100%); z-index: 2; }
    .hero-content { position: relative; z-index: 4; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; text-align: right; color: #fff; padding: 0 8%; max-width: 1200px; margin: 0 auto; }
    .hero-subtitle { font-size: 18px; font-weight: 900; background: rgba(244, 201, 120, 0.15); color: var(--gold); padding: 8px 24px; border-radius: 30px; margin-bottom: 20px; border: 1px solid rgba(244,201,120,0.3); backdrop-filter: blur(8px); transform: translateY(30px); opacity: 0; transition: all 1s ease 0.3s; }
    .hero-title { font-size: 4.5rem; font-weight: 900; line-height: 1.2; margin-bottom: 25px; text-shadow: 0 10px 30px rgba(0,0,0,0.4); max-width: 850px; transform: translateY(30px); opacity: 0; transition: all 1s ease 0.5s; }
    .hero-desc { font-size: 1.4rem; font-weight: 500; opacity: 0.9; margin-bottom: 40px; max-width: 700px; line-height: 1.8; text-shadow: 0 4px 15px rgba(0,0,0,0.3); transform: translateY(30px); opacity: 0; transition: all 1s ease 0.7s; }
    .hero-btns { display: flex; gap: 20px; flex-wrap: wrap; transform: translateY(30px); opacity: 0; transition: all 1s ease 0.9s; }
    .swiper-slide-active .hero-subtitle, .swiper-slide-active .hero-title, .swiper-slide-active .hero-desc, .swiper-slide-active .hero-btns { transform: translateY(0); opacity: 1; }
    .hero-btn-custom { width: auto !important; padding: 18px 45px !important; border-radius: var(--radius-full) !important; font-size: 18px !important; letter-spacing: 0.5px; }
    .outline-white { border-color: rgba(255,255,255,0.4) !important; color: #fff !important; background: rgba(255,255,255,0.05) !important; backdrop-filter: blur(5px); }
    .outline-white:hover { background: rgba(255,255,255,0.15) !important; border-color: #fff !important; color: #fff !important; }
    .swiper-pagination-bullets { bottom: 40px !important; display: flex; justify-content: center; gap: 8px; }
    .swiper-pagination-bullet { background: #fff !important; opacity: 0.4 !important; width: 40px !important; height: 4px !important; border-radius: 2px !important; transition: 0.4s !important; margin: 0 !important; }
    .swiper-pagination-bullet-active { background: var(--gold) !important; opacity: 1 !important; width: 80px !important; box-shadow: 0 0 10px rgba(244, 201, 120, 0.5); }
    .swiper-button-next, .swiper-button-prev { display: none !important; }
    @media (max-width: 768px) {
        .hero-content { align-items: center; text-align: center; }
        .hero-title { font-size: 2.8rem; }
        .hero-desc { font-size: 1.1rem; }
        .heroSwiper { height: 80vh; border-radius: 0 0 40px 40px; }
    }
</style>

<!-- Dynamic Hero Slider -->
<div class="swiper heroSwiper">
  <div class="swiper-wrapper">
    <?php foreach ($heroSlides as $slide): ?>
    <div class="swiper-slide">
      <img src="<?= e($slide['image'] ?: setting('hero.image', asset('images/hero.jpg'))) ?>" class="hero-bg-img" alt="<?= e($slide['title']) ?>">
      <div class="hero-overlay"></div>
      
      <div class="hero-content">
        <?php if (!empty($slide['subtitle'])): ?>
          <span class="hero-subtitle"><?= e($slide['subtitle']) ?></span>
        <?php endif; ?>
        
        <h1 class="hero-title"><?= e($slide['title']) ?></h1>
        
        <?php if (!empty($slide['description'])): ?>
          <p class="hero-desc"><?= e($slide['description']) ?></p>
        <?php endif; ?>
        
        <div class="hero-btns">
          <?php if (!empty($slide['cta_text'])): ?>
            <a href="<?= e(url($slide['cta_link'] ?? '#')) ?>" class="btn-n btn-n-primary btn-n-lg hero-btn-custom"><?= e($slide['cta_text']) ?></a>
          <?php endif; ?>
          <?php if (!empty($slide['secondary_text'])): ?>
            <a href="<?= e(url($slide['secondary_link'] ?? '#')) ?>" class="btn-n btn-n-outline btn-n-lg hero-btn-custom outline-white"><?= e($slide['secondary_text']) ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="swiper-pagination"></div>
</div>
<!-- End Dynamic Hero Slider -->

<div class="container-nour">
  
  <!-- المشاريع -->
  <div class="section-title-wrap">
    <span class="section-subtitle">مشاريع مباركة</span>
    <h2 class="section-title">مشاريع تنتظر دعمكم</h2>
    <p class="section-desc">كل تبرع يصنع فرقاً حقيقياً - اختر المشروع الذي يلامس قلبك</p>
  </div>

  <div class="projects-grid">
    <?php foreach ($projects as $p): 
      $img = !empty($p['cover_image']) ? $p['cover_image'] : 'https://placehold.co/600x400/F4F7FA/061B52?text=Nour';
    ?>
    <div class="project-card">
      <div class="p-img-wrap">
        <div class="p-title"><?= e($p['title']) ?></div>
        <div class="p-tag"><?= e($p['category_name'] ?? 'وقف خيري') ?></div>
        <img src="<?= e($img) ?>" class="p-img" alt="<?= e($p['title']) ?>">
      </div>
      
      <div class="p-body">
        <p class="p-desc"><?= e(mb_substr(strip_tags($p['short_description'] ?? 'ساهم بدعم هذا المشروع الخيري ليكون صدقة جارية.'), 0, 70)) ?>...</p>
        
        <div class="p-options">
          <label class="p-opt-label active" data-price="100">
            <input type="radio" name="opt_<?= $p['id'] ?>" checked>
            <span>مساهمة مبدئية</span>
            <span>100 ر.س</span>
          </label>
          <label class="p-opt-label" data-price="500">
            <input type="radio" name="opt_<?= $p['id'] ?>">
            <span>مساهمة فضية</span>
            <span>500 ر.س</span>
          </label>
        </div>

        <div class="p-controls">
          <div class="qty-group">
            <button type="button" class="qty-btn plus">+</button>
            <input type="text" class="qty-input val" value="1" readonly>
            <button type="button" class="qty-btn minus">-</button>
          </div>
          <div class="p-total-price">المبلغ الكلي: <span class="total-num">100</span> ر.س</div>
        </div>

        <div class="p-btns">
          <?php if (\App\Core\Auth::check()): ?>
            <button class="btn-add-cart" onclick="nourAddToCart({id:<?= $p['id'] ?>, title:'<?= e(addslashes($p['title'])) ?>', amount: parseFloat(this.closest('.p-body').querySelector('.total-num').innerText.replace(/,/g,''))})"><i data-lucide="shopping-cart" style="width:16px;"></i> أضف للسلة</button>
            <a href="<?= e(url('donate?project=' . (int)$p['id'])) ?>" class="btn-pay-now"><i data-lucide="credit-card" style="width:16px;"></i> تبرع الآن</a>
          <?php else: ?>
            <a href="<?= e(url('login')) ?>" class="btn-add-cart" style="text-decoration:none;" onclick="if(typeof Swal !== 'undefined'){ Swal.fire({icon:'info', title:'تسجيل الدخول', text:'يجب تسجيل الدخول لتتمكن من التبرع.', confirmButtonColor:'#061B52'}); return false; } else { alert('يجب تسجيل الدخول أولاً.'); return false; }"><i data-lucide="shopping-cart" style="width:16px;"></i> أضف للسلة</a>
            <a href="<?= e(url('login')) ?>" class="btn-pay-now" style="text-decoration:none;" onclick="if(typeof Swal !== 'undefined'){ Swal.fire({icon:'info', title:'تسجيل الدخول', text:'يجب تسجيل الدخول لتتمكن من التبرع.', confirmButtonColor:'#061B52'}); return false; } else { alert('يجب تسجيل الدخول أولاً.'); return false; }"><i data-lucide="credit-card" style="width:16px;"></i> تبرع الآن</a>
          <?php endif; ?>
        </div>
        <a href="<?= e(url('gift')) ?>" class="btn-gift"><i data-lucide="gift" style="width:16px;"></i> إهداء لمن تحب</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- أقسام التبرعات -->
  <div class="section-title-wrap" style="margin-top: 80px;">
    <span class="section-subtitle">استكشف المجالات</span>
    <h2 class="section-title">أقسام التبرعات</h2>
    <p class="section-desc">اختر المجال الأقرب إلى قلبك وابدأ رحلة العطاء</p>
  </div>

  <div class="slider-container">
    <div class="slider-arrow right" id="slideRight"><i data-lucide="chevron-right"></i></div>
    <div class="cats-slider" id="catsSlider">
      <?php foreach ($categories as $cat): ?>
        <a href="<?= e(url('projects?cat=' . $cat['id'])) ?>" class="premium-card">
          <div class="cat-icon-wrap">
            <?php 
              if ($cat['slug'] === 'water') {
                  echo getWaterBottleSvg();
              } else {
                  $imgUrl = $localImages[$cat['slug']] ?? $localImages['food'];
                  echo '<div class="cat-icon-gradient" style="-webkit-mask-image: url(\'' . e($imgUrl) . '\'); mask-image: url(\'' . e($imgUrl) . '\');"></div>';
              }
            ?>
          </div>
          <span class="cat-title"><?= e($cat['name']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="slider-arrow left" id="slideLeft"><i data-lucide="chevron-left"></i></div>
  </div>

  <!-- المركز الإعلامي (أحدث الأخبار) -->
  <?php if (!empty($news)): ?>
  <div class="section-title-wrap" style="margin-top: 60px;">
    <span class="section-subtitle">المركز الإعلامي</span>
    <h2 class="section-title">أحدث أخبارنا وتقاريرنا</h2>
  </div>
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 60px;">
      <?php foreach ($news as $n): ?>
      <a href="<?= e(url('news/' . $n['slug'])) ?>" style="background: #fff; border-radius: 24px; overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow-sm); transition: 0.3s; display: block; text-decoration: none;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='var(--shadow-md)'; this.style.borderColor='var(--primary-light)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)'; this.style.borderColor='var(--border)';">
          <div style="height: 220px; overflow: hidden; position: relative;">
              <img src="<?= e($n['cover_image'] ?: 'https://placehold.co/600x400/061B52/ffffff?text=News') ?>" style="width: 100%; height: 100%; object-fit: cover;">
              <span style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.95); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 900; box-shadow: 0 4px 10px rgba(0,0,0,0.1);"><?= e($n['category_name'] ?? 'أخبار') ?></span>
          </div>
          <div style="padding: 25px;">
              <div style="font-size: 12px; color: var(--text-muted); font-weight: 700; margin-bottom: 10px;"><i data-lucide="calendar" style="width:14px; vertical-align:middle;"></i> <?= dt($n['published_at'], 'Y-m-d') ?></div>
              <h3 style="font-size: 18px; font-weight: 900; color: var(--primary); margin-bottom: 15px; line-height: 1.5;"><?= e($n['title']) ?></h3>
              <p style="font-size: 14px; color: var(--text-muted); line-height: 1.7; margin-bottom: 0;"><?= e(mb_substr($n['excerpt'] ?? strip_tags($n['content']), 0, 100)) ?>...</p>
          </div>
      </a>
      <?php endforeach; ?>
  </div>
  <div style="text-align: center; margin-bottom: 80px;">
      <a href="<?= e(url('news')) ?>" class="btn-n btn-n-outline" style="border-radius: 12px;">عرض كافة الأخبار</a>
  </div>
  <?php endif; ?>

  <!-- شركاء النجاح -->
  <?php if (!empty($partners)): ?>
  <div class="section-title-wrap" style="margin-top: 40px;">
    <span class="section-subtitle">شركاء النجاح</span>
    <h2 class="section-title">شراكات تثمر عطاءً</h2>
  </div>
  <div style="display: flex; gap: 30px; justify-content: center; flex-wrap: wrap; margin-bottom: 80px; align-items: center;">
      <?php foreach ($partners as $partner): ?>
          <div style="background: #fff; border-radius: 16px; padding: 20px; box-shadow: var(--shadow-sm); border: 1px solid var(--border); width: 160px; height: 100px; display: flex; justify-content: center; align-items: center; transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='var(--primary-light)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
              <img src="<?= e($partner['logo']) ?>" alt="<?= e($partner['name']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain; filter: grayscale(100%); transition: 0.3s;" onmouseover="this.style.filter='grayscale(0%)';" onmouseout="this.style.filter='grayscale(100%)';">
          </div>
      <?php endforeach; ?>
  </div>
  <?php endif; ?>
  
</div>