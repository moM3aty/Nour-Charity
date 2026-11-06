<?php
// app/Views/admin/dashboard.php
use App\Core\Auth; use App\Core\Session; 
?>
<?php if ($m = Session::getFlash('success')): ?>
    <div style="display:none;" id="flash-msg"><?= e($m) ?></div>
<?php endif; ?>

<!-- صف علوي: كروت المؤشرات -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:20px; margin-bottom:25px;">
  <div class="kpi" style="background: linear-gradient(135deg, #061B52 0%, #092A9E 100%); color: #fff; border: none;">
      <h6 style="color: rgba(255,255,255,0.8); font-size: 15px;">إجمالي التبرعات المؤكدة</h6>
      <div class="num" data-stat="total_donations" style="color: var(--gold); font-size: 36px; margin: 10px 0;"><?= money($stats['total_donations']) ?></div>
      <div style="font-size:13px; color:rgba(255,255,255,0.9); font-weight: 700;">محصلة الشهر: <span data-stat="month_total"><?= money($stats['month_total']) ?></span></div>
      <i data-lucide="wallet" style="position: absolute; left: -10px; bottom: -10px; width: 100px; height: 100px; opacity: 0.1; color: #fff;"></i>
  </div>
  
  <div class="kpi" style="border-right: 5px solid #d97706;">
      <h6>تبرعات بانتظار التحقق</h6>
      <div class="num" data-stat="awaiting_verification" style="color:#d97706; font-size: 36px; margin: 10px 0;"><?= (int)$stats['awaiting_verification'] ?></div>
      <a href="<?= e(url('admin/donations?status=awaiting_verification')) ?>" style="font-size:13px; font-weight:800; color:var(--primary-light); text-decoration: underline;">مراجعة الحوالات البنكية ←</a>
      <i data-lucide="clock" style="position: absolute; left: 15px; top: 25px; width: 40px; height: 40px; color: #fde68a;"></i>
  </div>
  
  <div class="kpi" style="border-right: 5px solid #10b981;">
      <h6>المشاريع النشطة</h6>
      <div class="num" data-stat="active_projects" style="font-size: 36px; margin: 10px 0; color: var(--primary);"><?= (int)$stats['active_projects'] ?></div>
      <div style="font-size:13px; color:var(--text-muted); font-weight: 700;">المشاريع المكتملة: <span data-stat="completed_projects" style="color: #10b981;"><?= (int)$stats['completed_projects'] ?></span></div>
      <i data-lucide="layout-grid" style="position: absolute; left: 15px; top: 25px; width: 40px; height: 40px; color: #d1fae5;"></i>
  </div>
  
  <div class="kpi" style="border-right: 5px solid #ef4444;">
      <h6>طلبات ووارد جديد</h6>
      <div class="num" data-stat="new_requests_total" style="color:#ef4444; font-size: 36px; margin: 10px 0;"><?= (int)$stats['volunteer_apps'] + (int)$stats['beneficiary_requests'] + (int)$stats['contact_messages'] ?></div>
      <div style="font-size:12px; color:var(--text-muted); font-weight: 800;">
          تطوع: <span data-stat="volunteer_apps" style="color:#ef4444;"><?= (int)$stats['volunteer_apps'] ?></span> | 
          مساعدة: <span data-stat="beneficiary_requests" style="color:#ef4444;"><?= (int)$stats['beneficiary_requests'] ?></span>
      </div>
      <i data-lucide="bell-ring" style="position: absolute; left: 15px; top: 25px; width: 40px; height: 40px; color: #fee2e2;"></i>
  </div>
</div>

