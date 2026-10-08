<?php
$page = 'home'; $title = 'Home';
require __DIR__ . '/includes/header.php';
$notices = db()->query('SELECT * FROM notices ORDER BY pinned DESC, created_at DESC LIMIT 3')->fetchAll();
$photos = db()->query('SELECT * FROM gallery ORDER BY created_at DESC LIMIT 3')->fetchAll();
?>
<section class="section" style="margin-top:22px">
    <article class="notice pinned">
        <p class="meta"><?= e(env('SCHOLARSHIP_DATE', 'Date to be announced')) ?> · <?= (int) env('SCHOLARSHIP_FEE', '0') > 0 ? e(money((int) env('SCHOLARSHIP_FEE', '0'))) : 'Free' ?></p>
        <h2><?= e(env('SCHOLARSHIP_TITLE', 'Free Scholarship Test')) ?></h2>
        <p><?= e(env('SCHOLARSHIP_NOTE')) ?></p>
        <p><a class="btn btn-primary" href="<?= e(asset('scholarship.php')) ?>">Register and get challan</a></p>
    </article>
</section>
<section class="hero">
    <div class="hero-copy">
        <div class="kicker">Sindh Education Foundation school</div>
        <p class="urdu"><?= e(setting('urdu_name')) ?></p>
        <h1><?= e(setting('school_name')) ?>, <?= e(setting('school_place')) ?></h1>
        <p class="lede"><?= e(setting('tagline')) ?>. ECE to Class 5, led by <?= e(setting('owner_name')) ?>.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= e(asset('admissions.php')) ?>">Apply for admission</a>
            <a class="btn btn-dark" href="tel:+<?= e(setting('phone_raw')) ?>">Call <?= e(setting('phone')) ?></a>
            <a class="btn btn-ghost" href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener">Facebook</a>
        </div>
    </div>
    <div class="card"><h2>Office</h2><p><strong><?= e(setting('owner_name')) ?></strong><br><?= e(setting('owner_title')) ?></p><p><?= e(setting('address')) ?></p><p><?= e(setting('hours')) ?></p></div>
</section>
<section class="section"><div class="grid-4"><div class="stat"><b>ECE–5</b><span>Classes</span></div><div class="stat"><b>SEF</b><span>Sponsored school</span></div><div class="stat"><b>Co-ed</b><span>Boys and girls</span></div><div class="stat"><b><?= e(setting('phone')) ?></b><span><?= e(setting('owner_name')) ?></span></div></div></section>
<section class="section"><h2>Latest notices</h2><div class="grid-3"><?php foreach ($notices as $notice): ?><article class="notice <?= $notice['pinned'] ? 'pinned' : '' ?>"><p class="meta"><?= e(date('d M Y', strtotime($notice['created_at']))) ?></p><h3><?= e($notice['title']) ?></h3><p><?= e($notice['body']) ?></p></article><?php endforeach; ?></div></section>
<section class="section"><h2>Gallery</h2><?php if (!$photos): ?><p class="empty">Photos will appear here after the admin uploads them.</p><?php else: ?><div class="gallery-grid"><?php foreach ($photos as $photo): ?><figure><img src="<?= e(asset('uploads/gallery/' . $photo['filename'])) ?>" alt="<?= e($photo['title']) ?>"><figcaption><?= e($photo['title']) ?></figcaption></figure><?php endforeach; ?></div><?php endif; ?></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
