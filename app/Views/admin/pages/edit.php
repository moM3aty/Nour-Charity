<?php
// app/Views/admin/pages/edit.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تحرير صفحة: <?= e($page['title']) ?></h4>
  </div>
  <a href="<?= e(url('admin/pages')) ?>" class="btn btn-outline-primary" style="border-radius: 12px; background: #fff;"><i data-lucide="arrow-right" style="width:18px;"></i> العودة للصفحات</a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<form method="post" action="<?= e(url('admin/pages/' . (int)$page['id'])) ?>" class="admin-card" style="padding: 40px; border-radius: 20px;">
  <?= CSRF::field() ?>
  
  <div style="margin-bottom: 25px;">
      <label class="form-label">العنوان الرئيسي للصفحة <span style="color:#ef4444;">*</span></label>
      <input class="form-control" name="title" value="<?= e($page['title']) ?>" required style="font-size: 18px; font-weight: 900; color: var(--primary);">
  </div>

  <div style="margin-bottom: 25px;">
    <label class="form-label">محتوى الصفحة (Rich Text)</label>
    <textarea class="form-control rich-editor" name="content" rows="18"><?= e($page['content'] ?? '') ?></textarea>
  </div>
  
  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
    <div><label class="form-label">Meta Title (SEO)</label><input class="form-control" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>"></div>
    <div><label class="form-label">Meta Description (SEO)</label><input class="form-control" name="meta_description" value="<?= e($page['meta_description'] ?? '') ?>"></div>
  </div>

  <div style="margin-bottom: 30px;">
    <label class="form-label">حالة النشر</label>
    <select class="form-select" name="status" style="font-weight: 800; max-width: 300px;">
      <option value="draft" <?= $page['status']==='draft'?'selected':'' ?>>مسودة (غير ظاهرة)</option>
      <option value="published" <?= $page['status']==='published'?'selected':'' ?>>منشورة (متاحة للزوار)</option>
    </select>
  </div>
  
  <button class="btn btn-primary" style="padding: 16px 50px; font-size: 16px; font-weight: 900; border-radius: 14px; box-shadow: 0 8px 20px rgba(6,27,82,0.2);">
      <i data-lucide="save" style="margin-right: 8px;"></i> حفظ وتحديث الصفحة
  </button>
</form>