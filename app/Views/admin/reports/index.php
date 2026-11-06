<?php
// app/Views/admin/reports/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">التقارير المالية</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">تحليل شامل للتبرعات والمداخيل خلال فترة محددة.</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="<?= e(url('admin/reports/print?' . http_build_query(['from'=>$from,'to'=>$to]))) ?>" target="_blank" class="btn btn-outline-primary" style="background: #fff; border-radius: 12px;">
        <i data-lucide="printer" style="width:18px;"></i> طباعة التقرير
    </a>
    <a href="<?= e(url('admin/reports/export?' . http_build_query(['from'=>$from,'to'=>$to]))) ?>" class="btn btn-primary" style="border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2);">
        <i data-lucide="download" style="width:18px;"></i> تصدير (CSV)
    </a>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px; border-radius: 16px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
    <div style="flex:1; min-width:200px;">
      <label class="form-label" style="font-size:13px;">من تاريخ</label>
      <input type="date" class="form-control" name="from" value="<?= e($from) ?>">
    </div>
    <div style="flex:1; min-width:200px;">
      <label class="form-label" style="font-size:13px;">إلى تاريخ</label>
      <input type="date" class="form-control" name="to" value="<?= e($to) ?>">
    </div>
    <button class="btn btn-primary" style="height:48px; padding:0 30px; border-radius: 12px;">تحديث التقرير</button>
  </form>
</div>

<!-- KPIs -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px; margin-bottom:30px;">
  <div class="kpi kpi-gradient" style="border-radius: 16px;">
    <h6 style="color: rgba(255,255,255,0.8);">إجمالي التبرعات (المدفوعة)</h6>
    <div class="num" style="color: var(--gold); font-size: 32px; margin-top: 10px;"><?= money((float)$summary['total']) ?></div>
  </div>
  <div class="kpi" style="border-right:5px solid #10b981; border-radius: 16px; background: #fff;">
    <h6>إجمالي عدد العمليات الناجحة</h6>
    <div class="num" style="color:#10b981; font-size: 32px; margin-top: 10px;"><?= (int)$summary['count'] ?> <span style="font-size: 14px; color: var(--text-muted);">عملية</span></div>
  </div>
  <div class="kpi" style="border-right:5px solid #092A9E; border-radius: 16px; background: #fff;">
    <h6>متوسط قيمة التبرع الواحد</h6>
    <div class="num" style="color:#092A9E; font-size: 32px; margin-top: 10px;"><?= money((float)$summary['avg']) ?></div>
  </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
  <!-- حسب المشروع -->
  <div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
    <div style="padding: 20px 25px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
        <h6 style="font-weight:900; color:var(--primary); margin:0;">تحليل التبرعات حسب المشروع</h6>
    </div>
    <table class="table" style="margin:0;">
      <thead>
        <tr><th style="padding-right: 25px;">المشروع</th><th>عدد العمليات</th><th>الإجمالي</th></tr>
      </thead>
      <tbody>
        <?php foreach ($byProject as$p): ?>
          <tr>
            <td style="padding-right: 25px; font-weight: 800; color: var(--primary);"><?= e($p['title']) ?></td>
            <td><span class="badge bg-secondary"><?= (int)$p['donations_count'] ?></span></td>
            <td style="font-weight: 900; color: var(--gold-hover);"><?= money((float)$p['total']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if(empty($byProject)): ?><tr><td colspan="3" style="text-align:center; padding: 20px;">لا توجد بيانات</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- حسب الحالة -->
  <div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
    <div style="padding: 20px 25px; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
        <h6 style="font-weight:900; color:var(--primary); margin:0;">تحليل العمليات حسب الحالة</h6>
    </div>
    <table class="table" style="margin:0;">
      <thead>
        <tr><th style="padding-right: 25px;">الحالة</th><th>العدد</th><th>الإجمالي المالي</th></tr>
      </thead>
      <tbody>
        <?php foreach ($byStatus as$s): ?>
          <tr>
            <td style="padding-right: 25px;">
                <span style="padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 800; background: #f1f5f9; color: var(--primary);"><?= e($s['status']) ?></span>
            </td>
            <td><span class="badge bg-secondary"><?= (int)$s['count'] ?></span></td>
            <td style="font-weight: 900; color: var(--primary-light);"><?= money((float)$s['total']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if(empty($byStatus)): ?><tr><td colspan="3" style="text-align:center; padding: 20px;">لا توجد بيانات</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>