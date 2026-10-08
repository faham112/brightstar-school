<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { csrf_check(); $id=(int)$_POST['id']; if (($_POST['action']??'')==='read') db()->prepare('UPDATE messages SET is_read=1 WHERE id=?')->execute([$id]); if (($_POST['action']??'')==='delete') db()->prepare('DELETE FROM messages WHERE id=?')->execute([$id]); flash('ok','Message updated.'); redirect('messages.php'); }
$adminTitle='Messages'; $adminPage='messages'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
?>
<table><?php foreach ($rows as $row): ?><tr><td><strong><?= e($row['name']) ?></strong><br><?= e($row['phone']) ?><br><?= $row['is_read']?'Read':'Unread' ?></td><td><strong><?= e($row['subject']) ?></strong><br><?= nl2br(e($row['message'])) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><input type="hidden" name="action" value="read"><button>Mark read</button></form><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><input type="hidden" name="action" value="delete"><button>Delete</button></form></td></tr><?php endforeach; ?><?php if (!$rows): ?><tr><td>No messages yet.</td></tr><?php endif; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
