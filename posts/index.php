<?php
// ============================================================
// FILE: posts/index.php
// FUNGSI: Dashboard Penulis — Daftar semua artikel dalam tabel
// Tampilan ini untuk ADMIN/PENULIS, bukan pembaca umum
// ============================================================

// Jalur '../' berarti naik SATU folder ke atas (dari posts/ ke blogspot/)
require_once '../config/database.php';

$pageTitle = "Dashboard Artikel";

// ---- AMBIL SEMUA ARTIKEL ----
$sql = "SELECT posts.id, posts.title, posts.created_at, 
               categories.name AS category_name 
        FROM posts 
        LEFT JOIN categories ON posts.category_id = categories.id 
        ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $sql);

// '../layout/header.php' = naik ke folder blogspot/, lalu masuk layout/
include '../layout/header.php';
?>

<div class="container">
    
    <!-- Baris judul + tombol tambah artikel -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">📋 Dashboard Artikel</h2>
        <!-- btn-success = tombol hijau -->
        <a href="/blogspot/posts/create.php" class="btn btn-success">
            + Tulis Artikel Baru
        </a>
    </div>
    
    <!-- Kotak card pembungkus tabel -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            
            <!-- Tabel artikel -->
            <!-- table-hover = baris berubah warna saat di-hover -->
            <!-- table-striped = baris selang-seling berbeda warna -->
            <!-- mb-0 = hapus margin bawah default tabel -->
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="50%">Judul Artikel</th>
                        <th width="20%">Kategori</th>
                        <th width="15%">Tanggal</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                
                <?php
                // Variabel penghitung nomor urut baris
                $no = 1;
                
                if (mysqli_num_rows($result) > 0):
                    while ($post = mysqli_fetch_assoc($result)):
                ?>
                
                <tr>
                    <!-- Nomor urut — bukan ID database -->
                    <td><?= $no++ ?></td>
                    
                    <!-- Judul artikel sebagai link ke halaman detail -->
                    <td>
                        <a href="/blogspot/post.php?id=<?= $post['id'] ?>" 
                           class="text-decoration-none fw-semibold">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </td>
                    
                    <!-- Kategori dengan badge -->
                    <td>
                        <?php if ($post['category_name']): ?>
                            <span class="badge bg-info text-dark">
                                <?= htmlspecialchars($post['category_name']) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Umum</span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Tanggal dalam format singkat -->
                    <td>
                        <small><?= date('d M Y', strtotime($post['created_at'])) ?></small>
                    </td>
                    
                    <!-- Tombol Edit dan Hapus -->
                    <td class="text-center">
                        <!-- d-flex gap-1 = susun tombol bersebelahan dengan jarak kecil -->
                        <div class="d-flex gap-1 justify-content-center">
                            
                            <!-- Tombol Edit — kirim ID artikel ke edit.php -->
                            <a href="/blogspot/posts/edit.php?id=<?= $post['id'] ?>" 
                               class="btn btn-warning btn-sm">
                                ✏️
                            </a>
                            
                            <!-- Tombol Hapus — pakai form POST agar lebih aman dari link biasa -->
                            <!-- onclick="return confirm(...)" = minta konfirmasi sebelum hapus -->
                            <form action="/blogspot/posts/delete.php" method="POST" 
                                  style="display:inline;"
                                  onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                  
                                <!-- Input hidden = kirim ID tanpa ditampilkan ke pengguna -->
                                <input type="hidden" name="id" value="<?= $post['id'] ?>">
                                
                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            
                        </div>
                    </td>
                </tr>
                
                <?php
                    endwhile;
                else:
                ?>
                
                <!-- Baris kosong jika belum ada artikel -->
                <tr>
                    <!-- colspan="5" = baris ini memenuhi semua 5 kolom -->
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada artikel. 
                        <a href="/blogspot/posts/create.php">Buat sekarang?</a>
                    </td>
                </tr>
                
                <?php endif; ?>
                
                </tbody>
            </table>
            
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
