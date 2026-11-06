<?php
// app/Views/admin/banks/index.php
use App\Core\CSRF; use App\Core\Session; ?>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:10px;margin-bottom:20px;font-weight:800;"><?= e($m) ?></div><?php endif; ?>

<h4 style="margin:0 0 25px 0; font-weight:900; color:var(--sidebar-bg);">إدارة الحسابات البنكية</h4>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:25px; align-items:start;">
  
  <!-- قائمة البنوك -->
  <div style="display:flex; flex-direction:column; gap:15px;">
    <?php foreach ($banks as $b): ?>
      <div class="admin-card" style="padding:20px; display:flex; justify-content:space-between; align-items:center; border-right:5px solid <?= $b['is_active']?'#10b981':'#cbd5e1' ?>;">
        <div>
          <div style="font-weight:900; color:var(--sidebar-bg); font-size:16px; margin-bottom:5px;"><i data-lucide="landmark" style="width:16px; color:var(--gold-hover); display:inline-block; margin-left:5px;"></i> <?= e($b['bank_name']) ?></div>
          <div style="font-size:13px; font-weight:700; color:var(--text-muted); margin-bottom:3px;"><?= e($b['account_holder']) ?></div>
          <div style="font-family:monospace; font-size:14px; font-weight:800; color:#092A9E; background:#EAF2FF; display:inline-block; padding:2px 8px; border-radius:4px;" dir="ltr"><?= e($b['iban'] ?: $b['account_number']) ?></div>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px; align-items:flex-end;">
          <span class="badge bg-<?= $b['is_active']?'success':'secondary' ?>"><?= $b['is_active']?'نشط':'معطل' ?></span>
          <form method="post" action="<?= e(url('admin/banks/delete/' . (int)$b['id'])) ?>" onsubmit="return confirm('حذف الحساب؟');">
            <?= CSRF::field() ?>
            <button class="btn btn-sm" style="background:#fee2e2; color:#991b1b; border-radius:8px;"><i data-lucide="trash-2" style="width:14px;"></i></button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if(empty($banks)): ?><div style="text-align:center; padding:30px; color:var(--text-muted); font-weight:700;">لا توجد حسابات مضافة بعد.</div><?php endif; ?>
  </div>

  <!-- إضافة بنك -->
  <div class="admin-card" style="position:sticky; top:100px;">
    <h6 style="font-weight:900; color:var(--sidebar-bg); margin-bottom:20px; font-size:16px;">إضافة حساب جديد</h6>
    <form method="post" action="<?= e(url('admin/banks/store')) ?>">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" value="0">
      
      <div style="margin-bottom:15px;">
        <label class="form-label">اسم البنك</label>
        <input class="form-control" name="bank_name" placeholder="مثال: بنك الرياض" required>
      </div>
      
      <div style="margin-bottom:15px;">
        <label class="form-label">اسم صاحب الحساب</label>
        <input class="form-control" name="account_holder" placeholder="جمعية نور..." required>
      </div>

      <div style="margin-bottom:15px;">
        <label class="form-label">رقم الآيبان (IBAN)</label>
        <input class="form-control" name="iban" placeholder="SA..." dir="ltr" style="text-align:left;">
      </div>

      <div style="margin-bottom:15px;">
        <label class="form-label">رقم الحساب</label>
        <input class="form-control" name="account_number" placeholder="..." dir="ltr" style="text-align:left;">
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label">تعليمات إضافية</label>
        <textarea class="form-control" name="instructions" rows="2" placeholder="يرجى إرسال صورة الإيصال..."></textarea>
      </div>

      <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
        <input type="checkbox" name="is_active" value="1" id="bank_active" style="width:20px; height:20px; accent-color:var(--gold);" checked>
        <label for="bank_active" style="font-weight:800; cursor:pointer;">نشط ومرئي في الموقع</label>
      </div>

      <button class="btn btn-primary" style="width:100%; padding:14px;"><i data-lucide="save" style="width:18px;"></i> حفظ الحساب</button>
    </form>
  </div>
</div>