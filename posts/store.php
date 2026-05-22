<?php
// Proses simpan artikel baru
require_once '../config/database.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/posts/create.php");
    exit;
}

// Ambil dan bersihkan data
$title      = trim($_POST['title'] ?? '');
$content    = trim($_POST['content'] ?? '');
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;

// Validasi
if (empty($title) || empty($content)) {
    header("Location: /blogspot/posts/create.php");
    exit;
}

// Simpan ke database
$stmt = $conn->prepare(
    "INSERT INTO posts (title, content, category_id) VALUES (?, ?, ?)"
);

$stmt->bind_param("ssi", $title, $content, $category_id);

if ($stmt->execute()) {
    header("Location: /blogspot/posts/index.php?status=created");
} else {
    header("Location: /blogspot/posts/create.php");
}

$stmt->close();
exit;
?>
