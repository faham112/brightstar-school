<?php
$page='contact'; $title='Contact'; require __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $subject = trim($_POST['subject'] ?? ''); $message = trim($_POST['message'] ?? '');
    if ($name === '' || $phone === '' || $subject === '' || $message === '') { flash('error', 'Name, phone, subject, and message are required.'); }
    else { db()->prepare('INSERT INTO messages (name, phone, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?)')->execute([$name, $phone, $_POST['email'] ?? '', $subject, $message, date('Y-m-d H:i:s')]); flash('ok', 'Message sent.'); }
    redirect('contact.php');
}
?>
<section class="section"><h1>Contact</h1><p><?= e(setting('owner_name')) ?> · <?= e(setting('phone')) ?> · <?= e(setting('address')) ?></p></section>
<section class="section grid-2"><form class="form-card" method="post"><?= csrf_field() ?><label>Name</label><input name="name" required><label>Phone</label><input name="phone" required><label>Email</label><input name="email" type="email"><label>Subject</label><input name="subject" required><label>Message</label><textarea name="message" required></textarea><button class="btn btn-primary" type="submit">Send</button></form><aside class="card"><p><?= e(setting('hours')) ?></p><p><a href="https://wa.me/<?= e(setting('phone_raw')) ?>">WhatsApp</a></p><p><a href="<?= e(setting('facebook')) ?>">Facebook</a></p></aside></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
