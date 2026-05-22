<?php
// ============================================================
// FILE: config/database.php
// FUNGSI: Pusat koneksi ke database MySQL
// File ini dipanggil di SEMUA halaman yang butuh data
// ============================================================

// mysqli_connect() adalah fungsi bawaan PHP untuk konek ke MySQL
// Parameter: (host, username, password, nama_database)
$conn = mysqli_connect("localhost", "root", "", "blogspot_db");

// Cek apakah koneksi berhasil atau tidak
// mysqli_connect_errno() mengembalikan angka error, jika 0 = sukses
if (mysqli_connect_errno()) {
    // Jika gagal, tampilkan pesan error dan hentikan semua proses
    // die() = hentikan script sekarang juga
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// mysqli_set_charset() memastikan karakter (huruf) tersimpan dengan benar
// "utf8mb4" mendukung semua karakter termasuk emoji
mysqli_set_charset($conn, "utf8mb4");
?>
