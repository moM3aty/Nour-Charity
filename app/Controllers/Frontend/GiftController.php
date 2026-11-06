<?php
// app/Controllers/Frontend/GiftController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Models\GiftDonation;

final class GiftController extends Controller
{
    public function index(): void
    {
        $this->view('frontend/gift', [
            'title'    => 'تبرع هدية',
            'projects' => (new \App\Models\Project())->active(50),
        ]);
    }

    /**
     * عرض بطاقة الهدية بعد تأكيد الدفع (بواسطة token)
     */
    public function show(string $token): void
    {
        $gift = (new GiftDonation())->byToken($token);
        if (!$gift) { http_response_code(404); exit('بطاقة غير موجودة أو التبرع لم يتم تأكيده بعد.'); }
        $this->view('frontend/gift-card', [
            'title' => 'بطاقة هدية',
            'gift'  => $gift,
        ], 'frontend_bare');
    }
}