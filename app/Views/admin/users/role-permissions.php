<?php
// app/Views/admin/users/role-permissions.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تخصيص صلاحيات: <span style="color:var(--gold-hover);"><?= e($role['name']) ?></span></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">حدد الصفحات والعمليات التي يمكن لأصحاب هذا الدور الوصول إليها.</p>
  </div>
  <a href="<?= e(url('admin/users/roles')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px; background: #fff;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة لقائمة الأدوار
  </a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<?php if ($role['slug'] === 'super_admin'): ?>
  <div class="alert alert-info" style="background:#e0e7ff;color:#3730a3;padding:20px;border-radius:16px;margin-bottom:30px;font-weight:800; border: 1px solid #c7d2fe; display: flex; align-items: center; gap: 15px;">
    <i data-lucide="shield-alert" style="width:32px; height:32px;"></i>
    <div>
        <div style="font-size: 16px; margin-bottom: 5px;">صلاحيات مطلقة (Super Admin)</div>
        <div style="font-size: 13px; font-weight: 600;">هذا الدور يمتلك كل الصلاحيات بشكل افتراضي ولا يمكن تقييده أو التعديل عليه.</div>
    </div>
  </div>
<?php endif; ?>

<form method="post" action="<?= e(url('admin/users/roles/' . (int)$role['id'] . '/permissions')) ?>" <?= $role['slug']==='super_admin'?'onsubmit="return false"':'' ?> class="admin-card" style="padding: 40px; border-radius: 20px;">
  <?= CSRF::field() ?>
  
  <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:20px;">
    <?php foreach ($allPermissions as $p): ?>
      <label style="display:flex; align-items:flex-start; gap:15px; background:#f8fafc; padding:20px; border-radius:16px; border:2px solid #e2e8f0; cursor:pointer; transition:0.3s;" 
             onmouseover="this.style.borderColor='var(--gold)'; this.style.backgroundColor='#fff';" 
             onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc';">
        <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= e($p['slug']) ?>" <?= in_array($p['slug'], $assigned, true)?'checked':'' ?> <?= $role['slug']==='super_admin'?'checked disabled':'' ?> style="width:24px; height:24px; margin-top:2px; accent-color:var(--primary);">
        <div>
          <div style="font-weight:900; font-size:15px; color:var(--primary); margin-bottom:6px;"><?= e($p['name']) ?></div>
          <div style="font-family:monospace; font-size:12px; color:var(--text-muted); font-weight: 700; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; display: inline-block;"><?= e($p['slug']) ?></div>
        </div>
      </label>
    <?php endforeach; ?>
  </div>

  <?php if ($role['slug'] !== 'super_admin'): ?>
    <div style="margin-top:40px; padding-top:25px; border-top:1px dashed #e2e8f0; text-align:left;">
      <button class="btn btn-primary" style="padding:16px 40px; font-size:16px; font-weight: 900; border-radius: 14px; box-shadow: 0 10px 25px rgba(6,27,82,0.2);">
          <i data-lucide="save" style="margin-right: 8px;"></i> حفظ وتحديث الصلاحيات
      </button>
    </div>
  <?php endif; ?>
</form>