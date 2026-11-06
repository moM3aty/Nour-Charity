<?php
// app/Views/admin/hero/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">سلايدر الصفحة الرئيسية (Hero)</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة اللوحات الترحيبية في واجهة الموقع بكل سهولة.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="toggleForm()" style="border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2); padding: 12px 24px;">
      <i data-lucide="plus" style="width:18px; margin-left: 5px;"></i> شريحة جديدة
  </button>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<!-- نموذج الإضافة والتعديل (مخفي افتراضياً ويظهر عند الطلب) -->
<div id="formPanelWrap" style="display: none; margin-bottom: 40px;">
  <div class="admin-card" id="formPanel" style="padding: 35px; border-radius: 20px; border: 2px solid var(--gold); box-shadow: 0 15px 40px rgba(244, 201, 120, 0.15);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h6 id="formTitle" style="font-weight:900; color:var(--primary); margin:0; font-size:18px; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="image-plus" style="color: var(--gold);" id="formIcon"></i> <span>إضافة شريحة (Slide)</span>
        </h6>
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleForm()" style="border: none; background: #f1f5f9;"><i data-lucide="x" style="width:16px;"></i> إغلاق</button>
    </div>
    
    <form method="post" action="<?= e(url('admin/hero/save')) ?>" enctype="multipart/form-data" id="heroForm">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" id="slideId" value="0">
      
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
          <div>
            <label class="form-label" style="font-size: 13px;">العنوان الرئيسي <span style="color:#ef4444;">*</span></label>
            <input class="form-control" name="title" id="slideTitle" placeholder="مثال: معاً نصنع الأمل" required style="font-weight: 800;">
          </div>
          
          <div>
            <label class="form-label" style="font-size: 13px;">النص الفرعي (أعلى العنوان)</label>
            <input class="form-control" name="subtitle" id="slideSubtitle" placeholder="مثال: جمعية نور مخة">
          </div>
          
          <div style="grid-column: 1 / -1;">
            <label class="form-label" style="font-size: 13px;">نص الوصف</label>
            <textarea class="form-control" name="description" id="slideDescription" rows="2" placeholder="تفاصيل قصيرة..."></textarea>
          </div>

          <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
              <label class="form-label" style="font-size: 13px; color: var(--primary-light);">الزر الأول (رئيسي)</label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                  <input class="form-control" name="cta_text" id="slideCtaText" placeholder="النص: تبرع الآن">
                  <input class="form-control" name="cta_link" id="slideCtaLink" placeholder="الرابط: /donate" dir="ltr" style="text-align: left;">
              </div>
          </div>

          <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
              <label class="form-label" style="font-size: 13px; color: var(--text-muted);">الزر الثاني (شفاف اختياري)</label>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                  <input class="form-control" name="secondary_text" id="slideSecText" placeholder="النص: مشاريعنا">
                  <input class="form-control" name="secondary_link" id="slideSecLink" placeholder="الرابط: /projects" dir="ltr" style="text-align: left;">
              </div>
          </div>

          <div>
            <label class="form-label" style="font-size: 13px;">صورة السلايد (HD)</label>
            <div style="border: 2px dashed #cbd5e1; padding: 10px 15px; text-align: center; border-radius: 12px; background: #fff; position: relative;">
                <input type="file" class="form-control" name="image" id="slideImage" accept="image/*" style="font-size: 12px; padding: 8px;">
                <div id="currentImageWrapper" style="display: none; margin-top: 10px; padding: 6px; background: #fef3c7; border-radius: 8px;">
                    <span style="font-size: 12px; color: #92400e; font-weight: 800;"><i data-lucide="info" style="width:14px; vertical-align:middle;"></i> توجد صورة مسبقاً</span>
                </div>
            </div>
          </div>

          <div style="background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: center; gap: 15px;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin: 0;">
              <input type="checkbox" name="is_active" id="slideActive" value="1" style="width:18px; height:18px; accent-color:var(--gold);" checked>
              <span style="font-weight:800; color:var(--primary); font-size: 14px;">تفعيل عرض الشريحة</span>
            </label>
            
            <div style="display: flex; align-items: center; gap: 8px;">
                <label style="font-size: 13px; font-weight: 800; margin:0;">الترتيب:</label>
                <input type="number" class="form-control" name="sort_order" id="slideSort" value="0" style="width: 80px; padding: 6px; text-align: center; font-size: 14px;">
            </div>
          </div>
      </div>

      <div style="display: flex; gap: 10px; margin-top: 25px; border-top: 1px dashed #e2e8f0; padding-top: 20px;">
          <button type="submit" id="btnSubmit" class="btn btn-primary" style="padding:14px 40px; border-radius: 12px; font-size: 15px; font-weight: 900; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
              <i data-lucide="save" style="margin-right: 8px;"></i> حفظ ونشر السلايد
          </button>
          <button type="button" class="btn btn-outline-primary" style="padding:14px 20px; border-radius: 12px; font-weight: 900;" onclick="toggleForm()">إلغاء</button>
      </div>
    </form>
  </div>
