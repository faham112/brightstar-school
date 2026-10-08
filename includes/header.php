<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/db-safe.php';
$flash = take_flash();
$current = $page ?? '';
$safe = [
    'school_name' => 'Bright Star Public Elementary School',
    'school_place' => 'Yaro Lound',
    'urdu_name' => 'برائٹ سٹار پبلک ایلیمنٹری اسکول، یارو لوند',
    'phone' => env('SCHOOL_PHONE', '0304 3991097'),
    'phone_raw' => env('SCHOOL_PHONE_RAW', '923043991097'),
    'facebook' => env('FACEBOOK_URL', 'https://www.facebook.com/share/1C6LYiSR4n/'),
];
function school(string $key, string $default = ''): string {
    global $safe;
    if (function_exists('db_configured') && !db_configured()) { return $safe[$key] ?? $default; }
    try { return setting($key); }
    catch (Throwable $e) { return $safe[$key] ?? $default; }
}
$school = school('school_name', 'Bright Star Public Elementary School');
$place = school('school_place', 'Yaro Lound');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? $school) ?> · <?= e($school) ?></title>
    <link rel="icon" href="<?= e(logo_src()) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,650&family=Noto+Nastaliq+Urdu:wght@500&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
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
            <a href="<?= e(asset('index.php')) ?>">Home</a>
            <a href="<?= e(asset('about.php')) ?>">About</a>
            <a href="<?= e(asset('academics.php')) ?>">Academics</a>
            <a href="<?= e(asset('terms.php')) ?>">Weekly terms</a>
            <a href="<?= e(asset('admissions.php')) ?>">Admissions</a>
            <a href="<?= e(asset('scholarship.php')) ?>">Scholarship</a>
            <a href="<?= e(asset('gallery.php')) ?>">Gallery</a>
            <a href="<?= e(asset('contact.php')) ?>">Contact</a>
            <a class="nav-call" href="tel:+<?= e(school('phone_raw')) ?>"><?= e(school('phone')) ?></a>
        </nav>
    </div>
</header>
<?php if ($flash): ?><div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<main>
