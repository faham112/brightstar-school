<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); $id = (int) ($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'status') { db()->prepare('UPDATE scholarships SET status = ? WHERE id = ?')->execute([$_POST['status'] ?? 'pending', $id]); }
    if (($_POST['action'] ?? '') === 'delete') { db()->prepare('DELETE FROM scholarships WHERE id = ?')->execute([$id]); }
    flash('ok', 'Registration updated.'); redirect('scholarships.php');
}
$adminTitle='Scholarship test'; $adminPage='scholarships'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM scholarships ORDER BY created_at DESC')->fetchAll();
?>
<p>1Bill ready: <?= e(env('ONEBILL_READY', '0')) ?>. Fee: <?= e(money((int) env('SCHOLARSHIP_FEE', '0'))) ?>.</p>
<table><tr><th>Student</th><th>Challan</th><th>Status</th></tr><?php foreach ($rows as $row): ?><tr><td><strong><?= e($row['student_name']) ?></strong><br><?= e($row['father_name']) ?> · <?= e($row['class_name']) ?><br><?= e($row['phone']) ?></td><td><?= e($row['challan_no']) ?><br><?= e(money((int)$row['fee_amount'])) ?><br><?= e($row['payment_method']) ?> <?= e($row['transaction_id']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><select name="status"><?php foreach (['pending','paid','verified','rejected'] as $s): ?><option <?= $row['status']===$s?'selected':'' ?>><?= e($s) ?></option><?php endforeach; ?></select><button>Save</button></form></td></tr><?php endforeach; ?><?php if (!$rows): ?><tr><td colspan="3">No registrations yet.</td></tr><?php endif; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
