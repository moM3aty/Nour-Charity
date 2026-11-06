<?php 
// app/Views/admin/donations/offline.php
use App\Core\CSRF; use App\Core\Session; ?>
<a href="<?= e(url('admin/donations')) ?>" class="btn btn-sm btn-outline-secondary mb-3">← التبرعات</a>
<?php if ($m = Session::getFlash('error')): ?><div class="alert alert-danger"><?= e($m) ?></div><?php endif; ?>

<div class="alert alert-warning small">
  <strong>تنبيه:</strong> هذا النموذج لتسجيل تبرع تم استلامه خارج المنصة (نقداً أو تحويل يدوي تم التحقق منه). سيُسجَّل مباشرة كـ"مدفوع" في النظام. كل عملية تُوثَّق في Audit Logs باسمك.
</div>

<form method="post" action="<?= e(url('admin/donations/offline')) ?>" class="bg-white rounded p-4">
  <?= CSRF::field() ?>
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">اسم المتبرع</label><input class="form-control" name="name" required></div>
    <div class="col-md-6"><label class="form-label">البريد</label><input type="email" class="form-control" name="email"></div>
    <div class="col-md-6"><label class="form-label">الهاتف</label><input class="form-control" name="phone"></div>
    <div class="col-md-6"><label class="form-label">المبلغ</label><input type="number" step="0.01" class="form-control" name="amount" required></div>
    <div class="col-md-6">
      <label class="form-label">طريقة الاستلام</label>
      <select class="form-select" name="payment_method">
        <option value="offline">تسجيل يدوي</option>
        <option value="cash">نقداً</option>
        <option value="bank_transfer">تحويل بنكي</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">المشروع</label>
      <select class="form-select" name="project_id">
        <option value="0">تبرع عام</option>
        <?php foreach ($projects as $p): ?>
          <option value="<?= (int)$p['id'] ?>"><?= e($p['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12"><label class="form-label">رسالة/ملاحظة (اختياري)</label><textarea class="form-control" name="message" rows="2"></textarea></div>
    <div class="col-12"><button class="btn btn-primary">تسجيل التبرع كمدفوع</button></div>
  </div>
</form>