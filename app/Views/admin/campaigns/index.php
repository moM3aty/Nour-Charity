<?php
// app/Views/admin/campaigns/index.php
use App\Core\Session; ?>
<?php if ($m = Session::getFlash('success')): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:10px;margin-bottom:20px;font-weight:800;"><?= e($m) ?></div><?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);">حملات التبرع</h4>
  <a href="<?= e(url('admin/campaigns/create')) ?>" class="btn btn-primary"><i data-lucide="plus" style="width:18px;"></i> حملة جديدة</a>
</div>

<div class="admin-card" style="padding:0; overflow:hidden;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc;">
      <tr>
        <th style="padding:20px;">الحملة</th>
        <th>المشروع المرتبط</th>
        <th>مستهدف / تم جمع</th>
        <th>الحالة</th>
        <th style="text-align:left; padding-left:20px;">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $c): 
        $pct = ((float)$c['goal_amount'] > 0) ? min(100, round(((float)$c['raised_amount'] / (float)$c['goal_amount']) * 100)) : 0;
      ?>
        <tr>
          <td style="padding:20px;">
            <div style="display:flex; align-items:center; gap:15px;">
              <div style="width:50px; height:50px; border-radius:12px; background:#f1f5f9; overflow:hidden; flex-shrink:0;">
                <?php if(!empty($c['image'])): ?>
                  <img src="<?= e($c['image']) ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                  <div style="display:flex; justify-content:center; align-items:center; height:100%; color:var(--text-muted);"><i data-lucide="image"></i></div>
                <?php endif; ?>
              </div>
              <div>
                <strong style="display:block; font-size:15px; color:var(--sidebar-bg); margin-bottom:4px;"><?= e($c['title']) ?></strong>
                <small style="color:var(--text-muted); font-weight:600;"><code><?= e($c['slug']) ?></code></small>
              </div>
            </div>
          </td>
          <td><span class="badge bg-info"><?= e($c['project_title'] ?? 'حملة مستقلة') ?></span></td>
          <td>
            <div style="font-size:13px; font-weight:800; color:var(--sidebar-bg); margin-bottom:5px;">
              <?= money((float)$c['raised_amount']) ?> / <?= money((float)$c['goal_amount']) ?>
            </div>
            <div style="width:120px; height:6px; background:#e2e8f0; border-radius:3px; overflow:hidden;">
              <div style="height:100%; width:<?= $pct ?>%; background:linear-gradient(90deg, #092A9E, #F4C978);"></div>
            </div>
          </td>
          <td><span class="badge bg-<?= $c['status']==='active'?'success':($c['status']==='ended'?'secondary':'warning') ?>"><?= e($c['status']) ?></span></td>
          <td style="text-align:left; padding-left:20px;">
            <div style="display:flex; gap:8px; justify-content:flex-end;">
              <a href="<?= e(url('campaigns/' . $c['slug'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="عرض"><i data-lucide="eye" style="width:16px;"></i></a>
              <a href="<?= e(url('admin/campaigns/' . (int)$c['id'] . '/edit')) ?>" class="btn btn-primary btn-sm" title="تعديل"><i data-lucide="edit" style="width:16px;"></i></a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>