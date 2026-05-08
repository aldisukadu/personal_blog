<?php
// File: posts/index.php
// Menampilkan daftar semua artikel dalam bentuk tabel

include '../config/database.php';
$title = 'Dashboard Artikel';
include '../layout/header.php';

// Query JOIN untuk menampilkan artikel beserta nama kategorinya
$query = "SELECT posts.id, posts.title, posts.created_at, categories.name as category_name
          FROM posts
          LEFT JOIN categories ON posts.category_id = categories.id
          ORDER BY posts.created_at DESC";

$result = $conn->query($query);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2>📋 Dashboard Artikel</h2>
        <p class="text-muted">Kelola semua artikel Anda di sini</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="create.php" class="btn btn-success btn-lg">
            ➕ Tulis Artikel Baru
        </a>
    </div>
</div>

<!-- Daftar Artikel (Tabel) -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📰 Daftar Artikel</h5>
    </div>
    <div class="card-body">
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul Artikel</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
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
                                <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                            </td>
                            <td>
                                <?php if ($row['category_name']): ?>
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($row['category_name']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tanpa Kategori</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-warning btn-sm">
                                    ✏️ Edit
                                </a>
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
                Belum ada artikel. <a href="create.php">Tulis artikel pertama Anda sekarang!</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
