<?php
// config/routes.php
declare(strict_types=1);

/** @var App\Core\Router $router */

/* ============ Frontend ============ */
$router->get('/',              [App\Controllers\Frontend\HomeController::class, 'index']);
$router->get('/about',         [App\Controllers\Frontend\PageController::class, 'about']);
$router->get('/privacy',       [App\Controllers\Frontend\PageController::class, 'privacy']);
$router->get('/terms',         [App\Controllers\Frontend\PageController::class, 'terms']);
$router->get('/banks',         [App\Controllers\Frontend\PageController::class, 'banks']);

$router->get('/projects',        [App\Controllers\Frontend\ProjectController::class, 'index']);
$router->get('/projects/{slug}', [App\Controllers\Frontend\ProjectController::class, 'detail']);
$router->get('/campaigns',        [App\Controllers\Frontend\CampaignController::class, 'index']);
$router->get('/campaigns/{slug}', [App\Controllers\Frontend\CampaignController::class, 'detail']);
$router->get('/news',        [App\Controllers\Frontend\NewsController::class, 'index']);
$router->get('/news/{slug}', [App\Controllers\Frontend\NewsController::class, 'detail']);

$router->get('/contact',  [App\Controllers\Frontend\ContactController::class, 'index']);
$router->post('/contact', [App\Controllers\Frontend\ContactController::class, 'submit']);
$router->get('/volunteers',  [App\Controllers\Frontend\VolunteerController::class, 'index']);
$router->post('/volunteers', [App\Controllers\Frontend\VolunteerController::class, 'submit']);
$router->get('/assistance',  [App\Controllers\Frontend\AssistanceController::class, 'index']);
$router->post('/assistance', [App\Controllers\Frontend\AssistanceController::class, 'submit']);

$router->get('/donate',                 [App\Controllers\Frontend\DonationController::class, 'index']);
$router->post('/donate',                [App\Controllers\Frontend\DonationController::class, 'submit']);
$router->get('/donate/success',         [App\Controllers\Frontend\DonationController::class, 'success']);
$router->get('/donate/receipt',         [App\Controllers\Frontend\DonationController::class, 'receipt']);
$router->get('/donate/receipt/upload',  [App\Controllers\Frontend\ReceiptController::class, 'uploadForm']);
$router->post('/donate/receipt/upload', [App\Controllers\Frontend\ReceiptController::class, 'upload']);

$router->get('/cart',           [App\Controllers\Frontend\CartController::class, 'index']);
$router->post('/cart/checkout', [App\Controllers\Frontend\CartController::class, 'checkout']);

$router->get('/gift',         [App\Controllers\Frontend\GiftController::class, 'index']);
$router->get('/gift/{token}', [App\Controllers\Frontend\GiftController::class, 'show']);

$router->get('/sitemap.xml', [App\Controllers\Frontend\SitemapController::class, 'sitemap']);

/* ============ Admin Auth ============ */
$router->get('/admin',           [App\Controllers\Admin\AdminAuthController::class, 'loginForm']);
$router->get('/admin/login',     [App\Controllers\Admin\AdminAuthController::class, 'loginForm']);
$router->post('/admin/login',    [App\Controllers\Admin\AdminAuthController::class, 'login']);
$router->get('/admin/logout',    [App\Controllers\Admin\AdminAuthController::class, 'logout']);
$router->get('/admin/dashboard', [App\Controllers\Admin\AdminDashboardController::class, 'index']);
// ✅ نقطة جديدة: تحديث الإحصائيات لحظياً
$router->get('/admin/dashboard/stats', [App\Controllers\Admin\AdminDashboardController::class, 'stats']);

/* ============ Admin: Notifications ============ */
$router->get('/admin/notifications',                     [App\Controllers\Admin\AdminNotificationController::class, 'index']);
$router->post('/admin/notifications/read-all',           [App\Controllers\Admin\AdminNotificationController::class, 'markAllRead']);
$router->post('/admin/notifications/{id}/read',          [App\Controllers\Admin\AdminNotificationController::class, 'markRead']);

/* ============ Admin: Reports ============ */
$router->get('/admin/reports',        [App\Controllers\Admin\AdminReportController::class, 'index']);
$router->get('/admin/reports/export', [App\Controllers\Admin\AdminReportController::class, 'exportCsv']);
$router->get('/admin/reports/print',  [App\Controllers\Admin\AdminReportController::class, 'print']);

/* ============ Admin: Audit ============ */
$router->get('/admin/audit', [App\Controllers\Admin\AdminAuditController::class, 'index']);

/* ============ Admin: Donations ============ */
$router->get('/admin/donations',              [App\Controllers\Admin\AdminDonationController::class, 'index']);
$router->get('/admin/donations/export',       [App\Controllers\Admin\AdminDonationController::class, 'exportCsv']);
$router->get('/admin/donations/offline',      [App\Controllers\Admin\AdminOfflineDonationController::class, 'create']);
$router->post('/admin/donations/offline',     [App\Controllers\Admin\AdminOfflineDonationController::class, 'store']);
$router->get('/admin/donations/{id}',         [App\Controllers\Admin\AdminDonationController::class, 'show']);
$router->post('/admin/donations/{id}/verify', [App\Controllers\Admin\AdminDonationController::class, 'verify']);
$router->get('/admin/receipts/{id}/view',     [App\Controllers\Admin\AdminDonationController::class, 'viewReceipt']);

