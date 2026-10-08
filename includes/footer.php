</main>
<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <img class="footer-logo" src="<?= e(logo_src()) ?>" alt="">
            <h2><?= e(setting('school_name')) ?></h2>
            <p class="urdu"><?= e(setting('urdu_name')) ?></p>
            <p><?= e(setting('tagline')) ?></p>
        </div>
        <div>
            <h3>Visit</h3>
            <p><?= e(setting('address')) ?></p>
            <p><?= e(setting('hours')) ?></p>
            <p><a href="tel:+<?= e(setting('phone_raw')) ?>"><?= e(setting('phone')) ?></a></p>
        </div>
        <div>
            <h3>School</h3>
            <p><a href="<?= e(asset('admissions.php')) ?>">Admissions</a></p>
            <p><a href="<?= e(asset('scholarship.php')) ?>">Scholarship test</a></p>
            <p><a href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener">Facebook</a></p>
            <p><a href="https://wa.me/<?= e(setting('phone_raw')) ?>" target="_blank" rel="noopener">WhatsApp</a></p>
        </div>
        <div>
            <h3>Sponsor</h3>
            <p>Sindh Education Foundation, Government of Sindh.</p>
            <p>Owner: <?= e(setting('owner_name')) ?></p>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© <?= date('Y') ?> <?= e(setting('school_name')) ?>, <?= e(setting('school_place')) ?>.</span>
        <a href="<?= e(asset('admin/login.php')) ?>">Admin</a>
    </div>
</footer>
<script src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>
</html>
