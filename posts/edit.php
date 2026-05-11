<?php
// File: posts/edit.php
// Form untuk mengedit artikel yang sudah ada

include '../config/database.php';
$title = 'Edit Artikel';
include '../layout/header.php';

// Cek apakah ID artikel dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data artikel berdasarkan ID (Prepared Statement)
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "<div class='alert alert-danger'>Artikel tidak ditemukan!</div>";
    include '../layout/footer.php';
    exit;
}

// Ambil semua kategori untuk dropdown (Prepared Statement)
$categories_query = "SELECT id, name FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_query);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>✏️ Edit Artikel</h2>
    </div>
</div>

<!-- Form Edit Artikel -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📝 Form Edit Artikel</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php 
                    while ($row = $categories_result->fetch_assoc()): 
                        $selected = ($row['id'] == $post['category_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($row['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Isi Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="8" required>
<?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    ✅ Simpan Perubahan
                </button>
                <a href="index.php" class="btn btn-secondary btn-lg">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
