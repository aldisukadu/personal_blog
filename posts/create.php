<?php
// Form buat artikel baru
require_once '../config/database.php';

$pageTitle = "Tulis Artikel Baru";

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
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">✏️ Tulis Artikel Baru</h4>
                </div>
                <div class="card-body">
                    
                    <form action="/blogspot/posts/store.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Judul Artikel <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-control" 
                                   placeholder="Masukkan judul artikel..."
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori
                            </label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                
                                <?php
                                while ($cat = mysqli_fetch_assoc($cat_result)):
                                ?>
                                    <option value="<?= $cat['id'] ?>">
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                                
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Isi Artikel <span class="text-danger">*</span>
                            </label>
                            <textarea id="content" 
                                      name="content" 
                                      class="form-control" 
                                      rows="10" 
                                      placeholder="Tulis isi artikel di sini..."
                                      required></textarea>
                            <div class="form-text">Tekan Enter untuk baris baru.</div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                💾 Simpan Artikel
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
