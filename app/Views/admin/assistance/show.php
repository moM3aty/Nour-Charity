<?php
// app/Views/admin/assistance/show.php
use App\Core\CSRF; use App\Core\Session; ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);">طلب مساعدة: <?= e($req['request_number']) ?></h4>
  <a href="<?= e(url('admin/assistance')) ?>" class="btn btn-outline-primary"><i data-lucide="arrow-right" style="width:18px;"></i> عودة</a>
</div>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:10px;margin-bottom:20px;font-weight:800;"><?= e($m) ?></div><?php endif; ?>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; align-items:start;">
  <div class="admin-card">
    <h6 style="font-weight:900; color:var(--sidebar-bg); margin-bottom:20px; font-size:16px;">بيانات الحالة</h6>
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px; margin-bottom:25px;">
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">الاسم</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($req['full_name']) ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">الهاتف</div>
        <div style="font-weight:800; color:var(--sidebar-bg);" dir="ltr"><?= e($req['phone']) ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">نوع المساعدة</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($req['category'] ?? '—') ?></div>
      </div>
      <div style="background:#f8fafc; padding:15px; border-radius:12px;">
        <div style="font-size:12px; color:var(--text-muted); font-weight:700; margin-bottom:5px;">الموقع</div>
        <div style="font-weight:800; color:var(--sidebar-bg);"><?= e($req['location'] ?? '—') ?></div>
      </div>
    </div>

    <div style="margin-bottom:25px;">
      <h6 style="font-weight:800; color:var(--sidebar-bg); margin-bottom:10px;">وصف الحالة:</h6>
      <div style="background:#f8fafc; padding:15px; border-radius:12px; font-size:14px; color:var(--text-main); line-height:1.8;"><?= nl2br(e($req['description'] ?? '')) ?></div>
    </div>

    <?php if ($docs): ?>
      <div>
        <h6 style="font-weight:800; color:var(--sidebar-bg); margin-bottom:10px;">المستندات المرفقة:</h6>
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
          <?php foreach ($docs as $d): ?>
            <a href="<?= e(url('admin/assistance/documents/' . (int)$d['id'] . '/download')) ?>" class="btn btn-outline-primary btn-sm"><i data-lucide="paperclip" style="width:14px;"></i> <?= e($d['original_name'] ?: 'ملف') ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="admin-card" style="background:linear-gradient(135deg, #061B52, #092A9E); color:#fff;">
    <h6 style="font-weight:900; margin-bottom:20px; font-size:16px;">تحديث الحالة</h6>
    <form method="post" action="<?= e(url('admin/assistance/' . (int)$req['id'] . '/status')) ?>">
      <?= CSRF::field() ?>
      
      <label class="form-label" style="color:rgba(255,255,255,0.7);">حالة الطلب</label>
      <select class="form-select" name="status" style="background:rgba(0,0,0,0.2); border-color:rgba(255,255,255,0.1); color:#fff; margin-bottom:15px;">
        <?php foreach (['new'=>'جديد','reviewing'=>'قيد المراجعة','approved'=>'معتمد','rejected'=>'مرفوض','completed'=>'مكتمل'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $req['status']===$k?'selected':'' ?> style="color:#000;"><?= $v ?></option>
        <?php endforeach; ?>
      </select>

      <label class="form-label" style="color:rgba(255,255,255,0.7);">ملاحظات داخلية (سرية)</label>
      <textarea class="form-control" name="notes" rows="5" style="background:rgba(0,0,0,0.2); border-color:rgba(255,255,255,0.1); color:#fff; margin-bottom:15px;"><?= e($req['internal_notes'] ?? '') ?></textarea>

      <button class="btn" style="background:var(--gold); color:var(--sidebar-bg); width:100%; padding:12px; font-weight:900;">حفظ التحديث</button>
    </form>
  </div>
</div>