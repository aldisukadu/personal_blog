# 📖 Penjelasan Lengkap Kode post.php

File ini menampilkan **detail satu artikel lengkap** saat user mengklik "Baca Selengkapnya". Mari kita pelajari baris demi baris!

---

## **BAGIAN 1: SETUP & KONEKSI DATABASE**

```php
<?php
// File: post.php
// Halaman detail artikel - menampilkan satu artikel lengkap

include 'config/database.php';
```

**Penjelasan:**
- `<?php` - Menandai awal kode PHP
- `include 'config/database.php';` - Memanggil file `config/database.php` yang berisi koneksi database
  - Sehingga variabel `$conn` (koneksi database) sudah tersedia di file ini

**Analogi:** Seperti membuka pintu koneksi ke gudang database sebelum mengambil data.

---

## **BAGIAN 2: VALIDASI ID ARTIKEL**

```php
// Cek apakah ID artikel dikirim
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
```

**Penjelasan Baris demi Baris:**

| Baris | Kode | Artinya |
|-------|------|--------|
| 1 | `if (!isset($_GET['id']))` | Cek: apakah URL tidak membawa parameter `id`? Contoh: `post.php` (tanpa `?id=1`) |
| 1 | `!isset()` | `!` = "BUKAN", `isset()` = "ada/terdefinisi" → Jadi: "BUKAN ada" atau "tidak ada" |
| 2 | `header("Location: index.php");` | Jika tidak ada ID, redirect/pindah ke halaman `index.php` |
| 3 | `exit;` | Hentikan script PHP. Jangan lanjut ke baris berikutnya |

**Contoh Skenario:**
- ✅ Benar: `post.php?id=5` → Script berlanjut
- ❌ Salah: `post.php` → Redirect ke `index.php` dan hentikan

---

## **BAGIAN 3: AMBIL ID DARI URL**

```php
$id = $_GET['id'];
```

**Penjelasan:**
- `$_GET['id']` - Mengambil nilai `id` dari URL parameter (query string)
- `$id = ...` - Menyimpan nilai tersebut ke dalam variabel `$id`

**Contoh:**
- URL: `post.php?id=5` → `$id` berisi nilai `5`
- URL: `post.php?id=10` → `$id` berisi nilai `10`

**Catatan Penting:** Variabel `$id` ini **berasal dari user** (bisa berbahaya), itulah mengapa kita gunakan **Prepared Statement** di baris berikutnya!

---

## **BAGIAN 4: AMBIL DATA ARTIKEL DARI DATABASE (PREPARED STATEMENT)**

```php
// Ambil data artikel berdasarkan ID (Prepared Statement)
$stmt = $conn->prepare("SELECT id, title, content, created_at, category_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
```

### **Ini adalah bagian PALING PENTING! Mari kita bahas detail:**

| Baris | Kode | Penjelasan |
|-------|------|-----------|
| 1 | `$stmt = $conn->prepare(...)` | Siapkan/persiapkan query SQL ke database. `?` adalah placeholder (pengganti nilai) |
| 1 | `SELECT id, title, content, created_at, category_id FROM posts WHERE id = ?` | Query: "Ambil semua kolom dari tabel posts di mana id-nya sama dengan ?" |
| 2 | `$stmt->bind_param("i", $id);` | **Bind** = "ikat/masukkan". Masukkan nilai `$id` ke placeholder `?`. `"i"` = integer (angka) |
| 3 | `$stmt->execute();` | Jalankan/eksekusi query ke database |
| 4 | `$result = $stmt->get_result();` | Ambil hasil query dari database |
| 5 | `$post = $result->fetch_assoc();` | `fetch_assoc()` = ambil 1 baris data sebagai array asosiatif (pasangan key-value) |

### **Mengapa Prepared Statement?**

```
❌ TIDAK AMAN (Raw Query):
$query = "SELECT * FROM posts WHERE id = $id";
// Jika $id = "1 OR 1=1" → Query jadi "WHERE id = 1 OR 1=1" (semua data terekpos!)

✅ AMAN (Prepared Statement):
$stmt->bind_param("i", $id);
// PHP akan memastikan $id adalah INTEGER. Jadi tidak ada celah SQL Injection!
```

