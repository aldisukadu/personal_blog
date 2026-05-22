<?php
// ============================================================
// FILE: posts/create.php
// FUNGSI: Menampilkan FORM untuk membuat artikel baru
// File ini hanya MENAMPILKAN form — tidak memproses data
// Proses simpan data dilakukan oleh store.php
// ============================================================

require_once '../config/database.php';

$pageTitle = "Tulis Artikel Baru";

// ---- AMBIL DAFTAR KATEGORI ----
// Untuk mengisi pilihan dropdown <select> di form
$cat_result = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");

include '../layout/header.php';
?>

<div class="container">
    
    <!-- Tombol kembali ke dashboard -->
    <div class="mb-3">
        <a href="/blogspot/posts/index.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Dashboard
        </a>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">✏️ Tulis Artikel Baru</h4>
                </div>
                <div class="card-body">
                    
                    <!-- FORM INPUT ARTIKEL -->
                    <!-- action="store.php" = saat tombol submit ditekan, kirim data ke store.php -->
                    <!-- method="POST" = data dikirim secara tersembunyi (bukan di URL) -->
                    <form action="/blogspot/posts/store.php" method="POST">
                        
                        <!-- Input Judul -->
                        <div class="mb-3">
                            <!-- for="title" harus cocok dengan id="title" di bawah -->
                            <label for="title" class="form-label fw-semibold">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <!-- form-control = class Bootstrap untuk kotak input yang rapi -->
                            <!-- required = browser tidak izinkan submit jika kosong -->
                            <!-- placeholder = teks abu-abu petunjuk isi -->
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-control" 
                                   placeholder="Masukkan judul artikel..."
                                   required>
                        </div>
                        
                        <!-- Pilihan Kategori -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori
                            </label>
                            <!-- form-select = class Bootstrap untuk dropdown yang rapi -->
                            <select id="category_id" name="category_id" class="form-select">
                                <!-- Pilihan pertama: tanpa kategori -->
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                
                                <?php
                                // Loop untuk menampilkan semua kategori sebagai opsi
                                while ($cat = mysqli_fetch_assoc($cat_result)):
                                ?>
                                    <option value="<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                                
                            </select>
                        </div>
                        
                        <!-- Textarea Isi Artikel -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Isi Artikel <span class="text-danger">*</span>
                            </label>
                            <!-- rows="10" = tinggi textarea (10 baris terlihat) -->
                            <textarea id="content" 
                                      name="content" 
                                      class="form-control" 
                                      rows="10" 
                                      placeholder="Tulis isi artikel di sini..."
                                      required></textarea>
                            <!-- Catatan kecil di bawah textarea -->
                            <div class="form-text">Tekan Enter untuk baris baru.</div>
                        </div>
                        
                        <!-- Tombol-tombol aksi -->
                        <!-- d-flex gap-2 = susun tombol bersebelahan -->
                        <div class="d-flex gap-2">
                            <!-- type="submit" = tombol untuk mengirimkan form -->
                            <button type="submit" class="btn btn-success">
                                💾 Simpan Artikel
                            </button>
                            <!-- Tombol batal = kembali ke dashboard tanpa simpan -->
                            <a href="/blogspot/posts/index.php" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                        
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
