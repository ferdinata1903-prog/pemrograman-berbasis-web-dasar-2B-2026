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