**Hasil yang disimpan ke `$post`:**
```php
Array (
    [id] => 5
    [title] => "Belajar PHP Native"
    [content] => "PHP Native adalah..."
    [created_at] => "2025-05-10 14:30:00"
    [category_id] => 1
)
```

---

## **BAGIAN 5: VALIDASI - APAKAH ARTIKEL DITEMUKAN?**

```php
if (!$post) {
    echo "<div class='alert alert-danger'>Artikel tidak ditemukan!</div>";
    exit;
}
```

**Penjelasan:**
- `if (!$post)` - Jika variabel `$post` kosong/false (artikel tidak ada)
  - `!` = "BUKAN", jadi `!$post` = "BUKAN ada data" atau "tidak ada"
- Tampilkan pesan error dan hentikan script
- Contoh: User akses `post.php?id=999` (artikel tidak ada) → Muncul "Artikel tidak ditemukan!"

---

## **BAGIAN 6: AMBIL NAMA KATEGORI**

```php
// Ambil kategori jika ada
$category_name = '-';
if ($post['category_id']) {
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $post['category_id']);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    $cat = $cat_result->fetch_assoc();
    if ($cat) {
        $category_name = $cat['name'];
    }
}
```

### **Mari kita bahas step by step:**

| Step | Kode | Artinya |
|------|------|--------|
| 1 | `$category_name = '-';` | Siapkan variabel `$category_name` dengan nilai default `-` (jika kategori tidak ada) |
| 2 | `if ($post['category_id'])` | Cek: apakah artikel memiliki kategori? (jika `category_id` tidak kosong) |
| 3 | `$cat_stmt = $conn->prepare(...)` | Siapkan query untuk mencari kategori berdasarkan ID |
| 4-5 | `bind_param` + `execute` | Masukkan `category_id` dan jalankan query |
| 6 | `$cat = $cat_result->fetch_assoc();` | Ambil data kategori (nama kategori) |
| 7 | `if ($cat) { $category_name = $cat['name']; }` | Jika data kategori ada, simpan nama kategorinya |

**Contoh Hasil:**
- Article dengan `category_id = 1` → `$category_name = "Teknologi"`
- Article dengan `category_id = NULL` → `$category_name = "-"`

---

## **BAGIAN 7: ESCAPE HTML & SETUP HEADER**

```php
$title = htmlspecialchars($post['title']);
include 'layout/header.php';
```

**Penjelasan:**
- `htmlspecialchars($post['title'])` - Mengamankan judul agar karakter khusus (seperti `<script>`) tidak dijalankan
  - Contoh: `<script>alert('hacked')</script>` menjadi `&lt;script&gt;alert('hacked')&lt;/script&gt;` (ditampilkan sebagai text, bukan dijalankan)
- `$title` digunakan sebagai judul halaman (di browser tab)
- `include 'layout/header.php'` - Tampilkan header website

---

## **BAGIAN 8: TAMPILKAN JUDUL ARTIKEL**

```html
<div class="row">
    <div class="col-lg-8 mx-auto">
        <article>
            <header class="mb-4">
                <h1>
                    <?php echo htmlspecialchars($post['title']); ?>
                </h1>
```

**Penjelasan:**
- `htmlspecialchars($post['title'])` - Tampilkan judul artikel dengan cara yang aman (escape HTML)
- `<h1>` - Heading besar untuk judul artikel

**Contoh Output HTML:**
```html
<h1>Belajar PHP Native</h1>
```

---

## **BAGIAN 9: TAMPILKAN TANGGAL & METADATA**

```php
<div class="article-meta">
    <span>
        📅 <?php echo date('d F Y', strtotime($post['created_at'])); ?>
    </span>
```

### **Mari breakdown kode ini:**

