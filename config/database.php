<?php
// Konfigurasi Database
// File ini adalah pusat koneksi ke database

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'blog_db';

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set charset menjadi UTF-8 agar mendukung karakter Indonesia
$conn->set_charset("utf8mb4");
?>
