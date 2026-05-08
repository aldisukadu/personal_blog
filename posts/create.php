<?php
// File: posts/create.php
// Form untuk membuat artikel baru

include '../config/database.php';
$title = 'Tulis Artikel Baru';
include '../layout/header.php';

// Ambil semua kategori untuk dropdown
$categories_query = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_query);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>✍️ Tulis Artikel Baru</h2>
    </div>
</div>

<!-- Form Input Artikel -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📝 Form Artikel</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="store.php">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" 
                       placeholder="Masukkan judul artikel..." required>
                <small class="text-muted">Judul harus menarik dan deskriptif</small>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php 
                    while ($row = $categories_result->fetch_assoc()): 
                    ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <small class="text-muted">Pilih kategori yang sesuai untuk artikel Anda</small>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="8"
                          placeholder="Tulis isi artikel Anda di sini..." required></textarea>
                <small class="text-muted">Tulis artikel dengan jelas dan terstruktur</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    ✅ Simpan Artikel
                </button>
                <a href="index.php" class="btn btn-secondary btn-lg">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
