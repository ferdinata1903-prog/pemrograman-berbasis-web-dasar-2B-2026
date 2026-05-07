<?php
$riwayatBelajar = [
    [
        'tahun'     => 2025,
        'judul'     => 'Masuk Kuliah',
        'deskripsi' => 'Pertama kali mengenal dunia pemrograman dan logika dasar.',
        'highlight' => false,
    ],
    [
        'tahun'     => 2026,
        'judul'     => 'Belajar HTML & CSS',
        'deskripsi' => 'Membuat halaman web pertama dengan struktur HTML dan styling CSS.',
        'highlight' => false,
    ],
    [
        'tahun'     => 2026,
        'judul'     => 'Mulai Belajar PHP & MySQL',
        'deskripsi' => 'Membuat website dinamis pertama dengan sistem login dan CRUD sederhana.',
        'highlight' => true, // ← milestone: warna & tebal berbeda
    ],
    [
        'tahun'     => 2026,
        'judul'     => 'Proyek Pertama',
        'deskripsi' => 'Membangun sistem informasi perpustakaan mini sebagai tugas akhir semester.',
        'highlight' => false,
    ],
    [
        'tahun'     => 2026,
        'judul'     => 'Belajar Framework Laravel',
        'deskripsi' => 'Mulai menggunakan Laravel dan Vue.js, memahami konsep MVC dan REST API.',
        'highlight' => false,
    ],
    [
        'tahun'     => 2026,
        'judul'     => 'Freelance Pertama',
        'deskripsi' => 'Mendapat project freelance pertama: website profil untuk UMKM lokal.',
        'highlight' => true, // ← milestone
    ],
];

function gayaPenekanan($highlight) {
    if ($highlight) {
        return 'style="font-weight:bold; color:#d97706;"';
    }
    return 'style="color:#6b7280;"';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Timeline Belajar</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 650px; margin: auto; }
        h1 { text-align: center; color: #7c3aed; }
        p.subtitle { text-align: center; color: #888; margin-bottom: 30px; }

     
        .timeline { border-left: 3px solid #c4b5fd; padding-left: 24px; }

    
        .item {
            position: relative;
            margin-bottom: 28px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 14px 16px;
        }

        .item::before {
            content: '';
            position: absolute;
            left: -33px;
            top: 16px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #7c3aed;
            border: 2px solid white;
        }

        .item.milestone::before { background: #d97706; }
        .item.milestone { border-color: #fde68a; }

        .tahun { font-size: 0.82rem; margin-bottom: 4px; }
        .judul { font-size: 1rem; font-weight: bold; margin-bottom: 4px; color: #1f2937; }
        .item.milestone .judul { color: #92400e; }
        .deskripsi { font-size: 0.88rem; color: #6b7280; }
        .badge-milestone { background: #fef3c7; color: #92400e; font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; margin-left: 6px; }

        .nav { display: flex; gap: 12px; justify-content: center; margin-top: 24px; }
        .nav a { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; color: white; }
        .btn-profil { background: #2563eb; }
        .btn-blog   { background: #059669; }
    </style>
</head>
<body>
<div class="container">

    <h1>📅 Timeline Belajar Coding</h1>
    <p class="subtitle">Perjalanan dari nol hingga sekarang</p>

    <div class="timeline">

        <?php foreach ($riwayatBelajar as $item): ?>

            <div class="item <?= $item['highlight'] ? 'milestone' : '' ?>">

                <div class="tahun">
                    <span <?= gayaPenekanan($item['highlight']) ?>>
                        <?= $item['tahun'] ?>
                    </span>
                    <?php if ($item['highlight']): ?>
                        <span class="badge-milestone">⭐ Milestone</span>
                    <?php endif; ?>
                </div>

                <div class="judul"><?= htmlspecialchars($item['judul']) ?></div>
                <div class="deskripsi"><?= htmlspecialchars($item['deskripsi']) ?></div>

            </div>

        <?php endforeach; ?>

    </div>

    <div class="nav">
        <a href="index.php" class="btn-profil">👨‍💻 Kembali ke Profil</a>
        <a href="blog.php"  class="btn-blog">📝 Menuju Blog Developer</a>
    </div>

</div>
</body>
</html>
