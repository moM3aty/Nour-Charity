<?php
// app/Views/admin/banks/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<style>
  .bank-layout { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; align-items: start; }
  @media (max-width: 991px) { .bank-layout { grid-template-columns: 1fr; } }
  .bank-card { transition: all 0.3s ease; border: 2px solid transparent; border-right-width: 5px; }
  .bank-card-active { border-color: var(--gold) !important; border-right-color: var(--gold) !important; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(244, 201, 120, 0.15) !important; }
</style>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">إدارة الحسابات البنكية</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">أضف وعدل حسابات الجمعية التي ستظهر للمتبرعين.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="resetForm()" style="border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2);">
      <i data-lucide="plus" style="width:18px;"></i> إضافة حساب جديد
  </button>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div class="bank-layout">
  
  <!-- قائمة البنوك -->
  <div style="display:flex; flex-direction:column; gap:20px;">
    <?php foreach ($banks as $b): ?>
      <div class="admin-card bank-card" id="bank-card-<?= $b['id'] ?>" style="padding: 25px; border-radius: 16px; border-right-color: <?= $b['is_active'] ? '#10b981' : '#cbd5e1' ?>; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
            
            <div style="flex-grow: 1;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <i data-lucide="landmark" style="width:20px; color:var(--gold);"></i>
                    <h5 style="margin: 0; font-weight: 900; color: var(--sidebar-bg); font-size: 18px;"><?= e($b['bank_name']) ?></h5>
                    <span style="font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 20px; <?= $b['is_active'] ? 'background:#d1fae5; color:#065f46;' : 'background:#f1f5f9; color:#475569;' ?>">
                        <?= $b['is_active'] ? 'نشط ومرئي' : 'معطل' ?>
                    </span>
                </div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-muted); margin-bottom: 15px;">
                    <i data-lucide="user" style="width: 14px; vertical-align: middle;"></i> المالك: <?= e($b['account_holder']) ?>
                </div>
                
                <div style="display: grid; gap: 8px;">
                    <?php if($b['iban']): ?>
                    <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <span style="font-size: 12px; font-weight: 800; color: #64748b; width: 50px;">IBAN:</span>
                        <span style="font-family: monospace; font-size: 14px; font-weight: 900; color: #092A9E;" dir="ltr"><?= e($b['iban']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($b['account_number']): ?>
                    <div style="display: flex; align-items: center; gap: 10px; background: #f8fafc; padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <span style="font-size: 12px; font-weight: 800; color: #64748b; width: 50px;">رقم الحساب:</span>
                        <span style="font-family: monospace; font-size: 14px; font-weight: 900; color: var(--primary);" dir="ltr"><?= e($b['account_number']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex; gap:10px; align-items: center;">
              <button type="button" class="btn btn-outline-primary btn-sm" style="border-radius: 8px; font-weight: 800; padding: 8px 15px;" onclick='editBank(<?= json_encode($b, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                  <i data-lucide="edit" style="width:14px; margin-left: 4px;"></i> تعديل
              </button>
              <form method="post" action="<?= e(url('admin/banks/delete/' . (int)$b['id'])) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحساب؟');" style="margin:0;">
                <?= CSRF::field() ?>
                <button class="btn btn-sm" style="background:#fee2e2; color:#ef4444; border-radius:8px; padding: 8px 12px;"><i data-lucide="trash-2" style="width:16px;"></i></button>
              </form>
            </div>

        </div>
      </div>
    <?php endforeach; ?>
    
    <?php if(empty($banks)): ?>
        <div class="admin-card" style="text-align:center; padding:50px 20px; border: 2px dashed #cbd5e1;">
            <i data-lucide="landmark" style="width: 48px; height: 48px; color: var(--text-muted); opacity: 0.5; margin-bottom: 15px;"></i>
            <h6 style="font-weight: 900; color: var(--primary);">لا توجد حسابات بنكية</h6>
            <p style="color:var(--text-muted); font-size: 14px;">ابدأ بإضافة الحسابات البنكية لاستقبال التحويلات.</p>
        </div>
    <?php endif; ?>
  </div>

  <!-- نموذج الإضافة / التعديل الذكي -->
  <div class="admin-card" id="formPanel" style="position:sticky; top:100px; padding: 30px; border-radius: 16px; transition: 0.3s; border: 2px solid transparent;">
    <h6 id="formTitle" style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="plus-circle" style="color: var(--gold);" id="formIcon"></i> <span>إضافة حساب جديد</span>
    </h6>
    
    <form method="post" action="<?= e(url('admin/banks/store')) ?>" id="bankForm">
      <?= CSRF::field() ?>
      <input type="hidden" name="id" id="bankId" value="0">
      
      <div style="margin-bottom:15px;">
        <label class="form-label" style="font-size: 13px;">اسم البنك <span style="color:#ef4444;">*</span></label>
        <input class="form-control" name="bank_name" id="bankName" placeholder="مثال: بنك الرياض" required style="font-weight: 800;">
      </div>
      
      <div style="margin-bottom:15px;">
        <label class="form-label" style="font-size: 13px;">اسم صاحب الحساب <span style="color:#ef4444;">*</span></label>
        <input class="form-control" name="account_holder" id="accountHolder" placeholder="مثال: جمعية نور للأعمال الخيرية" required>
      </div>

      <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
          <div style="margin-bottom:15px;">
            <label class="form-label" style="font-size: 13px; color: var(--primary);">رقم الآيبان (IBAN)</label>
            <input class="form-control" name="iban" id="bankIban" placeholder="SA..." dir="ltr" style="text-align:left; font-family: monospace; font-weight: 800; font-size: 15px;">
          </div>

          <div style="margin-bottom:0;">
            <label class="form-label" style="font-size: 13px; color: var(--primary);">رقم الحساب العادي</label>
            <input class="form-control" name="account_number" id="bankAccountNumber" placeholder="123456789..." dir="ltr" style="text-align:left; font-family: monospace; font-weight: 800; font-size: 15px;">
          </div>
      </div>

      <div style="margin-bottom:20px;">
        <label class="form-label" style="font-size: 13px;">تعليمات إضافية (اختياري)</label>
        <textarea class="form-control" name="instructions" id="bankInstructions" rows="2" placeholder="مثال: يرجى كتابة رقم الجوال في ملاحظات التحويل..."></textarea>
      </div>

      <div style="display:flex; align-items:center; gap:10px; margin-bottom:25px; background: #fff; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <input type="checkbox" name="is_active" id="bankActive" value="1" style="width:18px; height:18px; accent-color:var(--gold);" checked>
        <label for="bankActive" style="font-weight:800; cursor:pointer; color: var(--primary); margin:0;">تفعيل وعرض الحساب للمتبرعين</label>
      </div>

      <div style="display: flex; gap: 10px;">
          <button type="submit" id="btnSubmit" class="btn btn-primary" style="flex:1; padding:14px; border-radius: 12px; font-weight: 900; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
              <i data-lucide="save" style="margin-right: 8px;"></i> حفظ الحساب
          </button>
          <button type="button" id="btnCancel" class="btn btn-outline-primary" style="display:none; padding:14px; border-radius: 12px; font-weight: 900;" onclick="resetForm()">
              إلغاء
          </button>
      </div>
    </form>
  </div>
</div>

<script>
function editBank(bank) {
    // إزالة التمييز عن كل البطاقات
    document.querySelectorAll('.bank-card').forEach(el => el.classList.remove('bank-card-active'));
    
    // تمييز البطاقة المحددة
    const activeCard = document.getElementById('bank-card-' + bank.id);
    if (activeCard) activeCard.classList.add('bank-card-active');

    // نقل البيانات للنموذج
    document.getElementById('bankId').value = bank.id;
    document.getElementById('bankName').value = bank.bank_name;
    document.getElementById('accountHolder').value = bank.account_holder;
    document.getElementById('bankIban').value = bank.iban || '';
    document.getElementById('bankAccountNumber').value = bank.account_number || '';
    document.getElementById('bankInstructions').value = bank.instructions || '';
    document.getElementById('bankActive').checked = bank.is_active == 1;

    // تعديل واجهة النموذج
    document.querySelector('#formTitle span').innerText = 'تعديل بيانات الحساب';
    document.getElementById('formIcon').setAttribute('data-lucide', 'edit-3');
    
    const formPanel = document.getElementById('formPanel');
    formPanel.style.borderColor = 'var(--gold)';
    formPanel.style.boxShadow = '0 15px 40px rgba(244, 201, 120, 0.15)';
    
    document.getElementById('btnSubmit').innerHTML = '<i data-lucide="edit" style="margin-right: 8px; display:inline-block; vertical-align:middle;"></i> تحديث البيانات';
    document.getElementById('btnCancel').style.display = 'block';
    
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // الصعود لأعلى النموذج في الشاشات الصغيرة
    if(window.innerWidth < 991) {
        formPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function resetForm() {
    // إزالة التمييز عن البطاقات
    document.querySelectorAll('.bank-card').forEach(el => el.classList.remove('bank-card-active'));
    
    // تصفير النموذج
    document.getElementById('bankForm').reset();
    document.getElementById('bankId').value = '0';
    document.getElementById('bankActive').checked = true;
    
    // استعادة شكل النموذج
    document.querySelector('#formTitle span').innerText = 'إضافة حساب جديد';
    document.getElementById('formIcon').setAttribute('data-lucide', 'plus-circle');
    
    const formPanel = document.getElementById('formPanel');
    formPanel.style.borderColor = 'transparent';
    formPanel.style.boxShadow = 'var(--card-shadow)';
    
    document.getElementById('btnSubmit').innerHTML = '<i data-lucide="save" style="margin-right: 8px; display:inline-block; vertical-align:middle;"></i> حفظ الحساب';
    document.getElementById('btnCancel').style.display = 'none';
    
    if (typeof lucide !== 'undefined') lucide.createIcons();
}
</script>