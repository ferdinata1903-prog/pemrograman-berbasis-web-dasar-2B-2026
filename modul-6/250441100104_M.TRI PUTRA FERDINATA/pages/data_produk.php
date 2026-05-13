<?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

// Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE nama LIKE ? OR kategori LIKE ? ORDER BY id DESC");
    $like = "%$search%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
}

// Data untuk grafik
$grafik_result = mysqli_query($conn, "SELECT kategori, COUNT(*) as total FROM produk GROUP BY kategori");
$grafik_labels = [];
$grafik_data   = [];
while ($g = mysqli_fetch_assoc($grafik_result)) {
    $grafik_labels[] = $g['kategori'];
    $grafik_data[]   = $g['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk - Nescafé Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Poppins', sans-serif; background: #f5ede3; min-height: 100vh; }

        .navbar {
            background: linear-gradient(135deg, #a91c07, #3d0d06);
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand { font-family: 'Playfair Display', serif; font-size: 26px; color: #fff; }
        .navbar-brand:hover,
        .navbar-brand:focus {
            color: #ffffff !important;
        }

        .navbar-right { display: flex; align-items: center; gap: 16px; }

        .user-badge {
            background: rgba(255,255,255,0.1);
            color: #fff;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
        }

        .role-badge {
            background: #f5c842;
            color: #1a0a00;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 6px;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.4);
            color: #fff;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-logout:hover { background: #c0392b; border-color: #c0392b; color: #fff; }

        .content { padding: 40px; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #c0392b;
            font-size: 13px;
            text-decoration: none;
            font-weight: 600;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1a0a00;
        }

        .btn-tambah {
            background: linear-gradient(135deg, #c0392b, #922b21);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-tambah:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
            transform: translateY(-1px);
        }

        /* Pencarian */
        .search-bar {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-bar input {
            border: 2px solid #e8ddd0;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 14px;
            flex: 1;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #c0392b;
        }

        .btn-search {
            background: linear-gradient(135deg, #c0392b, #922b21);
            color: #fff;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-search:hover { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        .btn-reset {
            background: #f0e8df;
            color: #555;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-reset:hover { background: #e0d0c0; color: #333; }

        /* Grafik */
        .chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            margin-bottom: 24px;
        }

        .chart-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #1a0a00;
            margin-bottom: 16px;
        }

        /* Tabel */
        .table-wrapper {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        }

        table { width: 100%; border-collapse: collapse; }

        thead {
            background: linear-gradient(135deg, #1a0a00, #3d0d06);
            color: #fff;
        }

        thead th {
            padding: 16px 20px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        tbody tr {
            border-bottom: 1px solid #f0e8df;
            transition: background 0.2s;
        }

        tbody tr:hover { background: #fdf6ee; }

        tbody td {
            padding: 14px 20px;
            font-size: 14px;
            color: #333;
            vertical-align: middle;
        }

        .product-img {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #f0e8df;
        }

        .no-img {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            background: #f0e8df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .badge-kategori {
            background: #fdecea;
            color: #c0392b;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .harga { font-weight: 600; color: #c0392b; }

        .btn-edit {
            background: #fff8e7;
            color: #d4ac0d;
            border: 1px solid #f5c842;
            padding: 5px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-edit:hover { background: #f5c842; color: #1a0a00; }

        .btn-hapus {
            background: #fdecea;
            color: #c0392b;
            border: 1px solid #f5c6c6;
            padding: 5px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-hapus:hover { background: #c0392b; color: #fff; }

        .empty-state { text-align: center; padding: 60px 20px; color: #aaa; }
        .empty-state .icon { font-size: 60px; margin-bottom: 16px; }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
        }

        .toast-notif {
            background: #fff;
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 280px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid #27ae60;
        }

        .toast-notif.hapus { border-left-color: #c0392b; }

        .toast-icon { font-size: 22px; }

        .toast-text { font-size: 14px; color: #333; font-weight: 500; }

        @keyframes slideIn {
            from { transform: translateX(100px); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

<div class="toast-container">
<?php if (isset($_GET['toast'])): ?>
    <?php if ($_GET['toast'] === 'tambah'): ?>
        <div class="toast-notif" id="toastMsg">
            <span class="toast-icon">✅</span>
            <span class="toast-text">Produk berhasil ditambahkan!</span>
        </div>
    <?php elseif ($_GET['toast'] === 'edit'): ?>
        <div class="toast-notif" id="toastMsg">
            <span class="toast-icon">✏️</span>
            <span class="toast-text">Produk berhasil diperbarui!</span>
        </div>
    <?php elseif ($_GET['toast'] === 'hapus'): ?>
        <div class="toast-notif hapus" id="toastMsg">
            <span class="toast-icon">🗑️</span>
            <span class="toast-text">Produk berhasil dihapus!</span>
        </div>
    <?php endif; ?>
<?php endif; ?>
</div>

<nav class="navbar">
    <div class="navbar-brand">NESCAFÉ STORE</div>
    <div class="navbar-right">
        <div class="user-badge">
            👤 <?= htmlspecialchars($_SESSION['username']) ?>
            <span class="role-badge"><?= $_SESSION['role'] ?></span>
        </div>
        <a href="../auth/logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="content">
    <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>

    <div class="page-header">
        <div class="page-title">☕ Data Produk Nescafé</div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="tambah.php" class="btn-tambah">+ Tambah Produk</a>
        <?php endif; ?>
    </div>

    <!-- Grafik -->
    <?php if (!empty($grafik_labels)): ?>
    <div class="chart-card">
        <div class="chart-title">📊 Distribusi Produk per Kategori</div>
        <div style="max-width: 400px; margin: 0 auto;">
            <canvas id="grafikProduk"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <!-- Pencarian -->
    <div class="search-bar">
        <form method="GET" style="display:flex; gap:12px; flex:1;">
            <input type="text" name="search" placeholder="🔍 Cari nama atau kategori produk..."
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn-search">Cari</button>
            <?php if ($search): ?>
                <a href="data_produk.php" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php
            $no  = 1;
            $ada = false;
            while ($row = mysqli_fetch_assoc($result)):
                $ada = true;
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php if ($row['gambar'] && file_exists($_SERVER['DOCUMENT_ROOT'] . '/modul_6/uploads/' . $row['gambar'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" class="product-img" alt="foto">
                        <?php else: ?>
                            <div class="no-img">☕</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                    <td><span class="badge-kategori"><?= htmlspecialchars($row['kategori']) ?></span></td>
                    <td class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?> pcs</td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">✏️ Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-hapus"
                           onclick="return confirm('Yakin ingin menghapus produk ini?')">🗑️ Hapus</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>

            <?php if (!$ada): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="icon">☕</div>
                            <p><?= $search ? 'Produk tidak ditemukan.' : 'Belum ada produk.' ?></p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Toast auto-hide
const toast = document.getElementById('toastMsg');
if (toast) {
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

// Grafik
<?php if (!empty($grafik_labels)): ?>
const ctx = document.getElementById('grafikProduk').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($grafik_labels) ?>,
        datasets: [{
            data: <?= json_encode($grafik_data) ?>,
            backgroundColor: ['#c0392b','#f5c842','#3d0d06','#e67e22'],
            borderWidth: 0,
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
<?php endif; ?>
</script>

</body>
</html>