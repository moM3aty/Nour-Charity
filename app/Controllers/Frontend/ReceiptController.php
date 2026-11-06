<?php
// app/Controllers/Frontend/ReceiptController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Database;
use App\Core\Session;
use App\Models\Donation;
use App\Models\TransferReceipt;

final class ReceiptController extends Controller
{
    /**
     * صفحة رفع صورة إيصال التحويل البنكي
     */
    public function uploadForm(): void
    {
        $ref = trim((string)($_GET['ref'] ?? ''));
        $donation = (new Donation())->byNumber($ref);
        if (!$donation) { http_response_code(404); exit('تبرع غير موجود'); }

        $this->view('frontend/upload-receipt', [
            'title'    => 'رفع إيصال التحويل',
            'donation' => $donation,
            'existing' => (new TransferReceipt())->forDonation((int)$donation['id']),
        ]);
    }

    /**
     * استقبال الملف — يُحفظ في storage/private (خارج الويب)
     */
    public function upload(): void
    {
        $this->requirePost();

        $ref = trim((string) $this->input('ref', ''));
        $donation = (new Donation())->byNumber($ref);
        if (!$donation) { http_response_code(404); exit('تبرع غير موجود'); }

        if (in_array($donation['status'], ['paid', 'cancelled', 'refunded'], true)) {
            Session::flash('error', 'لا يمكن رفع إيصال لهذا التبرع — الحالة الحالية لا تسمح.');
            $this->redirect('donate/success?ref=' . urlencode($ref));
        }

        if (empty($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'يرجى اختيار ملف صورة أو PDF صحيح.');
            $this->redirect('donate/receipt?ref=' . urlencode($ref));
        }

        $file = $_FILES['receipt'];
        $maxSize = (int) (config('uploads.max_size') ?? (8 * 1024 * 1024));
        if ($file['size'] > $maxSize) {
            Session::flash('error', 'حجم الملف يتجاوز الحد المسموح (8 ميجا).');
            $this->redirect('donate/receipt?ref=' . urlencode($ref));
        }

        // تحقق MIME
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        $allowed = array_merge(
            config('uploads.allowed_images') ?? [],
            ['application/pdf']
        );
        if (!in_array($mime, $allowed, true)) {
            Session::flash('error', 'صيغة الملف غير مسموحة. JPG/PNG/WebP/PDF فقط.');
            $this->redirect('donate/receipt?ref=' . urlencode($ref));
        }

        $ext = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            default => 'bin',
        };
        $dir = (config('uploads.private_path') ?? (BASE_PATH . '/storage/private')) . '/receipts/' . date('Y/m');
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
            Session::flash('error', 'خطأ في تجهيز مجلد التخزين.');
            $this->redirect('donate/receipt?ref=' . urlencode($ref));
        }
        $name = 'rcpt_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            Session::flash('error', 'فشل حفظ الملف. حاول لاحقاً.');
            $this->redirect('donate/receipt?ref=' . urlencode($ref));
        }

        // المسار النسبي داخل private_path
        $relative = str_replace((config('uploads.private_path') ?? (BASE_PATH . '/storage/private')), '', $dest);
        $relative = ltrim(str_replace('\\', '/', $relative), '/');

        Database::execute(
            "INSERT INTO transfer_receipts (donation_id, file_path, original_name, mime_type, uploaded_at)
             VALUES (?,?,?,?,NOW())",
            [(int)$donation['id'], $relative, substr($file['name'], 0, 200), $mime]
        );

        // ترقية الحالة لـawaiting_verification
        if ($donation['status'] === 'pending') {
            (new Donation())->updateStatus((int)$donation['id'], 'awaiting_verification');
        }

        Session::flash('success', 'تم استلام الإيصال. سيتم التحقق منه خلال ساعات قليلة.');
        $this->redirect('donate/success?ref=' . urlencode($ref));
    }
}