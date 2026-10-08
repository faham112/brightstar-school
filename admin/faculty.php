<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check(); if (($_POST['action']??'')==='create') db()->prepare('INSERT INTO faculty (name, role, subject, bio, sort_order) VALUES (?, ?, ?, ?, ?)')->execute([trim($_POST['name']??''), trim($_POST['role']??''), trim($_POST['subject']??''), trim($_POST['bio']??''), (int)($_POST['sort_order']??0)]); if (($_POST['action']??'')==='delete') db()->prepare('DELETE FROM faculty WHERE id=?')->execute([(int)$_POST['id']]); flash('ok','Staff updated.'); redirect('faculty.php'); }
$adminTitle='Faculty'; $adminPage='faculty'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM faculty ORDER BY sort_order, name')->fetchAll();
?>
<form class="card" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="create"><input name="name" placeholder="Name" required><input name="role" placeholder="Role" required><input name="subject" placeholder="Subject"><textarea name="bio"></textarea><button>Save</button></form>
<table><?php foreach ($rows as $row): ?><tr><td><strong><?= e($row['name']) ?></strong><br><?= e($row['role']) ?><br><?= e($row['bio']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button>Delete</button></form></td></tr><?php endforeach; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
