<?php
header('Content-Type: text/plain; charset=UTF-8');
$root = __DIR__;
$envFile = $root . '/.env';
echo "Bright Star check\n";
echo 'PHP ' . PHP_VERSION . "\n";
echo 'pdo_mysql: ' . (extension_loaded('pdo_mysql') ? 'yes' : 'NO') . "\n";
echo '.env file: ' . (is_file($envFile) ? 'found' : 'MISSING - upload .env next to index.php') . "\n";
if (!is_file($envFile)) { exit; }
$env = [];
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) { continue; }
    [$k, $v] = explode('=', $line, 2);
    $env[trim($k)] = trim(trim($v), "\"'");
}
$name = $env['DB_NAME'] ?? '';
$user = $env['DB_USER'] ?? '';
$pass = $env['DB_PASS'] ?? '';
$host = $env['DB_HOST'] ?? 'localhost';
$port = $env['DB_PORT'] ?? '3306';
echo 'DB_HOST=' . $host . "\n";
echo 'DB_NAME=' . ($name === '' || $name === 'paste-database-name' ? 'NOT SET' : $name) . "\n";
echo 'DB_USER=' . ($user === '' || $user === 'paste-database-user' ? 'NOT SET' : $user) . "\n";
echo 'DB_PASS=' . ($pass === '' || $pass === 'paste-database-password' ? 'NOT SET' : 'set') . "\n";
if ($name === '' || $user === '' || $pass === '' || str_contains($name, 'paste-') || str_contains($user, 'paste-') || str_contains($pass, 'paste-')) {
    echo "Fill DB_NAME, DB_USER and DB_PASS in .env with the Hostinger database values.\n";
    exit;
}
try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Database connection: OK\n";
    echo 'Tables: ' . implode(', ', $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN)) . "\n";
} catch (Throwable $e) {
    echo 'Database connection: FAILED\n';
    echo $e->getMessage() . "\n";
    echo "In Hostinger, assign the user to the database, then paste that exact name, user and password into .env.\n";
}
