<?php
require __DIR__ . '/../includes/bootstrap.php';
if (logged_in()) { redirect('index.php'); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        session_regenerate_id(true); $_SESSION['admin_id'] = $user['id']; $_SESSION['admin_name'] = $user['name']; redirect('index.php');
    }
    $error = 'Username or password is not correct.';
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="../assets/css/admin.css"></head><body>
<div class="admin-login"><form class="login-card" method="post"><h1>School admin</h1><p>Bright Star, Yaro Lound</p><?php if ($error): ?><p class="flash flash-error"><?= e($error) ?></p><?php endif; ?><?= csrf_field() ?><label>Username<br><input name="username" required></label><label>Password<br><input type="password" name="password" required></label><button type="submit">Log in</button><p><a href="../index.php">Back to website</a></p></form></div></body></html>
