<?php
// app/Views/admin/media/index.php
use App\Core\CSRF; use App\Core\Session; 
?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">مكتبة الوسائط</h4>
  </div>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:20px; border-radius: 16px;">
    <form method="post" action="<?= e(url('admin/media/upload')) ?>" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: flex-end;">
      <?= CSRF::field() ?>
      <div style="flex:1;">
          <label class="form-label">رفع وسائط جديدة (صور/PDF)</label>
          <input type="file" class="form-control" name="files[]" multiple accept="image/*,application/pdf" required style="background: #fff; padding: 12px;">
      </div>
      <button class="btn btn-primary" style="height:52px; padding: 0 30px; border-radius: 12px;">رفع</button>
    </form>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
  <?php foreach ($data['items'] as $m): 
      // التحقق من نوع الملف بناءً على الامتداد لحل مشكلة عدم ظهور الصور
      $ext = strtolower(pathinfo($m['path'], PATHINFO_EXTENSION));
      $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif']);
  ?>
    <div class="admin-card" style="padding: 15px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: space-between;">
      <div style="margin-bottom: 15px; border-radius: 12px; overflow: hidden; background: #f8fafc; text-align:center;">
        <?php if ($isImage): ?>
          <a href="<?= e($m['path']) ?>" target="_blank" style="display: block;">
            <img src="<?= e($m['path']) ?>" style="width:100%; height:150px; object-fit:cover;">
          </a>
        <?php else: ?>
          <div style="height:150px; display:flex; flex-direction: column; align-items:center; justify-content:center; color: var(--primary-light);">
            <i data-lucide="file-text" style="width: 48px; height: 48px; margin-bottom: 10px;"></i>
            <span style="font-weight: 800; font-size: 14px;"><?= strtoupper($ext) ?></span>
          </div>
        <?php endif; ?>
      </div>
      
      <div style="font-size: 13px; font-weight: 800; color: var(--primary); margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($m['original_name']) ?></div>
      
      <div style="display: flex; gap: 8px;">
        <input readonly class="form-control" style="font-size:11px; padding: 8px; border-radius: 8px; font-family: monospace; color: var(--text-muted); cursor: copy;" value="<?= e($m['path']) ?>" onclick="this.select(); document.execCommand('copy'); alert('تم النسخ');">
        <form method="post" action="<?= e(url('admin/media/' . (int)$m['id'] . '/delete')) ?>" onsubmit="return confirm('حذف؟')">
          <?= CSRF::field() ?>
          <button class="btn btn-sm" style="background:#fee2e2; color:#ef4444; border: none; width: 36px; height: 36px; border-radius: 8px;"><i data-lucide="trash-2" style="width:16px;"></i></button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
</div>