<?php
function prosesFramework($input) { // Membuat fungsi dgn nama prosesFramework. Fungsi ini menerima satu data masukan input(teks ygg diketik user).
    $array = explode(",", $input); 
    return array_map('trim', $array); 
}

function validasiForm($data) {
    $errors = [];
    if (empty($data['nama']))       $errors[] = "Nama wajib diisi.";
    if (empty($data['id_dev']))     $errors[] = "ID Developer wajib diisi.";
    if (empty($data['kota']))       $errors[] = "Kota/Tgl Lahir wajib diisi.";
    if (empty($data['email']))      $errors[] = "Email wajib diisi.";
    if (empty($data['wa']))         $errors[] = "No. WhatsApp wajib diisi.";
    if (empty($data['framework']))  $errors[] = "Framework wajib diisi.";
    if (empty($data['pengalaman'])) $errors[] = "Pengalaman wajib diisi.";
    if (empty($data['minat']))      $errors[] = "Minat Bidang wajib dipilih.";
    if (empty($data['skill']))      $errors[] = "Tingkat Skill wajib dipilih.";
    return $errors;
}

$submitted = false;
$errors    = [];
$result    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = [
        'nama'       => trim($_POST['nama'] ?? ''),
        'id_dev'     => trim($_POST['id_dev'] ?? ''),
        'kota'       => trim($_POST['kota'] ?? ''),
        'email'      => trim($_POST['email'] ?? ''),
        'wa'         => trim($_POST['wa'] ?? ''),
        'framework'  => trim($_POST['framework'] ?? ''),
        'pengalaman' => trim($_POST['pengalaman'] ?? ''),
        'tools'      => $_POST['tools'] ?? [],
        'minat'      => $_POST['minat'] ?? '',
        'skill'      => $_POST['skill'] ?? '',
    ];

    $errors = validasiForm($input);

    if (empty($errors)) {
        $submitted       = true;
        $daftarFramework = prosesFramework($input['framework']);

        $pesanFramework = '';
        if (count($daftarFramework) > 2) {
            $pesanFramework = "Skill Anda cukup luas di bidang development!";
        }

        $result = array_merge($input, [
            'framework'      => $daftarFramework,
            'pesanFramework' => $pesanFramework,
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Developer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 700px; margin: auto; }
        h1 { text-align: center; color: #2563eb; }
        h2 { color: #1e40af; margin-bottom: 10px; }
        .card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #e0e7ff; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 0.9rem; }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%; padding: 8px; margin-top: 4px;
            border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem;
        }
        textarea { height: 80px; resize: vertical; }
        .checkbox-row { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 6px; }
        .radio-row    { display: flex; gap: 20px; margin-top: 6px; }
        .hint { font-size: 0.78rem; color: #888; margin-top: 2px; }
        button { margin-top: 16px; width: 100%; padding: 10px; background: #2563eb; color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .error { background: #fee2e2; border: 1px solid #f87171; border-radius: 6px; padding: 12px; color: #b91c1c; margin-bottom: 14px; }
        .error ul { padding-left: 16px; }
        .sukses { background: #dcfce7; border: 1px solid #4ade80; border-radius: 6px; padding: 10px 14px; color: #15803d; margin-top: 12px; }
        .badge { display: inline-block; background: #dbeafe; color: #1d4ed8; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; margin: 2px; }
        .nav { display: flex; gap: 12px; justify-content: center; margin-top: 20px; }
        .nav a { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; color: white; }
        .btn-timeline { background: #7c3aed; }
        .btn-blog     { background: #059669; }
    </style>
</head>
<body>
<div class="container">

    <h1>Profil Developer</h1>
    <div class="card">
        <h2>Profil Interaktif Developer Pemula</h2>
        <table>
            <tr><th>Nama</th>           <td>M.Tri Putra Ferdinata</td></tr>
            <tr><th>ID Developer</th>   <td>DEV-2025-104</td></tr>
            <tr><th>Kota/Tgl Lahir</th> <td>Banjarmasin, 19 Maret 2006</td></tr>
            <tr><th>Email</th>          <td>ferdi@email.com</td></tr>
            <tr><th>No. WhatsApp</th>   <td>+62 812-3456-7890</td></tr>
        </table>
    </div>


    <div class="card">
        <h2>Form Isian Dinamis</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Mohon perbaiki kesalahan berikut:</strong>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">

            <label>ID Developer</label>
            <input type="text" name="id_dev" value="<?= htmlspecialchars($_POST['id_dev'] ?? '') ?>">

            <label>Kota / Tanggal Lahir</label>
            <input type="text" name="kota" value="<?= htmlspecialchars($_POST['kota'] ?? '') ?>">

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label>No. WhatsApp</label>
            <input type="text" name="wa" value="<?= htmlspecialchars($_POST['wa'] ?? '') ?>">

            <label>Framework / Tools yang Dikuasai</label>
            <input type="text" name="framework" value="<?= htmlspecialchars($_POST['framework'] ?? '') ?>" placeholder="Contoh: Laravel, Vue, Bootstrap">
            <p class="hint">Pisahkan dengan koma. Jika lebih dari 2, akan muncul pesan khusus!</p>

            <label>Cerita Singkat Pengalaman</label>
            <textarea name="pengalaman"><?= htmlspecialchars($_POST['pengalaman'] ?? '') ?></textarea>

            <label>Tools Penunjang</label>
            <div class="checkbox-row">
                <?php foreach (['VS Code','GitHub','Figma','Postman','Docker'] as $tool): ?>
                    <label style="font-weight:normal;">
                        <input type="checkbox" name="tools[]" value="<?= $tool ?>"
                            <?= in_array($tool, $_POST['tools'] ?? []) ? 'checked' : '' ?>>
                        <?= $tool ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Minat Bidang</label>
            <div class="radio-row">
                <?php foreach (['Frontend','Backend','Fullstack'] as $minat): ?>
                    <label style="font-weight:normal;">
                        <input type="radio" name="minat" value="<?= $minat ?>"
                            <?= (($_POST['minat'] ?? '') === $minat) ? 'checked' : '' ?>>
                        <?= $minat ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Tingkat Skill Coding</label>
            <select name="skill">
                <option value="">-- Pilih --</option>
                <?php foreach (['Dasar','Cukup','Profesional'] as $sk): ?>
                    <option value="<?= $sk ?>" <?= (($_POST['skill'] ?? '') === $sk) ? 'selected' : '' ?>>
                        <?= $sk ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Submit &amp; Tampilkan Hasil</button>
        </form>
    </div>

    <?php if ($submitted): ?>
    <div class="card">
        <h2>Hasil Input</h2>

        <table>
            <tr><th>Nama</th>           <td><?= htmlspecialchars($result['nama']) ?></td></tr>
            <tr><th>ID Developer</th>   <td><?= htmlspecialchars($result['id_dev']) ?></td></tr>
            <tr><th>Kota/Tgl Lahir</th> <td><?= htmlspecialchars($result['kota']) ?></td></tr>
            <tr><th>Email</th>          <td><?= htmlspecialchars($result['email']) ?></td></tr>
            <tr><th>No. WhatsApp</th>   <td><?= htmlspecialchars($result['wa']) ?></td></tr>
            <tr>
                <th>Framework</th>
                <td>
                    <?php foreach ($result['framework'] as $fw): ?>
                        <span class="badge"><?= htmlspecialchars($fw) ?></span>
                    <?php endforeach; ?>
                    <small>(<?= count($result['framework']) ?> item)</small>
                </td>
            </tr>
            <tr>
                <th>Tools Penunjang</th>
                <td><?= !empty($result['tools']) ? implode(', ', $result['tools']) : '-' ?></td>
            </tr>
            <tr><th>Minat</th> <td><?= htmlspecialchars($result['minat']) ?></td></tr>
            <tr><th>Skill</th> <td><?= htmlspecialchars($result['skill']) ?></td></tr>
        </table>

        <?php if (!empty($result['pesanFramework'])): ?>
            <div class="sukses">⭐ <?= htmlspecialchars($result['pesanFramework']) ?></div>
        <?php endif; ?>

        <h2 style="margin-top:16px; font-size:1rem;">Pengalaman:</h2>
        <p style="line-height:1.7; color:#444; margin-top:6px;">
            <?= nl2br(htmlspecialchars($result['pengalaman'])) ?>
        </p>
    </div>
    <?php endif; ?>

    <div class="nav">
        <a href="timeline.php" class="btn-timeline">📅 Timeline Belajar</a>
        <a href="blog.php"     class="btn-blog">📝 Blog Developer</a>
    </div>

</div>
</body>
</html>
