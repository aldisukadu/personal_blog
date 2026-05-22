<!DOCTYPE html>
<!-- ============================================================
     FILE: layout/header.php
     FUNGSI: Kerangka bagian ATAS halaman (dipasang berulang)
     Berisi: tag HTML awal, Bootstrap CSS, dan Navigasi
     Cara pakai: include '../layout/header.php'; di baris paling atas
     ============================================================ -->

<!-- Deklarasi tipe dokumen HTML5 -->
<html lang="id">

<head>
    <!-- Karakter encoding agar huruf Indonesia (á, é, dll) tampil benar -->
    <meta charset="UTF-8">
    
    <!-- Tag ini wajib agar tampilan Bootstrap responsif di HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Judul tab browser — diisi dari variabel $pageTitle jika ada -->
    <!-- isset() = cek apakah variabel sudah didefinisikan sebelumnya -->
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Solo Blog</title>
    
    <!-- Bootstrap 5 CSS dari CDN (tidak perlu download) -->
    <!-- CDN = server luar yang menyimpan file CSS/JS publik -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS tambahan buatan kita sendiri (inline di sini agar simple) -->
    <style>
        /* Warna navbar kustom — lebih gelap dari default Bootstrap */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }
        /* Efek hover pada card artikel di halaman depan */
        .card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
            transition: all 0.2s;
        }
        /* Warna badge kategori */
        .badge-kategori {
            font-size: 0.75rem;
        }
        /* Footer selalu di bawah */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
    </style>
</head>

<body>
    <!-- NAVBAR (Bilah navigasi atas) -->
    <!-- navbar-dark bg-dark = teks putih, background hitam -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- container = pembungkus agar konten tidak mepet ke tepi layar -->
        <div class="container">
            <!-- Brand/Logo blog — mengarah ke halaman utama -->
            <!-- Tanda '../' berarti naik satu folder ke atas -->
            <a class="navbar-brand" href="/blogspot/index.php">📝 Solo Blog</a>
            
            <!-- Tombol hamburger untuk tampilan mobile -->
            <button class="navbar-toggler" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Menu navigasi yang bisa collapse di mobile -->
            <div class="collapse navbar-collapse" id="navbarMenu">
                <!-- ms-auto = margin kiri otomatis (dorong ke kanan) -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/blogspot/index.php">🏠 Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blogspot/posts/index.php">📋 Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blogspot/categories/index.php">🗂️ Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2 px-3" 
                           href="/blogspot/posts/create.php">+ Tulis Artikel</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Tag <main> membungkus semua konten halaman -->
    <!-- py-4 = padding atas-bawah 4 unit Bootstrap -->
    <main class="py-4">
