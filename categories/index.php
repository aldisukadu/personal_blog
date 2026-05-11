<?php
// File: categories/index.php
// Menampilkan daftar kategori dan form untuk menambah kategori baru

include '../config/database.php';
$title = 'Kelola Kategori';
include '../layout/header.php';

// Proses tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $slug = strtolower(str_replace(' ', '-', $name));
    
    // Gunakan Prepared Statement untuk keamanan (SQL Injection Prevention)
    $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $slug);
    
    if ($stmt->execute()) {
        $success = "Kategori berhasil ditambahkan!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}

// Ambil semua kategori dari database (Prepared Statement)
$query = "SELECT id, name, slug FROM categories ORDER BY id DESC";
$result = $conn->query($query);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>📂 Kelola Kategori</h2>
    </div>
</div>

<!-- Pesan Sukses/Error -->
<?php if (isset($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Tambah Kategori -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">➕ Tambah Kategori Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <small class="text-muted">Contoh: Teknologi, Lifestyle, Tutorial</small>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Kategori</button>
        </form>
    </div>
</div>

<!-- Daftar Kategori (Tabel) -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">📋 Daftar Kategori</h5>
    </div>
    <div class="card-body">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): 
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo $row['slug']; ?></span>
                            </td>
                            <td>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus?')">
                                    🗑️ Hapus
                                </a>
                            </td>
                        </tr>
                        <?php $no++; ?>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">
                Belum ada kategori. Silakan tambahkan kategori terlebih dahulu.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
