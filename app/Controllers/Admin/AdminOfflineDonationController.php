<?php
// app/Controllers/Admin/AdminOfflineDonationController.php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Project;

final class AdminOfflineDonationController extends Controller
{
    public function create(): void
    {
        $this->requireAuth('donations.manage');
        $this->view('admin/donations/offline', [
            'title'    => 'تسجيل تبرع يدوي',
            'projects' => (new Project())->all('title ASC'),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('donations.manage');
        $this->requirePost();

        $v = new Validator($_POST);
        $v->required('name', 'الاسم')->required('amount', 'المبلغ')->numeric('amount', 'المبلغ');
        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('admin/donations/offline');
        }
        $amount = (float)$this->input('amount');
        if ($amount < 1) {
            Session::flash('error', 'المبلغ غير صالح.');
            $this->redirect('admin/donations/offline');
        }

        $donorId = (new Donor())->findOrCreate(
            trim((string)$this->input('name')),
            trim((string)$this->input('email', '')),
            trim((string)$this->input('phone', '')),
            0
        );

        $method = in_array($this->input('payment_method'), ['cash','offline','bank_transfer'], true)
            ? $this->input('payment_method') : 'offline';
        $projectId = (int)$this->input('project_id', 0) ?: null;

        $donationId = (new Donation())->createDonation([
            'donor_id'      => $donorId,
            'project_id'    => $projectId,
            'amount'        => $amount,
            'currency'      => setting('site.currency', 'SAR'),
            'donation_type' => 'one_time',
            'message'       => trim((string)$this->input('message', '')) ?: null,
            'payment_method'=> $method,
        ]);

        // Immediately mark as paid (recorded by admin)
        (new Donation())->updateStatus($donationId, 'paid', Auth::id(), 'Recorded manually by admin');

        // record audit
        \App\Core\AuditLog::record('donation.offline', 'donation', $donationId, "Offline donation recorded: $amount SAR");

        Session::flash('success', 'تم تسجيل التبرع بنجاح برقم ' . Database::fetch("SELECT donation_number FROM donations WHERE id = ?", [$donationId])['donation_number']);
        $this->redirect('admin/donations/' . $donationId);
    }
}