<?php
$daftarArtikel = [
    [
        'id'       => 1,
        'judul'    => 'Belajar HTML Pertama Kali',
        'tanggal'  => '12 Maret 2026',
        'refleksi' => 'Hari pertama belajar HTML terasa membingungkan. Tag seperti div, p, dan a terasa asing. Tapi setelah berhasil membuat halaman "Hello World", semuanya terasa menyenangkan dan saya semakin penasaran dengan dunia web.',
        'gambar'   => 'img/html.jpg',
    ],
    [
        'id'       => 2,
        'judul'    => 'Error Pertama yang Bikin Panik',
        'tanggal'  => '5 Juli 2026',
        'refleksi' => 'Saya lupa menutup satu tag dan seluruh tampilan web jadi berantakan. Setelah 2 jam debugging akhirnya ketemu. Pelajaran berharga: selalu cek struktur HTML dengan teliti!',
        'gambar'   => 'img/error.jpg',
    ],
    [
        'id'       => 3,
        'judul'    => 'Pertama Kali Pakai PHP',
        'tanggal'  => '20 Januari 2026',
        'refleksi' => 'PHP membuka mata saya bahwa website bisa dinamis dan "hidup". Pertama kali berhasil menyimpan data form ke database terasa seperti sihir. Ini titik balik saya serius belajar web.',
        'gambar'   => 'img/php.jpg',
    ],
    [
        'id'       => 4,
        'judul'    => 'Proyek Pertama Selesai',
        'tanggal'  => '30 November 2026',
        'refleksi' => 'Sistem informasi perpustakaan mini dengan CRUD lengkap akhirnya selesai! Rasa puas luar biasa ketika hasil karya sendiri bisa digunakan orang lain. Ini motivasi terbesar untuk terus belajar.',
        'gambar'   => 'img/project.jpg',
    ],
];

$kutipanMotivasi = [
    "First, solve the problem. Then, write the code. — John Johnson",
    "The best way to learn is to do. — Paul Halmos",
    "Every expert was once a beginner. — Helen Hayes",
    "Code is like humor. When you have to explain it, it's bad. — Cory House",
    "Programming is not about what you know, it's about what you can figure out.",
];

$indexAcak       = array_rand($kutipanMotivasi);      
$kutipanTerpilih = $kutipanMotivasi[$indexAcak];  

$idDipilih      = isset($_GET['id']) ? (int)$_GET['id'] : null;
$artikelDipilih = null;

if ($idDipilih !== null) {
    foreach ($daftarArtikel as $artikel) {
        if ($artikel['id'] === $idDipilih) {
            $artikelDipilih = $artikel;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Blog Developer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 700px; margin: auto; }
        h1 { text-align: center; color: #059669; }
        p.subtitle { text-align: center; color: #888; margin-bottom: 24px; }

        .card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        h2 { color: #065f46; margin-bottom: 12px; }

        .kutipan { background: #d1fae5; border-left: 4px solid #059669; border-radius: 6px; padding: 12px 16px; font-style: italic; color: #065f46; margin-bottom: 20px; }

        .daftar-artikel ul { list-style: none; padding: 0; }
        .daftar-artikel li { border-bottom: 1px solid #eee; }
        .daftar-artikel li:last-child { border-bottom: none; }
        .daftar-artikel a { display: block; padding: 10px 4px; color: #2563eb; text-decoration: none; }
        .daftar-artikel a:hover { color: #059669; }
        .daftar-artikel a.aktif { color: #059669; font-weight: bold; }


        .detail h2 { font-size: 1.3rem; }
        .tanggal { font-size: 0.82rem; color: #888; margin-bottom: 14px; }
        .gambar-box { background: #f3f4f6; border: 1px dashed #ccc; border-radius: 6px; padding: 20px; text-align: center; color: #9ca3af; font-size: 0.85rem; margin-bottom: 14px; }
        .refleksi { line-height: 1.8; color: #374151; }
        .link-ref { display: inline-block; margin-top: 12px; color: #059669; font-size: 0.85rem; }


        .placeholder { background: white; border: 1px dashed #ccc; border-radius: 8px; padding: 30px; text-align: center; color: #9ca3af; }

        .nav { display: flex; gap: 12px; justify-content: center; margin-top: 20px; }
        .nav a { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; color: white; }
        .btn-profil   { background: #2563eb; }
        .btn-timeline { background: #7c3aed; }
    </style>
</head>
<body>
<div class="container">

    <h1>📝 Blog Reflektif Developer</h1>
    <p class="subtitle">Cerita jujur perjalanan belajar coding</p>

    <div class="kutipan">
        💬 <?= htmlspecialchars($kutipanTerpilih) ?>
    </div>

    <div class="card daftar-artikel">
        <h2>📚 Daftar Artikel</h2>
        <ul>
            <?php foreach ($daftarArtikel as $artikel): ?>
                <li>
                    <a href="blog.php?id=<?= $artikel['id'] ?>"
                       class="<?= ($idDipilih === $artikel['id']) ? 'aktif' : '' ?>">
                        <?= ($idDipilih === $artikel['id']) ? '▶ ' : '○ ' ?>
                        <?= htmlspecialchars($artikel['judul']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($artikelDipilih !== null): ?>

        <div class="card detail">
            <h2><?= htmlspecialchars($artikelDipilih['judul']) ?></h2>
            <div class="tanggal">📅 <?= htmlspecialchars($artikelDipilih['tanggal']) ?></div>

            <?php if (file_exists($artikelDipilih['gambar'])): ?>
                <img src="<?= htmlspecialchars($artikelDipilih['gambar']) ?>"
                     alt="Ilustrasi" style="width:100%; border-radius:6px; margin-bottom:14px;">
            <?php else: ?>
                <div class="gambar-box">
                    🖼️ <?= htmlspecialchars($artikelDipilih['gambar']) ?><br>
                    <small>Letakkan gambar di folder /img/</small>
                </div>
            <?php endif; ?>

            <p class="refleksi"><?= htmlspecialchars($artikelDipilih['refleksi']) ?></p>

            <a href="https://developer.mozilla.org" target="_blank" class="link-ref">
                🔗 Referensi: MDN Web Docs
            </a>
        </div>

    <?php else: ?>

        <div class="placeholder">
            👆 Klik salah satu judul artikel di atas untuk membaca isinya.
        </div>

    <?php endif; ?>

    <div class="nav">
        <a href="index.php"    class="btn-profil">👨‍💻 Kembali ke Profil</a>
        <a href="timeline.php" class="btn-timeline">📅 Timeline Belajar</a>
    </div>

</div>
</body>
</html>
