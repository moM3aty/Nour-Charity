<?php
// app/Views/admin/users/roles.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">الأدوار والصلاحيات</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تحديد مجموعات الصلاحيات لتعيينها للمستخدمين والإداريين.</p>
  </div>
  <a href="<?= e(url('admin/users')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px; background: #fff;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للمستخدمين
  </a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap:25px;">
    <?php foreach ($roles as $r): ?>
    <div class="admin-card" style="padding: 30px; border-radius: 20px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
        
        <?php if($r['slug'] === 'super_admin'): ?>
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--gold);"></div>
        <?php endif; ?>

        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                <h5 style="font-weight: 900; color: var(--primary); font-size: 20px; margin: 0;"><?= e($r['name']) ?></h5>
                <div style="background: #f1f5f9; color: var(--text-muted); padding: 4px 10px; border-radius: 8px; font-family: monospace; font-size: 12px; font-weight: 700;"><?= e($r['slug']) ?></div>
            </div>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; margin: 0;"><?= e($r['description'] ?? 'لا يوجد وصف') ?></p>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed #cbd5e1; padding-top: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 800; color: var(--primary-light); background: #EAF2FF; padding: 6px 12px; border-radius: 8px;">
                <i data-lucide="users" style="width: 16px;"></i> <?= (int)$r['users_count'] ?> مستخدمين
            </div>
            
            <a href="<?= e(url('admin/users/roles/' . (int)$r['id'] . '/permissions')) ?>" class="btn btn-primary btn-sm" style="border-radius: 8px; padding: 8px 16px;">
                <i data-lucide="key" style="width:16px;"></i> إدارة الصلاحيات
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>