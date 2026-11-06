<?php
// app/Views/admin/assistance/index.php
use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">طلبات المساعدة</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة ومراجعة الطلبات الواردة من المستفيدين والمحتاجين.</p>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px; border-radius: 16px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
    <div style="flex:2; min-width:200px;">
      <label class="form-label" style="font-size:13px;">بحث بالاسم أو رقم الطلب</label>
      <input class="form-control" name="q" placeholder="ابحث..." value="<?= e($filters['q']) ?>">
    </div>
    <div style="flex:1; min-width:150px;">
      <label class="form-label" style="font-size:13px;">حالة الطلب</label>
      <select class="form-select" name="status">
        <option value="">كل الحالات</option>
        <?php foreach (['new'=>'جديد','reviewing'=>'قيد المراجعة','approved'=>'معتمد','rejected'=>'مرفوض','completed'=>'مكتمل ومصروف'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $filters['status']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-outline-primary" style="height:48px; padding:0 30px; border-radius: 12px;">تصفية البيانات</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">رقم الطلب</th>
        <th>مقدم الطلب</th>
        <th>نوع المساعدة</th>
        <th>الحالة</th>
        <th>تاريخ الطلب</th>
        <th style="text-align:left; padding-left:25px;">إجراء</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['items'] as $r): ?>
        <tr style="border-bottom: 1px solid #f1f5f9; <?= $r['status']==='new'?'background:#f0fdf4;':'' ?>">
          <td style="padding:20px 25px;">
              <code style="font-weight:900; color:var(--primary-light); background:#EAF2FF; padding:6px 10px; border-radius:8px; font-size: 14px;"><?= e($r['request_number']) ?></code>
          </td>
          <td>
            <strong style="display:block; color:var(--primary); font-size:15px; margin-bottom: 2px;"><?= e($r['full_name']) ?></strong>
            <span style="font-size:13px; color:var(--text-muted); font-weight:700;" dir="ltr"><?= e($r['phone']) ?></span>
          </td>
          <td>
              <span style="background: #f1f5f9; color: var(--text-main); padding: 4px 10px; border-radius: 8px; font-size: 13px; font-weight: 800;">
                  <?= e($r['category'] ?? 'عام') ?>
              </span>
          </td>
          <td>
              <?php
                  $stColors = ['approved' => 'background:#d1fae5; color:#065f46;', 'rejected' => 'background:#fee2e2; color:#991b1b;', 'reviewing' => 'background:#fef3c7; color:#92400e;', 'new' => 'background:#dbeafe; color:#1e40af;', 'completed' => 'background:#e0e7ff; color:#3730a3;'];
                  $sStyle = $stColors[$r['status']] ?? 'background:#f1f5f9; color:#475569;';
                  $labels = ['new'=>'جديد','reviewing'=>'قيد المراجعة','approved'=>'معتمد','rejected'=>'مرفوض','completed'=>'مكتمل'];
              ?>
              <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $sStyle ?>"><?= $labels[$r['status']] ?></span>
          </td>
          <td><span style="font-size:13px; font-weight:700; color:var(--text-muted);"><?= dt($r['created_at'], 'Y-m-d') ?></span></td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/assistance/' . (int)$r['id'])) ?>" class="btn btn-outline-primary btn-sm" style="border-radius: 8px;"><i data-lucide="eye" style="width:16px;"></i> فتح</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($data['items'])): ?><tr><td colspan="6" style="text-align:center; padding: 40px;">لا توجد طلبات.</td></tr><?php endif; ?>
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