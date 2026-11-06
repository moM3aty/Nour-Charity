<?php
// app/Views/admin/pages/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">إدارة الصفحات الثابتة</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تحرير محتوى صفحات الموقع الثابتة (من نحن، الشروط، إلخ).</p>
  </div>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">عنوان الصفحة</th>
        <th>المعرف (الرابط)</th>
        <th>حالة النشر</th>
        <th>تاريخ التحديث</th>
        <th style="text-align:left; padding-left:25px;">إجراء</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as$p): ?>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding:20px 25px;">
              <strong style="color:var(--primary); font-size: 16px; font-weight: 900;"><i data-lucide="file-text" style="width: 18px; color: var(--gold); vertical-align: middle; margin-left: 5px;"></i> <?= e($p['title']) ?></strong>
          </td>
          <td><code style="background: #f1f5f9; color: var(--text-muted); padding: 4px 10px; border-radius: 8px; font-size: 13px; font-weight: 700;">/<?= e($p['slug']) ?></code></td>
          <td>
              <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $p['status']==='published' ? 'background:#d1fae5; color:#065f46;' : 'background:#f1f5f9; color:#475569;' ?>">
                  <?= $p['status']==='published' ? 'منشورة ومرئية' : 'مسودة' ?>
              </span>
          </td>
          <td><span style="font-size:13px; font-weight:800; color:var(--text-muted);"><?= dt($p['updated_at'], 'Y-m-d') ?></span></td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/pages/' . (int)$p['id'])) ?>" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; padding: 8px 20px; font-weight: 900;">
                <i data-lucide="edit" style="width:16px;"></i> تحرير المحتوى
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>