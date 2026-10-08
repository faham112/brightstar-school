<?php $page='about'; $title='About'; require __DIR__ . '/includes/header.php'; $faculty = db()->query('SELECT * FROM faculty ORDER BY sort_order, name')->fetchAll(); ?>
<section class="section"><p class="kicker">About</p><h1><?= e(setting('school_name')) ?></h1><p class="urdu"><?= e(setting('urdu_name')) ?></p><p class="lede"><?= e(setting('about')) ?></p></section>
<section class="section grid-2"><article class="card"><h2>Mission</h2><p><?= e(setting('mission')) ?></p></article><article class="card"><h2>Vision</h2><p><?= e(setting('vision')) ?></p></article></section>
<section class="section"><h2>School head</h2><div class="grid-3"><?php foreach ($faculty as $person): ?><article class="person"><p class="meta"><?= e($person['role']) ?></p><h3><?= e($person['name']) ?></h3><p><?= e($person['bio']) ?></p></article><?php endforeach; ?></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
