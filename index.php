<?php
require_once __DIR__ . '/includes/functions.php';

$mahasiswa = [];
$errors = [];

$oldNames = $_POST["nama"] ?? [""];
$oldNims  = $_POST["nim"] ?? [""];
$oldTugas = $_POST["tugas"] ?? [""];
$oldUts   = $_POST["uts"] ?? [""];
$oldUas   = $_POST["uas"] ?? [""];
$rowCount = max(1, is_array($oldNames) ? count($oldNames) : 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $names = $_POST["nama"] ?? [];
    $nims  = $_POST["nim"] ?? [];
    $tugas = $_POST["tugas"] ?? [];
    $uts   = $_POST["uts"] ?? [];
    $uas   = $_POST["uas"] ?? [];

    if (!is_array($names) || !is_array($nims) ||
        !is_array($tugas) || !is_array($uts) || !is_array($uas)) {
        $errors[] = "Data yang dikirim tidak valid.";
    } else {
        $count = max(count($names), count($nims), count($tugas), count($uts), count($uas));

        for ($i = 0; $i < $count; $i++) {
            $nama = trim((string)($names[$i] ?? ""));
            $nim  = trim((string)($nims[$i] ?? ""));
            $nilaiTugas = trim((string)($tugas[$i] ?? ""));
            $nilaiUts   = trim((string)($uts[$i] ?? ""));
            $nilaiUas   = trim((string)($uas[$i] ?? ""));

            // Lewati baris yang seluruh kolomnya kosong.
            if ($nama === "" && $nim === "" && $nilaiTugas === "" &&
                $nilaiUts === "" && $nilaiUas === "") {
                continue;
            }

            $barisValid = true;
            if ($nama === "" || $nim === "") {
                $errors[] = "Baris " . ($i + 1) . ": nama dan NIM wajib diisi.";
                $barisValid = false;
            }

            $nilaiKomponen = [
                "Nilai tugas" => $nilaiTugas,
                "Nilai UTS" => $nilaiUts,
                "Nilai UAS" => $nilaiUas
            ];

            foreach ($nilaiKomponen as $label => $nilai) {
                if ($nilai === "" || !is_numeric($nilai) || (float)$nilai < 0 || (float)$nilai > 100) {
                    $errors[] = "Baris " . ($i + 1) . ": " . $label . " harus berupa angka dari 0 sampai 100.";
                    $barisValid = false;
                }
            }

            if ($barisValid) {
                $nilaiAkhir = hitungNilaiAkhir((float)$nilaiTugas, (float)$nilaiUts, (float)$nilaiUas);
                $mahasiswa[] = [
                    "nama" => $nama,
                    "nim" => $nim,
                    "tugas" => (float)$nilaiTugas,
                    "uts" => (float)$nilaiUts,
                    "uas" => (float)$nilaiUas,
                    "akhir" => $nilaiAkhir,
                    "grade" => tentukanGrade($nilaiAkhir),
                    "status" => tentukanStatus($nilaiAkhir),
                    "predikat" => tentukanPredikat($nilaiAkhir)
                ];
            }
        }

        if ($count === 0 || (empty($mahasiswa) && empty($errors))) {
            $errors[] = "Masukkan setidaknya satu data mahasiswa.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penghitung Nilai Mahasiswa</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/app.js" defer></script>
</head>
<body>
<header class="hero">
    <div class="hero-inner">
        <h1>Penghitung Nilai Mahasiswa</h1>
        <p>Hitung Nilai Akhir, Grade, Predikat, dan Status Kelulusan secara otomatis.</p>
    </div>
</header>

<main>
    <section class="card">
        <h2>Input Data Mahasiswa</h2>
        <p class="hint">Isi nama, NIM, nilai tugas, UTS, dan UAS. Nilai komponen harus berada pada rentang 0–100. Klik tombol “Tambah Mahasiswa” untuk memasukkan lebih dari satu data.</p>

        <?php if (!empty($errors)): ?>
            <div class="notice error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="table-wrap">
                <table id="inputTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Nilai Tugas</th>
                            <th>Nilai UTS</th>
                            <th>Nilai UAS</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="studentRows">
                    <?php for ($i = 0; $i < $rowCount; $i++): ?>
                        <tr>
                            <td class="row-number"><?= $i + 1 ?></td>
                            <td><input name="nama[]" aria-label="Nama mahasiswa <?= $i + 1 ?>" value="<?= e((string)($oldNames[$i] ?? "")) ?>" placeholder="Nama" required></td>
                            <td><input name="nim[]" aria-label="NIM mahasiswa <?= $i + 1 ?>" value="<?= e((string)($oldNims[$i] ?? "")) ?>" placeholder="NIM" required></td>
                            <td><input name="tugas[]" aria-label="Nilai tugas <?= $i + 1 ?>" type="number" min="0" max="100" step="any" value="<?= e((string)($oldTugas[$i] ?? "")) ?>" placeholder="0–100" required></td>
                            <td><input name="uts[]" aria-label="Nilai UTS <?= $i + 1 ?>" type="number" min="0" max="100" step="any" value="<?= e((string)($oldUts[$i] ?? "")) ?>" placeholder="0–100" required></td>
                            <td><input name="uas[]" aria-label="Nilai UAS <?= $i + 1 ?>" type="number" min="0" max="100" step="any" value="<?= e((string)($oldUas[$i] ?? "")) ?>" placeholder="0–100" required></td>
                            <td><button type="button" class="remove" onclick="hapusBaris(this)" aria-label="Hapus baris">Hapus</button></td>
                        </tr>
                    <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            <div class="actions">
                <button type="button" class="secondary" onclick="tambahBaris()">+ Tambah Mahasiswa</button>
                <button type="submit" class="primary">Hitung Nilai</button>
                <button type="reset" class="secondary" onclick="setTimeout(resetBaris, 0)">Reset Form</button>
            </div>
        </form>
    </section>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($errors) && !empty($mahasiswa)): ?>
    <section class="card">
        <h2>Hasil Perhitungan</h2>
        <div class="notice success">Perhitungan berhasil untuk <?= count($mahasiswa) ?> mahasiswa.</div>
        <div class="table-wrap">
            <table class="result-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Tugas (30%)</th>
                        <th>UTS (30%)</th>
                        <th>UAS (40%)</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Predikat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($mahasiswa as $index => $m): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($m["nama"]) ?></td>
                        <td><?= e($m["nim"]) ?></td>
                        <td><?= formatNilai($m["tugas"]) ?></td>
                        <td><?= formatNilai($m["uts"]) ?></td>
                        <td><?= formatNilai($m["uas"]) ?></td>
                        <td>
                            <strong><?= formatNilai($m["akhir"]) ?></strong>
                        </td>
                        <td class="grade"><?= e($m["grade"]) ?></td>
                        <td><?= e($m["predikat"]) ?></td>
                        <td>
                            <span class="status <?= $m["status"] === "Lulus" ? "lulus" : "tidak" ?>"><?= e($m["status"]) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="formula">
            <strong>Rumus:</strong> (Nilai Tugas × 0,30) + (Nilai UTS × 0,30) + (Nilai UAS × 0,40).<br>
            <strong>Kelulusan:</strong> nilai akhir ≥ 65 = Lulus; nilai akhir &lt; 65 = Tidak Lulus.
        </div>
    </section>
    <?php endif; ?>

    <section class="card">
        <h2>Ketentuan Penilaian</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Status berdasarkan nilai akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>85–100</td>
                        <td>A</td>
                        <td>Lulus</td>
                    </tr>
                    <tr>
                        <td>75–84.99</td>
                        <td>B</td>
                        <td>Lulus</td>
                    </tr>
                    <tr>
                        <td>65–74.99</td>
                        <td>C</td>
                        <td>Lulus</td>
                    </tr>
                    <tr>
                        <td>50–64.99</td>
                        <td>D</td>
                        <td>Tidak Lulus</td>
                    </tr>
                    <tr>
                        <td>&lt;50</td>
                        <td>E</td>
                        <td>Tidak Lulus</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>
</body>
</html>
