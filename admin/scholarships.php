<?php
require __DIR__ . '/../includes/bootstrap.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) ($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? 'pending';
    if (!in_array($status, ['pending', 'paid', 'verified', 'rejected'], true)) {
        $status = 'pending';
    }
    if (($_POST['action'] ?? '') === 'status') {
        db()->prepare('UPDATE scholarships SET status = ? WHERE id = ?')->execute([$status, $id]);
        flash('ok', 'Scholarship registration updated.');
    }
    if (($_POST['action'] ?? '') === 'delete') {
        db()->prepare('DELETE FROM scholarships WHERE id = ?')->execute([$id]);
        flash('ok', 'Registration removed.');
    }
    redirect('scholarships.php');
}

$adminTitle = 'Scholarship test';
$adminPage = 'scholarships';
require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM scholarships ORDER BY created_at DESC')->fetchAll();
?>
<p>Fee in .env is <?= e(money((int) env('SCHOLARSHIP_FEE', '0'))) ?>. Change amount, date, and JazzCash numbers there, then replace the file on Hostinger.</p>
<table>
    <tr><th>Student</th><th>Fee</th><th>Status</th><th></th></tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td>
                <strong><?= e($row['student_name']) ?></strong><br>
                <?= e($row['father_name']) ?> · <?= e($row['class_name']) ?><br>
                <?= e($row['phone']) ?> · <?= e($row['address']) ?><br>
                <?= e($row['created_at']) ?>
            </td>
            <td><?= e(money((int) $row['fee_amount'])) ?><br><?= e($row['payment_method']) ?><br><?= e($row['transaction_id']) ?></td>
            <td>
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="status">
                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                    <select name="status">
                        <?php foreach (['pending', 'paid', 'verified', 'rejected'] as $status): ?>
                            <option <?= $row['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Save</button>
                </form>
            </td>
            <td>
                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="4">No registrations yet.</td></tr><?php endif; ?>
</table>
<?php require __DIR__ . '/_footer.php'; ?>
