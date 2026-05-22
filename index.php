<?php
// ============================================================
// FILE: index.php (di root folder blogspot/)
// FUNGSI: Halaman DEPAN blog — tampilan untuk pembaca
// Menampilkan semua artikel dalam bentuk Card
// ============================================================

// Muat file koneksi database
// Jalur ini relatif dari posisi file ini (root blogspot/)
require_once 'config/database.php';

// Variabel untuk judul tab browser
// Variabel ini dibaca oleh layout/header.php
$pageTitle = "Beranda";

// ---- AMBIL DATA SEMUA ARTIKEL ----
// Kita pakai JOIN untuk menggabungkan tabel posts dan categories
// Tujuannya: agar nama kategori ikut tampil di setiap kartu artikel
// LEFT JOIN = ambil semua posts, meski category_id-nya NULL sekalipun
$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, 
               categories.name AS category_name 
        FROM posts 
        LEFT JOIN categories ON posts.category_id = categories.id 
        ORDER BY posts.created_at DESC";
// ORDER BY created_at DESC = artikel terbaru tampil paling atas

// mysqli_query() = jalankan perintah SQL ke database
$result = mysqli_query($conn, $sql);

// Pasang header (bagian atas halaman)
include 'layout/header.php';
?>

<div class="container">
    
    <!-- Judul halaman -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Artikel Terbaru</h2>
            <!-- Garis bawah dekoratif -->
            <hr>
        </div>
    </div>
    
    <!-- Baris untuk menampung kartu-kartu artikel -->
    <!-- row-cols-md-3 = 3 kolom di layar medium ke atas -->
    <!-- g-4 = jarak antar kartu (gap) -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        
        <?php
        // mysqli_num_rows() = hitung berapa baris data yang dikembalikan
        if (mysqli_num_rows($result) > 0):
            // Selama masih ada baris data, terus loop
            // mysqli_fetch_assoc() = ambil satu baris data sebagai array asosiatif
            // Contoh: $post['title'] = judul artikel
            while ($post = mysqli_fetch_assoc($result)):
        ?>
        
        <!-- Satu kartu untuk satu artikel -->
        <div class="col">
            <!-- h-100 = tinggi kartu menyesuaikan baris -->
            <div class="card h-100">
                <div class="card-body">
                    
                    <!-- Tampilkan badge kategori jika ada -->
                    <?php if ($post['category_name']): ?>
                        <!-- bg-primary = badge biru Bootstrap -->
                        <span class="badge bg-primary badge-kategori mb-2">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                    <?php else: ?>
                        <span class="badge bg-secondary badge-kategori mb-2">Umum</span>
                    <?php endif; ?>
                    
                    <!-- Judul artikel -->
                    <!-- htmlspecialchars() = cegah XSS, ubah < > & menjadi aman -->
                    <h5 class="card-title">
                        <?= htmlspecialchars($post['title']) ?>
                    </h5>
                    
                    <!-- Cuplikan isi artikel (100 karakter pertama) -->
                    <!-- strip_tags() = hapus semua tag HTML dari konten -->
                    <!-- substr() = ambil sebagian teks, mulai dari 0, sepanjang 100 karakter -->
                    <p class="card-text text-muted">
                        <?= htmlspecialchars(substr(strip_tags($post['content']), 0, 100)) ?>...
                    </p>
                </div>
                
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <!-- Tanggal artikel -->
                    <!-- date() = format ulang tanggal -->
                    <!-- strtotime() = ubah string tanggal jadi format UNIX agar bisa diformat ulang -->
                    <small class="text-muted">
                        <?= date('d M Y', strtotime($post['created_at'])) ?>
                    </small>
                    
                    <!-- Tombol baca selengkapnya -->
                    <!-- Kirim ID artikel sebagai parameter di URL -->
                    <a href="/blogspot/post.php?id=<?= $post['id'] ?>" 
                       class="btn btn-sm btn-outline-primary">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        </div>
        
        <?php
            endwhile; // Akhir loop while
        else: // Jika tidak ada artikel sama sekali
        ?>
        
        <!-- Pesan jika belum ada artikel -->
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>Belum ada artikel.</h5>
                <a href="/blogspot/posts/create.php" class="btn btn-primary mt-2">
                    Tulis Artikel Pertama
                </a>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div><!-- akhir .row -->
</div><!-- akhir .container -->

<?php
// Pasang footer (bagian bawah halaman)
include 'layout/footer.php';
?>
