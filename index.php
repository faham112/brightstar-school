<?php
$page = 'home'; $title = 'Home';
require __DIR__ . '/includes/header.php';
$notices = db()->query('SELECT * FROM notices ORDER BY pinned DESC, created_at DESC LIMIT 3')->fetchAll();
$photos = db()->query('SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6')->fetchAll();
?>
<section class="hero">
    <div class="hero-panel">
        <div class="kicker">Sindh Education Foundation school</div>
        <p class="urdu"><?= e(setting('urdu_name')) ?></p>
        <h1><?= e(setting('school_name')) ?></h1>
        <p class="lede"><?= e(setting('tagline')) ?>. ECE se Class 5 tak, <?= e(setting('school_place')) ?> ke bachon ke liye.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= e(asset('admissions.php')) ?>">Admission form</a>
            <a class="btn btn-dark" href="<?= e(asset('scholarship.php')) ?>">Scholarship test</a>
            <a class="btn btn-ghost" href="<?= e(asset('gallery.php')) ?>">School gallery</a>
        </div>
    </div>
    <div>
        <article class="notice pinned">
            <p class="meta"><?= e(env('SCHOLARSHIP_DATE', 'Date soon')) ?> · <?= (int) env('SCHOLARSHIP_FEE', '0') > 0 ? e(money((int) env('SCHOLARSHIP_FEE', '0'))) : 'Free' ?></p>
            <h2><?= e(env('SCHOLARSHIP_TITLE', 'Free Scholarship Test')) ?></h2>
            <p><?= e(env('SCHOLARSHIP_NOTE')) ?></p>
            <a class="btn btn-primary" href="<?= e(asset('scholarship.php')) ?>">Register and get challan</a>
        </article>
        <article class="card" style="margin-top:14px"><h2>Office</h2><p><strong><?= e(setting('owner_name')) ?></strong><br><?= e(setting('owner_title')) ?></p><p><?= e(setting('address')) ?></p><p><?= e(setting('hours')) ?></p><a class="btn btn-dark" href="tel:+<?= e(setting('phone_raw')) ?>">Call <?= e(setting('phone')) ?></a></article>
    </div>
</section>
<section class="section"><div class="grid-4"><div class="stat"><b>ECE–5</b><span>Classes</span></div><div class="stat"><b>SEF</b><span>Sponsored school</span></div><div class="stat"><b>Mon–Sat</b><span>Weekly terms</span></div><div class="stat"><b><?= e(setting('phone')) ?></b><span><?= e(setting('owner_name')) ?></span></div></div></section>
<section class="section"><h2>School systems</h2><div class="grid-3"><article class="card"><h3>Admissions</h3><p>Online inquiry, class choice, and office call-back.</p><a href="<?= e(asset('admissions.php')) ?>">Open admissions</a></article><article class="card"><h3>Weekly terms</h3><p>Monday to Saturday timetable for ECE, junior and senior classes.</p><a href="<?= e(asset('terms.php')) ?>">View weekly terms</a></article><article class="card"><h3>Gallery</h3><p>Campus, classroom and event photos, the way an institute gallery works.</p><a href="<?= e(asset('gallery.php')) ?>">Open gallery</a></article><article class="card"><h3>Notices</h3><p>Tests, holidays and parent messages from the office.</p><a href="<?= e(asset('notices.php')) ?>">Read notices</a></article><article class="card"><h3>Scholarship</h3><p>Online form, challan, JazzCash, Easypaisa or 1Bill.</p><a href="<?= e(asset('scholarship.php')) ?>">Register</a></article><article class="card"><h3>Contact</h3><p>Phone, WhatsApp, Facebook and a message form.</p><a href="<?= e(asset('contact.php')) ?>">Contact office</a></article></div></section>
<section class="section"><h2>Latest notices</h2><div class="grid-3"><?php foreach ($notices as $notice): ?><article class="notice <?= $notice['pinned'] ? 'pinned' : '' ?>"><p class="meta"><?= e(date('d M Y', strtotime($notice['created_at']))) ?></p><h3><?= e($notice['title']) ?></h3><p><?= e($notice['body']) ?></p></article><?php endforeach; ?></div></section>
<section class="section"><h2>Campus gallery</h2><?php if (!$photos): ?><p class="empty">Admin gallery se photos upload karega, yahan institute gallery ki tarah aa jayengi.</p><?php else: ?><div class="gallery-grid"><?php foreach ($photos as $photo): ?><figure><img src="<?= e(asset('uploads/gallery/' . $photo['filename'])) ?>" alt="<?= e($photo['title']) ?>"><figcaption><?= e($photo['title']) ?></figcaption></figure><?php endforeach; ?></div><?php endif; ?><p><a class="btn btn-primary" href="<?= e(asset('gallery.php')) ?>">Full gallery</a></p></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
