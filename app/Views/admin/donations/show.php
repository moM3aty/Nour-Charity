<?php
// app/Views/admin/donations/show.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">تفاصيل التبرع: <code style="color:var(--primary-light); background: #EAF2FF; padding: 4px 10px; border-radius: 8px;">#<?= e($donation['donation_number']) ?></code></h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تاريخ العملية: <?= dt($donation['created_at'], 'Y-m-d H:i') ?></p>
  </div>
  <a href="<?= e(url('admin/donations')) ?>" class="btn btn-outline-primary" style="padding: 10px 20px; border-radius: 12px; background: #fff;">
      <i data-lucide="arrow-right" style="width:18px;"></i> العودة للسجل
  </a>
</div>

<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:30px; align-items:start;">
  
  <!-- Column 1: Transaction Data -->
  <div style="display:flex; flex-direction:column; gap:30px;">
    
    <div class="admin-card" style="padding: 30px; border-radius: 16px;">
      <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px; display: flex; align-items: center; gap: 10px;">
          <i data-lucide="file-text" style="color: var(--gold);"></i> بيانات العملية
      </h6>
      
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        <div style="background:#f8fafc; padding:20px; border-radius:16px; border: 1px solid #e2e8f0;">
          <div style="font-size:13px; color:var(--text-muted); font-weight:800; margin-bottom:8px;">المبلغ الإجمالي</div>
          <div style="font-size:28px; font-weight:900; color:var(--gold-hover);"><?= money((float)$donation['amount']) ?></div>
        </div>
        
        <div style="background:#f8fafc; padding:20px; border-radius:16px; border: 1px solid #e2e8f0;">
          <div style="font-size:13px; color:var(--text-muted); font-weight:800; margin-bottom:8px;">الحالة الحالية</div>
          <?php
            $stColors = [
                'paid' => 'background:#d1fae5; color:#065f46;',
                'awaiting_verification' => 'background:#fef3c7; color:#92400e; border: 1px solid #fde68a;',
                'pending' => 'background:#f1f5f9; color:#475569;',
            ];
            $sStyle = $stColors[$donation['status']] ?? 'background:#f1f5f9; color:#475569;';
          ?>
          <div style="margin-top: 10px;">
              <span style="padding: 8px 18px; border-radius: 20px; font-size: 14px; font-weight: 900; <?= $sStyle ?>"><?= e($donation['status'] === 'awaiting_verification' ? 'بانتظار التحقق' : $donation['status']) ?></span>
          </div>
        </div>
        
        <div style="background:#f8fafc; padding:20px; border-radius:16px; border: 1px solid #e2e8f0;">
          <div style="font-size:13px; color:var(--text-muted); font-weight:800; margin-bottom:8px;">التوجيه / المشروع</div>
          <div style="font-weight:900; color:var(--primary); font-size: 16px;"><?= e($project['title'] ?? 'تبرع عام / سلة متعددة') ?></div>
        </div>
        
        <div style="background:#f8fafc; padding:20px; border-radius:16px; border: 1px solid #e2e8f0;">
          <div style="font-size:13px; color:var(--text-muted); font-weight:800; margin-bottom:8px;">طريقة الدفع</div>
          <div style="font-weight:900; color:var(--primary); font-size: 16px;"><?= e($donation['payment_method'] === 'bank_transfer' ? 'تحويل بنكي' : $donation['payment_method']) ?></div>
        </div>
      </div>
    </div>

    <?php if ($items): ?>
      <div class="admin-card" style="padding: 30px; border-radius: 16px;">
        <h6 style="font-weight:900; color:var(--primary); margin-bottom:20px; font-size:18px; display: flex; align-items: center; gap: 10px;">
            <i data-lucide="layers" style="color: var(--gold);"></i> تفاصيل سلة التبرع
        </h6>
        <table class="table" style="margin: 0; background: #f8fafc; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
          <thead>
              <tr>
                  <th style="background: #e2e8f0; color: var(--primary);">المشروع المستهدف</th>
                  <th style="background: #e2e8f0; color: var(--primary);">المبلغ المخصص</th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $it): ?>
              <tr>
                  <td style="font-weight:800; color: var(--primary); padding: 15px; border-bottom: 1px solid #e2e8f0;"><?= e($it['project_title'] ?? 'تبرع عام') ?></td>
                  <td style="font-weight:900; color:var(--gold-hover); padding: 15px; border-bottom: 1px solid #e2e8f0;"><?= money((float)$it['amount']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <!-- Column 2: Donor & Actions -->
  <div style="display:flex; flex-direction:column; gap:30px;">
    
    <!-- Donor Info -->
    <div class="admin-card" style="padding: 30px; border-radius: 16px;">
      <h6 style="font-weight:900; color:var(--primary); margin-bottom:25px; font-size:18px;">بيانات المتبرع</h6>
      <?php if ($donor): ?>
        <div style="display:flex; align-items:center; gap:15px; margin-bottom:20px;">
          <div style="width:60px; height:60px; background:linear-gradient(135deg, var(--gold), var(--gold-hover)); color:var(--primary); border-radius:50%; display:flex; justify-content:center; align-items:center; font-size:24px; font-weight:900; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.3);">
            <?= mb_substr($donor['name'], 0, 1) ?>
          </div>
          <div>
            <div style="font-weight:900; color:var(--primary); font-size: 18px; margin-bottom: 4px;"><?= e($donor['name']) ?></div>
            <div style="font-size:14px; color:var(--text-muted); font-weight:700;"><?= e($donor['email']) ?></div>
          </div>
        </div>
        <div style="font-size:15px; font-weight:900; color:var(--primary); background:#f8fafc; padding:15px; border-radius:12px; text-align:center; border: 1px solid #e2e8f0; letter-spacing: 1px;" dir="ltr">
          <i data-lucide="phone" style="width:16px; display:inline-block; vertical-align:middle; margin-right:8px; color: var(--text-muted);"></i>
          <?= e($donor['phone'] ?: 'غير مسجل') ?>
        </div>
        
        <?php if ($donation['is_anonymous']): ?>
          <div style="margin-top: 15px; background: #fef3c7; color: #92400e; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 800; text-align: center;">
              <i data-lucide="eye-off" style="width: 14px; vertical-align: middle;"></i> المتبرع يرغب بعدم إظهار اسمه بالموقع
          </div>
        <?php endif; ?>
      <?php else: ?>
        <p style="color:var(--text-muted); font-weight:700; text-align: center; padding: 20px;">تبرع مجهول المصدر.</p>
      <?php endif; ?>
    </div>

    <!-- Receipt Image (If Bank Transfer) -->
    <?php if ($receipt): ?>
      <div class="admin-card" style="padding: 30px; border-radius: 16px; border:2px dashed #cbd5e1; background: #f8fafc;">
        <h6 style="font-weight:900; color:var(--primary); margin-bottom:15px; font-size:18px; text-align: center;">إيصال التحويل المرفق</h6>
        <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px; font-weight:700; text-align: center;">تاريخ الرفع: <?= dt($receipt['uploaded_at'], 'Y-m-d H:i') ?></p>
        
        <a href="<?= e(url('admin/receipts/' . (int)$receipt['id'] . '/view')) ?>" target="_blank" class="btn btn-outline-primary" style="width:100%; padding: 14px; border-radius: 12px; background: #fff; font-weight: 900; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <i data-lucide="file-image" style="width:18px; margin-left: 8px;"></i> فتح / تحميل الإيصال
        </a>
      </div>
    <?php endif; ?>

    <!-- Verification Actions -->
    <?php if (in_array($donation['status'], ['pending','awaiting_verification'], true)): ?>
      <div class="admin-card" style="background:var(--primary-grad); color:#fff; border-radius: 16px; padding: 30px; box-shadow: 0 15px 30px rgba(6,27,82,0.3);">
        <h6 style="font-weight:900; margin-bottom:20px; font-size:18px; color: var(--gold);">اتخاذ إجراء مالي</h6>
        
        <form method="post" action="<?= e(url('admin/donations/' . (int)$donation['id'] . '/verify')) ?>" onsubmit="return confirm('تأكيد اعتماد التبرع؟ سيتم تحديث عدادات المشروع تلقائياً.');" style="margin-bottom:15px;">
          <?= CSRF::field() ?>
          <input type="hidden" name="action" value="approve">
          <button class="btn" style="background:#10b981; color:#fff; width:100%; padding:15px; font-weight:900; font-size: 15px; border-radius: 12px; border: 1px solid #059669; box-shadow: 0 4px 15px rgba(16,185,129,0.3);">
              <i data-lucide="check-circle" style="width:20px; margin-left: 8px;"></i> اعتماد التبرع كمدفوع
          </button>
        </form>
        
        <div style="text-align:center; margin:15px 0; color:rgba(255,255,255,0.5); font-size:13px; font-weight:800;">أو</div>
        
        <form method="post" action="<?= e(url('admin/donations/' . (int)$donation['id'] . '/verify')) ?>" onsubmit="return confirm('تأكيد رفض التبرع؟');">
          <?= CSRF::field() ?>
          <input type="hidden" name="action" value="reject">
          <input class="form-control" name="reason" placeholder="سبب الرفض (مطلوب للتوثيق)" required style="background:rgba(255,255,255,0.1); border-color:rgba(255,255,255,0.2); color:#fff; margin-bottom:12px; padding: 14px; border-radius: 12px;">
          <button class="btn" style="background:#ef4444; color:#fff; width:100%; padding:15px; font-weight:900; font-size: 15px; border-radius: 12px; border: 1px solid #dc2626; box-shadow: 0 4px 15px rgba(239,68,68,0.3);">
              <i data-lucide="x-circle" style="width:20px; margin-left: 8px;"></i> رفض وإلغاء العملية
          </button>
        </form>
      </div>
    <?php endif; ?>

  </div>
</div>