<?php
// app/Views/frontend/gift-card.php
// هذه الصفحة تعرض بطاقة الإهداء للمستلم
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title><?= e($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #061B52; min-height: 100vh; margin: 0; padding: 40px 16px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
        
        /* خلفية مزخرفة */
        body::before { content: ''; position: absolute; top: -20%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(244,201,120,0.15) 0%, transparent 70%); z-index: 1; }
        body::after { content: ''; position: absolute; bottom: -20%; left: -10%; width: 40vw; height: 40vw; background: radial-gradient(circle, rgba(66,123,212,0.2) 0%, transparent 70%); z-index: 1; }

        .gift-wrapper { position: relative; z-index: 10; max-width: 550px; width: 100%; }
        
        .gift-card { background: #fff; border-radius: 24px; padding: 50px 40px; text-align: center; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4); position: relative; overflow: hidden; }
        
        /* زينة علوية للبطاقة (شريط ذهبي) */
        .gift-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 8px; background: linear-gradient(90deg, #F4C978, #d97706); }
        
        .gift-icon { font-size: 64px; line-height: 1; margin-bottom: 20px; text-shadow: 0 10px 20px rgba(0,0,0,0.1); display: inline-block; animation: bounceIn 1s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        
        .gift-title { color: #061B52; font-weight: 900; font-size: 28px; margin-bottom: 5px; }
        .gift-ref { font-family: monospace; color: #94a3b8; font-size: 14px; margin-bottom: 25px; letter-spacing: 1px; }
        
        .gift-recipient { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 25px; }
        
        .gift-msg-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 30px; border-radius: 16px; margin-bottom: 30px; position: relative; }
        /* علامتي الاقتباس */
        .gift-msg-box::before { content: '"'; position: absolute; top: 10px; right: 20px; font-size: 60px; color: rgba(9,42,158,0.1); font-family: serif; line-height: 1; }
        .gift-msg-text { font-size: 18px; color: #092A9E; line-height: 1.8; font-weight: 700; position: relative; z-index: 2; }
        
        .gift-footer { border-top: 1px dashed #cbd5e1; padding-top: 25px; display: flex; justify-content: space-between; align-items: center; }
        .gift-status { display: inline-flex; align-items: center; gap: 8px; color: #059669; font-weight: 800; font-size: 14px; background: #d1fae5; padding: 6px 16px; border-radius: 20px; }
        .gift-brand { font-size: 13px; font-weight: 700; color: #64748b; }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.1); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="gift-wrapper">
        <div class="gift-card">
            <div class="gift-icon">🎁</div>
            <h1 class="gift-title">بطاقة إهداء</h1>
            <div class="gift-ref">رقم: <?= e($gift['donation_number']) ?></div>
            
            <?php if ($gift['recipient_name']): ?>
                <div class="gift-recipient">
                    <span style="color: #64748b; font-size: 16px; font-weight: 600;">إلى الغالي/ة:</span><br>
                    <span style="color: #092A9E; font-size: 26px;"><?= e($gift['recipient_name']) ?></span>
                </div>
            <?php endif; ?>
            
            <div style="font-size: 15px; color: #64748b; margin-bottom: 20px;">
                تم التبرع عنك بمبلغ 
                <span style="color: #F4C978; font-weight: 900; font-size: 22px; padding: 0 5px;"><?= money((float)$gift['amount'], $gift['currency']) ?></span>
                كصدقة جارية.
            </div>

            <?php if ($gift['message']): ?>
                <div class="gift-msg-box">
                    <div class="gift-msg-text"><?= nl2br(e($gift['message'])) ?></div>
                </div>
            <?php endif; ?>
            
            <div class="gift-footer">
                <div class="gift-status">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    عطاء مكتمل
                </div>
                <div class="gift-brand">
                    نور مخة العالمية
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="<?= e(url('/')) ?>" style="color: #fff; opacity: 0.8; text-decoration: none; font-size: 15px; font-weight: 600;">زيارة منصة نور مخة العالمية ↗</a>
        </div>
    </div>
</body>
</html>