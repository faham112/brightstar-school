<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'status') { db()->prepare('UPDATE admissions SET status = ? WHERE id = ?')->execute([$_POST['status'] ?? 'new', $id]); }
    if (($_POST['action'] ?? '') === 'delete') { db()->prepare('DELETE FROM admissions WHERE id = ?')->execute([$id]); }
    flash('ok', 'Admission updated.'); redirect('inquiries.php');
}
$adminTitle='Admissions'; $adminPage='admissions'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM admissions ORDER BY created_at DESC')->fetchAll();
?>
<table><tr><th>Child</th><th>Contact</th><th>Status</th></tr><?php foreach ($rows as $row): ?><tr><td><strong><?= e($row['student_name']) ?></strong><br><?= e($row['father_name']) ?> · <?= e($row['class_name']) ?></td><td><?= e($row['phone']) ?><br><?= e($row['address']) ?><br><?= e($row['message']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><select name="status"><?php foreach (['new','contacted','admitted','closed'] as $s): ?><option <?= $row['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button>Save</button></form></td></tr><?php endforeach; ?><?php if (!$rows): ?><tr><td colspan="3">No inquiries yet.</td></tr><?php endif; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
