<?php
// File: categories/delete.php
// Menghapus kategori dari database

include '../config/database.php';

// Cek apakah ID dikirim melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Gunakan Prepared Statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirect kembali ke halaman kategori dengan pesan sukses
        header("Location: index.php");
        exit;
    } else {
        // Jika ada error
        echo "Error: " . $stmt->error;
    }
}
?>
