<?php
$page='contact'; $title='Contact'; require __DIR__ . '/includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? ''); $phone = trim($_POST['phone'] ?? ''); $subject = trim($_POST['subject'] ?? ''); $message = trim($_POST['message'] ?? '');
    if ($name === '' || $phone === '' || $subject === '' || $message === '') { flash('error', 'Name, phone, subject, and message are required.'); }
    else { db()->prepare('INSERT INTO messages (name, phone, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, ?)')->execute([$name, $phone, $_POST['email'] ?? '', $subject, $message, date('Y-m-d H:i:s')]); flash('ok', 'Message school office ko mil gaya.'); }
    redirect('contact.php');
}
?>
<section class="section"><p class="kicker">Office</p><h1>Contact</h1><p class="lede"><?= e(setting('owner_name')) ?> · <?= e(setting('phone')) ?> · <?= e(setting('address')) ?></p></section>
<section class="section grid-2"><form class="form-card" method="post"><?= csrf_field() ?><h2>Message the office</h2><label>Name</label><input name="name" required><label>Phone</label><input name="phone" required><label>Email</label><input name="email" type="email"><label>Subject</label><input name="subject" required><label>Message</label><textarea name="message" required></textarea><button class="btn btn-primary" type="submit">Send message</button></form><aside class="card"><h2>Reach us</h2><p><?= e(setting('hours')) ?></p><p><a href="tel:+<?= e(setting('phone_raw')) ?>"><?= e(setting('phone')) ?></a></p><p><a href="https://wa.me/<?= e(setting('phone_raw')) ?>">WhatsApp</a></p><p><a href="<?= e(setting('facebook')) ?>">Facebook page</a></p><p><?= e(setting('address')) ?></p></aside></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
