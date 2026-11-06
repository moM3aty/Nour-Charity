-- =============================================================
-- Nour Makha International - Schema
-- Database: u582652079_charity  (or nour_makha_db)
-- Charset : utf8mb4 / utf8mb4_unicode_ci
-- Engine  : InnoDB
-- =============================================================
SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------- Roles & Permissions ----------------
CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS permissions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS role_permissions (
  role_id INT UNSIGNED NOT NULL,
  permission_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
  CONSTRAINT fk_rp_perm FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Users ----------------
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(50) NULL,
  avatar VARCHAR(255) NULL,
  role_id INT UNSIGNED NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  last_login DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  KEY idx_email (email),
  KEY idx_role (role_id),
  CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL,
  token VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_email (email),
  KEY idx_token (token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Settings ----------------
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` LONGTEXT NULL,
  `group` VARCHAR(50) NOT NULL DEFAULT 'general',
  type VARCHAR(20) NOT NULL DEFAULT 'text',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Pages & Sections ----------------
CREATE TABLE IF NOT EXISTS pages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(150) NOT NULL UNIQUE,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NULL,
  meta_title VARCHAR(255) NULL,
  meta_description VARCHAR(500) NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS page_sections (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  page_key VARCHAR(100) NOT NULL,
  section_key VARCHAR(100) NOT NULL,
  title VARCHAR(255) NULL,
  subtitle VARCHAR(500) NULL,
  content LONGTEXT NULL,
  image VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_page_section (page_key, section_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS hero_slides (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255) NULL,
  description TEXT NULL,
  image VARCHAR(255) NULL,
  cta_text VARCHAR(100) NULL,
  cta_link VARCHAR(255) NULL,
  secondary_text VARCHAR(100) NULL,
  secondary_link VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Categories ----------------
CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  description TEXT NULL,
  image VARCHAR(255) NULL,
  icon VARCHAR(50) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Projects ----------------
CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  short_description VARCHAR(500) NULL,
  description LONGTEXT NULL,
  objectives LONGTEXT NULL,
  beneficiaries TEXT NULL,
  cover_image VARCHAR(255) NULL,
  target_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  raised_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) NOT NULL DEFAULT 'SAR',
  status ENUM('draft','active','funded','completed','paused','archived') NOT NULL DEFAULT 'draft',
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  start_date DATE NULL,
  end_date DATE NULL,
  meta_title VARCHAR(255) NULL,
  meta_description VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  KEY idx_status (status),
  KEY idx_featured (is_featured),
  KEY idx_category (category_id),
  CONSTRAINT fk_project_cat FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  caption VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_pimg_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_updates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NULL,
  image VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pupd_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS donation_packages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NULL,
  name VARCHAR(150) NOT NULL,
  amount DECIMAL(14,2) NOT NULL,
  description VARCHAR(500) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_pkg_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Donors & Donations ----------------
CREATE TABLE IF NOT EXISTS donors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(50) NULL,
  is_anonymous TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS donations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donation_number VARCHAR(50) NOT NULL UNIQUE,
  donor_id INT UNSIGNED NULL,
  project_id INT UNSIGNED NULL,
  campaign_id INT UNSIGNED NULL,
  amount DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'SAR',
  donation_type ENUM('one_time','gift','general') NOT NULL DEFAULT 'one_time',
  message TEXT NULL,
  is_anonymous TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('pending','awaiting_verification','paid','failed','cancelled','refunded') NOT NULL DEFAULT 'pending',
  payment_method ENUM('bank_transfer','gateway','offline','cash') NOT NULL DEFAULT 'bank_transfer',
  reference VARCHAR(100) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  verified_at DATETIME NULL,
  verified_by INT UNSIGNED NULL,
  KEY idx_status (status),
  KEY idx_project (project_id),
  KEY idx_campaign (campaign_id),
  KEY idx_donor (donor_id),
  CONSTRAINT fk_donor FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE SET NULL,
  CONSTRAINT fk_don_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL,
  CONSTRAINT fk_don_verified FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS donation_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donation_id INT UNSIGNED NOT NULL,
  project_id INT UNSIGNED NULL,
  amount DECIMAL(14,2) NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  CONSTRAINT fk_ditem_don FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE,
  CONSTRAINT fk_ditem_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payment_transactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donation_id INT UNSIGNED NOT NULL,
  transaction_id VARCHAR(150) NULL,
  gateway VARCHAR(50) NULL,
  amount DECIMAL(14,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'SAR',
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  response LONGTEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tx_don FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Bank accounts & receipts ----------------
CREATE TABLE IF NOT EXISTS bank_accounts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  bank_name VARCHAR(150) NOT NULL,
  account_holder VARCHAR(150) NOT NULL,
  account_number VARCHAR(100) NULL,
  iban VARCHAR(60) NULL,
  logo VARCHAR(255) NULL,
  instructions TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transfer_receipts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donation_id INT UNSIGNED NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NULL,
  mime_type VARCHAR(100) NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  verified_at DATETIME NULL,
  verified_by INT UNSIGNED NULL,
  notes TEXT NULL,
  CONSTRAINT fk_receipt_don FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE,
  CONSTRAINT fk_receipt_ver FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Campaigns ----------------
CREATE TABLE IF NOT EXISTS campaigns (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NULL,
  goal_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  raised_amount DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  currency CHAR(3) NOT NULL DEFAULT 'SAR',
  image VARCHAR(255) NULL,
  start_date DATE NULL,
  end_date DATE NULL,
  status ENUM('draft','active','ended','paused') NOT NULL DEFAULT 'draft',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_camp_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Gift donations ----------------
CREATE TABLE IF NOT EXISTS gift_donations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donation_id INT UNSIGNED NOT NULL,
  recipient_name VARCHAR(150) NULL,
  recipient_email VARCHAR(150) NULL,
  message TEXT NULL,
  card_design VARCHAR(50) NULL,
  share_token VARCHAR(100) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_gift_don FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Volunteers ----------------
CREATE TABLE IF NOT EXISTS volunteer_opportunities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NULL,
  location VARCHAR(150) NULL,
  commitment VARCHAR(100) NULL,
  status ENUM('open','closed') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS volunteer_applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  opportunity_id INT UNSIGNED NULL,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NULL,
  city VARCHAR(100) NULL,
  skills TEXT NULL,
  interests TEXT NULL,
  availability VARCHAR(100) NULL,
  message TEXT NULL,
  cv_path VARCHAR(255) NULL,
  status ENUM('new','reviewing','accepted','rejected') NOT NULL DEFAULT 'new',
  internal_notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_vol_opp FOREIGN KEY (opportunity_id) REFERENCES volunteer_opportunities(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Beneficiary assistance requests ----------------
CREATE TABLE IF NOT EXISTS assistance_requests (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  request_number VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(50) NULL,
  category VARCHAR(100) NULL,
  location VARCHAR(150) NULL,
  description TEXT NULL,
  status ENUM('new','reviewing','approved','rejected','completed') NOT NULL DEFAULT 'new',
  internal_notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assistance_documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  request_id INT UNSIGNED NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_adoc_req FOREIGN KEY (request_id) REFERENCES assistance_requests(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- News / Articles ----------------
CREATE TABLE IF NOT EXISTS article_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt VARCHAR(500) NULL,
  content LONGTEXT NULL,
  cover_image VARCHAR(255) NULL,
  status ENUM('draft','published') NOT NULL DEFAULT 'draft',
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  published_at DATETIME NULL,
  meta_title VARCHAR(255) NULL,
  meta_description VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at DATETIME NULL,
  CONSTRAINT fk_art_cat FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Media ----------------
CREATE TABLE IF NOT EXISTS media (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NULL,
  path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) NULL,
  size INT UNSIGNED NULL,
  width INT NULL,
  height INT NULL,
  uploaded_by INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Partners / Messages / Notifications ----------------
CREATE TABLE IF NOT EXISTS partners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  logo VARCHAR(255) NULL,
  url VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NULL,
  subject VARCHAR(255) NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied','archived') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  type VARCHAR(50) NOT NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Audit & translations ----------------
CREATE TABLE IF NOT EXISTS audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(50) NULL,
  entity_id BIGINT NULL,
  description TEXT NULL,
  ip_address VARCHAR(45) NULL,
  user_agent VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_action (action),
  KEY idx_entity (entity_type, entity_id),
  KEY idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS translations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lang CHAR(2) NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  UNIQUE KEY uq_translation (lang, `key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------- Login attempts (throttling) ----------------
CREATE TABLE IF NOT EXISTS login_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  success TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_email_ip (email, ip_address, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;