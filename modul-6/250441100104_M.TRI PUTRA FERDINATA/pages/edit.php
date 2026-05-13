<?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: data_produk.php");
    exit;
}

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    header("Location: data_produk.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = $_POST['nama'];
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambar    = $produk['gambar']; // default gambar lama

    // Kalau ada upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext     = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $nama_file  = uniqid('produk_') . '.' . $ext;
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $nama_file);

            // Hapus gambar lama
            if ($produk['gambar'] && file_exists('../uploads/' . $produk['gambar'])) {
                unlink('../uploads/' . $produk['gambar']);
            }
            $gambar = $nama_file;
        }
    }

    $stmt2 = mysqli_prepare($conn, "UPDATE produk SET nama=?, kategori=?, harga=?, stok=?, deskripsi=?, gambar=? WHERE id=?");
    mysqli_stmt_bind_param($stmt2, "ssdissi", $nama, $kategori, $harga, $stok, $deskripsi, $gambar, $id);

    if (mysqli_stmt_execute($stmt2)) {
        header("Location: data_produk.php?toast=edit");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - Nescafé Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f5ede3; min-height: 100vh; }

        .navbar {
            background: linear-gradient(135deg, #1a0a00, #3d0d06);
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }

        .navbar-brand { font-family: 'Playfair Display', serif; font-size: 26px; color: #fff; }
        .navbar-brand span { color: #f5c842; }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.4);
            color: #fff;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            text-decoration: none;
        }

        .content { padding: 40px; max-width: 680px; margin: 0 auto; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #c0392b;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
        }

        .form-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }

        .form-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #1a0a00;
            margin-bottom: 6px;
        }

        .form-card-subtitle { color: #888; font-size: 13px; margin-bottom: 28px; }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border: 2px solid #e8ddd0;
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #d4ac0d;
            box-shadow: 0 0 0 3px rgba(212,172,13,0.1);
        }

        .img-preview-wrapper {
            border: 2px dashed #e8ddd0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .img-preview-wrapper:hover { border-color: #d4ac0d; background: #fdf6ee; }

        .img-preview-wrapper img {
            max-height: 180px;
            border-radius: 8px;
        }

        .img-label {
            color: #aaa;
            font-size: 12px;
            margin-top: 8px;
        }

        .btn-update {
            background: linear-gradient(135deg, #d4ac0d, #b7950b);
            color: #1a0a00;
            border: none;
            border-radius: 10px;
            padding: 13px 30px;
            font-weight: 700;
            font-size: 15px;
            transition: all 0.3s;
        }

        .btn-update:hover {
            background: linear-gradient(135deg, #f5c842, #d4ac0d);
            transform: translateY(-1px);
            color: #1a0a00;
        }

        .btn-batal {
            background: #f0e8df;
            color: #555;
            border: none;
            border-radius: 10px;
            padding: 13px 30px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-batal:hover { background: #e0d0c0; color: #333; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">Nesca<span>fé</span> Store</div>
    <a href="../auth/logout.php" class="btn-logout">Logout</a>
</nav>

<div class="content">
    <a href="data_produk.php" class="back-link">← Kembali ke Data Produk</a>

    <div class="form-card">
        <div class="form-card-title">✏️ Edit Produk</div>
        <div class="form-card-subtitle">Perbarui informasi produk di bawah ini</div>

        <form method="POST" id="editForm" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label class="form-label">Foto Produk</label>
                <div class="img-preview-wrapper" onclick="document.getElementById('gambar').click()">
                    <?php if ($produk['gambar'] && file_exists('../uploads/' . $produk['gambar'])): ?>
                        <img id="previewImg" src="../uploads/<?= htmlspecialchars($produk['gambar']) ?>" alt="Foto Produk">
                        <div class="img-label">Klik untuk ganti foto</div>
                    <?php else: ?>
                        <img id="previewImg" src="" alt="Preview" style="display:none; max-height:180px; border-radius:8px;">
                        <div class="placeholder" style="color:#aaa; font-size:13px;">
                            <span style="font-size:40px; display:block; margin-bottom:8px;">📷</span>
                            Klik untuk upload foto produk
                        </div>
                    <?php endif; ?>
                </div>
                <input type="file" name="gambar" id="gambar" accept="image/*" style="display:none">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" id="nama" class="form-control"
                       value="<?= htmlspecialchars($produk['nama']) ?>" required>
                <div class="invalid-feedback">Nama produk tidak boleh kosong.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" id="kategori" class="form-select" required>
                    <?php
                    $kategoriList = ['Kopi Sachet','Kopi Kaleng','Kopi Botol','Kopi Bubuk'];
                    foreach ($kategoriList as $k):
                    ?>
                    <option value="<?= $k ?>" <?= $produk['kategori']==$k ? 'selected' : '' ?>><?= $k ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Pilih kategori produk.</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control"
                           value="<?= $produk['harga'] ?>" min="0" required>
                    <div class="invalid-feedback">Harga harus lebih dari 0.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control"
                           value="<?= $produk['stok'] ?>" min="0" required>
                    <div class="invalid-feedback">Stok tidak boleh negatif.</div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn-update">Update Produk</button>
                <a href="data_produk.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('gambar').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            const placeholder = document.querySelector('.placeholder');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('editForm').addEventListener('submit', function(e) {
    let valid = true;
    const nama     = document.getElementById('nama');
    const kategori = document.getElementById('kategori');
    const harga    = document.getElementById('harga');
    const stok     = document.getElementById('stok');

    if (!nama.value.trim()) { nama.classList.add('is-invalid'); valid = false; }
    else { nama.classList.remove('is-invalid'); }

    if (!kategori.value) { kategori.classList.add('is-invalid'); valid = false; }
    else { kategori.classList.remove('is-invalid'); }

    if (!harga.value || harga.value <= 0) { harga.classList.add('is-invalid'); valid = false; }
    else { harga.classList.remove('is-invalid'); }

    if (stok.value === '' || stok.value < 0) { stok.classList.add('is-invalid'); valid = false; }
    else { stok.classList.remove('is-invalid'); }

    if (!valid) e.preventDefault();
});
</script>

</body>
</html>