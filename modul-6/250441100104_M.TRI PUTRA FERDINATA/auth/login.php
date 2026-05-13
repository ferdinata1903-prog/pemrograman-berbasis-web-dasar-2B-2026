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