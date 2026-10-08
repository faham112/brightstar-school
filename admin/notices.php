<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'create' && trim($_POST['title'] ?? '') !== '') {
        db()->prepare('INSERT INTO notices (title, body, pinned, created_at) VALUES (?, ?, ?, ?)')->execute([trim($_POST['title']), trim($_POST['body'] ?? ''), isset($_POST['pinned']) ? 1 : 0, date('Y-m-d H:i:s')]);
        flash('ok', 'Notice published.');
    }
    if (($_POST['action'] ?? '') === 'delete') { db()->prepare('DELETE FROM notices WHERE id = ?')->execute([(int) $_POST['id']]); flash('ok', 'Notice removed.'); }
    redirect('notices.php');
}
$adminTitle='Notices'; $adminPage='notices'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM notices ORDER BY created_at DESC')->fetchAll();
?>
<form class="card" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="create"><h2>New notice</h2><input name="title" placeholder="Title" required><textarea name="body" required></textarea><label><input type="checkbox" name="pinned"> Pin</label><button type="submit">Publish</button></form>
<table><?php foreach ($rows as $row): ?><tr><td><strong><?= e($row['title']) ?></strong><br><?= e($row['body']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button>Delete</button></form></td></tr><?php endforeach; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
