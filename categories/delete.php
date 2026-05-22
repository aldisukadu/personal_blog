<?php
// ============================================================
// FILE: categories/delete.php
// FUNGSI: Menghapus satu kategori dari database
// Catatan: karena pakai ON DELETE SET NULL di database,
// artikel yang pakai kategori ini TIDAK ikut terhapus,
// hanya category_id-nya berubah menjadi NULL
// ============================================================

require_once '../config/database.php';

// Hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/categories/index.php");
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: /blogspot/categories/index.php");
    exit;
}

// Hapus kategori
// Karena FOREIGN KEY di tabel posts memakai ON DELETE SET NULL,
// maka posts yang punya category_id ini otomatis jadi NULL
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: /blogspot/categories/index.php");
exit;
?>
