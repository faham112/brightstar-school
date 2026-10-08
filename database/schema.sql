-- Import in Hostinger phpMyAdmin if you prefer a manual setup.
-- The site also creates these tables on first visit when .env is correct.

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(120) NOT NULL
);

CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(80) PRIMARY KEY,
    `value` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    pinned TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    filename VARCHAR(180) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(120) NOT NULL,
    father_name VARCHAR(120) NOT NULL,
    class_name VARCHAR(40) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    dob VARCHAR(20) NULL,
    phone VARCHAR(30) NOT NULL,
    address VARCHAR(255) NULL,
    message TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(120) NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    role VARCHAR(120) NOT NULL,
    subject VARCHAR(120) NULL,
    bio TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(120) NOT NULL,
    father_name VARCHAR(120) NOT NULL,
    class_name VARCHAR(40) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    bform VARCHAR(40) NULL,
    address VARCHAR(255) NULL,
    fee_amount INT NOT NULL DEFAULT 0,
    payment_method VARCHAR(40) NOT NULL,
    transaction_id VARCHAR(80) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL
);