<!-- صف المنتصف: الرسم البياني وتحديثات المشاريع -->
<div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px; margin-bottom:25px;">
  
  <div class="admin-card" style="padding: 25px;">
      <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom:25px;">
        <h6 style="font-weight:900; color:var(--sidebar-bg); margin:0; font-size: 18px;">مؤشر التبرعات (آخر 30 يوم)</h6>
        <span style="font-size:12px; font-weight:800; color:#10b981; background: #d1fae5; padding: 4px 10px; border-radius: 12px; display: flex; align-items: center; gap: 5px;">
            <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981; animation: pulse 2s infinite;"></span> تحديث مباشر
        </span>
      </div>
      <div style="position: relative; height: 320px; width: 100%;">
          <canvas id="trendChart"></canvas>
      </div>
  </div>
  
  <div class="admin-card" style="padding: 25px;">
      <h6 style="font-weight:900; color:var(--sidebar-bg); margin-bottom:25px; font-size: 18px;">المشاريع الأكثر تفاعلاً</h6>
      <?php if(empty($topProjects)): ?>
          <p style="color: var(--text-muted); text-align: center; margin-top: 50px; font-weight: 700;">لا توجد بيانات كافية.</p>
      <?php else: ?>
          <?php foreach ($topProjects as $p): 
            $pct = ((float)$p['target_amount'] > 0) ? min(100, round(((float)$p['raised_amount'] / (float)$p['target_amount']) * 100)) : 0;
          ?>
            <div style="margin-bottom: 25px;">
              <div style="display:flex; justify-content:space-between; font-size:14px; font-weight:900; margin-bottom:8px; color: var(--primary);">
                <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 70%;"><?= e($p['title']) ?></span>
                <span style="color:var(--gold-hover);"><?= (int)$pct ?>%</span>
              </div>
              <div style="height:10px; background:#f1f5f9; border-radius:5px; overflow:hidden;">
                  <div style="height:100%; width:<?= (int)$pct ?>%; background:linear-gradient(90deg, #092A9E, #427BD4); border-radius: 5px;"></div>
              </div>
              <div style="font-size: 12px; color: var(--text-muted); margin-top: 6px; font-weight: 700;">تم جمع <?= money((float)$p['raised_amount']) ?></div>
            </div>
          <?php endforeach; ?>
      <?php endif; ?>
  </div>
</div>

<!-- صف سفلي: أحدث العمليات -->
<div style="display:grid; grid-template-columns: 1.5fr 1fr; gap:20px;">
  <div class="admin-card" style="padding: 0; overflow: hidden;">
      <div style="display:flex; justify-content:space-between; align-items: center; padding: 25px; border-bottom: 1px solid #f1f5f9;">
        <h6 style="font-weight:900; color:var(--sidebar-bg); margin:0; font-size: 18px;">أحدث التبرعات</h6>
        <a href="<?= e(url('admin/donations')) ?>" class="btn btn-sm btn-outline-primary">عرض السجل الكامل</a>
      </div>
      <?php if ($recentDonations): ?>
        <table class="table" style="margin: 0;">
          <thead style="background: #f8fafc;">
              <tr>
                  <th style="padding: 15px 25px;">رقم العملية</th>
                  <th>المتبرع</th>
                  <th>المبلغ</th>
                  <th>الحالة</th>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($recentDonations as $d): ?>
              <tr>
                <td style="padding: 15px 25px;"><a href="<?= e(url('admin/donations/' . (int)$d['id'])) ?>" style="color:var(--primary-light); font-weight:900; background: #EAF2FF; padding: 4px 8px; border-radius: 6px;">#<?= e($d['donation_number']) ?></a></td>
                <td style="font-weight: 700; color: var(--primary);"><?= e($d['donor_name'] ?? 'فاعل خير') ?></td>
                <td><strong style="color:var(--gold-hover); font-size: 16px;"><?= money((float)$d['amount']) ?></strong></td>
                <td>
                    <?php
                        $statusColors = [
                            'paid' => 'background:#d1fae5; color:#065f46;',
                            'pending' => 'background:#fef3c7; color:#92400e;',
                            'awaiting_verification' => 'background:#e0e7ff; color:#3730a3;'
                        ];
                        $sStyle = $statusColors[$d['status']] ?? 'background:#f1f5f9; color:#475569;';
                    ?>
                    <span style="padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 800; <?= $sStyle ?>"><?= e($d['status']) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
          <div style="padding: 40px; text-align: center; color: var(--text-muted); font-weight: 700;">لا توجد تبرعات مسجلة حتى الآن.</div>
      <?php endif; ?>
  </div>
  
  <div class="admin-card" style="padding: 0; overflow: hidden;">
      <div style="display:flex; justify-content:space-between; align-items: center; padding: 25px; border-bottom: 1px solid #f1f5f9;">
        <h6 style="font-weight:900; color:var(--sidebar-bg); margin:0; font-size: 18px;">أحدث رسائل الدعم</h6>
        <a href="<?= e(url('admin/contact')) ?>" style="font-size:13px; font-weight:800; color:var(--primary-light);">صندوق الوارد ←</a>
      </div>
      <div style="padding: 0 10px;">
          <?php foreach ($recentMessages as $m): ?>
            <a href="<?= e(url('admin/contact/' . (int)$m['id'])) ?>" style="display:block; border-bottom:1px solid #f1f5f9; padding:15px; text-decoration:none; transition: 0.2s; border-radius: 12px;">
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                  <strong style="font-size:14px; color:var(--sidebar-bg); display: flex; align-items: center; gap: 8px;">
                      <div style="width: 8px; height: 8px; border-radius: 50%; background: <?= $m['status']==='new' ? '#ef4444' : '#cbd5e1' ?>;"></div>
                      <?= e($m['name']) ?>
                  </strong>
                  <small style="color:var(--text-muted); font-weight:700;"><?= dt($m['created_at'], 'm/d H:i') ?></small>
              </div>
              <div style="font-size:13px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding-right: 16px;"><?= e($m['subject'] ?? $m['message']) ?></div>
            </a>
          <?php endforeach; ?>
          <?php if(empty($recentMessages)): ?>
              <div style="padding: 40px; text-align: center; color: var(--text-muted); font-weight: 700;">صندوق الوارد فارغ.</div>
          <?php endif; ?>
      </div>
  </div>
