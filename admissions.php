<?php
$page='admissions'; $title='Admissions'; require __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $student = trim($_POST['student_name'] ?? ''); $father = trim($_POST['father_name'] ?? ''); $class = trim($_POST['class_name'] ?? ''); $phone = trim($_POST['phone'] ?? '');
    if ($student === '' || $father === '' || $class === '' || $phone === '') { flash('error', 'Child, father, class, and phone are required.'); }
    else { db()->prepare('INSERT INTO admissions (student_name, father_name, class_name, gender, dob, phone, address, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([$student, $father, $class, $_POST['gender'] ?? '', $_POST['dob'] ?? '', $phone, $_POST['address'] ?? '', $_POST['message'] ?? '', date('Y-m-d H:i:s')]); flash('ok', 'Admission inquiry received.'); }
    redirect('admissions.php');
}
?>
<section class="section"><p class="kicker">Join</p><h1>Admissions</h1><p>ECE to Class 5. Call <?= e(setting('owner_name')) ?> on <?= e(setting('phone')) ?>.</p></section>
<section class="section grid-2"><form class="form-card" method="post"><?= csrf_field() ?><label>Child’s name</label><input name="student_name" required><label>Father’s name</label><input name="father_name" required><label>Class</label><select name="class_name"><?php foreach (classes() as $c): ?><option><?= e($c) ?></option><?php endforeach; ?></select><label>Gender</label><select name="gender"><option>Boy</option><option>Girl</option></select><label>Date of birth</label><input type="date" name="dob"><label>Phone</label><input name="phone" required><label>Village</label><input name="address"><label>Note</label><textarea name="message"></textarea><button class="btn btn-primary" type="submit">Send inquiry</button></form><aside class="card"><h2>Bring</h2><ul><li>B-Form</li><li>Two photographs</li><li>Parent CNIC copy</li></ul></aside></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
