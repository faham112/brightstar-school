<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
define('ROOT', dirname(__DIR__));
define('UPLOAD_DIR', ROOT . '/uploads');
foreach (['gallery', 'logo'] as $dir) {
    if (!is_dir(UPLOAD_DIR . '/' . $dir)) { mkdir(UPLOAD_DIR . '/' . $dir, 0775, true); }
}
function load_env(string $path): void {
    if (!is_file($path)) { return; }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) { continue; }
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) { continue; }
        $key = trim($parts[0]);
        $value = trim(trim($parts[1]), "\"'");
        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}
load_env(ROOT . '/.env');
function env(string $key, string $default = ''): string {
    $value = $_ENV[$key] ?? getenv($key);
    return ($value === false || $value === null || $value === '') ? $default : (string) $value;
}
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) { return $pdo; }
    if (env('DB_NAME') === '' || env('DB_USER') === '') {
        throw new RuntimeException('Copy .env.example to .env and add the Hostinger MySQL details.');
    }
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST', 'localhost') . ';port=' . env('DB_PORT', '3306') . ';dbname=' . env('DB_NAME') . ';charset=utf8mb4',
        env('DB_USER'), env('DB_PASS'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    install($pdo);
    return $pdo;
}
function install(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, name VARCHAR(120) NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (`key` VARCHAR(80) PRIMARY KEY, `value` TEXT NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS notices (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, body TEXT NOT NULL, pinned TINYINT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS gallery (id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(180) NOT NULL, filename VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS admissions (id INT AUTO_INCREMENT PRIMARY KEY, student_name VARCHAR(120) NOT NULL, father_name VARCHAR(120) NOT NULL, class_name VARCHAR(40) NOT NULL, gender VARCHAR(20) NOT NULL, dob VARCHAR(20) NULL, phone VARCHAR(30) NOT NULL, address VARCHAR(255) NULL, message TEXT NULL, status VARCHAR(20) NOT NULL DEFAULT 'new', created_at DATETIME NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, phone VARCHAR(30) NOT NULL, email VARCHAR(120) NULL, subject VARCHAR(180) NOT NULL, message TEXT NOT NULL, is_read TINYINT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS faculty (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, role VARCHAR(120) NOT NULL, subject VARCHAR(120) NULL, bio TEXT NULL, sort_order INT NOT NULL DEFAULT 0)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS scholarships (id INT AUTO_INCREMENT PRIMARY KEY, student_name VARCHAR(120) NOT NULL, father_name VARCHAR(120) NOT NULL, class_name VARCHAR(40) NOT NULL, phone VARCHAR(30) NOT NULL, bform VARCHAR(40) NULL, address VARCHAR(255) NULL, fee_amount INT NOT NULL DEFAULT 0, payment_method VARCHAR(40) NOT NULL, transaction_id VARCHAR(80) NULL, challan_no VARCHAR(30) NULL, status VARCHAR(20) NOT NULL DEFAULT 'pending', created_at DATETIME NOT NULL)");
    if (!$pdo->query("SHOW COLUMNS FROM scholarships LIKE 'challan_no'")->fetch()) {
        $pdo->exec("ALTER TABLE scholarships ADD COLUMN challan_no VARCHAR(30) NULL");
    }
    if ((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
        $pdo->prepare('INSERT INTO users (username, password_hash, name) VALUES (?, ?, ?)')->execute([env('ADMIN_USER', 'admin'), password_hash(env('ADMIN_PASS', 'BrightStar@304'), PASSWORD_DEFAULT), env('ADMIN_NAME', 'Ahsan Ali Lound')]);
    }
    $defaults = [
        'school_name' => 'Bright Star Public Elementary School',
        'school_place' => 'Yaro Lound',
        'tagline' => 'A bright start for every child in Yaro Lound',
        'urdu_name' => 'برائٹ سٹار پبلک ایلیمنٹری اسکول، یارو لوند',
        'owner_name' => 'Ahsan Ali Lound',
        'owner_title' => 'Owner / School Head',
        'phone' => env('SCHOOL_PHONE', '0304 3991097'),
        'phone_raw' => env('SCHOOL_PHONE_RAW', '923043991097'),
        'email' => '',
        'address' => 'Yaro Lound (Yaro Lund), District Ghotki, Sindh, Pakistan',
        'hours' => 'Monday to Saturday, 8:00 AM – 1:30 PM',
        'facebook' => env('FACEBOOK_URL', 'https://www.facebook.com/share/1C6LYiSR4n/'),
        'about' => 'Bright Star Public Elementary School, Yaro Lound is a community elementary school sponsored by the Sindh Education Foundation, Government of Sindh. Owner Ahsan Ali Lound looks after admissions and the daily school.',
        'mission' => 'Quality elementary education for every child in Yaro Lound, with Sindh Education Foundation support.',
        'vision' => 'A village where every child can read, reason, and rise.',
    ];
    $set = $pdo->prepare('INSERT IGNORE INTO settings (`key`, `value`) VALUES (?, ?)');
    foreach ($defaults as $k => $v) { $set->execute([$k, $v]); }
    if ((int) $pdo->query('SELECT COUNT(*) FROM notices')->fetchColumn() === 0) {
        $n = $pdo->prepare('INSERT INTO notices (title, body, pinned, created_at) VALUES (?, ?, ?, ?)');
        $n->execute(['Free Scholarship Test announced', 'Register online, get a challan, and pay by 1Bill, JazzCash, or Easypaisa.', 1, date('Y-m-d H:i:s')]);
        $n->execute(['Admissions open', 'ECE to Class 5. Call Ahsan Ali Lound on 0304 3991097.', 1, date('Y-m-d H:i:s')]);
    }
    if ((int) $pdo->query('SELECT COUNT(*) FROM faculty')->fetchColumn() === 0) {
        $pdo->prepare('INSERT INTO faculty (name, role, subject, bio, sort_order) VALUES (?, ?, ?, ?, ?)')->execute(['Ahsan Ali Lound', 'Owner / School Head', 'School leadership', 'Owner of Bright Star Public Elementary School, Yaro Lound.', 1]);
    }
}
function setting(string $key, string $default = ''): string {
    $stmt = db()->prepare('SELECT `value` FROM settings WHERE `key` = ?');
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    return $value === false ? $default : (string) $value;
}
function set_setting(string $key, string $value): void {
    db()->prepare('INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)')->execute([$key, $value]);
}
function e(?string $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function base_url(): string {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if (basename($dir) === 'admin') { $dir = dirname($dir); }
    return ($dir === '/' || $dir === '.' ) ? '' : rtrim($dir, '/');
}
function asset(string $path): string { return base_url() . '/' . ltrim($path, '/'); }
function redirect(string $path): void { header('Location: ' . $path); exit; }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_check(): void {
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) { http_response_code(400); exit('Invalid request token.'); }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function take_flash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }
function logged_in(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!logged_in()) { redirect('login.php'); } }
function logo_src(): string {
    $custom = setting('logo_file');
    if ($custom !== '' && is_file(UPLOAD_DIR . '/logo/' . $custom)) { return asset('uploads/logo/' . $custom); }
    if (is_file(ROOT . '/assets/images/logo.png')) { return asset('assets/images/logo.png'); }
    return asset('assets/images/logo.svg');
}
function save_upload(array $file, string $folder): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return null; }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 4 * 1024 * 1024) { throw new RuntimeException('Upload failed or file is over 4 MB.'); }
    $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) || @getimagesize($file['tmp_name']) === false) { throw new RuntimeException('Only a real image is allowed.'); }
    $name = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . '/' . $folder . '/' . $name)) { throw new RuntimeException('Could not save the image.'); }
    return $name;
}
function classes(): array { return ['ECE', 'Nursery', 'KG', 'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5']; }
function money(int $amount): string { return 'Rs ' . number_format($amount); }
function make_challan(int $id): string { return env('ONEBILL_PREFIX', 'BSP') . str_pad((string) $id, 8, '0', STR_PAD_LEFT); }
