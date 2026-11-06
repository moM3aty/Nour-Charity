<?php
// app/Views/admin/articles/index.php
use App\Core\Session; 
?>
<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">المركز الإعلامي والأخبار</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة المحتوى والمقالات والتقارير الميدانية.</p>
  </div>
  <div style="display:flex; gap:15px;">
    <a href="<?= e(url('admin/articles/categories')) ?>" class="btn btn-outline-primary" style="padding: 12px 20px; border-radius: 12px; background: #fff;">
        <i data-lucide="tags" style="width:18px;"></i> إدارة التصنيفات
    </a>
    <a href="<?= e(url('admin/articles/create')) ?>" class="btn btn-primary" style="padding: 12px 24px; border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2);">
        <i data-lucide="pen-tool" style="width:18px;"></i> كتابة مقال جديد
    </a>
  </div>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px;">تفاصيل المقال</th>
        <th>التصنيف</th>
        <th>حالة النشر</th>
        <th>تاريخ الإصدار</th>
        <th style="text-align:left; padding-left:25px;">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $a): ?>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding:20px;">
            <div style="display:flex; align-items:center; gap:15px;">
              <?php if($a['is_featured']): ?>
                  <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 10px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                      <i data-lucide="star" style="color:var(--gold-hover); fill:var(--gold-hover); width:20px;"></i>
                  </div>
              <?php else: ?>
                  <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 10px; display: flex; justify-content: center; align-items: center; flex-shrink: 0;">
                      <i data-lucide="file-text" style="color:var(--text-muted); width:20px;"></i>
                  </div>
              <?php endif; ?>
              <div>
                <strong style="display:block; font-size:16px; font-weight: 900; color:var(--primary); margin-bottom:4px; max-width: 400px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($a['title']) ?></strong>
                <code style="color:var(--text-muted); font-weight:700; background: #f8fafc; padding: 2px 8px; border-radius: 6px; font-size: 12px;"><?= e($a['slug']) ?></code>
              </div>
            </div>
          </td>
          <td>
              <span style="background: #EAF2FF; color: var(--primary-light); padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 800;">
                  <?= e($a['category_name'] ?? 'عام') ?>
              </span>
          </td>
          <td>
              <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $a['status']==='published' ? 'background:#d1fae5; color:#065f46;' : 'background:#fef3c7; color:#92400e;' ?>">
                  <?= $a['status']==='published' ? 'منشور' : 'مسودة' ?>
              </span>
          </td>
          <td>
              <span style="font-size:13px; font-weight:800; color:var(--text-main); display: flex; align-items: center; gap: 5px;">
                  <i data-lucide="calendar" style="width: 14px; color: var(--text-muted);"></i> <?= dt($a['published_at'] ?? $a['created_at'], 'Y-m-d') ?>
              </span>
          </td>
          <td style="text-align:left; padding-left:25px;">
            <div style="display:flex; gap:10px; justify-content:flex-end;">
              <a href="<?= e(url('news/' . $a['slug'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm" style="padding: 8px; border-radius: 8px;" title="معاينة"><i data-lucide="external-link" style="width:16px;"></i></a>
              <a href="<?= e(url('admin/articles/' . (int)$a['id'] . '/edit')) ?>" class="btn btn-primary btn-sm" style="padding: 8px; border-radius: 8px;" title="تعديل"><i data-lucide="edit" style="width:16px;"></i></a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($items)): ?>
          <tr><td colspan="5" style="text-align:center; padding:60px 20px; color:var(--text-muted); font-weight: 700;">لا توجد مقالات مضافة بعد.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>