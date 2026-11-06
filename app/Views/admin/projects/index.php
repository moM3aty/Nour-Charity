<?php
// app/Views/admin/projects/index.php
use App\Core\Session; 
?>
<?php if ($m = Session::getFlash('success')): ?>
    <div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #a7f3d0;"><i data-lucide="check-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>
<?php if ($m = Session::getFlash('error')): ?>
    <div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:15px;border-radius:12px;margin-bottom:20px;font-weight:800; border:1px solid #fecaca;"><i data-lucide="alert-circle" style="vertical-align:middle; width:20px; margin-left:8px;"></i> <?= e($m) ?></div>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
  <div>
      <h4 style="margin:0 0 5px 0; font-weight:900; color:var(--sidebar-bg); font-size:24px;">المشاريع الخيرية</h4>
      <p style="color:var(--text-muted); margin:0; font-size:14px; font-weight:600;">إدارة ومتابعة كافة المشاريع وحالة تمويلها.</p>
  </div>
  <a href="<?= e(url('admin/projects/create')) ?>" class="btn btn-primary" style="padding: 12px 24px; border-radius: 12px; box-shadow: 0 8px 15px rgba(6,27,82,0.2);">
      <i data-lucide="plus" style="width:18px;"></i> إضافة مشروع جديد
  </a>
</div>

<div class="admin-card" style="margin-bottom:25px; padding:25px;">
  <form method="get" style="display:flex; gap:15px; flex-wrap:wrap; align-items:flex-end;">
    <div style="flex:2; min-width:250px;">
      <label class="form-label" style="font-size: 13px;">بحث باسم المشروع</label>
      <input class="form-control" name="q" placeholder="أدخل كلمة البحث..." value="<?= e($q) ?>">
    </div>
    <div style="flex:1; min-width:200px;">
      <label class="form-label" style="font-size: 13px;">حالة المشروع</label>
      <select class="form-select" name="status">
        <option value="">كل الحالات</option>
        <?php foreach (['draft'=>'مسودة','active'=>'نشط','funded'=>'مكتمل التمويل','completed'=>'مكتمل التنفيذ','paused'=>'متوقف','archived'=>'مؤرشف'] as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $status===$k?'selected':'' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-outline-primary" style="height:48px; padding:0 30px; border-radius: 12px;">تصفية النتائج</button>
  </form>
</div>

<div class="admin-card" style="padding:0; overflow:hidden;">
  <table class="table" style="margin:0;">
    <thead style="background:#f8fafc; border-bottom: 2px solid #e2e8f0;">
      <tr>
        <th style="padding:20px;">اسم المشروع</th>
        <th>التصنيف</th>
        <th style="width: 25%;">حالة التمويل</th>
        <th>الحالة</th>
        <th style="text-align:left; padding-left:25px;">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $p): 
        $pct = ((float)$p['target_amount'] > 0) ? min(100, round(((float)$p['raised_amount'] / (float)$p['target_amount']) * 100)) : 0;
      ?>
        <tr style="border-bottom: 1px solid #f1f5f9;">
          <td style="padding:20px;">
            <div style="display:flex; align-items:center; gap:15px;">
              <div style="width:60px; height:60px; border-radius:12px; background:#f1f5f9; overflow:hidden; flex-shrink:0; border: 1px solid #e2e8f0;">
                <?php if(!empty($p['cover_image'])): ?>
                  <img src="<?= e($p['cover_image']) ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                  <div style="display:flex; justify-content:center; align-items:center; height:100%; color:var(--text-muted);"><i data-lucide="image"></i></div>
                <?php endif; ?>
              </div>
              <div>
                <strong style="display:block; font-size:15px; color:var(--sidebar-bg); margin-bottom:4px;"><?= e($p['title']) ?></strong>
                <div style="display:flex; gap: 8px; align-items: center;">
                    <small style="color:var(--text-muted); font-weight:600; font-family: monospace; background: #f8fafc; padding: 2px 6px; border-radius: 4px;"><?= e($p['slug']) ?></small>
                    <?php if($p['is_featured']): ?><span style="color:var(--gold-hover); font-size: 12px; font-weight: 800;"><i data-lucide="star" style="width: 14px; fill: var(--gold-hover);"></i> مميز</span><?php endif; ?>
                </div>
              </div>
            </div>
          </td>
          <td>
            <span style="background: #EAF2FF; color: var(--primary-light); padding: 4px 10px; border-radius: 8px; font-size: 13px; font-weight: 800;">
                <?= e($p['category_name'] ?? 'عام') ?>
            </span>
          </td>
          <td>
            <div style="font-size:13px; font-weight:800; color:var(--sidebar-bg); margin-bottom:6px; display: flex; justify-content: space-between;">
              <span><?= money((float)$p['raised_amount']) ?></span>
              <span style="color: var(--text-muted);"><?= money((float)$p['target_amount']) ?></span>
            </div>
            <div style="width:100%; height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
              <div style="height:100%; width:<?= $pct ?>%; background:linear-gradient(90deg, #092A9E, #427BD4); border-radius: 4px;"></div>
            </div>
            <div style="font-size: 12px; color: var(--gold-hover); font-weight: 900; margin-top: 4px; text-align: left;"><?= $pct ?>%</div>
          </td>
          <td>
            <?php
                $stColors = [
                    'active' => 'background:#d1fae5; color:#065f46;',
                    'draft' => 'background:#f1f5f9; color:#475569;',
                    'funded' => 'background:#dbeafe; color:#1e40af;',
                    'completed' => 'background:#e0e7ff; color:#3730a3;',
                    'paused' => 'background:#fef3c7; color:#92400e;',
                    'archived' => 'background:#fee2e2; color:#991b1b;'
                ];
                $sStyle = $stColors[$p['status']] ?? $stColors['draft'];
            ?>
            <span style="padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; <?= $sStyle ?>"><?= e($p['status']) ?></span>
          </td>
          <td style="text-align:left; padding-left:25px;">
            <div style="display:flex; gap:8px; justify-content:flex-end;">
              <a href="<?= e(url('projects/' . $p['slug'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="معاينة في الموقع" style="padding: 8px;"><i data-lucide="external-link" style="width:16px;"></i></a>
              <a href="<?= e(url('admin/projects/' . (int)$p['id'] . '/edit')) ?>" class="btn btn-primary btn-sm" title="تعديل المشروع" style="padding: 8px;"><i data-lucide="edit" style="width:16px;"></i></a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($items)): ?>
        <tr><td colspan="5" style="text-align:center; padding:60px 20px; color:var(--text-muted);">
            <i data-lucide="folder-open" style="width: 48px; height: 48px; opacity: 0.5; margin-bottom: 15px;"></i><br>
            لا توجد مشاريع مطابقة.
        </td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  
  <?php if (($pages ?? 0) > 1): ?>
    <div style="padding:20px; display:flex; gap:8px; justify-content:center; border-top:1px solid #f1f5f9; background: #f8fafc;">
      <?php for ($i=1;$i<=$pages;$i++): ?>
        <a href="?page=<?= $i ?>&<?= http_build_query(array_filter(['q'=>$q, 'status'=>$status])) ?>" 
           class="btn <?= $i===($page??1)?'btn-primary':'btn-outline-primary' ?> btn-sm" 
           style="width:36px; height:36px; padding:0; justify-content:center; font-size: 14px; border-radius: 10px;"><?= $i ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>