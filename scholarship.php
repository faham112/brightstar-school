<?php
$page = 'scholarship';
$title = 'Scholarship Test';
require __DIR__ . '/includes/header.php';

$fee = (int) env('SCHOLARSHIP_FEE', '500');
$open = env('SCHOLARSHIP_OPEN', '1') === '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $student = trim($_POST['student_name'] ?? '');
    $father = trim($_POST['father_name'] ?? '');
    $class = trim($_POST['class_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $bform = trim($_POST['bform'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $method = trim($_POST['payment_method'] ?? '');
    $txn = trim($_POST['transaction_id'] ?? '');
    $allowed = ['JazzCash', 'Easypaisa', 'Bank transfer'];
    if (!$open) {
        flash('error', 'Scholarship registration is closed right now.');
    } elseif ($student === '' || $father === '' || $class === '' || $phone === '' || !in_array($method, $allowed, true)) {
        flash('error', 'Please fill the child, father, class, phone, and payment method.');
    } elseif ($fee > 0 && $txn === '') {
        flash('error', 'Pay the registration fee, then enter the transaction ID.');
    } else {
        $stmt = db()->prepare('INSERT INTO scholarships (student_name, father_name, class_name, phone, bform, address, fee_amount, payment_method, transaction_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$student, $father, $class, $phone, $bform, $address, $fee, $method, $txn, $fee > 0 ? 'pending' : 'paid', date('Y-m-d H:i:s')]);
        flash('ok', 'Registration received. The office will confirm your fee on ' . $phone . '.');
    }
    redirect('scholarship.php');
}
?>
<section class="section">
    <p class="kicker">Announcement</p>
    <h1><?= e(env('SCHOLARSHIP_TITLE', 'Free Scholarship Test')) ?></h1>
    <p class="lede">Bright Star Public Elementary School, Yaro Lound is holding a free scholarship test on <?= e(env('SCHOLARSHIP_DATE', 'a date to be announced')) ?>. Selected students receive a free scholarship seat. <?= e(env('SCHOLARSHIP_NOTE')) ?></p>
    <p>Registration fee: <strong><?= $fee > 0 ? e(money($fee)) : 'Free' ?></strong></p>
</section>
<section class="section grid-2">
    <form class="form-card" method="post">
        <?= csrf_field() ?>
        <h2>Register online</h2>
        <?php if (!$open): ?><p>Registration is closed. Watch the notices page.</p><?php endif; ?>
        <div class="form-row">
            <div><label>Child’s name</label><input name="student_name" required <?= $open ? '' : 'disabled' ?>></div>
            <div><label>Father’s name</label><input name="father_name" required <?= $open ? '' : 'disabled' ?>></div>
        </div>
        <div class="form-row">
            <div>
                <label>Class</label>
                <select name="class_name" <?= $open ? '' : 'disabled' ?>>
                    <?php foreach (classes() as $class): ?><option><?= e($class) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div><label>Phone</label><input name="phone" required <?= $open ? '' : 'disabled' ?>></div>
        </div>
        <div class="form-row">
            <div><label>B-Form number</label><input name="bform" <?= $open ? '' : 'disabled' ?>></div>
            <div><label>Village</label><input name="address" placeholder="Yaro Lound" <?= $open ? '' : 'disabled' ?>></div>
        </div>
        <label>Payment method</label>
        <select name="payment_method" <?= $open ? '' : 'disabled' ?>>
            <option>JazzCash</option>
            <option>Easypaisa</option>
            <option>Bank transfer</option>
        </select>
        <label>Transaction ID</label>
        <input name="transaction_id" placeholder="After you pay, paste the TID here" <?= $open ? '' : 'disabled' ?>>
        <div class="btn-row"><button class="btn btn-primary" type="submit" <?= $open ? '' : 'disabled' ?>>Submit registration</button></div>
    </form>
    <aside class="card">
        <h2>Pay the fee</h2>
        <p>Send <?= $fee > 0 ? e(money($fee)) : 'no fee' ?> and write the child’s name in the payment note.</p>
        <p><strong>JazzCash</strong><br><?= e(env('JAZZCASH_NAME')) ?><br><?= e(env('JAZZCASH_NUMBER')) ?></p>
        <p><strong>Easypaisa</strong><br><?= e(env('EASYPAISA_NAME')) ?><br><?= e(env('EASYPAISA_NUMBER')) ?></p>
        <p><strong>Bank</strong><br><?= e(env('BANK_TITLE')) ?><br><?= e(env('BANK_NAME')) ?><br><?= e(env('BANK_ACCOUNT')) ?></p>
        <p>The office marks the form verified after the fee appears. Call <?= e(setting('phone')) ?> if you need help.</p>
    </aside>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
