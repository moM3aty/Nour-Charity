<?php
// app/Views/admin/articles/form.php
use App\Core\CSRF; use App\Core\Session; 
?>
<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php $isEdit = $article !== null; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;"><?= $isEdit ? 'تعديل المقال' : 'كتابة مقال جديد' ?></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">محرر نصوص متقدم يدعم رفع الصور وتنسيقها باحترافية.</p>
  </div>
  <a href="<?= e(url('admin/articles')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للمركز الإعلامي
  </a>
</div>

<form method="post" action="<?= e(url($isEdit ? 'admin/articles/' . (int)$article['id'] : 'admin/articles/store')) ?>" enctype="multipart/form-data" class="admin-card" style="padding: 40px; border-radius: 20px;">
  <?= CSRF::field() ?>
  
  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:40px;">
    
    <!-- المحتوى الرئيسي -->
    <div style="display: flex; flex-direction: column; gap: 25px;">
      <div>
        <label class="form-label" style="font-size: 15px;">عنوان المقال / الخبر <span style="color:#ef4444;">*</span></label>
        <input class="form-control" name="title" value="<?= e($article['title'] ?? '') ?>" required style="font-size:18px; font-weight:900; padding:18px; color: var(--primary);">
      </div>

      <div>
        <label class="form-label">المقدمة (تظهر كنبذة في بطاقات الأخبار ومحركات البحث)</label>
        <textarea class="form-control" name="excerpt" rows="3" style="resize: none;"><?= e($article['excerpt'] ?? '') ?></textarea>
      </div>

      <div>
        <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
            محتوى المقال (Rich Text)
            <span style="font-size: 11px; color: var(--gold-hover); background: #fef3c7; padding: 4px 10px; border-radius: 6px;">يدعم إدراج الصور</span>
        </label>
        <textarea class="form-control rich-editor" name="content" rows="18"><?= e($article['content'] ?? '') ?></textarea>
      </div>
    </div>

    <!-- الشريط الجانبي -->
    <div style="display: flex; flex-direction: column; gap: 25px; background: #f8fafc; padding: 30px; border-radius: 16px; border: 1px solid #e2e8f0;">
      
      <div>
        <label class="form-label">التصنيف</label>
        <select class="form-select" name="category_id" style="font-weight: 800;">
          <option value="0">— بدون تصنيف —</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= (int)$c['id'] ?>" <?= ($article['category_id'] ?? 0)==(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="form-label">حالة النشر</label>
        <select class="form-select" name="status" style="font-weight: 800; color: var(--primary);">
          <option value="draft" <?= ($article['status'] ?? 'draft')==='draft'?'selected':'' ?>>مسودة (غير مرئي للزوار)</option>
          <option value="published" <?= ($article['status'] ?? '')==='published'?'selected':'' ?>>منشور (متاح بالموقع)</option>
        </select>
      </div>

      <div>
        <label class="form-label">صورة الغلاف الأساسية</label>
        <div style="border: 2px dashed #cbd5e1; padding: 20px; text-align: center; border-radius: 12px; background: #fff;">
            <i data-lucide="image" style="width: 32px; height: 32px; color: var(--text-muted); margin-bottom: 10px;"></i>
            <input type="file" class="form-control" name="cover_image" accept="image/*" style="font-size: 13px; padding: 10px;">
            <?php if (!empty($article['cover_image'])): ?>
            <div style="margin-top:15px; border-radius:8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <img src="<?= e($article['cover_image']) ?>" style="width: 100%; height: 160px; object-fit: cover;">
            </div>
            <?php endif; ?>
        </div>
      </div>

      <div style="background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin:0;">
            <input type="checkbox" name="is_featured" value="1" style="width:20px; height:20px; accent-color:var(--gold);" <?= !empty($article['is_featured'])?'checked':'' ?>>
            <span style="font-weight:900; color:var(--primary); font-size: 14px;">تثبيت كمقال مميز</span>
        </label>
      </div>

      <hr style="border:0; border-top: 1px dashed #cbd5e1; margin: 5px 0;">
      
      <h6 style="font-weight: 900; color: var(--text-muted); font-size: 13px; margin-bottom: 5px;">إعدادات محركات البحث (SEO)</h6>
      <div>
        <input class="form-control" name="meta_title" value="<?= e($article['meta_title'] ?? '') ?>" placeholder="Meta Title" style="font-size: 13px; margin-bottom: 10px;">
        <textarea class="form-control" name="meta_description" rows="3" placeholder="Meta Description" style="font-size: 13px;"></textarea>
      </div>

      <button class="btn btn-primary" style="width:100%; padding:16px; font-size:16px; font-weight: 900; border-radius: 14px; box-shadow: 0 10px 20px rgba(6,27,82,0.2); margin-top: 10px;">
          <i data-lucide="check-circle" style="margin-right: 8px;"></i> <?= $isEdit ? 'حفظ المقال' : 'نشر المقال' ?>
      </button>
    </div>
  </div>
</form>

<?php if ($isEdit): ?>
  <div class="admin-card" style="margin-top:20px; border:1px solid #fecaca; background: #fff5f5; padding: 25px;">
    <form method="post" action="<?= e(url('admin/articles/' . (int)$article['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من حذف المقال؟');">
      <?= CSRF::field() ?>
      <button class="btn" style="background:#fee2e2; color:#991b1b; padding:12px 30px; font-weight:900; border-radius: 12px; border: 1px solid #f87171;">
          <i data-lucide="trash-2" style="width:18px; margin-right: 8px;"></i> حذف المقال نهائياً
      </button>
    </form>
  </div>
<?php endif; ?>