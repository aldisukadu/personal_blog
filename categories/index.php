<?php
// ============================================================
// FILE: categories/index.php
// FUNGSI: Dua-dalam-satu: Tampilkan daftar kategori + Form tambah
// Halaman ini menangani TAMPILAN dan juga PROSES simpan kategori
// ============================================================

require_once '../config/database.php';

$pageTitle   = "Kelola Kategori";
$pesan_sukses = ''; // Variabel untuk menyimpan pesan feedback
$pesan_error  = '';

// ============================================================
// BAGIAN 1: PROSES SIMPAN KATEGORI (jika form dikirim)
// Kita cek REQUEST_METHOD di sini sebelum menampilkan apapun
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = trim($_POST['name'] ?? '');
    
    if (!empty($name)) {
        
        // ---- BUAT SLUG DARI NAMA ----
        // Slug = versi URL-friendly dari nama. Contoh: "Tips PHP" → "tips-php"
        // strtolower() = ubah ke huruf kecil semua
        $slug = strtolower($name);
        // preg_replace() = ganti pola teks menggunakan regex
        // '/[^a-z0-9]+/' = karakter apapun SELAIN huruf/angka
        // '-' = ganti dengan tanda hubung
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        // trim('-') = hapus tanda '-' di awal atau akhir slug
        $slug = trim($slug, '-');
        
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $slug);
        
        if ($stmt->execute()) {
            $pesan_sukses = "Kategori <strong>" . htmlspecialchars($name) . "</strong> berhasil ditambahkan!";
        } else {
            // Error umum bisa karena slug duplikat (UNIQUE constraint)
            $pesan_error = "Gagal menambahkan kategori. Nama ini mungkin sudah ada.";
        }
        $stmt->close();
    } else {
        $pesan_error = "Nama kategori tidak boleh kosong.";
    }
}

// ============================================================
// BAGIAN 2: AMBIL SEMUA KATEGORI UNTUK DITAMPILKAN
// ============================================================
// Kita hitung juga berapa artikel di setiap kategori
// COUNT(posts.id) = hitung jumlah post yang punya category_id ini
// GROUP BY = kelompokkan hasil per kategori
$sql = "SELECT categories.id, categories.name, categories.slug, 
               COUNT(posts.id) AS jumlah_artikel
        FROM categories
        LEFT JOIN posts ON categories.id = posts.category_id
        GROUP BY categories.id, categories.name, categories.slug
        ORDER BY categories.name ASC";

$result = mysqli_query($conn, $sql);

include '../layout/header.php';
?>

<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">🗂️ Kelola Kategori</h2>
        <a href="/blogspot/posts/index.php" class="btn btn-outline-secondary btn-sm">
            ← Dashboard
        </a>
    </div>
    
    <div class="row g-4">
        
        <!-- KOLOM KIRI: Form Tambah Kategori -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">+ Tambah Kategori</h5>
                </div>
                <div class="card-body">
                    
                    <!-- Tampilkan pesan sukses jika ada -->
                    <?php if ($pesan_sukses): ?>
                        <!-- alert-success = kotak hijau Bootstrap -->
                        <div class="alert alert-success">
                            <?= $pesan_sukses ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Tampilkan pesan error jika ada -->
                    <?php if ($pesan_error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form mengirim ke file ini sendiri (action kosong = ke file saat ini) -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Nama Kategori
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Contoh: Teknologi"
                                   required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- KOLOM KANAN: Daftar Kategori -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Kategori</h5>
                </div>
                <div class="card-body p-0">
                    
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th class="text-center">Artikel</th>
                                <th class="text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($result) > 0):
                            while ($cat = mysqli_fetch_assoc($result)):
                        ?>
                        
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                            <td>
                                <!-- code = tampilan teks seperti kode (font monospace) -->
                                <code><?= htmlspecialchars($cat['slug']) ?></code>
                            </td>
                            <td class="text-center">
                                <!-- Badge menampilkan jumlah artikel -->
                                <span class="badge bg-secondary">
                                    <?= $cat['jumlah_artikel'] ?> artikel
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="/blogspot/categories/delete.php" method="POST"
                                      onsubmit="return confirm('Hapus kategori ini?\nArtikel terkait tidak akan terhapus.')">
                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        
                        <?php
                            endwhile;
                        else:
                        ?>
                        
                        <tr>
                            <td colspan="5" class="text-center py-3 text-muted">
                                Belum ada kategori.
                            </td>
                        </tr>
                        
                        <?php endif; ?>
                        
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        
    </div><!-- akhir .row -->
</div>

<?php include '../layout/footer.php'; ?>
