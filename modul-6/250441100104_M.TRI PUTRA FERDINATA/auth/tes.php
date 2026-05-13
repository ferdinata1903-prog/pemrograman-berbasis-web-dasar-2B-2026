<!-- file koneksi.php -->
<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_modul6";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

<!-- file cek_login.php -->
 <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!-- file login.php -->
 <?php
session_start();
require_once '../config/koneksi.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        header("Location: ../pages/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nescafé Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: #1a0a00;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(180,30,20,0.3) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(120,60,0,0.4) 0%, transparent 50%);
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            min-height: 560px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
            position: relative;
            z-index: 1;
        }

        .brand-panel {
            flex: 1;
            background: linear-gradient(160deg, #c0392b 0%, #7b1a10 50%, #3d0d06 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '☕';
            position: absolute;
            font-size: 200px;
            opacity: 0.07;
            bottom: -30px;
            right: -30px;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #fff;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .brand-logo span { color: #f5c842; }

        .brand-tagline {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .brand-desc {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            text-align: center;
            line-height: 1.8;
        }

        .coffee-circles {
            display: flex;
            gap: 12px;
            margin-top: 40px;
        }

        .coffee-circles span {
            width: 12px; height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
        }

        .coffee-circles span:nth-child(2) { background: #f5c842; }

        .form-panel {
            flex: 1;
            background: #fdf6ee;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 45px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1a0a00;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: #888;
            font-size: 13px;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .form-control {
            border: 2px solid #e8ddd0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            background: #fff;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1);
        }

        .btn-nescafe {
            background: linear-gradient(135deg, #c0392b, #922b21);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 1px;
            transition: all 0.3s;
            margin-top: 8px;
            width: 100%;
        }

        .btn-nescafe:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(192,57,43,0.4);
            color: #fff;
        }

        .link-register {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }

        .link-register a {
            color: #c0392b;
            font-weight: 600;
            text-decoration: none;
        }

        .alert-danger {
            background: #fdecea;
            border: 1px solid #f5c6c6;
            color: #c0392b;
            border-radius: 10px;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="brand-panel">
        <div class="brand-logo">Nesca<span>fé</span></div>
        <div class="brand-tagline">Good Morning Starts Here</div>
        <div class="brand-desc">
            Selamat datang di Nescafé Store.<br>
            Temukan koleksi kopi terbaik kami<br>
            untuk menemani hari-harimu.
        </div>
        <div class="coffee-circles">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="form-panel">
        <div class="form-title">Masuk ke Akun</div>
        <div class="form-subtitle">Masukkan kredensial kamu untuk melanjutkan</div>

        <?php if ($error): ?>
            <div class="alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" id="loginForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                <div class="invalid-feedback">Username tidak boleh kosong.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required minlength="6">
                <div class="invalid-feedback">Password minimal 6 karakter.</div>
            </div>
            <button type="submit" class="btn-nescafe">Masuk →</button>
        </form>

        <div class="link-register">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let valid = true;

    const username = document.getElementById('username');
    const password = document.getElementById('password');

    if (!username.value.trim()) {
        username.classList.add('is-invalid');
        valid = false;
    } else {
        username.classList.remove('is-invalid');
    }

    if (password.value.length < 6) {
        password.classList.add('is-invalid');
        valid = false;
    } else {
        password.classList.remove('is-invalid');
    }

    if (!valid) e.preventDefault();
});
</script>

</body>
</html>

<!-- file logout.php -->
 <?php
session_start();
session_destroy();
header("Location: ../auth/login.php");
exit;
?>

<!-- file register.php -->
 <?php
session_start();
require_once '../config/koneksi.php';

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'user';

    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password, $role);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Registrasi berhasil! Silakan login.";
    } else {
        $error = "Username atau email sudah digunakan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nescafé Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

      body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: #1a0a00;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-y: auto; /* ← ganti dari hidden ke auto */
            padding: 40px 20px; /* ← tambahkan padding agar tidak mepet */
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(180,30,20,0.3) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(120,60,0,0.4) 0%, transparent 50%);
        }

        .register-wrapper {
            display: flex;
            width: 900px;
            min-height: 620px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
            position: relative;
            z-index: 1;
            margin: auto; /* ← tambahkan ini */
        }

        .brand-panel {
            flex: 1;
            background: linear-gradient(160deg, #c0392b 0%, #7b1a10 50%, #3d0d06 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '☕';
            position: absolute;
            font-size: 200px;
            opacity: 0.07;
            bottom: -30px;
            right: -30px;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            color: #fff;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .brand-logo span { color: #f5c842; }
        .brand-tagline {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .brand-desc {
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            text-align: center;
            line-height: 1.8;
        }

        .coffee-circles {
            display: flex;
            gap: 12px;
            margin-top: 40px;
        }

        .coffee-circles span {
            width: 12px; height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
        }

        .coffee-circles span:nth-child(2) { background: #f5c842; }

        .form-panel {
            flex: 1;
            background: #fdf6ee;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 45px;
        }

        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1a0a00;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: #888;
            font-size: 13px;
            margin-bottom: 28px;
        }

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
            background: #fff;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1);
        }

        .btn-nescafe {
            background: linear-gradient(135deg, #c0392b, #922b21);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 1px;
            transition: all 0.3s;
            margin-top: 8px;
        }

        .btn-nescafe:hover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(192,57,43,0.4);
            color: #fff;
        }

        .link-login {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #888;
        }

        .link-login a {
            color: #c0392b;
            font-weight: 600;
            text-decoration: none;
        }

        .alert-danger {
            background: #fdecea;
            border: 1px solid #f5c6c6;
            color: #c0392b;
            border-radius: 10px;
            font-size: 13px;
            padding: 10px 14px;
        }

        .alert-success {
            background: #eafaf1;
            border: 1px solid #a9dfbf;
            color: #1e8449;
            border-radius: 10px;
            font-size: 13px;
            padding: 10px 14px;
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <div class="brand-panel">
        <div class="brand-logo">Nesca<span>fé</span></div>
        <div class="brand-tagline">Good Morning Starts Here</div>
        <div class="brand-desc">
            Bergabunglah bersama ribuan<br>
            pecinta kopi Nescafé dan nikmati<br>
            pengalaman berbelanja terbaik.
        </div>
        <div class="coffee-circles">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="form-panel">
        <div class="form-title">Buat Akun Baru</div>
        <div class="form-subtitle">Isi data di bawah untuk mendaftar</div>

        <?php if ($error): ?>
            <div class="alert alert-danger mb-3"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success mb-3"><?= $success ?> <a href="login.php">Login sekarang</a></div>
        <?php endif; ?>

        <form method="POST" id="registerForm" novalidate>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required minlength="3">
                <div class="invalid-feedback">Username minimal 3 karakter.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="contoh@email.com" required>
                <div class="invalid-feedback">Format email tidak valid.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required minlength="8">
                <div class="invalid-feedback">Password minimal 8 karakter.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" id="confirm_password" class="form-control" placeholder="Ulangi password" required>
                <div class="invalid-feedback">Password tidak cocok.</div>
            </div>
            <button type="submit" class="btn btn-nescafe w-100">Daftar Sekarang →</button>
        </form>

        <div class="link-login">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;

    const username         = document.getElementById('username');
    const email            = document.getElementById('email');
    const password         = document.getElementById('password');
    const confirmPassword  = document.getElementById('confirm_password');
    const emailRegex       = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (username.value.trim().length < 3) {
        username.classList.add('is-invalid'); valid = false;
    } else { username.classList.remove('is-invalid'); }

    if (!emailRegex.test(email.value.trim())) {
        email.classList.add('is-invalid'); valid = false;
    } else { email.classList.remove('is-invalid'); }

    if (password.value.length < 8) {
        password.classList.add('is-invalid'); valid = false;
    } else { password.classList.remove('is-invalid'); }

    if (confirmPassword.value !== password.value || confirmPassword.value === '') {
        confirmPassword.classList.add('is-invalid'); valid = false;
    } else { confirmPassword.classList.remove('is-invalid'); }

    if (!valid) e.preventDefault();
});
</script>

</body>
</html>

<!-- file dashboard.php -->
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

<!-- file data_produk -->
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

<!-- file edit.php -->
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

<!-- file hapus.php -->
 <?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: data_produk.php");
    exit;
}

$id = $_GET['id'];

// Hapus gambar juga
$stmt = mysqli_prepare($conn, "SELECT gambar FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

if ($produk && $produk['gambar'] && file_exists('../uploads/' . $produk['gambar'])) {
    unlink('../uploads/' . $produk['gambar']);
}

$stmt2 = mysqli_prepare($conn, "DELETE FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);

header("Location: data_produk.php?toast=hapus");
exit;
?>

<!-- file tambah.php -->
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

<!-- file index.php -->
 <?php
header("Location: auth/login.php");
exit;
?>