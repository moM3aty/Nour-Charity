<!-- 404 Error Page -->
<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>404 — الصفحة غير موجودة</title>
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
        /* خلفية متحركة */
        body::before {
            content: '';
            position: absolute;
            top: -20%; right: -10%;
            width: 50vw; height: 50vw;
            background: radial-gradient(circle, rgba(244,201,120,0.1) 0%, transparent 60%);
            border-radius: 50%;
            z-index: 1;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -20%; left: -10%;
            width: 60vw; height: 60vw;
            background: radial-gradient(circle, rgba(66,123,212,0.15) 0%, transparent 60%);
            border-radius: 50%;
            z-index: 1;
        }
        
        .error-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 32px;
            padding: 60px 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            position: relative;
            z-index: 10;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #F4C978, #d97706);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
            text-shadow: 0 10px 30px rgba(244,201,120,0.2);
        }
        
        h1 { color: #fff; font-size: 2rem; font-weight: 900; margin-bottom: 15px; }
        p { color: #94a3b8; font-size: 1.1rem; line-height: 1.8; margin-bottom: 40px; }
        
        .btn-group { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
        .btn {
            padding: 15px 35px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 16px;
            text-decoration: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #F4C978, #d97706);
            color: #061B52;
            box-shadow: 0 10px 20px rgba(244,201,120,0.3);
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(244,201,120,0.4); }
        
        .btn-outline {
            background: rgba(255,255,255,0.05);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-outline:hover { background: rgba(255,255,255,0.1); transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">404</div>
        <h1>الصفحة غير موجودة</h1>
        <p>عذراً، الصفحة التي تبحث عنها غير متوفرة أو تم نقلها إلى رابط آخر. قد يكون الرابط قديماً أو تم حذف الصفحة.</p>
        
        <div class="btn-group">
            <a href="/" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                الرئيسية
            </a>
            <a href="/projects" class="btn btn-outline">
                تصفح المشاريع
            </a>
        </div>
    </div>
</body>
</html>