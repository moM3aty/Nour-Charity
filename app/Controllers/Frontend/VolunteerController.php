<?php
// app/Controllers/Frontend/VolunteerController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;

final class VolunteerController extends Controller
{
    public function index(): void
    {
        $opps = Database::fetchAll("SELECT * FROM volunteer_opportunities WHERE status='open' ORDER BY created_at DESC");
        $this->view('frontend/volunteers', ['title' => 'التطوّع', 'opportunities' => $opps]);
    }

        public function submit(): void
    {
        $this->requirePost();
        $v = new Validator($_POST);
        $v->required('name', 'الاسم')->required('email', 'البريد')->email('email')->required('phone', 'الهاتف');
        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('volunteers');
        }

        // رفع CV اختياري
        $cvPath = null;
        if (!empty($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $allowed = array_merge(
                config('uploads.allowed_docs') ?? [],
                ['application/pdf']
            );
            $cvPath = store_private_upload($_FILES['cv'], 'volunteer-cv', $allowed);
        }

        Database::execute(
            "INSERT INTO volunteer_applications (name,email,phone,city,skills,interests,availability,message,cv_path,status,created_at)
             VALUES (?,?,?,?,?,?,?,?,?,'new',NOW())",
            [
                trim((string)$this->input('name')),
                trim((string)$this->input('email')),
                trim((string)$this->input('phone')),
                trim((string)$this->input('city', '')) ?: null,
                trim((string)$this->input('skills', '')) ?: null,
                trim((string)$this->input('interests', '')) ?: null,
                trim((string)$this->input('availability', '')) ?: null,
                trim((string)$this->input('message', '')) ?: null,
                $cvPath,
            ]
        );
        Session::flash('success', 'شكراً لك! تم استلام طلب التطوع وسنتواصل معك.');
        $this->redirect('volunteers');
    }
}