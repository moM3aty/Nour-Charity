<?php
// app/Controllers/Frontend/AssistanceController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Models\AssistanceRequest;

final class AssistanceController extends Controller
{
    public function index(): void
    {
        $this->view('frontend/assistance', ['title' => 'طلب مساعدة']);
    }

    public function submit(): void
    {
        $this->requirePost();

        $v = new Validator($_POST);
        $v->required('full_name', 'الاسم الكامل')
          ->required('phone', 'الهاتف')
          ->required('category', 'نوع المساعدة')
          ->required('description', 'وصف الحاجة')
          ->minLen('description', 20, 'وصف الحاجة');

        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('assistance');
        }

        $id = (new AssistanceRequest())->createRequest([
            'full_name'   => trim((string)$this->input('full_name')),
            'email'       => trim((string)$this->input('email', '')) ?: null,
            'phone'       => trim((string)$this->input('phone')),
            'category'    => trim((string)$this->input('category')),
            'location'    => trim((string)$this->input('location', '')) ?: null,
            'description' => trim((string)$this->input('description')),
        ]);

        // مستندات اختيارية
        if (!empty($_FILES['documents']) && is_array($_FILES['documents']['name'])) {
            $files = $_FILES['documents'];
            $allowed = array_merge(
                config('uploads.allowed_images') ?? [],
                config('uploads.allowed_docs') ?? []
            );
            $count = count($files['name']);
            for ($i = 0; $i < min($count, 5); $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                $file = [
                    'name'     => $files['name'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'size'     => $files['size'][$i],
                    'error'    => $files['error'][$i],
                ];
                $path = store_private_upload($file, 'assistance/' . $id, $allowed);
                if ($path) {
                    Database::execute(
                        "INSERT INTO assistance_documents (request_id, file_path, original_name, uploaded_at)
                         VALUES (?,?,?,NOW())",
                        [$id, $path, substr($file['name'], 0, 200)]
                    );
                }
            }
        }

        $req = Database::fetch("SELECT request_number FROM assistance_requests WHERE id = ?", [$id]);
        Session::flash('success', 'تم استلام طلبك. رقم المتابعة: ' . $req['request_number'] . ' — سنتواصل معك.');
        $this->redirect('assistance');
    }
}