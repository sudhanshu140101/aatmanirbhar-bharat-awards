CREATE DATABASE IF NOT EXISTS aatmanirbhar_nominations CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aatmanirbhar_nominations;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS nominations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(64) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    founder_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    business_category VARCHAR(32) NOT NULL,
    revenue VARCHAR(32) DEFAULT NULL,
    net_profit VARCHAR(32) DEFAULT NULL,
    net_worth VARCHAR(32) DEFAULT NULL,
    credit_facilities VARCHAR(32) DEFAULT NULL,
    udyam_registration VARCHAR(64) DEFAULT NULL,
    description TEXT NOT NULL,
    terms_accept TINYINT(1) NOT NULL DEFAULT 1,
    payment_verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created (created_at),
    INDEX idx_payment (payment_verified)
);

INSERT INTO admin_users (username, password) VALUES ('Admin_user', '$2y$10$AjLLVA8XiVtV.V5EuvbireIQ2pMtY8.D0c08bYHP9no.X.ErMVOku')
ON DUPLICATE KEY UPDATE id=id;
