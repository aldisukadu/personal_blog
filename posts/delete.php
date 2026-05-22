<?php
// ============================================================
// FILE: posts/delete.php
// FUNGSI: MENGHAPUS satu artikel dari database
// Dipanggil dari tombol hapus di posts/index.php
// Menggunakan POST (bukan GET) agar tidak bisa dihapus via URL
// ============================================================

require_once '../config/database.php';

// Wajib POST — tolak jika diakses langsung via URL (method GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/posts/index.php");
    exit;
}

// Ambil ID yang dikirim dari input hidden di form
$id = (int) ($_POST['id'] ?? 0);

// Jika ID tidak valid (0 atau negatif), tolak
if ($id <= 0) {
    header("Location: /blogspot/posts/index.php");
    exit;
}

// ---- HAPUS ARTIKEL DARI DATABASE ----
// DELETE FROM = perintah SQL untuk menghapus baris
// WHERE id = ? = pastikan hanya baris dengan ID itu yang dihapus
// TANPA WHERE, SEMUA ARTIKEL AKAN TERHAPUS!
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->close();

// Setelah hapus, kembali ke dashboard
header("Location: /blogspot/posts/index.php?status=deleted");
exit;
?>
