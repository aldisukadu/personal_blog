<?php
// File: post.php
// Halaman detail artikel - menampilkan satu artikel lengkap

include 'config/database.php';

// Cek apakah ID artikel dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Ambil data artikel berdasarkan ID (Prepared Statement)
$stmt = $conn->prepare("SELECT posts.id, posts.title, posts.content, posts.created_at, categories.name as category_name
                        FROM posts
                        LEFT JOIN categories ON posts.category_id = categories.id
                        WHERE posts.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "<div class='alert alert-danger'>Artikel tidak ditemukan!</div>";
    exit;
}

$title = htmlspecialchars($post['title']);
include 'layout/header.php';
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Header Artikel -->
        <article>
            <header class="mb-4">
                <h1>
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
                
                <div class="article-meta">
                    <span>
                        📅 <?php echo date('d F Y', strtotime($post['created_at'])); ?>
                    </span>
                    
                    <?php if ($post['category_name']): ?>
                        <span>
                            <span class="badge">
                                📂 <?php echo htmlspecialchars($post['category_name']); ?>
                            </span>
                        </span>
                    <?php endif; ?>
                    
                    <span>
                        ⏱️ <?php echo ceil(str_word_count($post['content']) / 200); ?> min baca
                    </span>
                </div>
            </header>

            <!-- Konten Artikel -->
            <div class="article-content my-5">
                <?php 
                // Format konten dengan paragraf
                $paragraphs = explode("\n", $post['content']);
                foreach ($paragraphs as $paragraph):
                    if (trim($paragraph)):
                ?>
                    <p>
                        <?php echo htmlspecialchars($paragraph); ?>
                    </p>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>

            <!-- Footer Artikel -->
            <footer class="d-flex gap-2">
                <a href="index.php" class="btn btn-secondary">
                    ← Kembali ke Beranda
                </a>
                <a href="posts/index.php" class="btn btn-outline-secondary">
                    📊 Ke Dashboard
                </a>
            </footer>
        </article>
    </div>
</div>

<?php include 'layout/footer.php'; ?>