</div>

<!-- قائمة السلايدات (شبكة كروت) -->
<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap:25px; align-items:start;">
  <?php foreach ($items as $s): ?>
    <div class="admin-card" style="padding:0; overflow:hidden; border-radius: 20px; border: 1px solid #e2e8f0; display:flex; flex-direction:column; transition: 0.3s; height: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.03);" id="slide-card-<?= $s['id'] ?>">
        <div style="height: 200px; position: relative; border-bottom: 2px solid var(--gold);">
            <img src="<?= e($s['image'] ?: 'https://placehold.co/1200x400/061B52/ffffff?text=Slide') ?>" style="width:100%; height:100%; object-fit:cover; filter: brightness(0.85);">
            <div style="position: absolute; top: 15px; right: 15px;">
                <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 900; box-shadow: 0 4px 10px rgba(0,0,0,0.2); <?= $s['is_active'] ? 'background:#10b981; color:#fff;' : 'background:#64748b; color:#fff;' ?>">
                    <?= $s['is_active'] ? 'نشط ومرئي' : 'معطل' ?>
                </span>
            </div>
            <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.5); color: #fff; padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 700; backdrop-filter: blur(4px);">
                الترتيب: <?= (int)$s['sort_order'] ?>
            </div>
        </div>
        
        <div style="padding: 25px; flex-grow: 1; display: flex; flex-direction: column;">
            <div style="font-size: 13px; color: var(--gold-hover); font-weight: 900; margin-bottom: 5px;"><?= e($s['subtitle'] ?? 'بدون عنوان فرعي') ?></div>
            <h5 style="font-weight: 900; color: var(--primary); font-size: 18px; margin-bottom: 12px; line-height: 1.4;"><?= e($s['title']) ?></h5>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.6; flex-grow: 1;"><?= e(mb_substr($s['description'] ?? 'بدون وصف', 0, 100)) ?></p>
            
            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px;">
                <?php if($s['cta_text']): ?>
                    <span style="background: #EAF2FF; color: var(--primary-light); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;"><i data-lucide="link" style="width:12px; vertical-align: middle;"></i> <?= e($s['cta_text']) ?></span>
                <?php endif; ?>
                <?php if($s['secondary_text']): ?>
                    <span style="background: #f1f5f9; color: var(--text-muted); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 800;"><i data-lucide="link-2" style="width:12px; vertical-align: middle;"></i> <?= e($s['secondary_text']) ?></span>
                <?php endif; ?>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border-top: 1px dashed #e2e8f0; padding-top: 20px;">
                <button type="button" class="btn btn-outline-primary" style="border-radius: 10px; padding: 10px; font-weight: 900; width: 100%; justify-content: center;" onclick='editSlide(<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                    <i data-lucide="edit" style="width:16px;"></i> تحرير
                </button>
                <form method="post" action="<?= e(url('admin/hero/' . (int)$s['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا السلايد بشكل نهائي؟');" style="margin:0;">
                    <?= CSRF::field() ?>
                    <button class="btn" style="background:#fee2e2; color:#ef4444; border-radius: 10px; padding: 10px; font-weight: 900; width: 100%; justify-content: center; border: 1px solid #fca5a5;"><i data-lucide="trash-2" style="width:16px;"></i> حذف</button>
                </form>
            </div>
        </div>
    </div>
  <?php endforeach; ?>
  
  <?php if (empty($items)): ?>
      <div style="grid-column: 1 / -1; padding: 80px 20px; text-align: center; border: 2px dashed #e2e8f0; background: #fff; border-radius: 20px;">
          <i data-lucide="monitor-play" style="width: 64px; height: 64px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.5;"></i>
          <h5 style="font-weight: 900; color: var(--primary); margin-bottom: 10px; font-size: 20px;">لا توجد شرائح مضافة</h5>
          <p style="color: var(--text-muted); font-size: 15px;">سيتم عرض الشريحة الافتراضية من الإعدادات. يمكنك البدء بإضافة شرائح جديدة.</p>
          <button type="button" class="btn btn-primary" onclick="toggleForm()" style="margin-top: 15px; border-radius: 12px; padding: 12px 25px;"><i data-lucide="plus"></i> إضافة شريحة الآن</button>
      </div>
  <?php endif; ?>
