<?php
// app/Views/admin/volunteers/show.php
use App\Core\CSRF; use App\Core\Session; ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);">تفاصيل المتطوع: <?= e($app['name']) ?></h4>
  <a href="<?= e(url('admin/volunteers')) ?>" class="btn btn-outline-primary"><i data-lucide="arrow-right" style="width:18px;"></i> عودة</a>
</div>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:10px;margin-bottom:20px;font-weight:800;"><?= e($m) ?></div><?php endif; ?>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; align-items:start;">
  <div class="admin-card">
    <h6 style="font-weight:900; color:var(--sidebar-bg); margin-bottom:20px; font-size:16px;">بيانات المتطوع</h6>
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:25px;">
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">البريد الإلكتروني</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($app['email']) ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">الهاتف</div>
        <div style="font-weight:800; color:var(--sidebar-bg);" dir="ltr"><?= e($app['phone']) ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">المدينة</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($app['city'] ?? '—') ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">الوقت المتاح</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($app['availability'] ?? '—') ?></div>
      </div>
    </div>

    <div style="margin-bottom:20px;">
      <h6 style="font-weight:800; color:var(--sidebar-bg); margin-bottom:10px;">المهارات والخبرات:</h6>
      <div style="background:#f8fafc; padding:15px; border-radius:12px; font-size:14px; color:var(--text-main); line-height:1.7;"><?= nl2br(e($app['skills'] ?: 'لم يذكر')) ?></div>
    </div>

    <div style="margin-bottom:20px;">
      <h6 style="font-weight:800; color:var(--sidebar-bg); margin-bottom:10px;">الاهتمامات:</h6>
      <div style="background:#f8fafc; padding:15px; border-radius:12px; font-size:14px; color:var(--text-main); line-height:1.7;"><?= nl2br(e($app['interests'] ?: 'لم يذكر')) ?></div>
    </div>

    <?php if ($app['cv_path']): ?>
      <a href="<?= e(url('admin/volunteers/' . (int)$app['id'] . '/cv')) ?>" class="btn btn-outline-primary"><i data-lucide="file-text" style="width:18px;"></i> تحميل السيرة الذاتية (CV)</a>
    <?php endif; ?>
  </div>

  <div class="admin-card" style="background:linear-gradient(135deg, #061B52, #092A9E); color:#fff;">
    <h6 style="font-weight:900; margin-bottom:20px; font-size:16px;">تحديث الحالة</h6>
    <form method="post" action="<?= e(url('admin/volunteers/' . (int)$app['id'] . '/status')) ?>">
      <?= CSRF::field() ?>
      
      <label class="form-label" style="color:rgba(255,255,255,0.7);">حالة الطلب</label>
      <select class="form-select" name="status" style="background:rgba(0,0,0,0.2); border-color:rgba(255,255,255,0.1); color:#fff; margin-bottom:15px;">
        <?php foreach (['new'=>'جديد','reviewing'=>'قيد المراجعة','accepted'=>'مقبول','rejected'=>'مرفوض'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $app['status']===$k?'selected':'' ?> style="color:#000;"><?= $v ?></option>
        <?php endforeach; ?>
      </select>

      <label class="form-label" style="color:rgba(255,255,255,0.7);">ملاحظات داخلية</label>
      <textarea class="form-control" name="notes" rows="4" style="background:rgba(0,0,0,0.2); border-color:rgba(255,255,255,0.1); color:#fff; margin-bottom:15px;"><?= e($app['internal_notes'] ?? '') ?></textarea>

      <button class="btn" style="background:var(--gold); color:var(--sidebar-bg); width:100%; padding:12px; font-weight:900;">تحديث الحالة</button>
    </form>
  </div>
</div>