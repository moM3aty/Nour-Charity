<?php
// app/Controllers/Frontend/DonationController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Models\BankAccount;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\GiftDonation;
use App\Models\Project;

final class DonationController extends Controller
{
    public function index(): void
    {
        // ✅ منع الدخول لغير المسجلين
        $this->requireUserLogin('donate');

        $projectId  = (int)($_GET['project'] ?? 0);
        $campaignId = (int)($_GET['campaign'] ?? 0);

        $project = $projectId ? (new Project())->find($projectId) : null;
        $campaign = $campaignId
            ? Database::fetch("SELECT * FROM campaigns WHERE id = ? AND status='active' LIMIT 1", [$campaignId])
            : null;

        $projects = (new Project())->active(50);
        $presets = array_map('intval', array_filter(array_map('trim', explode(',', (string) setting('donation.presets', '50,100,250,500,1000')))));
        if (empty($presets)) $presets = [50,100,250,500,1000];
        $banks = (new BankAccount())->active();

        // جلب بيانات المتبرع عشان نعبي الفورم تلقائياً
        $user = \App\Core\Auth::user();
        $donor = $user ? Database::fetch("SELECT * FROM donors WHERE email = ?", [$user['email']]) : null;

        $this->view('frontend/donate', [
            'title'      => 'تبرع الآن',
            'project'    => $project,
            'campaign'   => $campaign,
            'projects'   => $projects,
            'presets'    => $presets,
            'banks'      => $banks,
            'donor'      => $donor, // ✅ نمرر بيانات المتبرع
            'instructions' => setting('donation.instructions', ''),
        ]);
    }

    public function submit(): void
    {
        // ✅ منع الاستلام لغير المسجلين
        $this->requireUserLogin('donate');
        $this->requirePost();

        $v = new Validator($_POST);
        $v->required('name', 'الاسم')
          ->required('email', 'البريد')->email('email')
          ->required('phone', 'الهاتف')
          ->required('amount', 'المبلغ')->numeric('amount', 'المبلغ');

        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('donate');
        }

        // ... باقي كود الحفظ زي ما هو ...
        $amount = (float) $this->input('amount');
        if ($amount < 1 || $amount > 1000000) {
            Session::flash('error', 'قيمة التبرع غير صحيحة');
            $this->redirect('donate');
        }
        
        $projectId  = (int) ($this->input('project_id', 0));
        $campaignId = (int) ($this->input('campaign_id', 0));
        $isGift     = (int) ($this->input('is_gift', 0)) === 1;

        if ($projectId && !Database::fetch("SELECT id FROM projects WHERE id = ? AND deleted_at IS NULL", [$projectId])) $projectId = 0;
        if ($campaignId && !Database::fetch("SELECT id FROM campaigns WHERE id = ?", [$campaignId])) $campaignId = 0;

        $donorId = (new Donor())->findOrCreate(
            trim((string) $this->input('name')),
            trim((string) $this->input('email')),
            trim((string) $this->input('phone', '')),
            (int) ($this->input('is_anonymous', 0))
        );

        $donationId = (new Donation())->createDonation([
            'donor_id'       => $donorId,
            'project_id'     => $projectId ?: null,
            'campaign_id'    => $campaignId ?: null,
            'amount'         => $amount,
            'currency'       => setting('site.currency', 'SAR'),
            'donation_type'  => $isGift ? 'gift' : ($projectId || $campaignId ? 'one_time' : 'general'),
            'message'        => trim((string) $this->input('message', '')) ?: null,
            'is_anonymous'   => (int) ($this->input('is_anonymous', 0)),
            'payment_method' => 'bank_transfer',
        ]);

        if ($isGift) {
            (new GiftDonation())->create($donationId, [
                'recipient_name'  => trim((string) $this->input('recipient_name', '')),
                'recipient_email' => trim((string) $this->input('recipient_email', '')),
                'message'         => trim((string) $this->input('gift_message', '')),
                'card_design'     => (string) ($this->input('card_design', 'elegant')),
            ]);
        }

        $donation = Database::fetch("SELECT * FROM donations WHERE id = ?", [$donationId]);
        $this->redirect('donate/success?ref=' . urlencode($donation['donation_number']));
    }

    public function success(): void
    {
        $ref = trim((string) ($_GET['ref'] ?? ''));
        if ($ref === '') $this->redirect('donate');
        
        $donation = (new Donation())->byNumber($ref);
        if (!$donation) { http_response_code(404); exit('تبرع غير موجود'); }
        
        $banks = (new BankAccount())->active();
        $this->view('frontend/thank-you', [
            'title'    => 'شكراً لك',
            'donation' => $donation,
            'banks'    => $banks,
        ]);
    }

    public function receipt(): void
    {
        $ref = trim((string) ($_GET['ref'] ?? ''));
        $donation = (new Donation())->byNumber($ref);
        if (!$donation) { http_response_code(404); exit('غير موجود'); }

        $this->view('frontend/receipt', [
            'title'    => 'إيصال تبرع — ' . $donation['donation_number'],
            'donation' => $donation,
            'items'    => (new Donation())->items((int)$donation['id']),
        ], 'frontend_bare');
    }
}