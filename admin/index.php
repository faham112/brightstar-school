<?php $adminTitle='Dashboard'; $adminPage='dash'; require __DIR__ . '/_header.php'; ?>
<div class="cards">
<article class="card"><b><?= (int) db()->query('SELECT COUNT(*) FROM notices')->fetchColumn() ?></b> Notices</article>
<article class="card"><b><?= (int) db()->query('SELECT COUNT(*) FROM gallery')->fetchColumn() ?></b> Photos</article>
<article class="card"><b><?= (int) db()->query("SELECT COUNT(*) FROM admissions WHERE status='new'")->fetchColumn() ?></b> New admissions</article>
<article class="card"><b><?= (int) db()->query("SELECT COUNT(*) FROM scholarships WHERE status='pending'")->fetchColumn() ?></b> Pending challans</article>
</div>
<p>Signed in as <?= e($_SESSION['admin_name'] ?? 'admin') ?>.</p>
<?php require __DIR__ . '/_footer.php'; ?>
