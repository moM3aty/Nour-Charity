<?php
// app/Views/admin/notifications/index.php
use App\Core\CSRF; ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
  <h4 style="margin:0; font-weight:900; color:var(--sidebar-bg);">صندوق الإشعارات</h4>
  <form method="post" action="<?= e(url('admin/notifications/read-all')) ?>">
    <?= CSRF::field() ?>
    <button class="btn btn-outline-primary"><i data-lucide="check-check" style="width:18px;"></i> تعليم الكل كمقروء</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden;">
  <?php if (empty($items)): ?>
    <div style="text-align:center; padding:60px; color:var(--text-muted); font-weight:700;">
      <i data-lucide="bell-off" style="width:48px; height:48px; margin-bottom:15px; opacity:0.3;"></i><br>
      لا توجد إشعارات حالياً.
    </div>
  <?php else: ?>
    <?php foreach ($items as $n): ?>
      <div style="display:flex; justify-content:space-between; align-items:start; padding:20px; border-bottom:1px solid #f1f5f9; background:<?= $n['is_read']?'#fff':'#f8fafc' ?>; transition:0.2s;">
        <div style="display:flex; gap:15px;">
          <div style="width:40px; height:40px; border-radius:50%; background:<?= $n['is_read']?'#e2e8f0':'#EAF2FF' ?>; color:<?= $n['is_read']?'#64748b':'var(--sidebar-bg)' ?>; display:flex; justify-content:center; align-items:center; flex-shrink:0;">
            <i data-lucide="<?= $n['type']==='system'?'info':'bell' ?>" style="width:20px;"></i>
          </div>
          <div>
            <h6 style="font-weight:900; color:var(--sidebar-bg); margin-bottom:5px; font-size:15px; <?= !$n['is_read']?'color:#092A9E;':'' ?>"><?= e($n['title']) ?></h6>
            <?php if ($n['message']): ?><p style="font-size:14px; color:var(--text-muted); margin-bottom:8px; line-height:1.6;"><?= e($n['message']) ?></p><?php endif; ?>
            <small style="color:#94a3b8; font-weight:700; font-size:12px;"><i data-lucide="clock" style="width:12px; display:inline-block; vertical-align:middle;"></i> <?= dt($n['created_at'], 'Y-m-d H:i') ?></small>
          </div>
        </div>
        <?php if (!$n['is_read']): ?>
          <form method="post" action="<?= e(url('admin/notifications/' . (int)$n['id'] . '/read')) ?>">
            <?= CSRF::field() ?>
            <button class="btn btn-sm" style="background:#EAF2FF; color:var(--sidebar-bg); border:none;" title="تعليم كمقروء"><i data-lucide="check" style="width:16px;"></i></button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>