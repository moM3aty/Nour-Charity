<?php
// app/Views/admin/donations/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">سجل التبرعات</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">متابعة ومراجعة كافة العمليات المالية والتحويلات البنكية.</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="<?= e(url('admin/donations/offline')) ?>" class="btn btn-outline-primary" style="border-radius: 10px; background: #fff;"><i data-lucide="plus-circle" style="width:18px;"></i> تسجيل تبرع يدوي</a>
    <a href="<?= e(url('admin/donations/export')) ?>" class="btn btn-primary" style="border-radius: 10px; box-shadow: 0 5px 15px rgba(6,27,82,0.2);"><i data-lucide="download" style="width:18px;"></i> تصدير CSV</a>
  </div>
</div>

<!-- KPIs -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:25px;">
  <div class="kpi kpi-gradient" style="border-radius: 16px;">
    <h6 style="font-size: 15px; color: rgba(255,255,255,0.8);">إجمالي التبرعات المدفوعة</h6>
    <div class="num" style="color: var(--gold); font-size: 32px; margin-top: 10px;"><?= money($summary['paid_total']) ?></div>
    <div style="font-size:13px; color:rgba(255,255,255,0.9); margin-top:10px; font-weight: 700;">عدد العمليات الناجحة: <?= (int)$summary['paid_count'] ?></div>
  </div>
  <div class="kpi" style="border-right:5px solid #d97706; border-radius: 16px; background: #fff;">
    <h6 style="font-size: 15px;">بانتظار التحقق (حوالات)</h6>
    <div class="num" style="color:#d97706; font-size: 32px; margin-top: 10px;"><?= money($summary['pending_total']) ?></div>
    <div style="font-size:13px; color:var(--text-muted); margin-top:10px; font-weight: 700;">عدد العمليات المعلقة: <?= (int)$summary['pending_count'] ?></div>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px; border-radius: 16px;">
  <form method="get" style="display:flex; flex-wrap:wrap; gap:15px; align-items:flex-end;">
    <div style="flex: 1; min-width: 200px;">
      <label class="form-label" style="font-size:13px;">بحث بالرقم أو الاسم</label>
      <input class="form-control" name="q" placeholder="ابحث..." value="<?= e($filters['q']) ?>">
    </div>
    <div style="flex: 1; min-width: 150px;">
      <label class="form-label" style="font-size:13px;">الحالة</label>
      <select class="form-select" name="status">
        <option value="">كل الحالات</option>
        <?php foreach (['pending'=>'معلق','awaiting_verification'=>'بانتظار التحقق','paid'=>'مدفوع','failed'=>'فشل','cancelled'=>'ملغي'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $filters['status']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="flex: 1; min-width: 180px;">
      <label class="form-label" style="font-size:13px;">المشروع</label>
      <select class="form-select" name="project_id">
        <option value="">الكل</option>
        <?php foreach ($projects as $p): ?>
          <option value="<?= (int)$p['id'] ?>" <?= $filters['project_id']===(int)$p['id']?'selected':'' ?>><?= e($p['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="flex: 1; min-width: 140px;">
      <label class="form-label" style="font-size:13px;">من تاريخ</label>
      <input type="date" class="form-control" name="from" value="<?= e($filters['from']) ?>">
    </div>
    <div style="flex: 1; min-width: 140px;">
      <label class="form-label" style="font-size:13px;">إلى تاريخ</label>
      <input type="date" class="form-control" name="to" value="<?= e($filters['to']) ?>">
    </div>
    <div>
      <button class="btn btn-primary" style="height:48px; padding:0 30px; border-radius: 12px;">تصفية</button>
    </div>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">رقم العملية</th>
        <th>المتبرع</th>
        <th>التوجيه (المشروع)</th>
        <th>المبلغ</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th style="text-align:left; padding-left:25px;">إجراء</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['items'] as $d): ?>
        <tr style="border-bottom: 1px solid #f1f5f9; <?= $d['status']==='awaiting_verification' ? 'background: #fffbeb;' : '' ?>">
          <td style="padding:20px 25px;">
              <code style="color:var(--primary-light); font-weight:900; font-size:14px; background:#EAF2FF; padding:6px 10px; border-radius:8px;">#<?= e($d['donation_number']) ?></code>
          </td>
          <td>
            <strong style="display:block; font-size:14px; color:var(--primary); margin-bottom: 2px;"><?= e($d['donor_name'] ?? 'فاعل خير') ?></strong>
            <small style="color:var(--text-muted); font-weight: 600;"><?= e($d['donor_email'] ?? '') ?></small>
          </td>
          <td style="font-weight: 700; color: var(--text-main); font-size: 14px;">
              <?= e($d['project_title'] ?? 'تبرع عام / سلة') ?>
          </td>
          <td><strong style="color:var(--gold-hover); font-size:17px;"><?= money((float)$d['amount']) ?></strong></td>
          <td>
              <?php
                  $stColors = [
                      'paid' => 'background:#d1fae5; color:#065f46;',
                      'awaiting_verification' => 'background:#fef3c7; color:#92400e; border: 1px solid #fde68a;',
                      'pending' => 'background:#f1f5f9; color:#475569;',
                      'failed' => 'background:#fee2e2; color:#991b1b;',
                  ];
                  $sStyle = $stColors[$d['status']] ?? 'background:#f1f5f9; color:#475569;';
              ?>
              <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $sStyle ?>">
                  <?= e($d['status'] === 'awaiting_verification' ? 'بانتظار التحقق' : $d['status']) ?>
              </span>
          </td>
          <td><span style="font-size:13px; color:var(--text-muted); font-weight:700;"><?= dt($d['created_at'], 'Y-m-d H:i') ?></span></td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/donations/' . (int)$d['id'])) ?>" class="btn btn-outline-primary btn-sm" style="padding: 8px 16px; border-radius: 8px;">
                <i data-lucide="eye" style="width:16px; margin-right: 4px;"></i> التفاصيل
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($data['items'])): ?>
        <tr><td colspan="7" style="text-align:center; padding:60px 20px; color:var(--text-muted);">
            <i data-lucide="inbox" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 15px;"></i><br>
            لا توجد تبرعات مسجلة مطابقة للبحث.
        </td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  
  <?php if (($data['pages'] ?? 0) > 1): ?>
    <div style="padding:20px; display:flex; gap:8px; justify-content:center; border-top:1px solid #f1f5f9; background: #f8fafc;">
      <?php for ($i=1;$i<=$data['pages'];$i++): ?>
        <a href="?page=<?= $i ?>&<?= http_build_query(array_filter($filters)) ?>" 
           class="btn <?= $i===($data['page']??1)?'btn-primary':'btn-outline-primary' ?> btn-sm" 
           style="width:36px; height:36px; padding:0; justify-content:center; font-size: 14px; border-radius: 10px;"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>