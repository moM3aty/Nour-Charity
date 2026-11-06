<?php 
// app/Views/admin/articles/categories.php
use App\Core\CSRF; use App\Core\Session; ?>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success"><?= e($m) ?></div><?php endif; ?>
<a href="<?= e(url('admin/articles')) ?>" class="btn btn-sm btn-outline-secondary mb-3">← الأخبار</a>
<div class="row g-3">
  <div class="col-md-7">
    <div class="bg-white rounded p-4">
      <?php foreach ($items as $c): ?>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span><?= e($c['name']) ?> <small class="text-muted">(<?= e($c['slug']) ?>)</small></span>
          <form method="post" action="<?= e(url('admin/articles/categories/delete/' . (int)$c['id'])) ?>" onsubmit="return confirm('حذف؟')">
            <?= CSRF::field() ?>
            <button class="btn btn-sm btn-outline-danger">حذف</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="col-md-5">
    <form method="post" action="<?= e(url('admin/articles/categories/save')) ?>" class="bg-white rounded p-4">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      <input class="form-control form-control-sm mb-2" name="name" placeholder="اسم التصنيف" required>
      <button class="btn btn-primary btn-sm w-100">حفظ</button>
    </form>
  </div>
</div>