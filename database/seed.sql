-- =========================================================
-- Nour Makha - Seed (Essential)
-- ⚠️ DEMO: this data is safe starter data.
-- =========================================================
SET NAMES utf8mb4;

-- Roles
INSERT INTO roles (name, slug, description) VALUES
('Super Admin',        'super_admin',        'Full access'),
('Administrator',      'administrator',      'Manage all except critical'),
('Content Editor',     'content_editor',     'Manage content, news, pages'),
('Donation Manager',   'donation_manager',   'Manage donations & payments'),
('Volunteer Manager',  'volunteer_manager',  'Manage volunteer applications'),
('Viewer',             'viewer',             'Read-only access')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Permissions
INSERT INTO permissions (name, slug, description) VALUES
('Manage Projects',            'projects.manage',        ''),
('Manage Donations',           'donations.manage',       ''),
('Verify Transfers',           'transfers.verify',       ''),
('Manage Campaigns',           'campaigns.manage',       ''),
('Manage Content',             'content.manage',         ''),
('Manage Media',               'media.manage',           ''),
('Manage News',                'news.manage',            ''),
('Manage Volunteers',          'volunteers.manage',      ''),
('Manage Assistance',          'assistance.manage',      ''),
('Manage Partners',            'partners.manage',        ''),
('Manage Users',               'users.manage',           ''),
('Manage Settings',            'settings.manage',        ''),
('View Reports',               'reports.view',           ''),
('View Audit Logs',            'audit.view',             '')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Super Admin gets everything
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r CROSS JOIN permissions p WHERE r.slug = 'super_admin';

-- Admin gets almost everything except users/settings
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r CROSS JOIN permissions p
WHERE r.slug = 'administrator'
  AND p.slug NOT IN ('users.manage','settings.manage');

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('content.manage','news.manage','media.manage')
WHERE r.slug = 'content_editor';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug IN ('donations.manage','transfers.verify','campaigns.manage','reports.view')
WHERE r.slug = 'donation_manager';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug = 'volunteers.manage'
WHERE r.slug = 'volunteer_manager';

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id FROM roles r JOIN permissions p ON p.slug = 'reports.view'
WHERE r.slug = 'viewer';