/* ============ Admin: Banks ============ */
$router->get('/admin/banks',              [App\Controllers\Admin\AdminBankController::class, 'index']);
$router->post('/admin/banks/store',       [App\Controllers\Admin\AdminBankController::class, 'store']);
$router->post('/admin/banks/delete/{id}', [App\Controllers\Admin\AdminBankController::class, 'delete']);

/* ============ Admin: Projects ============ */
$router->get('/admin/projects',                                 [App\Controllers\Admin\AdminProjectController::class, 'index']);
$router->get('/admin/projects/create',                          [App\Controllers\Admin\AdminProjectController::class, 'create']);
$router->post('/admin/projects/store',                          [App\Controllers\Admin\AdminProjectController::class, 'store']);
$router->get('/admin/projects/{id}/edit',                       [App\Controllers\Admin\AdminProjectController::class, 'edit']);
$router->post('/admin/projects/{id}',                           [App\Controllers\Admin\AdminProjectController::class, 'update']);
$router->post('/admin/projects/{id}/delete',                    [App\Controllers\Admin\AdminProjectController::class, 'delete']);
$router->post('/admin/projects/{id}/images/upload',             [App\Controllers\Admin\AdminProjectController::class, 'uploadImage']);
$router->post('/admin/projects/{id}/images/{imageId}/delete',   [App\Controllers\Admin\AdminProjectController::class, 'deleteImage']);
$router->post('/admin/projects/{id}/updates',                   [App\Controllers\Admin\AdminProjectController::class, 'addUpdate']);
$router->post('/admin/projects/{id}/updates/{updateId}/delete', [App\Controllers\Admin\AdminProjectController::class, 'deleteUpdate']);

/* ============ Admin: Campaigns ============ */
$router->get('/admin/campaigns',              [App\Controllers\Admin\AdminCampaignController::class, 'index']);
$router->get('/admin/campaigns/create',       [App\Controllers\Admin\AdminCampaignController::class, 'create']);
$router->post('/admin/campaigns/store',       [App\Controllers\Admin\AdminCampaignController::class, 'store']);
$router->get('/admin/campaigns/{id}/edit',    [App\Controllers\Admin\AdminCampaignController::class, 'edit']);
$router->post('/admin/campaigns/{id}',        [App\Controllers\Admin\AdminCampaignController::class, 'update']);
$router->post('/admin/campaigns/{id}/delete', [App\Controllers\Admin\AdminCampaignController::class, 'delete']);

/* ============ Admin: Categories ============ */
$router->get('/admin/categories',              [App\Controllers\Admin\AdminCategoryController::class, 'index']);
$router->post('/admin/categories/save',        [App\Controllers\Admin\AdminCategoryController::class, 'save']);
$router->post('/admin/categories/delete/{id}', [App\Controllers\Admin\AdminCategoryController::class, 'delete']);

/* ============ Admin: Articles ============ */
$router->get('/admin/articles',                          [App\Controllers\Admin\AdminArticleController::class, 'index']);
$router->get('/admin/articles/create',                   [App\Controllers\Admin\AdminArticleController::class, 'create']);
$router->post('/admin/articles/store',                   [App\Controllers\Admin\AdminArticleController::class, 'store']);
$router->get('/admin/articles/categories',               [App\Controllers\Admin\AdminArticleController::class, 'categories']);
$router->post('/admin/articles/categories/save',         [App\Controllers\Admin\AdminArticleController::class, 'categorySave']);
$router->post('/admin/articles/categories/delete/{id}',  [App\Controllers\Admin\AdminArticleController::class, 'categoryDelete']);
$router->get('/admin/articles/{id}/edit',                [App\Controllers\Admin\AdminArticleController::class, 'edit']);
$router->post('/admin/articles/{id}',                    [App\Controllers\Admin\AdminArticleController::class, 'update']);
$router->post('/admin/articles/{id}/delete',             [App\Controllers\Admin\AdminArticleController::class, 'delete']);

/* ============ Admin: Users ============ */
$router->get('/admin/users',                        [App\Controllers\Admin\AdminUserController::class, 'index']);
$router->get('/admin/users/create',                 [App\Controllers\Admin\AdminUserController::class, 'create']);
$router->post('/admin/users/store',                 [App\Controllers\Admin\AdminUserController::class, 'store']);
$router->get('/admin/users/roles',                  [App\Controllers\Admin\AdminUserController::class, 'roles']);
$router->get('/admin/users/roles/{id}/permissions', [App\Controllers\Admin\AdminUserController::class, 'rolePermissions']);
$router->post('/admin/users/roles/{id}/permissions',[App\Controllers\Admin\AdminUserController::class, 'rolePermissionsSave']);
$router->get('/admin/users/{id}/edit',              [App\Controllers\Admin\AdminUserController::class, 'edit']);
$router->post('/admin/users/{id}',                  [App\Controllers\Admin\AdminUserController::class, 'update']);
$router->post('/admin/users/{id}/delete',           [App\Controllers\Admin\AdminUserController::class, 'delete']);

