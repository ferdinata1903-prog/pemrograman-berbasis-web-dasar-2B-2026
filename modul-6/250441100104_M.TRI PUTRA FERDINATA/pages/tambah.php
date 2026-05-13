<?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: data_produk.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = $_POST['nama'];
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambar    = "";

    // Upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext        = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed    = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $nama_file  = uniqid('produk_') . '.' . $ext;
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/modul_6/uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $nama_file);
            $gambar = $nama_file;
        } else {
            $error = "Format gambar harus JPG, PNG, atau WEBP.";
        }
    }

    if (!$error) {
        $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama, kategori, harga, stok, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdiss", $nama, $kategori, $harga, $stok, $deskripsi, $gambar);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: data_produk.php?toast=tambah");
            exit;
        } else {
            $error = "Gagal menambahkan produk.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Nescafé Store</title>
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
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1);
        }

        /* Preview gambar */
        .img-preview-wrapper {
            border: 2px dashed #e8ddd0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .img-preview-wrapper:hover { border-color: #c0392b; background: #fdf6ee; }

        .img-preview-wrapper img {
            max-height: 180px;
            border-radius: 8px;
            display: none;
        }

        .img-preview-wrapper .placeholder {
            color: #aaa;
            font-size: 13px;
        }

        .img-preview-wrapper .placeholder span {
            font-size: 40px;
            display: block;
            margin-bottom: 8px;
        }

        .btn-simpan {
            background: linear-gradient(135deg, #c0392b, #922b21);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px 30px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
        }

        .btn-simpan:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            transform: translateY(-1px);
            color: #fff;
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

        .alert-danger {
            background: #fdecea;
            border: 1px solid #f5c6c6;
            color: #c0392b;
            border-radius: 10px;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 20px;
        }
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
        <div class="form-card-title">➕ Tambah Produk</div>
        <div class="form-card-subtitle">Isi detail produk Nescafé baru di bawah ini</div>

        <?php if ($error): ?>
            <div class="alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="tambahForm" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label class="form-label">Foto Produk</label>
                <div class="img-preview-wrapper" onclick="document.getElementById('gambar').click()">
                    <div class="placeholder">
                        <span>📷</span>
                        Klik untuk upload foto produk<br>
                        <small>JPG, PNG, WEBP — Maks 2MB</small>
                    </div>
                    <img id="previewImg" src="" alt="Preview">
                </div>
                <input type="file" name="gambar" id="gambar" accept="image/*" style="display:none">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Nescafé Classic 200g" required>
                <div class="invalid-feedback">Nama produk tidak boleh kosong.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" id="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Kopi Sachet">Kopi Sachet</option>
                    <option value="Kopi Kaleng">Kopi Kaleng</option>
                    <option value="Kopi Botol">Kopi Botol</option>
                    <option value="Kopi Bubuk">Kopi Bubuk</option>
                </select>
                <div class="invalid-feedback">Pilih kategori produk.</div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Contoh: 25000" min="0" required>
                    <div class="invalid-feedback">Harga harus lebih dari 0.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" id="stok" class="form-control" placeholder="Contoh: 100" min="0" required>
                    <div class="invalid-feedback">Stok tidak boleh negatif.</div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
            <div class="d-flex gap-3">
                <button type="submit" class="btn-simpan">Simpan Produk</button>
                <a href="data_produk.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
// Preview gambar sebelum upload
document.getElementById('gambar').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('previewImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.querySelector('.placeholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Validasi form
document.getElementById('tambahForm').addEventListener('submit', function(e) {
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