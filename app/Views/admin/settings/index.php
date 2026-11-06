<?php
// app/Views/admin/settings/index.php
use App\Core\CSRF; use App\Core\Session; 
$currentGroup = $_GET['group'] ?? 'general'; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">إعدادات النظام</h4>
  </div>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:25px; padding:15px 20px; border-radius: 16px;">
  <div style="display:flex; gap:12px; overflow-x:auto; scrollbar-width:none; padding-bottom: 5px;">
    <?php $labels = ['general'=>'عام ومالي','brand'=>'الهوية البصرية','contact'=>'بيانات التواصل','social'=>'التواصل الاجتماعي','seo'=>'محركات البحث SEO','hero'=>'واجهة الموقع (Hero)','donations'=>'إعدادات التبرع']; ?>
    <?php foreach (array_keys($schema) as $g): ?>
      <!-- هنا تم إصلاح الرابط ليعمل بشكل سليم -->
      <a href="<?= e(url('admin/settings?group=' . urlencode($g))) ?>" class="btn <?= $g===$currentGroup?'btn-primary':'btn-outline-primary' ?>" 
         style="border-radius:12px; padding:12px 24px; white-space:nowrap; font-weight: 800;">
        <?= e($labels[$g] ?? $g) ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<form method="post" action="<?= e(url('admin/settings/save')) ?>" enctype="multipart/form-data" class="admin-card" style="padding: 40px; border-radius: 20px;">
  <?= CSRF::field() ?>
  <input type="hidden" name="group" value="<?= e($currentGroup) ?>">
  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:30px;">
    <?php foreach (($schema[$currentGroup] ?? []) as $key => $meta): 
      $label = $meta[0]; $type = $meta[1]; $val = $values[$key] ?? '';
    ?>
      <div style="<?= $type==='textarea'?'grid-column: 1 / -1;':'' ?>">
        <label class="form-label" style="font-weight: 800; font-size: 14px; color: var(--primary); margin-bottom: 8px;"><?= e($label) ?></label>
        
        <?php if ($type === 'textarea'): ?>
          <textarea class="form-control" name="<?= e($key) ?>" rows="5" style="border-radius: 12px; resize: vertical;"><?= e($val) ?></textarea>
        <?php elseif ($type === 'boolean'): ?>
          <label style="display:flex; align-items:center; gap:12px; background:#f8fafc; padding:14px; border-radius:12px; border:1px solid #e2e8f0; cursor:pointer;">
            <input type="checkbox" name="<?= e($key) ?>" value="1" style="width:20px; height:20px; accent-color:var(--gold);" <?= $val==='1'?'checked':'' ?>>
            <span style="font-weight:800; color: var(--primary); margin:0;">تفعيل الخاصية</span>
          </label>
        <?php elseif ($type === 'color'): ?>
          <input type="color" name="<?= e($key) ?>" value="<?= e($val ?: '#000000') ?>" style="width:100%; height: 50px; padding:5px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <?php elseif ($type === 'image'): ?>
          <input type="hidden" name="<?= e($key) ?>_existing" value="<?= e($val) ?>">
          <input type="file" class="form-control" name="<?= e($key) ?>" accept="image/*" style="background:#fff;">
          <?php if ($val): ?><img src="<?= e($val) ?>" style="max-height:80px; margin-top:10px; border-radius: 8px;"><?php endif; ?>
        <?php else: ?>
          <input type="text" class="form-control" name="<?= e($key) ?>" value="<?= e($val) ?>" style="border-radius: 12px; font-weight: 700;">
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <div style="margin-top:40px; padding-top:20px; border-top:1px dashed #e2e8f0;">
    <button class="btn btn-primary" style="padding:16px 40px; font-weight: 900; border-radius: 14px;">حفظ الإعدادات</button>
  </div>
</form>