/* ============ Admin: Media ============ */
$router->get('/admin/media',              [App\Controllers\Admin\AdminMediaController::class, 'index']);
$router->post('/admin/media/upload',      [App\Controllers\Admin\AdminMediaController::class, 'upload']);
// ✅ نقطة جديدة: رفع صور من محرر TinyMCE
$router->post('/admin/editor/upload',     [App\Controllers\Admin\AdminMediaController::class, 'editorUpload']);
$router->post('/admin/media/{id}/delete', [App\Controllers\Admin\AdminMediaController::class, 'delete']);

/* ============ Admin: Hero ============ */
$router->get('/admin/hero',              [App\Controllers\Admin\AdminHeroController::class, 'index']);
$router->post('/admin/hero/save',        [App\Controllers\Admin\AdminHeroController::class, 'save']);
$router->post('/admin/hero/{id}/delete', [App\Controllers\Admin\AdminHeroController::class, 'delete']);

/* ============ Admin: Volunteers ============ */
$router->get('/admin/volunteers',                                 [App\Controllers\Admin\AdminVolunteerController::class, 'index']);
$router->get('/admin/volunteers/export',                          [App\Controllers\Admin\AdminVolunteerController::class, 'exportCsv']);
$router->get('/admin/volunteers/opportunities',                   [App\Controllers\Admin\AdminVolunteerController::class, 'opportunities']);
$router->post('/admin/volunteers/opportunities/save',             [App\Controllers\Admin\AdminVolunteerController::class, 'opportunitySave']);
$router->post('/admin/volunteers/opportunities/delete/{id}',      [App\Controllers\Admin\AdminVolunteerController::class, 'opportunityDelete']);
$router->get('/admin/volunteers/{id}',                            [App\Controllers\Admin\AdminVolunteerController::class, 'show']);
$router->get('/admin/volunteers/{id}/cv',                         [App\Controllers\Admin\AdminVolunteerController::class, 'downloadCv']);
$router->post('/admin/volunteers/{id}/status',                    [App\Controllers\Admin\AdminVolunteerController::class, 'updateStatus']);

/* ============ Admin: Assistance ============ */
$router->get('/admin/assistance',                          [App\Controllers\Admin\AdminAssistanceController::class, 'index']);
$router->get('/admin/assistance/{id}',                     [App\Controllers\Admin\AdminAssistanceController::class, 'show']);
$router->post('/admin/assistance/{id}/status',             [App\Controllers\Admin\AdminAssistanceController::class, 'updateStatus']);
$router->get('/admin/assistance/documents/{id}/download',  [App\Controllers\Admin\AdminAssistanceController::class, 'downloadDoc']);

/* ============ Admin: Partners ============ */
$router->get('/admin/partners',              [App\Controllers\Admin\AdminPartnerController::class, 'index']);
$router->post('/admin/partners/save',        [App\Controllers\Admin\AdminPartnerController::class, 'save']);
$router->post('/admin/partners/delete/{id}', [App\Controllers\Admin\AdminPartnerController::class, 'delete']);

/* ============ Admin: Settings ============ */
$router->get('/admin/settings',       [App\Controllers\Admin\AdminSettingsController::class, 'index']);
$router->post('/admin/settings/save', [App\Controllers\Admin\AdminSettingsController::class, 'save']);

/* ============ Admin: Pages ============ */
$router->get('/admin/pages',              [App\Controllers\Admin\AdminPageController::class, 'index']);
$router->get('/admin/pages/about',        [App\Controllers\Admin\AdminPageController::class, 'aboutSections']);
$router->post('/admin/pages/about',       [App\Controllers\Admin\AdminPageController::class, 'aboutSectionsSave']);
$router->get('/admin/pages/{id}',         [App\Controllers\Admin\AdminPageController::class, 'edit']);
$router->post('/admin/pages/{id}',        [App\Controllers\Admin\AdminPageController::class, 'save']);

/* ============ Admin: Contact ============ */
$router->get('/admin/contact',              [App\Controllers\Admin\AdminContactController::class, 'index']);
$router->get('/admin/contact/{id}',         [App\Controllers\Admin\AdminContactController::class, 'show']);
$router->post('/admin/contact/{id}/status', [App\Controllers\Admin\AdminContactController::class, 'updateStatus']);

$router->get('/gallery', [App\Controllers\Frontend\GalleryController::class, 'index']);
/* User Auth */
$router->get('/login',    [App\Controllers\Frontend\UserAuthController::class, 'loginForm']);
$router->post('/login',   [App\Controllers\Frontend\UserAuthController::class, 'login']);
$router->get('/register', [App\Controllers\Frontend\UserAuthController::class, 'registerForm']);
$router->post('/register',[App\Controllers\Frontend\UserAuthController::class, 'register']);
$router->get('/logout',   [App\Controllers\Frontend\UserAuthController::class, 'logout']);
$router->get('/profile',  [App\Controllers\Frontend\UserAuthController::class, 'profile']);