</div>

<script>
const formWrap = document.getElementById('formPanelWrap');

function toggleForm() {
    if (formWrap.style.display === 'none' || formWrap.style.display === '') {
        formWrap.style.display = 'block';
        resetForm(); // تفريغ الفورم في حال فتحه كجديد
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        formWrap.style.display = 'none';
        // مسح التأثير البصري من جميع الكروت
        document.querySelectorAll('[id^="slide-card-"]').forEach(el => {
            el.style.border = '1px solid #e2e8f0';
            el.style.transform = 'scale(1)';
            el.style.boxShadow = '0 4px 15px rgba(0,0,0,0.03)';
        });
    }
}

function editSlide(slide) {
    // إظهار النموذج إذا كان مخفياً
    formWrap.style.display = 'block';

    // إبراز الكرت المُحدد بصرياً وإخفاء الباقي
    document.querySelectorAll('[id^="slide-card-"]').forEach(el => {
        el.style.border = '1px solid #e2e8f0';
        el.style.transform = 'scale(1)';
        el.style.boxShadow = '0 4px 15px rgba(0,0,0,0.03)';
    });
    const activeCard = document.getElementById('slide-card-' + slide.id);
    if (activeCard) {
        activeCard.style.border = '2px solid var(--gold)';
        activeCard.style.transform = 'translateY(-5px)';
        activeCard.style.boxShadow = '0 15px 30px rgba(244, 201, 120, 0.2)';
    }

    // تعبئة البيانات في النموذج
    document.getElementById('slideId').value = slide.id;
    document.getElementById('slideTitle').value = slide.title || '';
    document.getElementById('slideSubtitle').value = slide.subtitle || '';
    document.getElementById('slideDescription').value = slide.description || '';
    document.getElementById('slideCtaText').value = slide.cta_text || '';
    document.getElementById('slideCtaLink').value = slide.cta_link || '';
    document.getElementById('slideSecText').value = slide.secondary_text || '';
    document.getElementById('slideSecLink').value = slide.secondary_link || '';
    document.getElementById('slideSort').value = slide.sort_order || 0;
    document.getElementById('slideActive').checked = slide.is_active == 1;
    
    // إظهار تنبيه بوجود صورة سابقة
    const imgWrapper = document.getElementById('currentImageWrapper');
    if (slide.image) {
        imgWrapper.style.display = 'block';
    } else {
        imgWrapper.style.display = 'none';
    }

    // تعديل واجهة النموذج
    document.querySelector('#formTitle span').innerText = 'تعديل بيانات الشريحة';
    document.getElementById('btnSubmit').innerHTML = '<i data-lucide="edit-3" style="margin-right: 8px; display:inline-block; vertical-align:middle;"></i> تحديث البيانات';
    
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // التمرير الناعم للنموذج
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    if(typeof Swal !== 'undefined') {
        Swal.fire({
            toast: true, position: 'top-end', icon: 'info',
            title: 'وضع التعديل مفعل', showConfirmButton: false,
            timer: 2000, background: '#061B52', color: '#fff'
        });
    }
}

function resetForm() {
    document.getElementById('heroForm').reset();
    document.getElementById('slideId').value = '0';
    document.getElementById('slideActive').checked = true;
    document.getElementById('currentImageWrapper').style.display = 'none';
    
    document.querySelector('#formTitle span').innerText = 'إضافة شريحة (Slide)';
    document.getElementById('btnSubmit').innerHTML = '<i data-lucide="save" style="margin-right: 8px; display:inline-block; vertical-align:middle;"></i> حفظ ونشر السلايد';
    
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
</script>