-- Settings (general + brand)
INSERT INTO settings (`key`, `value`, `group`, `type`) VALUES
('site.name_ar',            'نور مخة العالمية للأعمال الخيرية', 'brand', 'text'),
('site.name_en',            'Nour Makha International', 'brand', 'text'),
('site.assoc_name_ar',      'جمعية نور للأعمال الخيرية', 'brand', 'text'),
('site.description',        'منصة إنسانية خيرية مستقلة تهدف إلى صناعة الأثر في حياة الأسر المحتاجة.', 'brand', 'textarea'),
('site.logo',               '/assets/images/logo.png', 'brand', 'image'),
('site.favicon',            '/assets/images/favicon.png', 'brand', 'image'),
('brand.primary',           '#092A9E', 'brand', 'color'),
('brand.navy',              '#061B52', 'brand', 'color'),
('brand.secondary',         '#427BD4', 'brand', 'color'),
('brand.ice',               '#EAF2FF', 'brand', 'color'),
('brand.gold',              '#F4C978', 'brand', 'color'),
('site.phone',              '', 'contact', 'text'),
('site.whatsapp',           '', 'contact', 'text'),
('site.email',              '', 'contact', 'text'),
('site.address',            '', 'contact', 'textarea'),
('site.facebook',           '', 'social', 'text'),
('site.twitter',            '', 'social', 'text'),
('site.instagram',          '', 'social', 'text'),
('site.youtube',            '', 'social', 'text'),
('site.currency',           'SAR', 'general', 'text'),
('site.currency_symbol',    'ر.س', 'general', 'text'),
('seo.default_title',       'نور مخة العالمية للأعمال الخيرية', 'seo', 'text'),
('seo.default_description', 'منصة خيرية إنسانية مستقلة لصناعة الأثر في حياة المحتاجين.', 'seo', 'textarea'),
('maintenance.mode',        '0', 'general', 'boolean'),
('hero.title',              'معًا نصنع الأمل... ونضيء حياة الآخرين', 'hero', 'text'),
('hero.description',        'في نور مخة العالمية، نؤمن أن كل عطاء مهما كان حجمه قادر على صناعة فرق حقيقي في حياة إنسان.', 'hero', 'textarea'),
('hero.cta_primary_text',   'تبرع الآن', 'hero', 'text'),
('hero.cta_primary_link',   '/donate', 'hero', 'text'),
('hero.cta_secondary_text', 'اكتشف مشاريعنا', 'hero', 'text'),
('hero.cta_secondary_link', '/projects', 'hero', 'text'),
('donation.presets',        '50,100,250,500,1000', 'donations', 'text'),
('donation.instructions',   'يرجى تحويل المبلغ إلى الحساب البنكي ثم رفع صورة التحويل لتأكيد التبرع.', 'donations', 'textarea')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- Donation categories
INSERT INTO categories (name, slug, description, icon, sort_order) VALUES
('كفالة الأيتام',           'orphan-sponsorship', 'كفالة ورعاية الأيتام', 'baby', 1),
('المساعدات الغذائية',      'food-aid',           'سلال غذائية للأسر المحتاجة', 'utensils', 2),
('الرعاية الصحية',          'healthcare',         'دعم علاجي وصحي', 'heart-pulse', 3),
('التعليم',                 'education',          'دعم تعليم الطلاب', 'graduation-cap', 4),
('سقيا المياه',             'water',              'حفر آبار وتوفير مياه', 'droplets', 5),
('بناء وتجهيز المرافق',     'facilities',         'بناء مساجد ومراكز', 'building', 6),
('مساعدة الأسر المحتاجة',   'families',           'دعم الأسر المتعففة', 'home', 7),
('الصدقات العامة',          'general-sadaqah',    'صدقات تطوعية', 'hand-coins', 8),
('الحملات الموسمية',        'seasonal',           'حملات رمضان والأضحى', 'calendar-heart', 9),
('دعم المشاريع الإنسانية',  'humanitarian',       'دعم المشاريع الإنسانية', 'globe', 10)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Article categories
INSERT INTO article_categories (name, slug) VALUES
('أخبار الجمعية', 'news'),
('تقارير ميدانية', 'field-reports'),
('قصص نجاح', 'success-stories')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Legal pages
INSERT INTO pages (slug, title, content, status) VALUES
('privacy', 'سياسة الخصوصية', '<p>سيتم إضافة سياسة الخصوصية النهائية من قبل الإدارة.</p>', 'published'),
('terms',   'الشروط والأحكام', '<p>سيتم إضافة الشروط والأحكام النهائية من قبل الإدارة.</p>', 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Bank account placeholder (inactive until admin fills real data)
INSERT INTO bank_accounts (bank_name, account_holder, account_number, iban, instructions, is_active) VALUES
('— أدخل اسم البنك —', 'جمعية نور للأعمال الخيرية', '—', '—',
 'قم بالتحويل ثم ارفع صورة الإيصال لتأكيد التبرع.', 0);
 -- عرض تجريبي
INSERT INTO projects (category_id, title, slug, short_description, target_amount, raised_amount, status, is_featured, cover_image)
VALUES 
(1, 'كفالة 100 يتيم', 'orphan-sponsorship-100', 'كفالة شهرية لـ100 يتيم في المناطق المحتاجة', 100000, 45000, 'active', 1, NULL),
(2, 'سلال غذائية للأسر', 'food-baskets', 'توفير سلال غذائية شهرية للأسر المتعففة', 50000, 32000, 'active', 1, NULL),
(3, 'عيادة متنقلة', 'mobile-clinic', 'عيادة طبية متنقلة للقرى النائية', 200000, 80000, 'active', 1, NULL);

INSERT INTO campaigns (title, slug, description, goal_amount, raised_amount, status, is_active)
VALUES 
('حملة الشتاء الدافئ', 'winter-warmth', 'توفير ملابس وأغطية شتوية للأسر المحتاجة', 80000, 35000, 'active', 1),
('إفطار صائم', 'iftar', 'توفير وجبات إفطار خلال رمضان', 120000, 67000, 'active', 1);

INSERT INTO articles (title, slug, excerpt, content, status, published_at)
VALUES 
('نور مخة تطلق حملتها الجديدة', 'new-campaign-launch', 'تطلق الجمعية حملة شاملة لدعم الأسر المحتاجة.', '<p>تفاصيل الخبر...</p>', 'published', NOW()),
('تقرير ميداني: نتائج زيارة القرية', 'field-report-1', 'نتائج زيارة ميدانية شاملة.', '<p>تفاصيل التقرير...</p>', 'published', NOW()),
('قصة نجاح: من الحاجة إلى الاستقرار', 'success-story-1', 'قصة ملهمة لأحد المستفيدين.', '<p>القصة الكاملة...</p>', 'published', NOW());
INSERT INTO page_sections (page_key, section_key, title, content, is_active, sort_order) VALUES
('about', 'story', 'من فكرة إلى أثر',
 '<p>انطلقت جمعية نور للأعمال الخيرية إيماناً منّا بأن العطاء لا يعرف حدوداً، وأن العمل الخيري المنظم قادر على تغيير حياة إنسان. اليوم نعمل على مشاريع في مجالات الغذاء، الصحة، التعليم، المياه، وكفالة الأيتام.</p><p>نعتمد على فريق من المتطوعين المخلصين، وشراكة مع متبرعين كرماء، ونظام شفافية كامل يتيح تتبع كل ريال حتى وصوله لمستحقيه.</p>', 1, 1),
('about', 'vision', 'رؤيتنا',
 '<p>مجتمع إنساني متكافل، لا يُترك فيه محتاج دون عون.</p>', 1, 2),
('about', 'mission', 'رسالتنا',
 '<p>تقديم مساعدات إنسانية موثقة بشفافية لرفع المعاناة عن الأسر والأطفال المحتاجين.</p>', 1, 3),
('about', 'values', 'قيمنا',
 '<p>الشفافية، الرحمة، الاحترافية، الأمانة، احترام الكرامة الإنسانية.</p>', 1, 4),
('about', 'objectives', 'أهدافنا',
 '<ul><li>تقديم المساعدات الغذائية والصحية للأسر المحتاجة</li><li>كفالة الأيتام ورعاية الطلاب المحتاجين</li><li>تنفيذ مشاريع المياه والبنية التحتية</li><li>تأهيل وتدريب المتطوعين</li><li>تعزيز ثقافة العمل الخيري</li></ul>', 1, 5)
ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content);
-- Demo hero slide
INSERT INTO hero_slides (title, subtitle, description, cta_text, cta_link, secondary_text, secondary_link, sort_order, is_active)
VALUES ('معًا نصنع الأمل... ونضيء حياة الآخرين', 'جمعية نور للأعمال الخيرية',
        'في نور مخة العالمية، نؤمن أن كل عطاء مهما كان حجمه قادر على صناعة فرق حقيقي في حياة إنسان.',
        'تبرع الآن', '/donate', 'اكتشف مشاريعنا', '/projects', 1, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);
INSERT INTO notifications (user_id, type, title, message, is_read, created_at)
SELECT NULL, 'system', 'مرحباً بك في لوحة التحكم',
       'يمكنك البدء بإضافة مشروعك الأول من قسم "المشاريع".',
       0, NOW()
WHERE NOT EXISTS (SELECT 1 FROM notifications WHERE type = 'system');

-- Sample audit log entry
INSERT INTO audit_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at)
SELECT u.id, 'system.install', 'system', NULL, 'System installed — Batch 6 complete',
       '127.0.0.1', 'installer', NOW()
FROM users u ORDER BY u.id LIMIT 1;