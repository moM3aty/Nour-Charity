<?php
// app/Views/admin/hero/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">سلايدر الصفحة الرئيسية (Hero)</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تحكم بالصور والنصوص الترحيبية المعروضة في واجهة الموقع.</p>
  </div>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px; align-items:start;">
  
  <!-- قائمة السلايدات -->
  <div style="display:flex; flex-direction:column; gap:20px;">
      <?php foreach ($items as $s): ?>
        <div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px; border: 1px solid #e2e8f0; display:flex; flex-direction:column;">
            <div style="height: 180px; position: relative;">
                <img src="<?= e($s['image'] ?: 'https://placehold.co/1200x400/061B52/ffffff?text=Slide') ?>" style="width:100%; height:100%; object-fit:cover; filter: brightness(0.7);">
                <div style="position: absolute; top: 15px; right: 15px;">
                    <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 900; box-shadow: 0 4px 10px rgba(0,0,0,0.2); <?= $s['is_active'] ? 'background:#10b981; color:#fff;' : 'background:#64748b; color:#fff;' ?>">
                        <?= $s['is_active'] ? 'نشط ومرئي' : 'معطل' ?>
                    </span>
                </div>
            </div>
            <div style="padding: 25px;">
                <div style="font-size: 13px; color: var(--gold-hover); font-weight: 900; margin-bottom: 5px;"><?= e($s['subtitle'] ?? 'بدون عنوان فرعي') ?></div>
                <h5 style="font-weight: 900; color: var(--primary); font-size: 20px; margin-bottom: 10px;"><?= e($s['title']) ?></h5>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px; line-height: 1.6;"><?= e($s['description'] ?? 'بدون وصف') ?></p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 20px;">
                    <div style="display: flex; gap: 10px;">
                        <?php if($s['cta_text']): ?>
                            <span style="background: #EAF2FF; color: var(--primary-light); padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 800;"><i data-lucide="link" style="width:12px;"></i> <?= e($s['cta_text']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <form method="post" action="<?= e(url('admin/hero/' . (int)$s['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا السلايد؟');">
                      <?= CSRF::field() ?>
                      <button class="btn btn-sm" style="background:#fee2e2; color:#ef4444; border-radius: 8px; padding: 8px 15px; font-weight: 800;"><i data-lucide="trash-2" style="width:16px; margin-left: 5px;"></i> حذف الشريحة</button>
                    </form>
                </div>
            </div>
        </div>
      <?php endforeach; ?>
      
      <?php if (empty($items)): ?>
          <div class="admin-card" style="padding: 60px 20px; text-align: center; border: 2px dashed #e2e8f0;">
              <i data-lucide="monitor-play" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.5;"></i>
              <h5 style="font-weight: 900; color: var(--primary); margin-bottom: 10px;">لا توجد سلايدات مضافة</h5>
              <p style="color: var(--text-muted); font-size: 14px;">سيتم عرض السلايد الافتراضي من الإعدادات. أضف سلايدات جديدة من النموذج الجانبي.</p>
          </div>
      <?php endif; ?>
  </div>

  <!-- نموذج الإضافة -->
  <div class="admin-card" style="position:sticky; top:100px; padding: 35px; border-radius: 16px;">
    <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="image-plus" style="color: var(--gold);"></i> إضافة شريحة (Slide)
    </h6>
    
    <form method="post" action="<?= e(url('admin/hero/save')) ?>" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      
      <div style="margin-bottom:15px;">
        <label class="form-label" style="font-size: 13px;">العنوان الرئيسي <span style="color:#ef4444;">*</span></label>
        <input class="form-control" name="title" placeholder="مثال: معاً نصنع الأمل" required style="font-weight: 800;">
      </div>
      
      <div style="margin-bottom:15px;">
        <label class="form-label" style="font-size: 13px;">النص الفرعي (أعلى العنوان)</label>
        <input class="form-control" name="subtitle" placeholder="مثال: جمعية نور مخة">
      </div>
      
      <div style="margin-bottom:15px;">
        <label class="form-label" style="font-size: 13px;">نص الوصف</label>
        <textarea class="form-control" name="description" rows="3" placeholder="تفاصيل قصيرة..."></textarea>
      </div>

      <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
          <label class="form-label" style="font-size: 13px; color: var(--primary-light);">الزر الأول (رئيسي)</label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
              <input class="form-control" name="cta_text" placeholder="نص الزر: تبرع الآن">
              <input class="form-control" name="cta_link" placeholder="الرابط: /donate" dir="ltr" style="text-align: left;">
          </div>
      </div>

      <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0;">
          <label class="form-label" style="font-size: 13px; color: var(--text-muted);">الزر الثاني (شفاف اختياري)</label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
              <input class="form-control" name="secondary_text" placeholder="مثال: مشاريعنا">
              <input class="form-control" name="secondary_link" placeholder="الرابط: /projects" dir="ltr" style="text-align: left;">
          </div>
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label" style="font-size: 13px;">صورة السلايد (HD)</label>
        <div style="border: 2px dashed #cbd5e1; padding: 15px; text-align: center; border-radius: 12px; background: #fff;">
            <i data-lucide="image" style="width: 24px; color: var(--text-muted); margin-bottom: 10px;"></i>
            <input type="file" class="form-control" name="image" accept="image/*" style="font-size: 12px; padding: 8px;">
        </div>
      </div>

      <div style="margin-bottom:25px; background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
        <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin: 0;">
          <input type="checkbox" name="is_active" value="1" style="width:18px; height:18px; accent-color:var(--gold);" checked>
          <span style="font-weight:800; color:var(--primary); font-size: 13px;">تفعيل العرض</span>
        </label>
        
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 12px; font-weight: 800; margin:0;">الترتيب:</label>
            <input type="number" class="form-control" name="sort_order" value="0" style="width: 60px; padding: 6px; text-align: center; font-size: 13px;">
        </div>
      </div>

      <button class="btn btn-primary" style="width:100%; padding:16px; border-radius: 14px; font-size: 16px; font-weight: 900; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
          <i data-lucide="save" style="width:18px; margin-right: 8px;"></i> حفظ ونشر السلايد
      </button>
    </form>
  </div>
</div>