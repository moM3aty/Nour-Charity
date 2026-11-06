<?php
// app/Views/frontend/receipt.php
// هذه الصفحة Bare Layout للطباعة
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title><?= e($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #F4F7FA; padding: 40px 16px; margin: 0; color: #1e293b; }
        .receipt-card { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 24px; padding: 50px; box-shadow: 0 15px 40px rgba(6, 27, 82, 0.08); border-top: 8px solid #092A9E; }
        .r-header { display: flex; justify-content: space-between; border-bottom: 2px dashed #e2e8f0; padding-bottom: 25px; margin-bottom: 30px; align-items: center; }
        .r-brand h2 { margin: 0; font-weight: 900; color: #061B52; font-size: 24px; }
        .r-brand p { margin: 5px 0 0; font-size: 13px; color: #64748b; }
        .r-meta { text-align: left; }
        .r-meta-ref { font-family: monospace; font-size: 16px; font-weight: 800; color: #092A9E; background: #EAF2FF; padding: 4px 10px; border-radius: 6px; display: inline-block; margin-bottom: 5px; }
        .r-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; font-weight: 600; }
        .r-row-total { display: flex; justify-content: space-between; padding: 25px 0; margin-top: 10px; font-size: 22px; font-weight: 900; color: #092A9E; border-top: 2px solid #092A9E; border-bottom: none; }
        .r-note { margin-top: 40px; padding: 20px; background: #f8fafc; border-radius: 12px; font-size: 13px; color: #64748b; line-height: 1.8; text-align: center; }
        .print-btn { background: #092A9E; color: #fff; border: none; padding: 12px 30px; border-radius: 12px; font-family: inherit; font-weight: 800; font-size: 15px; cursor: pointer; transition: 0.3s; margin-top: 30px; }
        .print-btn:hover { background: #061B52; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; border: 1px solid #ccc; border-top: 4px solid #000; border-radius: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <div class="r-header">
            <div class="r-brand">
                <h2>نور مخة العالمية</h2>
                <p>للأعمال الخيرية والإنسانية</p>
            </div>
            <div class="r-meta" dir="ltr">
                <div class="r-meta-ref"><?= e($donation['donation_number']) ?></div>
                <div style="font-size:12px; color:#64748b;"><?= dt($donation['created_at'], 'Y-m-d H:i') ?></div>
            </div>
        </div>

        <h3 style="color:#061B52; margin-bottom:25px; font-weight: 900; text-align: center; font-size: 20px;">ملخص التبرع</h3>

        <div class="r-row"><span style="color: #64748b;">طريقة الدفع</span><span><?= e($donation['payment_method']) ?></span></div>
        <div class="r-row"><span style="color: #64748b;">العملة</span><span><?= e($donation['currency']) ?></span></div>
        <div class="r-row"><span style="color: #64748b;">حالة العملية</span>
            <span style="color: <?= $donation['status']==='paid'?'#059669':'#d97706' ?>; font-weight: 800;">
                <?= e($donation['status'] === 'paid' ? 'مكتمل ومدفوع' : 'معلق / بانتظار التحويل') ?>
            </span>
        </div>
        
        <div style="margin-top: 25px;">
            <h4 style="font-size: 14px; color: #64748b; margin-bottom: 15px;">تفاصيل التوجيه:</h4>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="r-row" style="padding: 10px 0; font-size: 14px;">
                        <span>- <?= e($item['project_title'] ?? 'تبرع عام') ?></span>
                        <span style="font-weight: 800;"><?= money((float)$item['amount']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="r-row" style="padding: 10px 0; font-size: 14px;">
                    <span>- <?= e($donation['project_title'] ?? 'تبرع عام') ?></span>
                    <span style="font-weight: 800;"><?= money((float)$donation['amount']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="r-row-total">
            <span>المبلغ الإجمالي</span>
            <span style="color: #F4C978;"><?= money((float)$donation['amount']) ?></span>
        </div>

        <div class="r-note">
            هذا المستند هو إشعار بتسجيل التبرع في منصة نور مخة العالمية.<br>
            في حال اختيار التحويل البنكي، لا يعتبر هذا الإيصال نهائياً إلا بعد تأكيد استلام المبلغ من قبل الإدارة المالية.
        </div>

        <div style="text-align:center;" class="no-print">
            <button onclick="window.print()" class="print-btn">طباعة الإيصال</button>
            <a href="<?= e(url('/')) ?>" style="display: block; margin-top: 15px; color: #64748b; font-weight: 700; text-decoration: underline;">العودة للرئيسية</a>
        </div>
    </div>
</body>
</html>