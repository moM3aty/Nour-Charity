<?php
// app/Views/admin/volunteers/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">طلبات التطوع</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">مراجعة طلبات الانضمام للفريق التطوعي.</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="<?= e(url('admin/volunteers/opportunities')) ?>" class="btn btn-outline-primary" style="background: #fff; border-radius: 12px;">
        <i data-lucide="briefcase" style="width:18px;"></i> إدارة الفرص التطوعية
    </a>
    <a href="<?= e(url('admin/volunteers/export')) ?>" class="btn btn-primary" style="border-radius: 12px;">
        <i data-lucide="download" style="width:18px;"></i> تصدير (CSV)
    </a>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px; border-radius: 16px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
    <div style="flex:2; min-width:200px;">
      <label class="form-label" style="font-size:13px;">بحث بالاسم أو الهاتف</label>
      <input class="form-control" name="q" placeholder="ابحث..." value="<?= e($filters['q']) ?>">
    </div>
    <div style="flex:1; min-width:150px;">
      <label class="form-label" style="font-size:13px;">حالة الطلب</label>
      <select class="form-select" name="status">
        <option value="">كل الحالات</option>
        <?php foreach (['new'=>'جديد','reviewing'=>'قيد المراجعة','accepted'=>'مقبول','rejected'=>'مرفوض'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $filters['status']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-outline-primary" style="height:48px; padding:0 30px; border-radius: 12px;">تصفية</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">المتطوع</th>
        <th>معلومات الاتصال</th>
        <th>المدينة</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th style="text-align:left; padding-left:25px;">إجراء</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['items'] as $a): ?>
        <tr style="border-bottom: 1px solid #f1f5f9; <?= $a['status']==='new'?'background:#f0fdf4;':'' ?>">
          <td style="padding:20px 25px;">
              <strong style="color:var(--primary); font-size: 15px;"><?= e($a['name']) ?></strong>
          </td>
          <td>
            <div style="font-size:14px; font-weight:800; color:var(--primary-light);" dir="ltr"><?= e($a['phone']) ?></div>
            <div style="font-size:12px; color:var(--text-muted);"><?= e($a['email']) ?></div>
          </td>
          <td><span class="badge bg-secondary"><?= e($a['city'] ?? '—') ?></span></td>
          <td>
              <?php
                  $stColors = ['accepted' => 'background:#d1fae5; color:#065f46;', 'rejected' => 'background:#fee2e2; color:#991b1b;', 'reviewing' => 'background:#fef3c7; color:#92400e;', 'new' => 'background:#dbeafe; color:#1e40af;'];
                  $sStyle = $stColors[$a['status']] ?? 'background:#f1f5f9; color:#475569;';
              ?>
              <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $sStyle ?>"><?= e($a['status']) ?></span>
          </td>
          <td><span style="font-size:13px; font-weight:700; color:var(--text-muted);"><?= dt($a['created_at'], 'Y-m-d') ?></span></td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/volunteers/' . (int)$a['id'])) ?>" class="btn btn-primary btn-sm" style="border-radius: 8px;">التفاصيل</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($data['items'])): ?><tr><td colspan="6" style="text-align:center; padding: 40px;">لا توجد طلبات.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>