<?php
// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "blogspot_db");

if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