| Fungsi | Kode | Artinya |
|--------|------|--------|
| 1 | `$post['created_at']` | Ambil tanggal dari database. Contoh: `"2025-05-10 14:30:00"` |
| 2 | `strtotime(...)` | Konversi string tanggal menjadi format Unix timestamp (angka) |
| 3 | `date('d F Y', ...)` | Format tanggal menjadi format yang mudah dibaca |
| - | `d` | Hari (01-31) |
| - | `F` | Nama bulan penuh (January, February, dll) |
| - | `Y` | Tahun 4 digit (2025) |

**Contoh Transformasi:**
```
Input:  "2025-05-10 14:30:00"
↓ strtotime()
Unix: 1747,000,000
↓ date('d F Y')
Output: "10 May 2025"
```

---

## **BAGIAN 10: TAMPILKAN KATEGORI (CONDITIONAL)**

```php
<?php if ($category_name !== '-'): ?>
    <span>
        <span class="badge">
            📂 <?php echo htmlspecialchars($category_name); ?>
        </span>
    </span>
<?php endif; ?>
```

**Penjelasan:**
- `if ($category_name !== '-')` - Jika kategori ada (BUKAN tanda `-`)
- `endif;` - Akhir dari pernyataan `if`
- Tampilkan kategori dengan ikon 📂 dalam badge (label warna)

**Skenario:**
- ✅ Jika ada kategori: Tampilkan "📂 Teknologi"
- ❌ Jika tidak ada: Tidak tampil apa-apa (karena masih `'-'`)

---

## **BAGIAN 11: HITUNG ESTIMASI WAKTU BACA**

```php
<span>
    ⏱️ <?php echo ceil(str_word_count($post['content']) / 200); ?> min baca
</span>
```

### **Breakdown Fungsi:**

| Fungsi | Kode | Artinya |
|--------|------|--------|
| 1 | `$post['content']` | Isi artikel (contoh: 1000 kata) |
| 2 | `str_word_count(...)` | Hitung jumlah kata. Hasil: `1000` |
| 3 | `... / 200` | Bagi dengan 200 (asumsi rata-rata pembaca 200 kata/menit). Hasil: `5` |
| 4 | `ceil(...)` | Bulatkan ke atas. `5.3` menjadi `6`, `4.1` menjadi `5` |

**Contoh:**
```
1000 kata ÷ 200 = 5 menit
1050 kata ÷ 200 = 5.25 → ceil() = 6 menit
```

---

## **BAGIAN 12: TAMPILKAN ISI ARTIKEL DENGAN PARAGRAF**

```php
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
```

### **Mari pelajari setiap baris:**

| Baris | Kode | Penjelasan |
|-------|------|-----------|
| 1 | `$paragraphs = explode("\n", $post['content']);` | Pisahkan konten berdasarkan newline (`\n`). Hasilnya array berisi paragraf-paragraf |
| 2 | `foreach ($paragraphs as $paragraph):` | Loop: ambil setiap paragraf satu-satu |
| 3 | `if (trim($paragraph)):` | `trim()` = hapus spasi kosong. Jika paragraf tidak kosong, lanjut |
| 4 | `echo htmlspecialchars($paragraph);` | Tampilkan paragraf dengan cara yang aman (escape HTML) |
| 5 | `endif;` + `endforeach;` | Akhir dari if dan foreach |

### **Contoh:**

**Input `$post['content']`:**
```
Ini adalah paragraf pertama.

Ini adalah paragraf kedua.

Ini adalah paragraf ketiga.
```

**Proses `explode("\n")`:**
```php
Array (
    [0] => "Ini adalah paragraf pertama."
    [1] => ""
    [2] => "Ini adalah paragraf kedua."
    [3] => ""
    [4] => "Ini adalah paragraf ketiga."
)
```

**Proses Loop (dengan `trim()` dan `if`):**
- Loop 0: "Ini adalah paragraf pertama." → Tidak kosong → Tampilkan dalam `<p>`
- Loop 1: "" → Kosong → Skip (tidak tampil)
- Loop 2: "Ini adalah paragraf kedua." → Tidak kosong → Tampilkan dalam `<p>`
- Loop 3: "" → Kosong → Skip
- Loop 4: "Ini adalah paragraf ketiga." → Tidak kosong → Tampilkan dalam `<p>`

