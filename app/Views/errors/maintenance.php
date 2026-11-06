<!-- Maintenance Page -->
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>الموقع تحت الصيانة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Cairo', sans-serif; }
        body { 
            background: #061B52; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
            position: relative;
        }
        
        .maintenance-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 32px;
            padding: 60px 40px;
            max-width: 550px;
            width: 90%;
            text-align: center;
            position: relative;
            z-index: 10;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }
        
        /* أيقونة متحركة */
        .icon-wrapper {
            width: 120px;
            height: 120px;
            background: rgba(244,201,120,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
        }
        .icon-wrapper::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            border: 2px dashed rgba(244,201,120,0.4);
            animation: spin 10s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        
        .icon-wrapper svg {
            width: 50px;
            height: 50px;
            color: #F4C978;
            animation: pulse 2s infinite alternate;
        }
        @keyframes pulse { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }

        h1 { color: #fff; font-size: 2rem; font-weight: 900; margin-bottom: 15px; }
        p { color: #94a3b8; font-size: 1.1rem; line-height: 1.8; }
        
        .contact-info {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 700;
        }
        .contact-info a { color: #F4C978; text-decoration: none; }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <div class="icon-wrapper">
            <!-- أيقونة إعدادات/صيانة -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </div>
        
        <h1>نعمل على تحسين تجربتك</h1>
        <p>الموقع يخضع لبعض أعمال الصيانة والتطوير حالياً. سنعود للعمل قريباً جداً بإذن الله. شكراً لتفهمك وصبرك.</p>
        
        <div class="contact-info">
            للتواصل العاجل: <a href="mailto:info@nourmakha.com">info@nourmakha.com</a>
        </div>
    </div>
</body>
</html>