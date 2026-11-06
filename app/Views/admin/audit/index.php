<!-- app/Views/admin/audit/index.php -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);">سجل التدقيق (Audit Logs)</h4>
</div>

<div class="admin-card" style="margin-bottom:20px; padding:20px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap;">
    <input class="form-control" name="q" placeholder="بحث بالوصف أو الكيان..." value="<?= e($filters['q']) ?>" style="flex:1; min-width:200px;">
    <select class="form-select" name="action" style="flex:1; min-width:200px;">
      <option value="">كل الإجراءات</option>
      <?php foreach ($actions as $a): ?>
        <option value="<?= e($a['action']) ?>" <?= $filters['action']===$a['action']?'selected':'' ?>><?= e($a['action']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-outline-primary" style="padding:0 25px;">بحث</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden;">
  <div style="padding:15px 20px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between;">
    <span style="font-weight:800; color:var(--sidebar-bg); font-size:14px;">إجمالي السجلات: <?= (int)$total ?></span>
  </div>
  <table class="table" style="margin:0;">
    <thead style="background:#fff;">
      <tr>
        <th style="padding:20px;">التاريخ</th>
        <th>المستخدم</th>
        <th>الإجراء</th>
        <th>الكيان</th>
        <th>الوصف / IP</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $i): ?>
        <tr>
          <td style="padding:20px;"><span style="font-size:13px; font-weight:800; color:var(--sidebar-bg);"><?= dt($i['created_at'], 'Y-m-d H:i:s') ?></span></td>
          <td>
            <?php if ($i['user_name']): ?>
              <span class="badge bg-info"><i data-lucide="user" style="width:12px; display:inline-block; margin-left:4px;"></i> <?= e($i['user_name']) ?></span>
            <?php else: ?>
              <span class="badge bg-secondary">النظام</span>
            <?php endif; ?>
          </td>
          <td><code style="color:#d97706; font-weight:800; font-size:13px; background:#fef3c7; padding:4px 8px; border-radius:6px;"><?= e($i['action']) ?></code></td>
          <td>
            <?php if ($i['entity_type']): ?>
              <strong style="font-size:13px; color:var(--sidebar-bg);"><?= e($i['entity_type']) ?></strong>
              <?php if ($i['entity_id']): ?><span style="color:var(--text-muted); font-size:12px;"> #<?= (int)$i['entity_id'] ?></span><?php endif; ?>
            <?php else: ?>
              <span style="color:var(--text-muted);">—</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="font-size:13px; font-weight:700; color:var(--text-main); margin-bottom:4px;"><?= e($i['description'] ?? '') ?></div>
            <code style="font-size:11px; color:var(--text-muted);"><?= e($i['ip_address'] ?? '') ?></code>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <!-- Pagination -->
  <?php if ($pages > 1): ?>
    <div style="padding:20px; display:flex; gap:8px; justify-content:center; border-top:1px solid #f1f5f9;">
      <?php for ($i=1;$i<=$pages;$i++): ?>
        <a href="?page=<?= $i ?>&<?= http_build_query(array_filter($filters)) ?>" class="btn <?= $i===$page?'btn-primary':'btn-outline-primary' ?> btn-sm" style="width:36px; height:36px; padding:0; justify-content:center;"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>