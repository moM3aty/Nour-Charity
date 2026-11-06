<?php
// app/Controllers/Frontend/ProjectController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Project;

final class ProjectController extends Controller
{
       public function index(): void
    {
        $search     = trim((string)($_GET['q'] ?? ''));
        $categoryId = (int)($_GET['cat'] ?? 0);
        $page       = max(1, (int)($_GET['page'] ?? 1));
        
        // جلب المشاريع
        $projectsData = (new Project())->paginate($search, $categoryId, 'active', $page, 9);
        
        $this->view('frontend/projects', [
            'title'        => 'المشاريع الخيرية',
            'projectsData' => $projectsData, 
            'categories'   => (new Category())->active(),
            'search'       => $search,
            'categoryId'   => $categoryId,
        ]);
    }

    public function detail(string $slug): void
    {
        $p = (new Project())->bySlug($slug);
        if (!$p) { 
            http_response_code(404); 
            $this->view('errors/404', ['title'=>'غير موجود']); 
            return; 
        }
        
        $this->view('frontend/project-detail', [
            'title'            => $p['title'],
            'meta_description' => (string)($p['short_description'] ?? ''),
            'project'          => $p,
            'images'           => (new Project())->images((int)$p['id']),
            'packages'         => (new Project())->packages((int)$p['id']),
            'related'          => (new Project())->related((int)$p['category_id'], (int)$p['id'], 3),
        ]);
    }
}