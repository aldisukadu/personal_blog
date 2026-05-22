<?php
// ============================================================
// FILE: posts/store.php
// FUNGSI: MEMPROSES data dari form create.php
// File ini tidak punya tampilan — hanya proses lalu redirect
// Alur: Terima data → Validasi → Simpan ke DB → Redirect
// ============================================================

require_once '../config/database.php';

// ---- CEK METODE REQUEST ----
// $_SERVER['REQUEST_METHOD'] = cek cara file ini diakses
// Harus POST (dari form), bukan GET (akses langsung via URL)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Jika diakses langsung (bukan dari form), tolak dan redirect
    header("Location: /blogspot/posts/create.php");
    exit;
}

// ---- AMBIL DAN BERSIHKAN DATA DARI FORM ----
// $_POST['title'] = ambil nilai field "title" yang dikirim form
// trim() = hapus spasi di awal dan akhir string
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');

// Untuk category_id: jika kosong (tidak dipilih), simpan sebagai NULL
// Operator ternary: kondisi ? nilai_jika_benar : nilai_jika_salah
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
// (int) = paksa menjadi integer untuk keamanan tambahan

// ---- VALIDASI SERVER-SIDE ----
// Jangan percaya validasi browser saja — cek ulang di server
if (empty($title) || empty($content)) {
    // Jika judul atau isi kosong, kembali ke form
    // Idealnya kirim pesan error, tapi untuk belajar kita redirect saja
    header("Location: /blogspot/posts/create.php");
    exit;
}

// ---- SIMPAN KE DATABASE DENGAN PREPARED STATEMENT ----
// Tanda '?' adalah placeholder yang akan diisi nanti
// Cara ini AMAN dari SQL Injection
$stmt = $conn->prepare(
    "INSERT INTO posts (title, content, category_id) VALUES (?, ?, ?)"
);

// bind_param() = isi setiap '?' dengan nilai nyata
// "ssi" artinya: s=string, s=string, i=integer
// Urutan harus SAMA dengan urutan '?' di query
$stmt->bind_param("ssi", $title, $content, $category_id);

// execute() = jalankan query insert
if ($stmt->execute()) {
    // Jika berhasil, arahkan ke dashboard dengan pesan sukses di URL
    header("Location: /blogspot/posts/index.php?status=created");
} else {
    // Jika gagal, kembali ke form (untuk produksi nyata, log error-nya)
    header("Location: /blogspot/posts/create.php");
}

// Tutup statement untuk bebaskan memori
$stmt->close();
exit;
?>
