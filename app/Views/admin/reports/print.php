<?php
// app/Views/admin/reports/print.php
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>التقرير المالي - منصة نور مخة</title>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Cairo', sans-serif; padding: 40px; color: #1e293b; background: #fff; line-height: 1.6; }
    .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #092A9E; padding-bottom: 20px; }
    .header h1 { color: #061B52; font-weight: 900; margin-bottom: 5px; font-size: 28px; }
    .header p { color: #64748b; font-size: 14px; font-weight: 700; margin: 0; }
    .summary-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; margin-bottom: 30px; display: flex; justify-content: space-around; text-align: center; }
    .summary-item h3 { font-size: 14px; color: #64748b; margin-bottom: 5px; }
    .summary-item p { font-size: 24px; font-weight: 900; color: #092A9E; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #e2e8f0; padding: 12px 15px; text-align: right; }
    th { background: #061B52; color: #fff; font-weight: 700; }
    td { font-weight: 600; }
    .total-row { background: #f1f5f9; font-weight: 900; }
    .print-btn { background: #092A9E; color: #fff; border: none; padding: 15px 30px; border-radius: 8px; font-family: inherit; font-weight: 700; cursor: pointer; display: block; margin: 0 auto 30px; }
    @media print { .no-print { display: none; } body { padding: 0; } }
</style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">طباعة التقرير</button>

    <div class="header">
        <h1>نور مخة العالمية للأعمال الخيرية</h1>
        <p>التقرير المالي الموحد للتبرعات</p>
        <p>الفترة من: <?= e($from) ?> إلى: <?= e($to) ?></p>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <h3>إجمالي المبالغ المحصلة</h3>
            <p><?= money((float)$summary['total']) ?></p>
        </div>
        <div class="summary-item">
            <h3>عدد العمليات الناجحة</h3>
            <p><?= (int)$summary['count'] ?></p>
        </div>
    </div>

    <h3 style="color: #061B52;">الإيرادات المفصلة حسب المشروع</h3>
    <table>
        <thead>
            <tr><th>المشروع</th><th>عدد التبرعات</th><th>المبلغ المحصل</th></tr>
        </thead>
        <tbody>
            <?php foreach ($byProject as $p): ?>
                <tr>
                    <td><?= e($p['title']) ?></td>
                    <td><?= (int)$p['donations_count'] ?></td>
                    <td style="color: #d97706;"><?= money((float)$p['total']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($byProject)): ?>
                <tr><td colspan="3" style="text-align: center;">لا توجد إيرادات في هذه الفترة.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 50px; display: flex; justify-content: space-between; font-weight: 700; color: #64748b;">
        <div>توقيع المحاسب: ..........................</div>
        <div>توقيع المدير: ..........................</div>
        <div>تاريخ الطباعة: <?= date('Y-m-d') ?></div>
    </div>
</body>
</html>