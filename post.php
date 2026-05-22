<?php
// ============================================================
// FILE: post.php (di root folder blogspot/)
// FUNGSI: Halaman detail — menampilkan ISI LENGKAP satu artikel
// URL pemanggilnya: post.php?id=5 (angka 5 = ID artikel)
// ============================================================

require_once 'config/database.php';

// ---- AMBIL ID DARI URL ----
// $_GET adalah array yang berisi data dari URL (setelah tanda ?)
// Contoh URL: post.php?id=3 → $_GET['id'] = "3"
// isset() = cek apakah ?id= ada di URL
if (!isset($_GET['id'])) {
    // Jika tidak ada ID di URL, paksa balik ke halaman depan
    // header("Location: ...") = redirect/alihkan browser ke URL lain
    header("Location: /blogspot/index.php");
    exit; // Hentikan script agar kode di bawah tidak jalan
}

// (int) = casting: paksa nilai menjadi tipe integer (angka bulat)
// Ini mencegah nilai aneh seperti "3; DROP TABLE posts"
$id = (int) $_GET['id'];

// ---- AMBIL DATA ARTIKEL DENGAN PREPARED STATEMENT ----
// Kenapa Prepared Statement? Agar aman dari SQL Injection
// Tanda '?' adalah placeholder — nilai aslinya akan diisi setelah
$stmt = $conn->prepare(
    "SELECT posts.*, categories.name AS category_name 
     FROM posts 
     LEFT JOIN categories ON posts.category_id = categories.id 
     WHERE posts.id = ?"
);

// bind_param() = isi placeholder '?' dengan nilai asli
// "i" artinya Integer (angka bulat) — sesuai tipe data id
$stmt->bind_param("i", $id);

// execute() = jalankan query yang sudah disiapkan
$stmt->execute();

// get_result() = ambil hasil query sebagai objek result
$result = $stmt->get_result();

// fetch_assoc() = ambil satu baris data sebagai array asosiatif
$post = $result->fetch_assoc();

// Jika artikel dengan ID itu tidak ditemukan di database
if (!$post) {
    // Redirect ke halaman depan
    header("Location: /blogspot/index.php");
    exit;
}

// Set judul untuk tab browser
$pageTitle = $post['title'];

include 'layout/header.php';
?>

<div class="container">
    <!-- Tombol kembali ke halaman depan -->
    <div class="mb-3">
        <a href="/blogspot/index.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Beranda
        </a>
    </div>
    
    <!-- Kotak artikel utama -->
    <div class="row justify-content-center">
        <!-- col-lg-8 = lebar 8 kolom di layar besar (max 12 kolom) -->
        <div class="col-lg-8">
            <article>
                
                <!-- Badge kategori -->
                <?php if ($post['category_name']): ?>
                    <span class="badge bg-primary mb-3">
                        <?= htmlspecialchars($post['category_name']) ?>
                    </span>
                <?php endif; ?>
                
                <!-- Judul artikel — h1 untuk SEO dan aksesibilitas -->
                <h1 class="fw-bold mb-3">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                
                <!-- Info tanggal -->
                <p class="text-muted mb-4">
                    <small>
                        📅 Diterbitkan pada <?= date('d F Y, H:i', strtotime($post['created_at'])) ?>
                    </small>
                </p>
                
                <!-- Garis pemisah -->
                <hr>
                
                <!-- Isi artikel -->
                <!-- nl2br() = ubah baris baru (\n) menjadi tag <br> -->
                <!-- Tanpa ini, paragraf tidak akan terpisah di browser -->
                <!-- htmlspecialchars() tetap dipakai untuk keamanan -->
                <div class="article-content" style="line-height: 1.8; font-size: 1.1rem;">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>
                
                <hr class="mt-5">
                
                <!-- Tombol aksi untuk admin -->
                <div class="d-flex gap-2">
                    <a href="/blogspot/posts/edit.php?id=<?= $post['id'] ?>" 
                       class="btn btn-warning btn-sm">
                        ✏️ Edit Artikel
                    </a>
                </div>
                
            </article>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
