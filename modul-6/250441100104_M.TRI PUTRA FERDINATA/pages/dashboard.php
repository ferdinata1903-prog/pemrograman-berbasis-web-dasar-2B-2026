<?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

// Hitung total produk
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$total_stok   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(stok) as total FROM produk"))['total'];
$total_user   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nescafé Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5ede3;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, #842407, #3d0d06);
            padding: 16px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(46, 46, 46, 0.4);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #ffffff;
            letter-spacing: 1px;
        }
        .navbar-brand:hover,
        .navbar-brand:focus {
            color: #ffffff !important;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

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

        .btn-logout:hover {
            background: #c0392b;
            border-color: #c0392b;
            color: #fff;
        }

        /* HERO */
        .hero {
            background: 
                linear-gradient(135deg, rgba(192,57,43,0.75) 0%, rgba(26,10,0,0.85) 100%),
                url('../bg_nescafe.png') center/cover no-repeat;
            padding: 60px 40px;  
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            margin-bottom: 8px;
        }

        .hero p {
            color: rgba(255, 255, 255, 0.78);
            font-size: 15px;
        }

        /* STATS CARD */
        .stats-section {
            padding: 40px;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover { transform: translateY(-4px); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
        }

        .stat-icon.red    { background: #fdecea; }
        .stat-icon.yellow { background: #fef9e7; }
        .stat-icon.brown  { background: #fdf2e9; }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #1a0a00;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
        }

        /* QUICK ACCESS */
        .quick-section {
            padding: 0 40px 40px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            color: #1a0a00;
            margin-bottom: 20px;
        }

        .quick-card {
            background: #fff;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            text-decoration: none;
            color: inherit;
            display: block;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .quick-card:hover {
            border-color: #c0392b;
            transform: translateY(-4px);
            color: inherit;
        }

        .quick-card .icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .quick-card h6 {
            font-weight: 600;
            color: #1a0a00;
            margin-bottom: 4px;
        }

        .quick-card p {
            font-size: 12px;
            color: #888;
            margin: 0;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
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

<!-- HERO -->
<div class="hero">
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Kelola produk Nescafé Store dengan mudah dari dashboard ini.</p>
</div>

<!-- STATS -->
<div class="stats-section">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon red">☕</div>
                <div>
                    <div class="stat-number"><?= $total_produk ?></div>
                    <div class="stat-label">Total Produk</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon yellow">📦</div>
                <div>
                    <div class="stat-number"><?= $total_stok ?? 0 ?></div>
                    <div class="stat-label">Total Stok</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon brown">👥</div>
                <div>
                    <div class="stat-number"><?= $total_user ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="quick-section">
    <div class="section-title">Akses Cepat</div>
    <div class="row g-4">
        <div class="col-md-4">
            <a href="data_produk.php" class="quick-card">
                <div class="icon">📋</div>
                <h6>Data Produk</h6>
                <p>Lihat semua produk Nescafé</p>
            </a>
        </div>
        <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="col-md-4">
            <a href="tambah.php" class="quick-card">
                <div class="icon">➕</div>
                <h6>Tambah Produk</h6>
                <p>Tambahkan produk baru ke daftar</p>
            </a>
        </div>
        <?php endif; ?>
        <div class="col-md-4">
            <a href="../auth/logout.php" class="quick-card">
                <div class="icon">🚪</div>
                <h6>Logout</h6>
                <p>Keluar dari akun kamu</p>
            </a>
        </div>
    </div>
</div>

</body>
</html>