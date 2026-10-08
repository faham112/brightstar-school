<?php
$page = 'scholarship';
$title = 'Fee challan';
require __DIR__ . '/includes/header.php';

$no = trim($_GET['no'] ?? '');
$stmt = db()->prepare('SELECT * FROM scholarships WHERE challan_no = ?');
$stmt->execute([$no]);
$row = $stmt->fetch();
if (!$row) {
    echo '<section class="section"><h1>Challan not found</h1><p>Check the number or register again.</p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $txn = trim($_POST['transaction_id'] ?? '');
    $method = trim($_POST['payment_method'] ?? '1Bill');
    if ($txn === '') {
        flash('error', 'Enter the transaction ID from the bank or wallet receipt.');
    } else {
        db()->prepare('UPDATE scholarships SET transaction_id = ?, payment_method = ?, status = ? WHERE id = ?')
            ->execute([$txn, $method, 'pending', (int) $row['id']]);
        flash('ok', 'Receipt saved. The office will mark this challan paid after checking.');
    }
    redirect('challan.php?no=' . urlencode($no));
}
$ready = env('ONEBILL_READY', '0') === '1';
?>
<section class="section">
    <p class="kicker">Fee challan</p>
    <h1><?= e($row['challan_no']) ?></h1>
    <p><?= e($row['student_name']) ?>, <?= e($row['class_name']) ?> · <?= e($row['father_name']) ?></p>
    <p>Amount due: <strong><?= e(money((int) $row['fee_amount'])) ?></strong> · Status: <?= e($row['status']) ?></p>
    <p><button class="btn btn-dark" type="button" onclick="window.print()">Print challan</button></p>
</section>
<section class="section grid-2">
    <article class="card">
        <h2><?= $ready ? 'Pay with 1Bill' : 'School challan' ?></h2>
        <?php if ($ready): ?>
            <p>Open any bank app, JazzCash, or Easypaisa. Choose bill payment, then 1Bill. Biller: <?= e(env('ONEBILL_BILLER')) ?>.</p>
            <p>Consumer / invoice number:</p>
            <p style="font-size:1.6rem;font-weight:700"><?= e($row['challan_no']) ?></p>
            <p>The app should show <?= e(money((int) $row['fee_amount'])) ?> before you confirm.</p>
        <?php else: ?>
            <p>1Bill is not switched on yet. Pay this challan by JazzCash, Easypaisa, or bank transfer, and keep the receipt.</p>
            <p>Challan number: <strong><?= e($row['challan_no']) ?></strong></p>
        <?php endif; ?>
        <p><strong>JazzCash</strong> <?= e(env('JAZZCASH_NAME')) ?> · <?= e(env('JAZZCASH_NUMBER')) ?></p>
        <p><strong>Easypaisa</strong> <?= e(env('EASYPAISA_NAME')) ?> · <?= e(env('EASYPAISA_NUMBER')) ?></p>
        <p><strong>Bank</strong> <?= e(env('BANK_TITLE')) ?> · <?= e(env('BANK_NAME')) ?> · <?= e(env('BANK_ACCOUNT')) ?></p>
    </article>
    <form class="form-card" method="post">
        <?= csrf_field() ?>
        <h2>After payment</h2>
        <label>How you paid</label>
        <select name="payment_method">
            <option>1Bill</option>
            <option>JazzCash</option>
            <option>Easypaisa</option>
            <option>Bank transfer</option>
        </select>
        <label>Transaction ID</label>
        <input name="transaction_id" value="<?= e($row['transaction_id']) ?>" required>
        <button class="btn btn-primary" type="submit">Save receipt</button>
    </form>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
