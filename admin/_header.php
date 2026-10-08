<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_admin();
$adminTitle = $adminTitle ?? 'Dashboard';
$adminPage = $adminPage ?? '';
$flash = take_flash();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($adminTitle) ?> · Admin</title><link rel="stylesheet" href="../assets/css/admin.css"></head><body>
<div class="shell"><aside class="side"><a href="index.php"><strong>Bright Star Admin</strong></a>
<a class="<?= $adminPage==='dash'?'active':'' ?>" href="index.php">Dashboard</a>
<a class="<?= $adminPage==='notices'?'active':'' ?>" href="notices.php">Notices</a>
<a class="<?= $adminPage==='gallery'?'active':'' ?>" href="gallery.php">Gallery</a>
<a class="<?= $adminPage==='admissions'?'active':'' ?>" href="inquiries.php">Admissions</a>
<a class="<?= $adminPage==='scholarships'?'active':'' ?>" href="scholarships.php">Scholarship test</a>
<a class="<?= $adminPage==='messages'?'active':'' ?>" href="messages.php">Messages</a>
<a class="<?= $adminPage==='faculty'?'active':'' ?>" href="faculty.php">Faculty</a>
<a class="<?= $adminPage==='settings'?'active':'' ?>" href="settings.php">Settings and logo</a>
<a href="../index.php">View website</a><a href="logout.php">Log out</a></aside><section class="main"><h1><?= e($adminTitle) ?></h1>
<?php if ($flash): ?><p class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></p><?php endif; ?>
