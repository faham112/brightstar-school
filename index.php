<?php
$page = 'home'; $title = 'Home';
require __DIR__ . '/includes/header.php';
$notices = function_exists('rows') ? rows('SELECT * FROM notices ORDER BY pinned DESC, created_at DESC LIMIT 3') : [];
$photos = function_exists('rows') ? rows('SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6') : [];
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
            <p class="meta"><?= e(env('SCHOLARSHIP_DATE', 'Date soon')) ?></p>
            <h2><?= e(env('SCHOLARSHIP_TITLE', 'Free Scholarship Test')) ?></h2>
            <p><?= e(env('SCHOLARSHIP_NOTE')) ?></p>
            <a class="btn btn-primary" href="<?= e(asset('scholarship.php')) ?>">Register and get challan</a>
        </article>
        <?php if (!db_configured()): ?><p class="card">Website up hai. Forms tab chalenge jab .env mein Hostinger database name, user aur password asal values hon.</p><?php endif; ?>
    </div>
</section>
<section class="section"><h2>Latest notices</h2><div class="grid-3"><?php foreach ($notices as $notice): ?><article class="notice"><h3><?= e($notice['title']) ?></h3><p><?= e($notice['body']) ?></p></article><?php endforeach; ?><?php if (!$notices): ?><p class="empty">Notices database connect hone ke baad yahan aayengi.</p><?php endif; ?></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
