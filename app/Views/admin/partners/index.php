<?php
// app/Views/admin/partners/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">شركاء النجاح</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة شعارات الجهات المانحة والشركاء لعرضها في الموقع.</p>
  </div>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px; align-items:start;">
  
  <!-- قائمة الشركاء -->
  <div style="display: flex; flex-direction: column; gap: 15px;">
      <?php foreach ($items as $p): ?>
        <div class="admin-card" style="padding: 20px 25px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
          <div style="display: flex; gap: 20px; align-items: center;">
            <div style="width: 80px; height: 60px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1; display: flex; justify-content: center; align-items: center; overflow: hidden; padding: 5px;">
                <?php if ($p['logo']): ?><img src="<?= e($p['logo']) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;"><?php else: ?><i data-lucide="image" style="color:var(--text-muted);"></i><?php endif; ?>
            </div>
            <div>
              <strong style="display: block; font-size: 16px; font-weight: 900; color: var(--primary); margin-bottom: 4px;"><?= e($p['name']) ?></strong>
              <?php if ($p['url']): ?><a href="<?= e($p['url']) ?>" target="_blank" style="font-size: 12px; color: var(--primary-light); font-weight: 700; text-decoration: underline;" dir="ltr"><?= e($p['url']) ?></a><?php endif; ?>
            </div>
          </div>
          
          <div style="display: flex; gap: 15px; align-items: center;">
            <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $p['is_active'] ? 'background:#d1fae5; color:#065f46;' : 'background:#f1f5f9; color:#475569;' ?>">
                <?= $p['is_active'] ? 'نشط' : 'معطل' ?>
            </span>
            <form method="post" action="<?= e(url('admin/partners/delete/' . (int)$p['id'])) ?>" onsubmit="return confirm('حذف الشريك؟')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm" style="background:#fee2e2; color:#ef4444; border-radius: 8px; padding: 8px 12px;"><i data-lucide="trash-2" style="width:16px;"></i></button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($items)): ?>
          <div class="admin-card" style="padding: 60px 20px; text-align: center; border: 2px dashed #e2e8f0;">
              <i data-lucide="briefcase" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.5;"></i>
              <h5 style="font-weight: 900; color: var(--primary);">لا يوجد شركاء مضافين</h5>
          </div>
      <?php endif; ?>
  </div>

  <!-- نموذج الإضافة -->
  <div class="admin-card" style="position:sticky; top:100px; padding: 35px; border-radius: 16px;">
    <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="plus-circle" style="color: var(--gold);"></i> إضافة شريك جديد
    </h6>
    <form method="post" action="<?= e(url('admin/partners/save')) ?>" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      
      <div style="margin-bottom:15px;">
          <label class="form-label">اسم الشريك / الجهة <span style="color:#ef4444;">*</span></label>
          <input class="form-control" name="name" required style="font-weight: 800;">
      </div>
      
      <div style="margin-bottom:15px;">
          <label class="form-label">رابط الموقع (اختياري)</label>
          <input class="form-control" name="url" placeholder="https://..." dir="ltr" style="text-align: left;">
      </div>
      
      <div style="margin-bottom:20px;">
        <label class="form-label">شعار الشريك</label>
        <div style="border: 2px dashed #cbd5e1; padding: 15px; text-align: center; border-radius: 12px; background: #fff;">
            <i data-lucide="image" style="width: 24px; color: var(--text-muted); margin-bottom: 10px;"></i>
            <input type="file" class="form-control" name="logo" accept="image/*" style="font-size: 12px; padding: 8px;">
        </div>
      </div>

      <div style="margin-bottom:25px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
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
          <i data-lucide="save" style="width:18px; margin-right: 8px;"></i> حفظ الشريك
      </button>
    </form>
  </div>
</div>