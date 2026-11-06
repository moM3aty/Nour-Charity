<?php
// app/Views/frontend/cart.php
use App\Core\CSRF; use App\Core\Session;
?>
<link rel="stylesheet" href="<?= e(asset('css/premium-globals.css')) ?>?v=<?= time() ?>">

<style>
    .cart-layout { display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start; max-width: 1100px; margin: 0 auto; }
    .cart-main { flex: 1.5; min-width: 60%; }
    .cart-sidebar { flex: 1; min-width: 350px; position: sticky; top: 100px; }
    
    .cart-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 20px; background: #fff; border: 1px solid var(--border);
        border-radius: 16px; margin-bottom: 15px; transition: var(--transition);
    }
    .cart-item:hover { border-color: var(--primary-light); box-shadow: var(--shadow-sm); transform: translateX(-5px); }
    .cart-item-icon { width: 50px; height: 50px; background: #EAF2FF; border-radius: 12px; display: flex; justify-content: center; align-items: center; color: var(--primary-light); }
    .cart-item-title { font-weight: 900; font-size: 16px; color: var(--primary); margin-bottom: 4px; }
    .cart-item-price { font-size: 16px; font-weight: 800; color: var(--gold-hover); }
    .cart-item-remove { background: #fee2e2; border: 0; color: #dc2626; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.2s; }
    .cart-item-remove:hover { background: #fca5a5; transform: rotate(90deg); }

    .cart-add-panel { background: #f8fafc; padding: 25px; border-radius: 20px; margin-top: 30px; border: 1px dashed var(--border); }
    
    @media (max-width: 992px) {
        .cart-layout { flex-direction: column; }
        .cart-sidebar { width: 100%; position: static; }
    }
</style>

<section class="section" style="padding-top:60px; min-height: 80vh;">
    <div class="container-nour">
        <div class="section-title-wrap">
            <span class="section-subtitle">سلة الخير</span>
            <h1 class="section-title">مساهماتك في سلة واحدة</h1>
            <p class="section-desc">راجع تبرعاتك واعتمدها بخطوة واحدة سهلة.</p>
        </div>

        <?php if ($m = Session::getFlash('error')): ?>
            <div class="premium-panel" style="max-width:800px; margin:0 auto 30px; background:#fee2e2; border-color:#fca5a5; padding: 20px;">
                <div style="display:flex; align-items:center; gap:10px; color:#991b1b; font-weight:800; font-size:16px;">
                    <i data-lucide="alert-circle"></i> <?= e($m) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="cart-layout">
            <div class="cart-main">
                <div class="premium-panel" style="padding: 40px;">
                    <h3 style="color: var(--primary); margin-bottom: 25px; font-weight: 900; font-size: 20px;">عناصر السلة</h3>
                    
                    <div id="cartList" style="min-height: 200px;"></div>

                    <div class="cart-add-panel">
                        <h4 style="font-weight: 900; color: var(--primary); margin-bottom: 15px; font-size: 16px;">هل ترغب بإضافة تبرع آخر؟</h4>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <select id="addProject" class="form-select" style="flex: 2; min-width: 200px; background: #fff;">
                                <option value="0">صدقة عامة / مشروع خيري مفتوح</option>
                                <?php foreach ($projects as $p): ?>
                                    <option value="<?= (int)$p['id'] ?>"><?= e($p['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" id="addAmount" class="form-control" placeholder="المبلغ (ر.س)" style="flex: 1; min-width: 120px; background: #fff;" min="1">
                            <button type="button" class="btn-n btn-n-outline" onclick="addItem()" style="padding: 0 25px; border-radius: 14px;">
                                إضافة <i data-lucide="plus" style="width:18px; margin-right:5px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cart-sidebar">
                <form method="post" action="<?= e(url('cart/checkout')) ?>" class="premium-panel" style="padding: 35px;">
                    <?= CSRF::field() ?>
                    <input type="hidden" name="cart_json" id="cartJson">
                    
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="font-size: 15px; color: var(--text-muted); font-weight: 700; margin-bottom: 8px;">الإجمالي الكلي</div>
                        <div id="cartTotal" style="font-size: 40px; font-weight: 900; color: var(--primary); line-height: 1;">0 ر.س</div>
                    </div>
                    
                    <hr style="border:0; border-top: 1px dashed var(--border); margin: 25px 0;">
                    
                    <h4 style="font-weight: 900; color: var(--primary); margin-bottom: 20px; font-size: 18px;">معلومات مقدم التبرع</h4>
                    
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">الاسم الكامل</label>
                        <input type="text" name="name" class="form-control" value="<?= e($donor['name'] ?? '') ?>" required>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="<?= e($donor['phone'] ?? '') ?>" required>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 13px; font-weight: 800; color: var(--primary); margin-bottom: 8px;">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="<?= e($donor['email'] ?? '') ?>" required>
                    </div>
                    
                    <button type="submit" class="btn-n btn-n-primary btn-n-lg" onclick="return submitCart()" style="box-shadow: 0 10px 25px rgba(9, 42, 158, 0.25);">
                        إتمام التبرع <i data-lucide="arrow-left" style="width:20px; margin-right:8px;"></i>
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</section>

<script>
    const CART_KEY = 'nour_cart';
    const PROJECTS = <?= json_encode(array_map(fn($p) => ['id' => (int)$p['id'], 'title' => $p['title']], $projects), JSON_UNESCAPED_UNICODE) ?>;
    
    function getCart() { try { return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); } catch { return []; } }
    function saveCart(items) { localStorage.setItem(CART_KEY, JSON.stringify(items)); render(); }
    
    function render() {
        const items = getCart();
        const list = document.getElementById('cartList');
        let total = 0;
        
        if (!items.length) {
            list.innerHTML = `
                <div style="text-align: center; padding: 60px 20px; background: #f8fafc; border-radius: 16px; border: 2px dashed var(--border);">
                    <i data-lucide="shopping-cart" style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 20px;"></i>
                    <h4 style="color: var(--primary); font-weight: 900; font-size: 20px;">سلة التبرعات فارغة</h4>
                    <p style="color: var(--text-muted); margin-top: 10px;">لم تقم بإضافة أي مشاريع للسلة بعد.</p>
                </div>`;
            if(typeof lucide !== 'undefined') lucide.createIcons();
        } else {
            list.innerHTML = items.map((it, i) => {
                const p = PROJECTS.find(p => p.id === it.project_id);
                const title = p ? p.title : 'صدقة عامة / مشروع خيري مفتوح';
                total += parseFloat(it.amount || 0);
                
                return `
                <div class="cart-item">
                    <div style="display:flex; align-items:center; gap: 15px;">
                        <div class="cart-item-icon">
                            <i data-lucide="heart" style="width:24px;"></i>
                        </div>
                        <div>
                            <div class="cart-item-title">${title}</div>
                            <div class="cart-item-price">${Number(it.amount).toLocaleString()} ر.س</div>
                        </div>
                    </div>
                    <button type="button" onclick="removeItem(${i})" class="cart-item-remove" title="حذف">
                        <i data-lucide="trash-2" style="width:18px;"></i>
                    </button>
                </div>`;
            }).join('');
            if(typeof lucide !== 'undefined') lucide.createIcons();
        }
        document.getElementById('cartTotal').textContent = total.toLocaleString() + ' ر.س';
        document.getElementById('cartJson').value = JSON.stringify(items);
        
        // تحديث الـ Badge في الهيدر
        const badge = document.querySelector('.cart-badge');
        if(badge) badge.textContent = items.length;
    }
    
    function addItem() {
        const pid = parseInt(document.getElementById('addProject').value || '0');
        const amt = parseFloat(document.getElementById('addAmount').value || '0');
        if (amt < 1 || isNaN(amt)) { 
            if (typeof Swal !== 'undefined') Swal.fire({icon:'warning', title:'تنبيه', text:'يرجى إدخال مبلغ صحيح أكبر من الصفر', confirmButtonColor:'#061B52'});
            else alert('يرجى إدخال مبلغ صحيح'); 
            return; 
        }
        
        const items = getCart();
        items.push({ id: Date.now(), project_id: pid, amount: amt });
        document.getElementById('addAmount').value = '';
        saveCart(items);
    }
    
    function removeItem(i) { 
        const items = getCart(); 
        items.splice(i, 1); 
        saveCart(items); 
    }
    
    function submitCart() {
        const items = getCart();
        if (!items.length) { 
            if (typeof Swal !== 'undefined') Swal.fire({icon:'error', title:'السلة فارغة', text:'يرجى إضافة تبرع واحد على الأقل للمتابعة', confirmButtonColor:'#061B52'});
            else alert('السلة فارغة، يرجى إضافة تبرع أولاً'); 
            return false; 
        }
        document.getElementById('cartJson').value = JSON.stringify(items);
        
        // تفريغ السلة من الـ LocalStorage بعد الإرسال الناجح للـ Backend
        // ليتم تسجيلها كطلب جديد
        setTimeout(() => { localStorage.removeItem(CART_KEY); }, 1000);
        return true;
    }
    
    document.addEventListener('DOMContentLoaded', render);
</script>