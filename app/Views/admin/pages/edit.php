<?php
// app/Views/admin/pages/edit.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تحرير صفحة: <?= e($page['title']) ?></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">استخدم المحرر المتقدم لتنسيق محتوى الصفحة وإدراج الصور بسهولة.</p>
  </div>
  <a href="<?= e(url('admin/pages')) ?>" class="btn btn-outline-primary" style="border-radius: 12px; background: #fff;"><i data-lucide="arrow-right" style="width:18px; margin-left: 5px;"></i> العودة للصفحات</a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border: 1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:5px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<form method="post" action="<?= e(url('admin/pages/' . (int)$page['id'])) ?>" class="admin-card" style="padding: 40px; border-radius: 20px;">
  <?= CSRF::field() ?>
  
  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:40px;">
      
      <!-- العمود الأول: المحتوى الأساسي -->
      <div style="display: flex; flex-direction: column; gap: 25px;">
          <div>
              <label class="form-label" style="font-size: 15px;">العنوان الرئيسي للصفحة <span style="color:#ef4444;">*</span></label>
              <input class="form-control" name="title" value="<?= e($page['title']) ?>" required style="font-size: 18px; font-weight: 900; color: var(--primary); padding: 18px;">
          </div>

          <div>
            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                محتوى الصفحة (Rich Text)
                <span style="font-size: 11px; color: var(--gold-hover); background: #fef3c7; padding: 4px 10px; border-radius: 6px; font-weight: 800;">يدعم إدراج الصور</span>
            </label>
            <!-- الكلاس rich-editor يقوم بتشغيل محرر TinyMCE المربوط بالسكربت -->
            <textarea class="form-control rich-editor" name="content" rows="18"><?= e($page['content'] ?? '') ?></textarea>
          </div>
      </div>

      <!-- العمود الثاني: الإعدادات الجانبية -->
      <div style="display: flex; flex-direction: column; gap: 20px; background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
          
          <div>
            <label class="form-label">حالة النشر</label>
            <select class="form-select" name="status" style="font-weight: 800; color: var(--primary);">
              <option value="draft" <?= $page['status']==='draft'?'selected':'' ?>>مسودة (غير ظاهرة للزوار)</option>
              <option value="published" <?= $page['status']==='published'?'selected':'' ?>>منشورة (متاحة في الموقع)</option>
            </select>
          </div>
          
          <hr style="border:0; border-top: 1px dashed #cbd5e1; margin: 10px 0;">
          
          <h6 style="font-weight: 900; color: var(--text-muted); font-size: 13px; margin-bottom: 5px;">إعدادات محركات البحث (SEO)</h6>
          
          <div>
              <input class="form-control" name="meta_title" value="<?= e($page['meta_title'] ?? '') ?>" placeholder="Meta Title (اختياري)" style="font-size: 13px; margin-bottom: 15px;">
              <textarea class="form-control" name="meta_description" rows="4" placeholder="Meta Description (اختياري)" style="font-size: 13px; resize: none;"></textarea>
          </div>

          <button class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; font-weight: 900; border-radius: 14px; box-shadow: 0 10px 20px rgba(6,27,82,0.2); margin-top: 15px;">
              <i data-lucide="save" style="margin-right: 8px;"></i> حفظ وتحديث الصفحة
          </button>
          
      </div>
      
  </div>
</form>