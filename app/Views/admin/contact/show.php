<?php
// app/Views/admin/contact/show.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تفاصيل الرسالة</h4>
  </div>
  <a href="<?= e(url('admin/contact')) ?>" class="btn btn-outline-primary" style="border-radius: 12px; background: #fff;"><i data-lucide="arrow-right" style="width:18px;"></i> العودة للبريد</a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div style="background:#d1fae5; color:#065f46; padding:15px; border-radius:12px; font-weight:800; margin-bottom:20px;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:30px; align-items:start;">
  <div class="admin-card" style="padding: 40px; border-radius: 20px;">
    <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px dashed #e2e8f0;">
        <h3 style="color: var(--primary); font-weight: 900; margin-bottom: 15px;"><?= e($msg['subject'] ?? '(بدون موضوع)') ?></h3>
        <div style="display: flex; gap: 20px; color: var(--text-muted); font-size: 14px; font-weight: 700;">
            <span><i data-lucide="user" style="width: 16px; vertical-align: middle;"></i> <?= e($msg['name']) ?></span>
            <span dir="ltr"><i data-lucide="mail" style="width: 16px; vertical-align: middle;"></i> <?= e($msg['email']) ?></span>
            <?php if ($msg['phone']): ?><span dir="ltr"><i data-lucide="phone" style="width: 16px; vertical-align: middle;"></i> <?= e($msg['phone']) ?></span><?php endif; ?>
        </div>
    </div>
    
    <div style="background: #f8fafc; padding: 30px; border-radius: 16px; font-size: 16px; line-height: 2; color: var(--text-main); font-weight: 600; white-space: pre-wrap; border: 1px solid #e2e8f0;"><?= e($msg['message']) ?></div>
  </div>
  
  <div class="admin-card" style="padding: 30px; border-radius: 20px;">
    <h6 style="font-weight: 900; color: var(--primary); margin-bottom: 20px;"><i data-lucide="settings" style="color: var(--gold);"></i> الإجراءات</h6>
    
    <form method="post" action="<?= e(url('admin/contact/' . (int)$msg['id'] . '/status')) ?>" style="margin-bottom: 25px;">
      <?= CSRF::field() ?>
      <label class="form-label">تحديث حالة الرسالة</label>
      <select class="form-select" name="status" style="margin-bottom: 15px; font-weight: 800;">
        <?php foreach (['new'=>'جديدة','read'=>'مقروءة','replied'=>'تم الرد','archived'=>'مؤرشفة'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $msg['status']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary" style="width: 100%; border-radius: 12px; padding: 12px; font-weight: 900;">تحديث الحالة</button>
    </form>
    
    <hr style="border:0; border-top: 1px solid #e2e8f0; margin: 25px 0;">
    
    <a href="mailto:<?= e($msg['email']) ?>" class="btn btn-outline-primary" style="width: 100%; border-radius: 12px; padding: 14px; font-weight: 900; display: flex; justify-content: center; align-items: center; gap: 10px;">
        <i data-lucide="send"></i> رد عبر الإيميل
    </a>
  </div>
</div>