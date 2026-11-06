<?php
// app/Views/admin/pages/about.php
use App\Core\CSRF; use App\Core\Session; ?>
<a href="<?= e(url('admin/pages')) ?>" class="btn btn-sm btn-outline-secondary mb-3">← الصفحات</a>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success"><?= e($m) ?></div><?php endif; ?>

<form method="post" action="<?= e(url('admin/pages/about')) ?>" class="bg-white rounded p-4">
  <?= CSRF::field() ?>
  <?php
  $fields = [
    'story'      => 'قصتنا',
    'vision'     => 'الرؤية',
    'mission'    => 'الرسالة',
    'values'     => 'القيم',
    'objectives' => 'الأهداف',
  ];
  foreach ($fields as $key => $label):
    $title = $sections[$key]['title'] ?? '';
    $content = $sections[$key]['content'] ?? '';
  ?>
    <fieldset class="mb-4 p-3" style="border:1px solid #e6ecf5;border-radius:12px;">
      <legend class="fs-6 fw-bold mb-2"><?= e($label) ?></legend>
      <input class="form-control form-control-sm mb-2" name="<?= e($key) ?>_title" placeholder="العنوان" value="<?= e($title) ?>">
      <!-- ✅ محرر TinyMCE -->
      <textarea class="form-control rich-editor" name="<?= e($key) ?>_content" rows="5" placeholder="المحتوى"><?= e($content) ?></textarea>
    </fieldset>
  <?php endforeach; ?>
  <button class="btn btn-primary">حفظ</button>
</form>