**Output HTML:**
```html
<p>Ini adalah paragraf pertama.</p>
<p>Ini adalah paragraf kedua.</p>
<p>Ini adalah paragraf ketiga.</p>
```

---

## **BAGIAN 13: TAMPILKAN TOMBOL NAVIGASI**

```html
<!-- Footer Artikel -->
<footer class="d-flex gap-2">
    <a href="index.php" class="btn btn-secondary">
        ← Kembali ke Beranda
    </a>
    <a href="posts/index.php" class="btn btn-outline-secondary">
        📊 Ke Dashboard
    </a>
</footer>
```

**Penjelasan:**
- `<footer>` - Bagian footer artikel
- `<a href="index.php">` - Link ke halaman beranda
- `<a href="posts/index.php">` - Link ke dashboard artikel
- User bisa klik tombol untuk navigasi

---

## **BAGIAN 14: INCLUDE FOOTER**

```php
<?php include 'layout/footer.php'; ?>
```

**Penjelasan:**
- Tampilkan footer website (bagian bawah halaman)
- Biasanya berisi copyright, kontak, dll

---

## **RINGKASAN ALUR KESELURUHAN**

```
┌─────────────────────────────────────────────────────────────┐
│ 1. User klik "Baca Selengkapnya" di artikel id=5            │
│    (URL: post.php?id=5)                                      │
└────────────────┬────────────────────────────────────────────┘
                 ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. PHP Script Berjalan                                      │
│    - Cek ID ada di URL? ✓                                    │
│    - Ambil $id = 5 dari $_GET['id']                         │
└────────────────┬────────────────────────────────────────────┘
                 ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Query Database (Prepared Statement)                      │
│    SELECT * FROM posts WHERE id = 5                         │
│    → Hasil: Array berisi data artikel (judul, konten, dll)  │
└────────────────┬────────────────────────────────────────────┘
                 ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Validasi Artikel                                         │
│    - Artikel ditemukan? ✓                                    │
│    - Ada kategorinya? Query dan ambil nama kategori         │
└────────────────┬────────────────────────────────────────────┘
                 ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. Tampilkan ke Browser                                     │
│    - Judul artikel                                           │
│    - Tanggal publikasi                                       │
│    - Kategori (jika ada)                                     │
│    - Estimasi waktu baca                                     │
│    - Konten artikel (paragraf per paragraf)                 │
│    - Tombol navigasi                                         │
└─────────────────────────────────────────────────────────────┘
```

---

## **KEY POINTS YANG HARUS DIINGAT**

| Konsep | Penjelasan | Contoh |
|--------|-----------|--------|
| **Prepared Statement** | Cara aman menggunakan input user | `$stmt->bind_param("i", $id);` |
| **Escape Output** | Amankan data sebelum ditampilkan | `htmlspecialchars($post['title'])` |
| **explode()** | Pisahkan string berdasarkan delimiter | `explode("\n", "a\nb\nc")` → `["a", "b", "c"]` |
| **foreach** | Loop setiap elemen array | `foreach ($arr as $item)` |
| **trim()** | Hapus spasi kosong | `trim("  hello  ")` → `"hello"` |
| **date()** | Format tanggal | `date('d F Y', time())` → `"10 May 2025"` |
| **str_word_count()** | Hitung jumlah kata | `str_word_count("hello world")` → `2` |

---

## **KODE LENGKAP DENGAN KOMENTAR BAHASA INDONESIA**

