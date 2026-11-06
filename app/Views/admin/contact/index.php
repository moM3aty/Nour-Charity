<?php
// app/Views/admin/contact/index.php
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">البريد الوارد</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">رسائل واستفسارات زوار الموقع.</p>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px; border-radius: 16px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
    <div style="flex:2; min-width:200px;">
      <label class="form-label" style="font-size:13px;">بحث بالاسم أو البريد</label>
      <input class="form-control" name="q" placeholder="ابحث..." value="<?= e($filters['q']) ?>">
    </div>
    <div style="flex:1; min-width:150px;">
      <label class="form-label" style="font-size:13px;">حالة الرسالة</label>
      <select class="form-select" name="status">
        <option value="">الكل</option>
        <?php foreach (['new'=>'جديدة','read'=>'مقروءة','replied'=>'تم الرد','archived'=>'مؤرشفة'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $filters['status']===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-outline-primary" style="height:48px; padding:0 30px; border-radius: 12px;">بحث</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden; border-radius: 16px;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px 25px;">المرسل</th>
        <th>الموضوع</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th style="text-align:left; padding-left:25px;">إجراء</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['items'] as $m): ?>
        <tr style="border-bottom: 1px solid #f1f5f9; <?= $m['status']==='new'?'background:#fef2f2;':'' ?>">
          <td style="padding:20px 25px;">
            <strong style="display:block; color:var(--primary); font-size:15px; margin-bottom: 2px; font-weight: 900;"><?= e($m['name']) ?></strong>
            <span style="font-size:13px; color:var(--text-muted); font-weight:700;" dir="ltr"><?= e($m['email']) ?></span>
          </td>
          <td style="font-weight: 800; color: var(--text-main); font-size: 14px; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
              <?= e($m['subject'] ?? 'بدون موضوع') ?>
          </td>
          <td>
              <?php
                  $stColors = ['new' => 'background:#fee2e2; color:#991b1b;', 'replied' => 'background:#d1fae5; color:#065f46;', 'read' => 'background:#f1f5f9; color:#475569;', 'archived' => 'background:#fef3c7; color:#92400e;'];
                  $labels = ['new'=>'جديدة','read'=>'مقروءة','replied'=>'تم الرد','archived'=>'مؤرشفة'];
              ?>
              <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $stColors[$m['status']] ?>"><?= $labels[$m['status']] ?></span>
          </td>
          <td><span style="font-size:13px; font-weight:800; color:var(--text-muted);"><?= dt($m['created_at'], 'Y-m-d H:i') ?></span></td>
          <td style="text-align:left; padding-left:25px;">
            <a href="<?= e(url('admin/contact/' . (int)$m['id'])) ?>" class="btn btn-primary btn-sm" style="border-radius: 8px; padding: 8px 16px;"><i data-lucide="mail-open" style="width:16px;"></i> فتح</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($data['items'])): ?><tr><td colspan="5" style="text-align:center; padding: 40px;">لا توجد رسائل.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>