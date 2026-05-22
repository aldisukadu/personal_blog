<?php
// ============================================================
// FILE: posts/edit.php
// FUNGSI: Menampilkan form EDIT artikel yang sudah ada
// Perbedaan dengan create.php: form ini sudah terisi data lama
// Data lama diambil dari database berdasarkan ID di URL
// ============================================================

require_once '../config/database.php';

$pageTitle = "Edit Artikel";

// ---- CEK DAN AMBIL ID DARI URL ----
if (!isset($_GET['id'])) {
    header("Location: /blogspot/posts/index.php");
    exit;
}

$id = (int) $_GET['id'];

// ---- AMBIL DATA ARTIKEL YANG AKAN DIEDIT ----
// Prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

// Jika ID tidak ditemukan di database, tolak
if (!$post) {
    header("Location: /blogspot/posts/index.php");
    exit;
}

// ---- AMBIL SEMUA KATEGORI UNTUK DROPDOWN ----
$cat_result = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");

include '../layout/header.php';
?>

<div class="container">
    
    <div class="mb-3">
        <a href="/blogspot/posts/index.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Dashboard
        </a>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">✏️ Edit Artikel</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Form edit — action mengarah ke update.php -->
                    <form action="/blogspot/posts/update.php" method="POST">
                        
                        <!-- Input HIDDEN untuk ID artikel -->
                        <!-- Harus ada agar update.php tahu artikel mana yang diedit -->
                        <!-- type="hidden" = tidak terlihat, tapi ikut terkirim -->
                        <input type="hidden" name="id" value="<?= $post['id'] ?>">
                        
                        <!-- Input Judul — sudah terisi nilai lama -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <!-- value="..." = isi awal input dari data lama di database -->
                            <!-- htmlspecialchars() wajib di value juga, agar tanda " tidak merusak HTML -->
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-control" 
                                   value="<?= htmlspecialchars($post['title']) ?>"
                                   required>
                        </div>
                        
                        <!-- Pilihan Kategori — opsi lama sudah terpilih -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori
                            </label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                
                                <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
                                    <!-- 
                                        selected = membuat opsi ini terpilih secara default
                                        Kondisi: ID kategori di database SAMA dengan ID opsi ini
                                        Operator == membandingkan dua nilai
                                    -->
                                    <option value="<?= $cat['id'] ?>"
                                        <?= ($post['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- Textarea Isi — sudah terisi konten lama -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Isi Artikel <span class="text-danger">*</span>
                            </label>
                            <!-- Untuk textarea, nilai diletakkan DI ANTARA tag pembuka dan penutup -->
                            <!-- Bukan di atribut value seperti input biasa -->
                            <textarea id="content" 
                                      name="content" 
                                      class="form-control" 
                                      rows="10"
                                      required><?= htmlspecialchars($post['content']) ?></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                💾 Simpan Perubahan
                            </button>
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