```php
<?php
// ============================================
// FILE: post.php
// FUNGSI: Menampilkan detail satu artikel
// ============================================

// 1. KONEKSI DATABASE
include 'config/database.php';

// 2. VALIDASI - APAKAH ID DIKIRIM?
if (!isset($_GET['id'])) {
    // Jika tidak ada ID, redirect ke halaman utama
    header("Location: index.php");
    exit;
}

// 3. AMBIL ID DARI URL
$id = $_GET['id'];

// 4. QUERY DATABASE - AMBIL ARTIKEL BERDASARKAN ID
// Menggunakan Prepared Statement untuk keamanan (SQL Injection Prevention)
$stmt = $conn->prepare("SELECT id, title, content, created_at, category_id FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);              // Masukkan $id sebagai integer
$stmt->execute();                          // Jalankan query
$result = $stmt->get_result();             // Ambil hasil
$post = $result->fetch_assoc();            // Konversi ke array asosiatif

// 5. VALIDASI - APAKAH ARTIKEL DITEMUKAN?
if (!$post) {
    echo "<div class='alert alert-danger'>Artikel tidak ditemukan!</div>";
    exit;
}

// 6. QUERY KATEGORI
$category_name = '-';  // Default: tidak ada kategori
if ($post['category_id']) {
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $post['category_id']);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    $cat = $cat_result->fetch_assoc();
    if ($cat) {
        $category_name = $cat['name'];  // Simpan nama kategori
    }
}

// 7. SIAPKAN JUDUL HALAMAN & TAMPILKAN HEADER
$title = htmlspecialchars($post['title']);
include 'layout/header.php';
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <article>
            <header class="mb-4">
                <!-- 8. TAMPILKAN JUDUL ARTIKEL -->
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                
                <div class="article-meta">
                    <!-- 9. TAMPILKAN TANGGAL -->
                    <span>📅 <?php echo date('d F Y', strtotime($post['created_at'])); ?></span>
                    
                    <!-- 10. TAMPILKAN KATEGORI (JIKA ADA) -->
                    <?php if ($category_name !== '-'): ?>
                        <span class="badge">📂 <?php echo htmlspecialchars($category_name); ?></span>
                    <?php endif; ?>
                    
                    <!-- 11. HITUNG & TAMPILKAN WAKTU BACA -->
                    <span>⏱️ <?php echo ceil(str_word_count($post['content']) / 200); ?> min baca</span>
                </div>
            </header>

            <!-- 12. TAMPILKAN KONTEN ARTIKEL (PARAGRAF PER PARAGRAF) -->
            <div class="article-content my-5">
                <?php 
                $paragraphs = explode("\n", $post['content']);  // Pisah berdasarkan newline
                foreach ($paragraphs as $paragraph):
                    if (trim($paragraph)):  // Jika paragraf tidak kosong
                ?>
                    <p><?php echo htmlspecialchars($paragraph); ?></p>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>

            <!-- 13. TOMBOL NAVIGASI -->
            <footer class="d-flex gap-2">
                <a href="index.php" class="btn btn-secondary">← Kembali ke Beranda</a>
                <a href="posts/index.php" class="btn btn-outline-secondary">📊 Ke Dashboard</a>
            </footer>
        </article>
    </div>
</div>

<!-- 14. TAMPILKAN FOOTER WEBSITE -->
<?php include 'layout/footer.php'; ?>
```

---

## **TIPS BELAJAR LEBIH LANJUT**

✅ Coba modifikasi kode:
- Ubah format tanggal di `date('d F Y')` → coba `'Y-m-d H:i'`
- Ubah pembagi waktu baca `/ 200` → coba `/ 100` atau `/ 300`
- Tambahkan `echo "<pre>"; print_r($post); echo "</pre>";` untuk lihat struktur array

✅ Pahami konsep:
- **Prepared Statement** ← Sangat penting untuk keamanan!
- **htmlspecialchars()** ← Selalu gunakan saat menampilkan data user
- **Array Asosiatif** ← `$post['title']`, `$post['content']`, dll

✅ Dokumentasi Resmi:
- PHP `str_word_count()`: https://www.php.net/manual/en/function.str-word-count.php
- PHP `date()`: https://www.php.net/manual/en/function.date.php
- PHP `explode()`: https://www.php.net/manual/en/function.explode.php

---

**Semoga penjelasan ini membantu Anda memahami alur kode PHP Native! 🚀**
