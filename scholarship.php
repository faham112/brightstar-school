<?php
$page='scholarship'; $title='Scholarship Test'; require __DIR__ . '/includes/header.php';
$fee = (int) env('SCHOLARSHIP_FEE', '500'); $open = env('SCHOLARSHIP_OPEN', '1') === '1';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $student = trim($_POST['student_name'] ?? ''); $father = trim($_POST['father_name'] ?? ''); $class = trim($_POST['class_name'] ?? ''); $phone = trim($_POST['phone'] ?? '');
    if (!$open) { flash('error', 'Registration is closed.'); redirect('scholarship.php'); }
    if ($student === '' || $father === '' || $class === '' || $phone === '') { flash('error', 'Child, father, class, and phone are required.'); redirect('scholarship.php'); }
    db()->prepare('INSERT INTO scholarships (student_name, father_name, class_name, phone, bform, address, fee_amount, payment_method, transaction_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([$student, $father, $class, $phone, $_POST['bform'] ?? '', $_POST['address'] ?? '', $fee, 'Challan', '', $fee > 0 ? 'pending' : 'paid', date('Y-m-d H:i:s')]);
    $id = (int) db()->lastInsertId(); $challan = make_challan($id);
    db()->prepare('UPDATE scholarships SET challan_no = ? WHERE id = ?')->execute([$challan, $id]);
    redirect('challan.php?no=' . urlencode($challan));
}
?>
<section class="section"><p class="kicker">Announcement</p><h1><?= e(env('SCHOLARSHIP_TITLE', 'Free Scholarship Test')) ?></h1><p class="lede">Test date: <?= e(env('SCHOLARSHIP_DATE')) ?>. <?= e(env('SCHOLARSHIP_NOTE')) ?></p><p>Registration fee: <strong><?= $fee > 0 ? e(money($fee)) : 'Free' ?></strong></p></section>
<section class="section grid-2"><form class="form-card" method="post"><?= csrf_field() ?><h2>Register</h2><label>Child’s name</label><input name="student_name" required <?= $open ? '' : 'disabled' ?>><label>Father’s name</label><input name="father_name" required <?= $open ? '' : 'disabled' ?>><label>Class</label><select name="class_name"><?php foreach (classes() as $c): ?><option><?= e($c) ?></option><?php endforeach; ?></select><label>Phone</label><input name="phone" required <?= $open ? '' : 'disabled' ?>><label>B-Form</label><input name="bform"><label>Village</label><input name="address" placeholder="Yaro Lound"><button class="btn btn-primary" type="submit">Create challan</button></form><aside class="card"><h2>How to pay</h2><p>The next page gives a challan number. Pay by 1Bill when it is switched on, or by JazzCash <?= e(env('JAZZCASH_NUMBER')) ?> and Easypaisa <?= e(env('EASYPAISA_NUMBER')) ?>.</p></aside></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
