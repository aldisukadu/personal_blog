<?php
// File: posts/delete.php
// Menghapus artikel dari database

include '../config/database.php';

// Cek apakah ID dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Gunakan Prepared Statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirect kembali ke halaman dashboard
        header("Location: index.php");
        exit;
    } else {
        // Jika ada error
        echo "Error: " . $stmt->error;
    }
} else {
    // Jika tidak ada ID, redirect
    header("Location: index.php");
    exit;
}
?>
