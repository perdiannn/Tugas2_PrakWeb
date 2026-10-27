PENGHITUNG NILAI MAHASISWA
==========================

Struktur:
- index.php                 Halaman utama dan proses form
- includes/functions.php    Fungsi perhitungan dan helper output
- assets/css/style.css      Semua aturan CSS
- assets/js/app.js          Interaksi tambah/hapus/reset baris

Cara menjalankan di Laragon:
1. Ekstrak folder penghitung_nilai_mahasiswa ke C:\laragon\www\
2. Pastikan Apache dan PHP berjalan di Laragon.
3. Buka http://localhost/penghitung_nilai_mahasiswa/

Program ini menghitung nilai akhir berdasarkan bobot:
- Tugas 30%
- UTS 30%
- UAS 40%

Grade:
A >= 85
B >= 75
C >= 65
D >= 50
E < 50

Status lulus jika nilai akhir >= 65.

Catatan:
Program ini belum menggunakan database; data dihitung saat form dikirim dan
hasilnya tidak disimpan secara permanen.
