<?php
// File: posts/store.php
// Memproses dan menyimpan data artikel ke database

include '../config/database.php';

// Cek apakah form dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $content = $_POST['content'];
    
    // Gunakan Prepared Statement untuk keamanan (SQL Injection Prevention)
    $stmt = $conn->prepare("INSERT INTO posts (category_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $category_id, $title, $content);
    
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke dashboard dengan pesan sukses
        header("Location: index.php?message=Artikel berhasil disimpan!");
        exit;
    } else {
        // Jika gagal
        echo "Error: " . $stmt->error;
    }
} else {
    // Jika akses langsung tanpa form, redirect ke create.php
    header("Location: create.php");
    exit;
}
?>
