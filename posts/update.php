<?php
// File: posts/update.php
// Memproses dan menyimpan perubahan artikel ke database

include '../config/database.php';

// Cek apakah form dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    
    // Gunakan Prepared Statement untuk keamanan
    $stmt = $conn->prepare("UPDATE posts SET category_id = ?, title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("issi", $category_id, $title, $content, $id);
    
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke dashboard
        header("Location: index.php");
        exit;
    } else {
        // Jika gagal
        echo "Error: " . $stmt->error;
    }
} else {
    // Jika akses langsung tanpa form, redirect ke index
    header("Location: index.php");
    exit;
}
?>
