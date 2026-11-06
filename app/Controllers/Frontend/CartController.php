<?php
// app/Controllers/Frontend/CartController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Project;

final class CartController extends Controller
{
    public function index(): void
    {
        // ✅ منع الدخول للسلة لغير المسجلين
        $this->requireUserLogin('cart');
        
        $projects = (new Project())->active(50);
        $user = \App\Core\Auth::user();
        $donor = $user ? Database::fetch("SELECT * FROM donors WHERE email = ?", [$user['email']]) : null;

        $this->view('frontend/cart', [
            'title'    => 'سلة التبرعات',
            'projects' => $projects,
            'donor'    => $donor,
        ]);
    }

    public function checkout(): void
    {
        $this->requireUserLogin('cart');
        $this->requirePost();

        $v = new Validator($_POST);
        $v->required('name', 'الاسم')
          ->required('email', 'البريد')->email('email')
          ->required('phone', 'الهاتف');

        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('cart');
        }

        $cartJson = (string) $this->input('cart_json', '[]');
        $cart = json_decode($cartJson, true);
        if (!is_array($cart) || empty($cart)) {
            Session::flash('error', 'السلة فارغة.');
            $this->redirect('cart');
        }

        // نتحقق من كل عنصر: مشروع موجود، مبلغ منطقي
        $validItems = [];
        $total = 0.0;
        foreach ($cart as $it) {
            $amount = (float) ($it['amount'] ?? 0);
            if ($amount < 1 || $amount > 1000000) continue;
            $pid = (int) ($it['project_id'] ?? 0);
            if ($pid > 0 && !Database::fetch("SELECT id FROM projects WHERE id = ? AND deleted_at IS NULL", [$pid])) {
                $pid = 0;
            }
            $validItems[] = ['project_id' => $pid ?: null, 'amount' => $amount];
            $total += $amount;
        }

        if (empty($validItems) || $total <= 0) {
            Session::flash('error', 'لا توجد عناصر صالحة في السلة.');
            $this->redirect('cart');
        }

        $donorId = (new Donor())->findOrCreate(
            trim((string) $this->input('name')),
            trim((string) $this->input('email')),
            trim((string) $this->input('phone', '')),
            (int) ($this->input('is_anonymous', 0))
        );

        $donationId = (new Donation())->createFromCart(
            [
                'donor_id'      => $donorId,
                'message'       => trim((string) $this->input('message', '')) ?: null,
                'is_anonymous'  => (int) ($this->input('is_anonymous', 0)),
            ],
            $validItems,
            'bank_transfer'
        );

        $donation = Database::fetch("SELECT * FROM donations WHERE id = ?", [$donationId]);
        $this->redirect('donate/success?ref=' . urlencode($donation['donation_number']));
    }
}