</div>

<style>
    @keyframes pulse { 0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); } 100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // رسم منحنى التبرعات باستخدام Chart.js بشكل متقدم
    const ctx = document.getElementById('trendChart');
    if (ctx) {
        // إنشاء تدرج لوني جميل تحت الخط
        let gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(9, 42, 158, 0.4)');
        gradient.addColorStop(1, 'rgba(9, 42, 158, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($trendLabels) ?>,
                datasets: [{
                    label: 'إجمالي التبرعات (ر.س)', 
                    data: <?= json_encode($trendData) ?>,
                    borderColor: '#092A9E', 
                    backgroundColor: gradient,
                    borderWidth: 3, 
                    fill: true, 
                    tension: 0.4, 
                    pointBackgroundColor: '#F4C978',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#061B52',
                        titleFont: { family: 'Cairo', size: 14 },
                        bodyFont: { family: 'Cairo', size: 14, weight: 'bold' },
                        padding: 12,
                        displayColors: false,
                        cornerRadius: 8
                    }
                }, 
                scales: { 
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#e2e8f0', drawBorder: false },
                        ticks: { font: { family: 'Cairo', weight: 'bold' }, color: '#64748b' }
                    }, 
                    x: { 
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: 'Cairo', weight: 'bold' }, color: '#64748b', maxRotation: 0 }
                    } 
                } 
            }
        });
    }

    // تأثير تحريك الأرقام
    function animateNumber(el, from, to, isMoney) {
      const duration = 1000, start = performance.now();
      function step(now) {
        const p = Math.min(1, (now - start) / duration);
        const v = from + (to - from) * (1 - Math.pow(1 - p, 3)); // Ease Out Cubic
        el.textContent = isMoney ? v.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ر.س' : Math.round(v).toLocaleString('en-US');
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }

    // تحديث البيانات عبر الـ AJAX
    async function refreshStats() {
      try {
        const res = await fetch('<?= url('admin/dashboard/stats') ?>', { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return;
        const data = await res.json();
        const moneyKeys = ['total_donations', 'month_total'];
        
        document.querySelectorAll('[data-stat]').forEach(el => {
          const key = el.dataset.stat;
          if (data[key] === undefined) return;
          const current = parseFloat(el.textContent.replace(/[^\d.]/g, '')) || 0;
          const target = parseFloat(data[key]);
          if (Math.abs(current - target) > 0.001) animateNumber(el, current, target, moneyKeys.includes(key));
        });
      } catch (e) { console.error('Failed to fetch stats', e); }
    }
    
    // التحديث كل 30 ثانية
    setInterval(refreshStats, 30000);
});
</script>