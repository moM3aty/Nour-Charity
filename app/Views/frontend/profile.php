<?php
// app/Views/frontend/profile.php
use App\Core\Auth;
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    .profile-section { padding: 60px 0 100px; background: #f8fafc; min-height: 80vh; }
    .profile-layout { display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start; max-width: 1200px; margin: 0 auto; }
    
    .profile-sidebar { width: 320px; text-align: center; position: sticky; top: 100px; }
    .profile-avatar {
        width: 120px; height: 120px; margin: 0 auto 20px;
        background: var(--primary-grad); color: #fff;
        font-size: 48px; font-weight: 900;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%; box-shadow: 0 10px 25px rgba(9, 42, 158, 0.2);
        border: 4px solid #fff;
    }
    .profile-name { font-size: 24px; font-weight: 900; color: var(--primary); margin-bottom: 5px; }
    .profile-email { color: var(--text-muted); font-size: 15px; margin-bottom: 25px; font-weight: 600; }
    
    .profile-nav { display: flex; flex-direction: column; gap: 10px; text-align: right; }
    .profile-nav a {
        padding: 16px 20px; border-radius: 16px; font-weight: 800; font-size: 15px;
        color: var(--text-main); display: flex; align-items: center; gap: 12px;
        transition: var(--transition); background: #f1f5f9; border: 1px solid transparent;
    }
    .profile-nav a:hover { background: #fff; border-color: var(--primary-light); transform: translateX(-5px); }
    .profile-nav a.active { background: var(--primary); color: #fff; box-shadow: 0 8px 20px rgba(6,27,82,0.15); }
    
    .profile-content { flex: 1; min-width: 60%; }
    
    .table-premium { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
    .table-premium th { text-align: right; padding: 0 20px 10px; color: var(--text-muted); font-size: 14px; font-weight: 800; border-bottom: 2px solid var(--border); }
    .table-premium td { padding: 20px; background: #fff; border: 1px solid var(--border); border-style: solid none; font-weight: 700; transition: 0.3s; }
    .table-premium td:first-child { border-right-style: solid; border-radius: 0 16px 16px 0; }
    .table-premium td:last-child { border-left-style: solid; border-radius: 16px 0 0 16px; }
    .table-premium tr:hover td { border-color: var(--primary-light); box-shadow: 0 5px 15px rgba(0,0,0,0.03); }

    @media (max-width: 992px) {
        .profile-layout { flex-direction: column; }
        .profile-sidebar { width: 100%; position: static; }
    }
</style>

<section class="profile-section">
    <div class="container-nour">
        <div class="profile-layout">
            
            <!-- القائمة الجانبية -->
            <div class="profile-sidebar">
                <div class="premium-panel" style="padding: 40px 20px;">
                    <div class="profile-avatar">
                        <?= mb_substr(e($user['name']), 0, 1) ?>
                    </div>
                    <h2 class="profile-name"><?= e($user['name']) ?></h2>
                    <div class="profile-email"><?= e($user['email']) ?></div>
                    
                    <hr style="border:0; border-top: 1px dashed var(--border); margin: 25px 0;">
                    
                    <div class="profile-nav">
                        <a href="<?= e(url('profile')) ?>" class="active"><i data-lucide="layout-dashboard" style="width:20px;"></i> سجل التبرعات</a>
                        <a href="<?= e(url('donate')) ?>"><i data-lucide="heart" style="width:20px;"></i> تبرع جديد</a>
                        <a href="<?= e(url('logout')) ?>" style="color: #dc2626; background: #fee2e2; margin-top: 10px;"><i data-lucide="log-out" style="width:20px;"></i> تسجيل الخروج</a>
                    </div>
                </div>
            </div>

            <!-- المحتوى الرئيسي -->
            <div class="profile-content">
                <div class="premium-panel" style="padding: 40px;">
                    <h3 class="h3" style="color: var(--primary); margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
                        <i data-lucide="history" style="color: var(--gold);"></i> سجل التبرعات الخاص بك
                    </h3>
                    
                    <?php if (empty($donations)): ?>
                        <div style="text-align: center; padding: 80px 20px; background: #f8fafc; border-radius: 20px; border: 2px dashed var(--border);">
                            <i data-lucide="folder-open" style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 20px;"></i>
                            <h4 style="color: var(--primary); font-weight: 900; font-size: 20px;">لا توجد تبرعات مسجلة حتى الآن</h4>
                            <p style="color: var(--text-muted); margin-top: 10px; margin-bottom: 25px;">ابدأ رحلة العطاء الآن وكن سبباً في تغيير حياة إنسان.</p>
                            <a href="<?= e(url('projects')) ?>" class="btn-n btn-n-primary">استكشف المشاريع <i data-lucide="arrow-left" style="margin-right: 8px;"></i></a>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table-premium">
                                <thead>
                                    <tr>
                                        <th>رقم التبرع</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإيصال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($donations as $d): ?>
                                    <tr>
                                        <td style="color: var(--primary);"><code style="background: #EAF2FF; padding: 6px 10px; border-radius: 8px; font-size: 14px;">#<?= e($d['donation_number']) ?></code></td>
                                        <td style="font-weight: 900; color: var(--gold-hover); font-size: 16px;"><?= money((float)$d['amount']) ?></td>
                                        <td style="color: var(--text-muted); font-size: 14px;"><?= dt($d['created_at'], 'Y-m-d') ?></td>
                                        <td>
                                            <?php
                                                $statusColors = [
                                                    'paid' => 'background:#d1fae5; color:#065f46;',
                                                    'pending' => 'background:#fef3c7; color:#92400e;',
                                                    'awaiting_verification' => 'background:#e0e7ff; color:#3730a3;'
                                                ];
                                                $sStyle = $statusColors[$d['status']] ?? 'background:#f1f5f9; color:#475569;';
                                            ?>
                                            <span style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 900; display: inline-block; <?= $sStyle ?>">
                                                <?= e($d['status'] === 'paid' ? 'مكتمل' : ($d['status'] === 'pending' ? 'بانتظار التحويل' : 'قيد المراجعة')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= e(url('donate/receipt?ref=' . urlencode($d['donation_number']))) ?>" target="_blank" 
                                               style="color: var(--primary-light); font-weight: 800; font-size: 14px; display: flex; align-items: center; gap: 5px; text-decoration: none;">
                                                <i data-lucide="printer" style="width: 16px;"></i> عرض
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>