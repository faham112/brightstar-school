<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    try {
        if (($_POST['action'] ?? '') === 'profile') {
            foreach (['school_name','school_place','tagline','urdu_name','owner_name','owner_title','phone','phone_raw','email','address','hours','facebook','about','mission','vision'] as $key) { set_setting($key, trim($_POST[$key] ?? '')); }
            if (!empty($_FILES['logo']['name'])) { $name = save_upload($_FILES['logo'], 'logo'); if ($name) { set_setting('logo_file', $name); } }
            flash('ok', 'School details saved.');
        }
        if (($_POST['action'] ?? '') === 'password') {
            $password = $_POST['password'] ?? '';
            if (strlen($password) < 8 || $password !== ($_POST['confirm'] ?? '')) { flash('error', 'Password must be 8 characters and match.'); }
            else { db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($password, PASSWORD_DEFAULT), (int) $_SESSION['admin_id']]); flash('ok', 'Password changed.'); }
        }
    } catch (RuntimeException $ex) { flash('error', $ex->getMessage()); }
    redirect('settings.php');
}
$adminTitle='Settings'; $adminPage='settings'; require __DIR__ . '/_header.php';
?>
<form class="card" method="post" enctype="multipart/form-data"><?= csrf_field() ?><input type="hidden" name="action" value="profile"><h2>School profile</h2><p>Upload the Facebook logo here.</p><input type="file" name="logo" accept="image/*"><?php foreach (['school_name','school_place','urdu_name','tagline','owner_name','owner_title','phone','phone_raw','email','address','hours','facebook'] as $key): ?><label><?= e($key) ?><input name="<?= e($key) ?>" value="<?= e(setting($key)) ?>"></label><?php endforeach; ?><label>About<textarea name="about"><?= e(setting('about')) ?></textarea></label><label>Mission<textarea name="mission"><?= e(setting('mission')) ?></textarea></label><label>Vision<textarea name="vision"><?= e(setting('vision')) ?></textarea></label><button>Save profile</button></form>
<form class="card" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="password"><h2>Password</h2><input type="password" name="password" required><input type="password" name="confirm" required><button>Update password</button></form>
<?php require __DIR__ . '/_footer.php'; ?>
