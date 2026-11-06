<?php
// app/Views/admin/campaigns/form.php
use App\Core\CSRF; use App\Core\Session; ?>
<?php if ($m = Session::getFlash('error')): ?><div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:10px;margin-bottom:20px;font-weight:800;"><?= e($m) ?></div><?php endif; ?>
<?php $isEdit = $campaign !== null; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);"><?= $isEdit ? 'تعديل الحملة' : 'إضافة حملة جديدة' ?></h4>
  <a href="<?= e(url('admin/campaigns')) ?>" class="btn btn-outline-primary"><i data-lucide="arrow-right" style="width:18px;"></i> عودة</a>
</div>

<form method="post" action="<?= e(url($isEdit ? 'admin/campaigns/' . (int)$campaign['id'] : 'admin/campaigns/store')) ?>" enctype="multipart/form-data" class="admin-card">
  <?= CSRF::field() ?>
  
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:25px;">
    <div style="grid-column: 1 / -1;">
      <label class="form-label">عنوان الحملة <span style="color:red">*</span></label>
      <input class="form-control" name="title" value="<?= e($campaign['title'] ?? '') ?>" required style="font-size:18px; font-weight:800; padding:18px;">
    </div>

    <div>
      <label class="form-label">ربط بمشروع (اختياري)</label>
      <select class="form-select" name="project_id">
        <option value="0">— حملة مستقلة —</option>
        <?php foreach ($projects as $p): ?>
          <option value="<?= (int)$p['id'] ?>" <?= ($campaign['project_id'] ?? 0)==(int)$p['id']?'selected':'' ?>><?= e($p['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="form-label">المبلغ المستهدف</label>
      <input type="number" step="0.01" class="form-control" name="goal_amount" value="<?= e($campaign['goal_amount'] ?? '') ?>">
    </div>

    <div>
      <label class="form-label">تاريخ البدء</label>
      <input type="date" class="form-control" name="start_date" value="<?= e($campaign['start_date'] ?? '') ?>">
    </div>

    <div>
      <label class="form-label">تاريخ الانتهاء</label>
      <input type="date" class="form-control" name="end_date" value="<?= e($campaign['end_date'] ?? '') ?>">
    </div>

    <div>
      <label class="form-label">حالة الحملة</label>
      <select class="form-select" name="status">
        <?php foreach (['draft'=>'مسودة','active'=>'نشطة','ended'=>'منتهية','paused'=>'متوقفة'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= ($campaign['status'] ?? 'draft')===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="display:flex; align-items:center; gap:10px; margin-top:30px;">
      <input type="checkbox" name="is_active" value="1" id="is_active" style="width:20px; height:20px; accent-color:var(--gold);" <?= ($campaign['is_active'] ?? 1)?'checked':'' ?>>
      <label for="is_active" style="font-weight:800; cursor:pointer;">تنشيط الحملة وعرضها في الموقع</label>
    </div>

    <div style="grid-column: 1 / -1;">
      <label class="form-label">وصف الحملة (Rich Text)</label>
      <textarea class="form-control rich-editor" name="description" rows="8"><?= e($campaign['description'] ?? '') ?></textarea>
    </div>

    <div>
      <label class="form-label">صورة الحملة</label>
      <input type="file" class="form-control" name="image" accept="image/*" style="padding:10px;">
      <?php if (!empty($campaign['image'])): ?>
        <img src="<?= e($campaign['image']) ?>" style="height:80px; border-radius:8px; margin-top:10px;">
      <?php endif; ?>
    </div>
  </div>

  <div style="margin-top:40px; padding-top:20px; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
    <button class="btn btn-primary" style="padding:15px 40px; font-size:16px;"><?= $isEdit ? 'حفظ التعديلات' : 'إنشاء الحملة' ?></button>
  </div>
</form>

<?php if ($isEdit): ?>
  <div class="admin-card" style="margin-top:20px; border:1px solid #fee2e2;">
    <form method="post" action="<?= e(url('admin/campaigns/' . (int)$campaign['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذه الحملة نهائياً؟');">
      <?= CSRF::field() ?>
      <button class="btn" style="background:#fee2e2; color:#991b1b; width:100%; padding:15px; font-weight:800;"><i data-lucide="trash-2" style="width:18px;"></i> حذف الحملة نهائياً</button>
    </form>
  </div>
<?php endif; ?>