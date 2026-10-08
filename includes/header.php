<?php
require_once __DIR__ . '/bootstrap.php';
$flash = take_flash();
$current = $page ?? '';
$school = setting('school_name');
$place = setting('school_place');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? $school) ?> · <?= e($school) ?></title>
    <link rel="icon" href="<?= e(logo_src()) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,650;9..144,720&family=Noto+Nastaliq+Urdu:wght@500&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
</head>
<body>
<header class="site-header">
    <div class="sponsor-bar"><span>Sponsored by the Sindh Education Foundation · Government of Sindh</span></div>
    <div class="nav-wrap">
        <a class="brand" href="<?= e(asset('index.php')) ?>">
            <img src="<?= e(logo_src()) ?>" alt="<?= e($school) ?> logo">
            <span><strong><?= e($school) ?></strong><em><?= e($place) ?></em></span>
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle>Menu</button>
        <nav class="main-nav" data-nav>
            <a class="<?= $current === 'home' ? 'active' : '' ?>" href="<?= e(asset('index.php')) ?>">Home</a>
            <a class="<?= $current === 'about' ? 'active' : '' ?>" href="<?= e(asset('about.php')) ?>">About</a>
            <a class="<?= $current === 'academics' ? 'active' : '' ?>" href="<?= e(asset('academics.php')) ?>">Academics</a>
            <a class="<?= $current === 'terms' ? 'active' : '' ?>" href="<?= e(asset('terms.php')) ?>">Weekly terms</a>
            <a class="<?= $current === 'admissions' ? 'active' : '' ?>" href="<?= e(asset('admissions.php')) ?>">Admissions</a>
            <a class="<?= $current === 'scholarship' ? 'active' : '' ?>" href="<?= e(asset('scholarship.php')) ?>">Scholarship</a>
            <a class="<?= $current === 'gallery' ? 'active' : '' ?>" href="<?= e(asset('gallery.php')) ?>">Gallery</a>
            <a class="<?= $current === 'contact' ? 'active' : '' ?>" href="<?= e(asset('contact.php')) ?>">Contact</a>
            <a class="nav-call" href="tel:+<?= e(setting('phone_raw')) ?>"><?= e(setting('phone')) ?></a>
        </nav>
    </div>
</header>
<?php if ($flash): ?><div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<main>
