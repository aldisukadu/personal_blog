<?php
// File: index.php
// Halaman utama blog - menampilkan daftar artikel untuk pembaca

include 'config/database.php';
$title = 'Beranda';
include 'layout/header.php';

// Query untuk menampilkan semua artikel dengan kategorinya (JOIN)
$query = "SELECT posts.id, posts.title, posts.content, posts.created_at, categories.name as category_name
          FROM posts
          LEFT JOIN categories ON posts.category_id = categories.id
          ORDER BY posts.created_at DESC";

$result = $conn->query($query);
?>

<div class="row mb-5">
    <div class="col-lg-10 mx-auto">
        <h1 class="display-4">📚 Blog Pribadi Saya</h1>
        <p class="lead text-muted">Berbagi pengetahuan tentang teknologi, lifestyle, dan tutorial</p>
        <hr class="my-4">
    </div>
</div>

<!-- Daftar Artikel dalam bentuk Card -->
<div class="row">
    <div class="col-lg-10 mx-auto">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h3 class="card-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>
                            <?php if ($row['category_name']): ?>
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="card-text">
                            <?php 
                            // Tampilkan 150 karakter pertama dari konten
                            $preview = substr($row['content'], 0, 150);
                            if (strlen($row['content']) > 150) {
                                $preview .= '...';
                            }
                            echo htmlspecialchars($preview);
                            ?>
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                📅 <?php echo date('d F Y', strtotime($row['created_at'])); ?>
                            </small>
                            <a href="post.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Belum ada artikel</h4>
                <p>Saat ini belum ada artikel yang dipublikasikan. Silakan kunjungi kembali nanti!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
