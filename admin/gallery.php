<?php
require __DIR__ . '/../includes/bootstrap.php'; require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    try {
        if (($_POST['action'] ?? '') === 'upload') {
            $name = save_upload($_FILES['photo'] ?? [], 'gallery');
            if ($name) { db()->prepare('INSERT INTO gallery (title, filename, created_at) VALUES (?, ?, ?)')->execute([trim($_POST['title'] ?? 'School photo'), $name, date('Y-m-d H:i:s')]); flash('ok', 'Photo added.'); }
        }
        if (($_POST['action'] ?? '') === 'delete') {
            $stmt = db()->prepare('SELECT filename FROM gallery WHERE id = ?'); $stmt->execute([(int) $_POST['id']]); $file = $stmt->fetchColumn();
            if ($file && is_file(UPLOAD_DIR . '/gallery/' . $file)) { unlink(UPLOAD_DIR . '/gallery/' . $file); }
            db()->prepare('DELETE FROM gallery WHERE id = ?')->execute([(int) $_POST['id']]); flash('ok', 'Photo removed.');
        }
    } catch (RuntimeException $ex) { flash('error', $ex->getMessage()); }
    redirect('gallery.php');
}
$adminTitle='Gallery'; $adminPage='gallery'; require __DIR__ . '/_header.php';
$rows = db()->query('SELECT * FROM gallery ORDER BY created_at DESC')->fetchAll();
?>
<form class="card" method="post" enctype="multipart/form-data"><?= csrf_field() ?><input type="hidden" name="action" value="upload"><input name="title" placeholder="Caption"><input type="file" name="photo" accept="image/*" required><button>Upload</button></form>
<table><?php foreach ($rows as $row): ?><tr><td><img src="../uploads/gallery/<?= e($row['filename']) ?>" alt="" style="width:90px;height:64px;object-fit:cover"></td><td><?= e($row['title']) ?></td><td><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button>Delete</button></form></td></tr><?php endforeach; ?></table>
<?php require __DIR__ . '/_footer.php'; ?>
