<?php
// ============================================================
// FILE: posts/update.php
// FUNGSI: MEMPROSES data dari form edit.php
// Mirip store.php tapi pakai perintah UPDATE bukan INSERT
// Alur: Terima data → Validasi → Update DB → Redirect
// ============================================================

require_once '../config/database.php';

// Hanya izinkan akses via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/posts/index.php");
    exit;
}

// ---- AMBIL DATA DARI FORM ----
// ID artikel yang mau diupdate (dikirim via input hidden)
$id      = (int) ($_POST['id'] ?? 0);
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

// Validasi dasar
if ($id === 0 || empty($title) || empty($content)) {
    header("Location: /blogspot/posts/index.php");
    exit;
}

// ---- UPDATE DATA DI DATABASE ----
// Perintah UPDATE mengubah baris yang sudah ada
// WHERE id = ? memastikan hanya artikel dengan ID itu yang berubah
$stmt = $conn->prepare(
    "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?"
);

// bind_param — "ssii" = string, string, integer, integer
// Urutan: title(s), content(s), category_id(i), id(i)
$stmt->bind_param("ssii", $title, $content, $category_id, $id);

if ($stmt->execute()) {
    header("Location: /blogspot/posts/index.php?status=updated");
} else {
    // Redirect ke halaman edit dengan ID yang sama jika gagal
    header("Location: /blogspot/posts/edit.php?id=" . $id);
}

$stmt->close();
exit;
?>
