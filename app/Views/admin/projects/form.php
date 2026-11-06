<?php
// app/Views/admin/projects/form.php
use App\Core\CSRF; use App\Core\Session; 
?>
<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php $isEdit = $project !== null; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;"><?= $isEdit ? 'تعديل المشروع' : 'إضافة مشروع جديد' ?></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">يرجى تعبئة كافة التفاصيل بدقة لضمان ظهورها بالشكل الأمثل في الموقع.</p>
  </div>
  <a href="<?= e(url('admin/projects')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للقائمة
  </a>
</div>

<form method="post" action="<?= e(url($isEdit ? 'admin/projects/' . (int)$project['id'] : 'admin/projects/store')) ?>" enctype="multipart/form-data" class="admin-card" style="padding: 40px;">
  <?= CSRF::field() ?>
  
  <div style="display:grid; grid-template-columns: 2fr 1fr; gap:40px;">
    
    <!-- Column 1: Main Content -->
    <div style="display: flex; flex-direction: column; gap: 25px;">
        <div>
            <label class="form-label" style="font-size: 15px;">عنوان المشروع <span style="color:#ef4444;">*</span></label>
            <input class="form-control" name="title" value="<?= e($project['title'] ?? '') ?>" required style="font-size: 18px; font-weight: 800; padding: 18px;">
        </div>

        <div>
            <label class="form-label">الوصف القصير (يظهر في كروت المشاريع بالرئيسية)</label>
            <textarea class="form-control" name="short_description" rows="3" style="resize: none;"><?= e($project['short_description'] ?? '') ?></textarea>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 5px;">يفضل ألا يتجاوز سطرين.</div>
        </div>

        <div>
            <label class="form-label">الوصف التفصيلي للمشروع (Rich Text)</label>
            <textarea class="form-control rich-editor" name="description" rows="12"><?= e($project['description'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label class="form-label">أهداف المشروع</label>
                <textarea class="form-control rich-editor" name="objectives" rows="5"><?= e($project['objectives'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="form-label">الفئات المستفيدة</label>
                <textarea class="form-control" name="beneficiaries" rows="8"><?= e($project['beneficiaries'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Column 2: Settings & Meta -->
    <div style="display: flex; flex-direction: column; gap: 25px; background: #f8fafc; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0;">
        
        <div>
            <label class="form-label">صورة الغلاف</label>
            <div style="border: 2px dashed #cbd5e1; padding: 20px; text-align: center; border-radius: 12px; background: #fff;">
                <i data-lucide="image" style="width: 32px; height: 32px; color: var(--text-muted); margin-bottom: 10px;"></i>
                <input type="file" class="form-control" name="cover_image" accept="image/*" style="font-size: 13px; padding: 10px;">
                <?php if (!empty($project['cover_image'])): ?>
                    <div style="margin-top:15px; border-radius:8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <img src="<?= e($project['cover_image']) ?>" style="width: 100%; height: 150px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <label class="form-label">التصنيف</label>
            <select class="form-select" name="category_id">
                <option value="0">— بدون تصنيف —</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($project['category_id'] ?? 0)==(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label">المبلغ المستهدف (ر.س)</label>
            <input type="number" step="0.01" class="form-control" name="target_amount" value="<?= e($project['target_amount'] ?? '') ?>" style="font-weight: 800; color: var(--primary); font-size: 16px;">
        </div>

        <div>
            <label class="form-label">حالة المشروع</label>
            <select class="form-select" name="status" style="font-weight: 800;">
                <?php foreach (['draft'=>'مسودة','active'=>'نشط (متاح للتبرع)','funded'=>'مكتمل التمويل','completed'=>'مكتمل التنفيذ','paused'=>'متوقف','archived'=>'مؤرشف'] as $k=>$v): ?>
                    <option value="<?= $k ?>" <?= ($project['status'] ?? 'draft')===$k?'selected':'' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label class="form-label">تاريخ البدء</label>
                <input type="date" class="form-control" name="start_date" value="<?= e($project['start_date'] ?? '') ?>">
            </div>
            <div>
                <label class="form-label">تاريخ الانتهاء</label>
                <input type="date" class="form-control" name="end_date" value="<?= e($project['end_date'] ?? '') ?>">
            </div>
        </div>

        <div style="background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin:0;">
                <input type="checkbox" name="is_featured" value="1" style="width:20px; height:20px; accent-color:var(--gold);" <?= !empty($project['is_featured'])?'checked':'' ?>>
                <span style="font-weight:800; color:var(--primary); font-size: 14px;">تمييز المشروع في الصفحة الرئيسية</span>
            </label>
        </div>

        <!-- SEO -->
        <hr style="border:0; border-top: 1px dashed #cbd5e1; margin: 10px 0;">
        <h6 style="font-weight: 900; color: var(--text-muted); font-size: 13px; margin-bottom: 10px;">إعدادات محركات البحث (SEO)</h6>
        <div>
            <input class="form-control" name="meta_title" value="<?= e($project['meta_title'] ?? '') ?>" placeholder="Meta Title" style="font-size: 13px; margin-bottom: 10px;">
            <textarea class="form-control" name="meta_description" rows="3" placeholder="Meta Description" style="font-size: 13px;"></textarea>
        </div>
        
    </div>
  </div>

  <div style="margin-top:40px; padding-top:25px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
    <button class="btn btn-primary" style="padding:16px 50px; font-size:16px; border-radius: 14px; box-shadow: 0 10px 25px rgba(6,27,82,0.3);">
        <i data-lucide="save" style="margin-right: 8px;"></i> <?= $isEdit ? 'حفظ التعديلات' : 'إنشاء المشروع واعتماده' ?>
    </button>
  </div>
</form>

<?php if ($isEdit): ?>
  <div class="admin-card" style="margin-top:20px; border:1px solid #fecaca; background: #fff5f5;">
    <form method="post" action="<?= e(url('admin/projects/' . (int)$project['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من أرشفة هذا المشروع؟ (لن يُحذف بالكامل بل سيؤرشف)');">
      <?= CSRF::field() ?>
      <button class="btn" style="background:#fee2e2; color:#991b1b; padding:15px 30px; font-weight:900; border-radius: 12px;">
          <i data-lucide="archive" style="width:18px; margin-right: 8px;"></i> أرشفة / إيقاف المشروع
      </button>
    </form>
  </div>
<?php endif; ?>