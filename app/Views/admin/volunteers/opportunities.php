<?php
// app/Views/admin/volunteers/opportunities.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">الفرص التطوعية</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">أضف مجالات وفرص تطوعية ليراها الزوار في الموقع.</p>
  </div>
  <a href="<?= e(url('admin/volunteers')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px; background: #fff;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للطلبات
  </a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px; align-items:start;">
  
  <div style="display: flex; flex-direction: column; gap: 20px;">
      <?php foreach ($items as $o): ?>
        <div class="admin-card" style="padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0;">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
              <div>
                  <h5 style="font-weight: 900; color: var(--primary); margin-bottom: 5px;"><?= e($o['title']) ?></h5>
                  <div style="font-size: 13px; color: var(--text-muted); font-weight: 700;">
                      <i data-lucide="map-pin" style="width:14px; vertical-align:middle;"></i> <?= e($o['location'] ?? 'غير محدد') ?> 
                      &nbsp;|&nbsp; 
                      <i data-lucide="clock" style="width:14px; vertical-align:middle;"></i> <?= e($o['commitment'] ?? 'غير محدد') ?>
                  </div>
              </div>
              <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $o['status']==='open' ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;' ?>">
                  <?= $o['status']==='open' ? 'مفتوحة' : 'مغلقة' ?>
              </span>
          </div>
          
          <p style="font-size: 14px; color: var(--text-main); line-height: 1.6; margin-bottom: 20px;"><?= nl2br(e($o['description'])) ?></p>
          
          <div style="text-align: left; border-top: 1px dashed #cbd5e1; padding-top: 15px;">
              <form method="post" action="<?= e(url('admin/volunteers/opportunities/delete/' . (int)$o['id'])) ?>" onsubmit="return confirm('حذف هذه الفرصة نهائياً؟')">
                <?= CSRF::field() ?>
                <button class="btn btn-sm" style="background:#fee2e2; color:#ef4444; border-radius: 8px; padding: 8px 15px; font-weight: 800;"><i data-lucide="trash-2" style="width:16px;"></i> حذف الفرصة</button>
              </form>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if(empty($items)): ?><div class="admin-card" style="text-align:center; padding:50px;"><p style="color:var(--text-muted); font-weight:800;">لا توجد فرص مضافة.</p></div><?php endif; ?>
  </div>

  <div class="admin-card" style="position:sticky; top:100px; padding: 35px; border-radius: 16px;">
    <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="plus-circle" style="color: var(--gold);"></i> إضافة فرصة جديدة
    </h6>
    <form method="post" action="<?= e(url('admin/volunteers/opportunities/save')) ?>">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      
      <div style="margin-bottom:15px;">
          <label class="form-label">المسمى / العنوان</label>
          <input class="form-control" name="title" required placeholder="مثال: منسق ميداني">
      </div>
      
      <div style="margin-bottom:15px;">
          <label class="form-label">المدينة / الموقع</label>
          <input class="form-control" name="location" placeholder="مثال: مكة المكرمة">
      </div>
      
      <div style="margin-bottom:15px;">
          <label class="form-label">الالتزام المطلوب</label>
          <input class="form-control" name="commitment" placeholder="مثال: 4 ساعات أسبوعياً">
      </div>

      <div style="margin-bottom:15px;">
          <label class="form-label">التفاصيل والمهام</label>
          <textarea class="form-control" name="description" rows="4" style="resize:none;"></textarea>
      </div>

      <div style="margin-bottom:25px;">
          <label class="form-label">حالة الفرصة</label>
          <select class="form-select" name="status" style="font-weight: 800;">
            <option value="open">مفتوحة (متاحة للتسجيل)</option>
            <option value="closed">مغلقة</option>
          </select>
      </div>
      
      <button class="btn btn-primary" style="width:100%; padding:16px; border-radius: 14px; font-size: 16px; font-weight: 900; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
          <i data-lucide="save" style="width:18px; margin-right: 8px;"></i> حفظ الفرصة
      </button>
    </form>
  </div>
</div>