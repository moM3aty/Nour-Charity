<?php
// app/Views/admin/users/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">المستخدمون ومدراء النظام</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة حسابات الإدارة والمشتركين وصلاحيات الدخول.</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="<?= e(url('admin/users/roles')) ?>" class="btn btn-outline-primary" style="padding: 12px 20px; border-radius: 12px; background: #fff;">
        <i data-lucide="shield-check" style="width:18px;"></i> إدارة الأدوار والصلاحيات
    </a>
    <a href="<?= e(url('admin/users/create')) ?>" class="btn btn-primary" style="padding: 12px 24px; border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2);">
        <i data-lucide="user-plus" style="width:18px;"></i> إضافة مستخدم جديد
    </a>
  </div>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">بيانات المستخدم</th>
        <th>الدور (الصلاحية)</th>
        <th>الحالة</th>
        <th>تاريخ آخر دخول</th>
        <th style="text-align:left; padding-left:25px;">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as$u): ?>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding:20px 25px;">
            <div style="display:flex; align-items:center; gap:15px;">
              <div style="width:45px; height:45px; background:linear-gradient(135deg, #EAF2FF, #dbeafe); color:var(--primary-light); border-radius:12px; display:flex; justify-content:center; align-items:center; font-weight:900; font-size:18px;">
                <?= mb_substr($u['name'], 0, 1) ?>
              </div>
              <div>
                <strong style="display:block; font-size:15px; color:var(--primary); font-weight:900; margin-bottom:2px;"><?= e($u['name']) ?></strong>
                <small style="color:var(--text-muted); font-weight:700; font-family: monospace;"><?= e($u['email']) ?></small>
              </div>
            </div>
          </td>
          <td>
              <?php
                  $isViewer = ($u['role_slug'] ?? '') === 'viewer';
                  $roleBg =$isViewer ? 'background:#f1f5f9; color:#475569;' : 'background:#e0e7ff; color:#3730a3;';
              ?>
              <span style="padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 900; <?= $roleBg ?>">
                  <?= $isViewer ? '<i data-lucide="user" style="width:12px; margin-left:4px;"></i>' : '<i data-lucide="shield" style="width:12px; margin-left:4px;"></i>' ?>
                  <?= e($u['role_name'] ?? '—') ?>
              </span>
          </td>
          <td>
              <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $u['is_active'] ? 'background:#d1fae5; color:#065f46;' : 'background:#fee2e2; color:#991b1b;' ?>">
                  <?= $u['is_active'] ? 'نشط' : 'معطل' ?>
              </span>
          </td>
          <td>
              <?php if($u['last_login']): ?>
                  <span style="font-size:13px; color:var(--primary); font-weight:700; background: #f8fafc; padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0;"><?= dt($u['last_login'], 'Y-m-d H:i') ?></span>
              <?php else: ?>
                  <span style="font-size:13px; color:var(--text-muted); font-weight:700;">لم يسجل دخول بعد</span>
              <?php endif; ?>
          </td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/users/' . (int)$u['id'] . '/edit')) ?>" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; padding: 8px 16px;">
                <i data-lucide="edit" style="width:16px;"></i> تعديل
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>