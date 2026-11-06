<?php
// app/Controllers/Frontend/ContactController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;

final class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('frontend/contact', ['title' => 'تواصل معنا']);
    }

    public function submit(): void
    {
        $this->requirePost();
        $v = new Validator($_POST);
        $v->required('name', 'الاسم')->required('email', 'البريد')->email('email')
          ->required('message', 'الرسالة')->minLen('message', 10, 'الرسالة');
        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('contact');
        }
        Database::execute(
            "INSERT INTO contact_messages (name,email,phone,subject,message,status,created_at) VALUES (?,?,?,?,?,'new',NOW())",
            [
                trim((string)$this->input('name')),
                trim((string)$this->input('email')),
                trim((string)$this->input('phone', '')) ?: null,
                trim((string)$this->input('subject', '')) ?: null,
                trim((string)$this->input('message')),
            ]
        );
        Session::flash('success', 'تم إرسال رسالتك بنجاح، سنرد عليك قريباً.');
        $this->redirect('contact');
    }
}