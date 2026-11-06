<?php
// app/Views/admin/users/form.php
use App\Core\CSRF; use App\Core\Session; 
?>
<?php $isEdit = $user !== null; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;"><?= $isEdit ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد' ?></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تحديد الصلاحيات وإدارة الوصول للمنصة.</p>
  </div>
  <a href="<?= e(url('admin/users')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px; background: #fff;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للمستخدمين
  </a>
</div>

<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:30px; align-items:start;">
    
    <form method="post" action="<?= e(url($isEdit ? 'admin/users/' . (int)$user['id'] : 'admin/users/store')) ?>" class="admin-card" style="padding: 40px; border-radius: 20px;">
      <?= CSRF::field() ?>
      
      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:25px;">
        <div style="grid-column: 1 / -1;">
          <label class="form-label">الاسم بالكامل <span style="color:#ef4444;">*</span></label>
          <input class="form-control" name="name" value="<?= e($user['name'] ?? '') ?>" required style="font-size: 16px; font-weight: 800;">
        </div>
        
        <div>
          <label class="form-label">البريد الإلكتروني <span style="color:#ef4444;">*</span></label>
          <input type="email" class="form-control" name="email" value="<?= e($user['email'] ?? '') ?>" required dir="ltr" style="text-align:left; font-weight: 800;">
        </div>

        <div>
          <label class="form-label">الدور (الصلاحية) <span style="color:#ef4444;">*</span></label>
          <select class="form-select" name="role_id" required style="font-weight: 800;">
            <?php foreach ($roles as $r): ?>
              <option value="<?= (int)$r['id'] ?>" <?= ($user['role_id'] ?? 0)==(int)$r['id']?'selected':'' ?>><?= e($r['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="grid-column: 1 / -1;">
          <label class="form-label"><?= $isEdit ? 'كلمة مرور جديدة (اتركها فارغة للتجاهل)' : 'كلمة المرور الأساسية' ?> <?= !$isEdit ? '<span style="color:#ef4444;">*</span>' : '' ?></label>
          <div style="position: relative;">
              <input type="password" class="form-control" name="password" <?= $isEdit ? '' : 'required minlength=10' ?> placeholder="يجب أن تتكون من 10 أحرف على الأقل" dir="ltr" style="text-align:left;">
              <i data-lucide="lock" style="position: absolute; left: 15px; top: 15px; color: var(--text-muted); width: 20px;"></i>
          </div>
        </div>

        <div style="grid-column: 1 / -1; display:flex; align-items:center; gap:10px; margin-top:10px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
          <input type="checkbox" name="is_active" value="1" id="is_active" style="width:20px; height:20px; accent-color:var(--gold);" <?= ($user['is_active'] ?? 1)?'checked':'' ?>>
          <label for="is_active" style="font-weight:900; color:var(--primary); cursor:pointer; margin:0;">حساب نشط (مسموح له بتسجيل الدخول)</label>
        </div>
      </div>

      <div style="margin-top:40px; padding-top:25px; border-top:1px dashed #e2e8f0;">
        <button class="btn btn-primary" style="padding:16px 40px; font-size:16px; font-weight: 900; border-radius: 14px; box-shadow: 0 10px 20px rgba(6,27,82,0.2);">
            <i data-lucide="save" style="margin-right: 8px;"></i> <?= $isEdit ? 'حفظ التعديلات' : 'إنشاء الحساب' ?>
        </button>
      </div>
    </form>

    <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="admin-card" style="padding: 25px; border-radius: 16px; background: #f8fafc; border: 1px solid #e2e8f0;">
            <h6 style="font-weight:900; color:var(--primary); margin-bottom:15px; display:flex; align-items:center; gap:8px;"><i data-lucide="info" style="color:var(--gold);"></i> تعليمات الصلاحيات</h6>
            <ul style="padding-right: 15px; color: var(--text-main); font-size: 14px; line-height: 1.8;">
                <li><strong>Super Admin:</strong> وصول كامل لكافة خصائص النظام والإعدادات.</li>
                <li><strong>Viewer:</strong> مخصصة للمتبرعين المسجلين في الموقع، تقتصر صلاحياتهم على واجهة المستخدم (حسابي) ولا يمكنهم الدخول للإدارة.</li>
            </ul>
        </div>

        <?php if ($isEdit && $user['id'] !== \App\Core\Auth::id()): ?>
            <div class="admin-card" style="padding: 25px; border-radius: 16px; border:1px solid #fecaca; background: #fff5f5;">
                <h6 style="font-weight:900; color:#991b1b; margin-bottom:15px;">منطقة الخطر</h6>
                <p style="font-size: 13px; color: #7f1d1d; margin-bottom: 20px;">حذف المستخدم سيؤدي إلى إيقاف حسابه بشكل نهائي (Soft Delete) لحفظ السجلات المالية المرتبطة به.</p>
                <form method="post" action="<?= e(url('admin/users/' . (int)$user['id'] . '/delete')) ?>" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                <?= CSRF::field() ?>
                <button class="btn" style="background:#fee2e2; color:#ef4444; width:100%; padding:14px; font-weight:900; border-radius: 12px; border: 1px solid #fca5a5;">
                    <i data-lucide="trash-2" style="width:18px; margin-right: 8px;"></i> حذف الحساب
                </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>