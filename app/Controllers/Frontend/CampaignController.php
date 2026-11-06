<?php
// app/Controllers/Frontend/CampaignController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Models\Campaign;

final class CampaignController extends Controller
{
    public function index(): void
    {
        $this->view('frontend/campaigns', [
            'title'     => 'الحملات',
            'campaigns' => (new Campaign())->active(20),
        ]);
    }

    public function detail(string $slug): void
    {
        $c = (new Campaign())->bySlug($slug);
        if (!$c) { http_response_code(404); $this->view('errors/404', ['title'=>'غير موجود']); return; }
        $this->view('frontend/campaign-detail', ['title' => $c['title'], 'campaign' => $c]);
    }
}