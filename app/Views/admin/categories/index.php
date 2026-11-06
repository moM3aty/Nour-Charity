<?php
// app/Views/admin/categories/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تصنيفات المشاريع</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تنظيم وهيكلة مجالات التبرع في المنصة.</p>
  </div>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:30px; align-items:start;">
  
  <div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
    <table class="table" style="margin:0;">
      <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
        <tr><th style="padding:20px;">بيانات التصنيف</th><th>الحالة</th><th style="text-align:left; padding-left:20px;">إجراء</th></tr>
      </thead>
      <tbody>
        <?php foreach ($items as $c): ?>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding:20px;">
              <div style="display:flex; align-items:center; gap:15px;">
                <div style="width:50px; height:50px; background:linear-gradient(135deg, #EAF2FF, #dbeafe); color:var(--primary-light); border-radius:14px; display:flex; justify-content:center; align-items:center; box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);">
                  <i data-lucide="<?= e($c['icon'] ?: 'folder') ?>" style="width:24px; height:24px;"></i>
                </div>
                <div>
                  <strong style="display:block; color:var(--primary); font-size:16px; font-weight: 900; margin-bottom: 4px;"><?= e($c['name']) ?></strong>
                  <code style="color:var(--text-muted); font-weight:700; background: #f8fafc; padding: 2px 8px; border-radius: 6px; font-size: 12px;"><?= e($c['slug']) ?></code>
                </div>
              </div>
            </td>
            <td>
                <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $c['is_active'] ? 'background:#d1fae5; color:#065f46;' : 'background:#f1f5f9; color:#475569;' ?>">
                    <?= $c['is_active'] ? 'نشط ومرئي' : 'معطل' ?>
                </span>
            </td>
            <td style="text-align:left; padding-left:20px;">
              <form method="post" action="<?= e(url('admin/categories/delete/' . (int)$c['id'])) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');">
                <?= CSRF::field() ?>
                <button class="btn btn-sm" style="background:#fee2e2; color:#991b1b; border-radius: 8px; padding: 8px 12px;"><i data-lucide="trash-2" style="width:16px;"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="admin-card" style="position:sticky; top:100px; padding: 35px; border-radius: 16px;">
    <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="folder-plus" style="color: var(--gold);"></i> إضافة تصنيف جديد
    </h6>
    <form method="post" action="<?= e(url('admin/categories/save')) ?>" enctype="multipart/form-data">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      
      <div style="margin-bottom:20px;">
        <label class="form-label">اسم التصنيف <span style="color:#ef4444;">*</span></label>
        <input class="form-control" name="name" required style="font-size: 15px; font-weight: 800;">
      </div>
      
      <div style="margin-bottom:20px;">
        <label class="form-label">المعرف الفريد (Slug)</label>
        <input class="form-control" name="slug" dir="ltr" style="text-align:left;" placeholder="يُنشأ تلقائياً إن تُرك فارغاً">
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label">أيقونة النظام (Lucide)</label>
        <div style="position: relative;">
            <input class="form-control" name="icon" placeholder="heart, droplet, book, home..." dir="ltr" style="text-align:left; padding-left: 45px;">
            <i data-lucide="search" style="position: absolute; left: 15px; top: 15px; color: var(--text-muted); width: 18px;"></i>
        </div>
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label">الوصف (اختياري)</label>
        <textarea class="form-control" name="description" rows="3" style="resize: none;"></textarea>
      </div>

      <div style="margin-bottom:30px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin: 0;">
          <input type="checkbox" name="is_active" value="1" style="width:20px; height:20px; accent-color:var(--gold);" checked>
          <span style="font-weight:900; color:var(--primary); font-size: 14px;">نشط ومرئي للزوار</span>
        </label>
      </div>

      <button class="btn btn-primary" style="width:100%; padding:16px; border-radius: 14px; font-size: 16px; font-weight: 900; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
          <i data-lucide="save" style="width:18px; margin-right: 8px;"></i> حفظ التصنيف
      </button>
    </form>
  </div>
</div>