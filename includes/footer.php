</main>
<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <h2><?= e(function_exists('school') ? school('school_name') : 'Bright Star Public Elementary School') ?></h2>
            <p>Yaro Lound · Sindh Education Foundation</p>
        </div>
        <div>
            <h3>Visit</h3>
            <p>Yaro Lound, District Ghotki, Sindh</p>
            <p><a href="tel:+923043991097">0304 3991097</a></p>
        </div>
        <div>
            <h3>School</h3>
            <p><a href="<?= e(asset('admissions.php')) ?>">Admissions</a></p>
            <p><a href="<?= e(asset('scholarship.php')) ?>">Scholarship test</a></p>
            <p><a href="<?= e(asset('contact.php')) ?>">Contact</a></p>
        </div>
    </div>
    <div class="footer-bottom"><span>© <?= date('Y') ?> Bright Star Public Elementary School</span><a href="<?= e(asset('admin/login.php')) ?>">Admin</a></div>
</footer>
<script src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>
</html>
