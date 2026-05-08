# 📁 Penjelasan Lengkap Struktur Proyek Blog Pribadi

## 🗂️ Daftar Isi
1. [Struktur Folder](#struktur-folder)
2. [Penjelasan Masing-Masing File](#penjelasan-masingmasing-file)
3. [Alur Kerja Aplikasi](#alur-kerja-aplikasi)
4. [Cara Menggunakan Setiap Modul](#cara-menggunakan-setiap-modul)

---

## 📋 Struktur Folder

```
personal_blog/
│
├── 📂 config/
│   └── database.php              # Konfigurasi koneksi database
│
├── 📂 layout/
│   ├── header.php                # Template header (navbar + CSS)
│   └── footer.php                # Template footer (script + closing tag)
│
├── 📂 posts/
│   ├── index.php                 # Dashboard - Daftar semua artikel
│   ├── create.php                # Form membuat artikel baru
│   ├── store.php                 # Proses simpan artikel ke DB
│   ├── edit.php                  # Form edit artikel
│   ├── update.php                # Proses update artikel di DB
│   └── delete.php                # Proses hapus artikel dari DB
│
├── 📂 categories/
│   ├── index.php                 # Daftar kategori + form tambah
│   └── delete.php                # Proses hapus kategori
│
├── index.php                     # Halaman utama (beranda)
├── post.php                      # Halaman detail artikel
├── setup.sql                     # File SQL untuk database
├── README.md                     # Dokumentasi setup
├── PENJELASAN.md                 # Penjelasan konsep PHP
└── STRUKTUR_PROYEK.md            # File ini
```

---

## 📄 Penjelasan Masing-Masing File

### 🔧 **config/database.php**

**Fungsi:** File pusat koneksi ke database MySQL

**Isi File:**
```php
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'personal_blog';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
```

**Penjelasan Baris Demi Baris:**

| Baris | Penjelasan |
|-------|-----------|
| `$host = 'localhost'` | Alamat server MySQL (localhost = komputer sendiri) |
| `$user = 'root'` | Username MySQL default di XAMPP |
| `$password = ''` | Password MySQL (kosong = tidak ada password di XAMPP) |
| `$database = 'personal_blog'` | Nama database yang digunakan |
| `new mysqli(...)` | Membuat koneksi baru ke MySQL |
| `$conn->connect_error` | Cek apakah koneksi berhasil |
| `set_charset("utf8mb4")` | Agar mendukung karakter Indonesia |

**Cara Menggunakan:**
```php
// Di file manapun, letakkan di baris paling atas
include 'config/database.php';

// Sekarang $conn sudah tersedia untuk query
$result = $conn->query("SELECT * FROM posts");
```

**Kapan Dimodifikasi:**
- Jika mengganti password MySQL
- Jika database dibuat di server lain (bukan localhost)
- Jika username MySQL berbeda

---

### 🎨 **layout/header.php**

**Fungsi:** Template bagian atas website yang dipakai berulang kali

**Isi Utama:**
1. Tag HTML `<!DOCTYPE>`, `<head>`, `<meta>`
2. Link Bootstrap 5 CSS
3. Custom CSS styling (dark mode, card, button, dll)
4. Navbar menu navigasi
5. Opening `<div class="container">`

**Struktur HTML:**
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags & CSS Bootstrap -->
</head>
<body>
    <!-- Navbar & Menu -->
    <nav class="navbar navbar-dark bg-dark">
        <!-- Menu: Beranda, Dashboard, Tulis Artikel, Kategori -->
    </nav>
    
    <!-- Container utama mulai -->
    <div class="container">
```

**Custom CSS yang Diaplikasikan:**
- Dark mode dengan gradient hitam (#0f0f0f - #1a1a1a)
- Styling card, button, table, alert
- Styling untuk halaman artikel (typography, spacing, dll)
- Responsive design mobile

**Cara Menggunakan:**
```php
// Di setiap file PHP
$title = 'Nama Halaman';
include 'layout/header.php';

// Sekarang Anda bisa langsung nulis konten
?>
<h1>Konten Halaman</h1>
<?php
include 'layout/footer.php';
```

**Kapan Dimodifikasi:**
- Jika ingin menambah menu di navbar
- Jika ingin mengganti warna/styling
- Jika ingin menambah link eksternal (CSS/JS)

---

### 📄 **layout/footer.php**

**Fungsi:** Template bagian bawah website

**Isi Utama:**
1. Closing `</div>` untuk container
2. Footer dengan copyright
3. Script Bootstrap 5 JavaScript
4. Closing `</body>` dan `</html>`

**Struktur HTML:**
```html
    </div> <!-- End container -->
    
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2026 Blog Pribadi Saya</p>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

**Fungsi Bootstrap JavaScript:**
- Membuat dropdown menu jalan
- Membuat modal dialog jalan
- Membuat tooltip jalan
- Dll fitur Bootstrap interaktif

**Kapan Dimodifikasi:**
- Jika ingin menambah custom JavaScript
- Jika ingin mengubah footer text
- Jika ingin menambah script analytics

---

### 📰 **posts/index.php** (Dashboard Artikel)

**Fungsi:** Menampilkan daftar semua artikel dalam bentuk tabel

**Alur Kerja:**
1. Include database connection
2. Query SQL dengan JOIN untuk ambil artikel + kategori
3. Looping artikel dan tampilkan dalam tabel

**Query SQL:**
```sql
SELECT posts.id, posts.title, posts.created_at, categories.name
FROM posts
LEFT JOIN categories ON posts.category_id = categories.id
ORDER BY posts.created_at DESC;
```

**Tabel yang Ditampilkan:**

| No | Judul Artikel | Kategori | Tanggal | Aksi |
|----|---|---|---|---|
| 1 | Memulai PHP Native | Teknologi | 08 May 2026 | Edit / Hapus |
| 2 | Tips Produktif | Lifestyle | 07 May 2026 | Edit / Hapus |

**Tombol & Link:**
- **Tombol Hijau "Tulis Artikel Baru"** → Ke `posts/create.php`
- **Tombol Kuning "Edit"** → Ke `posts/edit.php?id=1`
- **Tombol Merah "Hapus"** → Ke `posts/delete.php?id=1` (dengan konfirmasi)

**Fitur:**
- Table dengan hover effect
- Badge untuk kategori
- Format tanggal (08 May 2026)
- Prepared Statement untuk keamanan

---

### ✍️ **posts/create.php** (Form Buat Artikel)

**Fungsi:** Menampilkan form untuk membuat artikel baru

**Form Input:**

| Field | Tipe | Keterangan |
|-------|------|-----------|
| Judul | Text | Judul artikel (required) |
| Kategori | Dropdown | Pilih dari tabel categories |
| Isi | Textarea | Konten artikel (required) |

**Proses:**
1. Ambil data dari dropdown kategori dengan query:
   ```sql
   SELECT * FROM categories ORDER BY name ASC
   ```
2. Tampilkan form kosong
3. Saat submit, data dikirim ke `posts/store.php`

**Tombol:**
- **Simpan Artikel** (hijau) → Submit form ke store.php
- **Batal** (abu-abu) → Kembali ke index.php

---

### 💾 **posts/store.php** (Proses Simpan Artikel)

**Fungsi:** Menerima data dari form create.php dan menyimpan ke database

**Proses:**
1. Cek apakah form dikirim dengan method POST
2. Ambil data: `$_POST['title']`, `$_POST['category_id']`, `$_POST['content']`
3. Gunakan Prepared Statement untuk insert

**Query SQL:**
```sql
INSERT INTO posts (category_id, title, content) VALUES (?, ?, ?)
```

**Penjelasan:**
- `?` adalah placeholder (tempat kosong untuk data yang aman)
- `bind_param("iss", ...)` = integer, string, string

**Hasil:**
- Jika berhasil: Redirect ke `posts/index.php`
- Jika gagal: Tampilkan error message

**Keamanan:**
✅ Prepared Statement = tidak ada SQL Injection  
✅ Tidak perlu escape manual  
✅ Data otomatis aman

---

### ✏️ **posts/edit.php** (Form Edit Artikel)

**Fungsi:** Menampilkan form untuk edit artikel yang sudah ada

**Proses:**
1. Ambil ID dari URL: `$_GET['id']`
2. Query artikel berdasarkan ID
3. Query kategori untuk dropdown
4. Tampilkan form dengan data yang sudah ada

**Query SQL:**
```sql
SELECT * FROM posts WHERE id = ?
```

**Form Prepopulated:**
- Judul sudah terisi dengan judul lama
- Kategori sudah ter-select kategori lama
- Isi sudah terisi dengan konten lama

**Tombol:**
- **Simpan Perubahan** (hijau) → Submit ke update.php
- **Batal** (abu-abu) → Kembali ke index.php

---

### 🔄 **posts/update.php** (Proses Update Artikel)

**Fungsi:** Menerima data dari form edit.php dan update database

**Proses:**
1. Ambil data: `$_POST['id']`, `$_POST['title']`, `$_POST['category_id']`, `$_POST['content']`
2. Update record di tabel posts

**Query SQL:**
```sql
UPDATE posts SET category_id = ?, title = ?, content = ? WHERE id = ?
```

**Hasil:**
- Jika berhasil: Redirect ke `posts/index.php`
- Jika gagal: Tampilkan error message

---

### 🗑️ **posts/delete.php** (Proses Hapus Artikel)

**Fungsi:** Menghapus artikel dari database

**Proses:**
1. Ambil ID dari URL: `$_GET['id']`
2. Query DELETE dengan WHERE id

**Query SQL:**
```sql
DELETE FROM posts WHERE id = ?
```

**Alur:**
- User klik tombol "Hapus" di index.php
- Muncul konfirmasi: "Yakin ingin menghapus?"
- Jika OK: Redirect ke delete.php dan hapus artikel
- Redirect balik ke index.php

**Catatan:** Tidak ada halaman tampilan, langsung proses & redirect

---

### 📂 **categories/index.php** (Kelola Kategori)

**Fungsi:** Menampilkan daftar kategori dan form tambah kategori

**Form Input:**
- **Nama Kategori** (text input)
- Slug otomatis dibuat: `strtolower(str_replace(" ", "-", $name))`

**Tabel Kategori:**

| No | Nama Kategori | Slug | Aksi |
|----|---|---|---|
| 1 | Teknologi | teknologi | Hapus |
| 2 | Lifestyle | lifestyle | Hapus |

**Proses:**
1. Form dikirim dengan method POST ke file yang sama (index.php)
2. Insert ke database
3. Refresh halaman untuk tampilkan kategori terbaru

**Query SQL (Create):**
```sql
INSERT INTO categories (name, slug) VALUES (?, ?)
```

**Query SQL (Read):**
```sql
SELECT * FROM categories ORDER BY id DESC
```

---

### 🗑️ **categories/delete.php** (Proses Hapus Kategori)

**Fungsi:** Menghapus kategori dari database

**Proses:**
1. Ambil ID dari URL: `$_GET['id']`
2. Delete kategori

**Query SQL:**
```sql
DELETE FROM categories WHERE id = ?
```

**Catatan Penting:**
- Jika kategori masih dipakai artikel, artikel tidak otomatis terhapus
- Artikel akan set `category_id = NULL` (tanpa kategori)
- Ini karena `ON DELETE SET NULL` di foreign key

---

### 🏠 **index.php** (Halaman Utama - Beranda)

**Fungsi:** Halaman pertama yang dilihat pengunjung blog

**Konten:**
- Judul blog: "📚 Blog Pribadi Saya"
- Deskripsi singkat
- Daftar artikel terbaru dalam bentuk CARD

**Setiap Card berisi:**
- Judul artikel
- Badge kategori (warna ungu)
- Preview konten (150 karakter pertama)
- Tanggal artikel
- Tombol "Baca Selengkapnya"

**Query SQL:**
```sql
SELECT posts.id, posts.title, posts.content, posts.created_at, 
       categories.name as category_name
FROM posts
LEFT JOIN categories ON posts.category_id = categories.id
ORDER BY posts.created_at DESC
```

**Fitur:**
- Menggunakan query JOIN
- Sorting DESC (terbaru di atas)
- Card dengan hover effect
- Link ke halaman detail artikel

---

### 📖 **post.php** (Halaman Detail Artikel)

**Fungsi:** Menampilkan satu artikel secara lengkap

**Konten:**
1. **Header Artikel:**
   - Judul besar (2.5rem)
   - Metadata: Tanggal, Kategori, Waktu baca

2. **Isi Artikel:**
   - Konten penuh dari database
   - Line-height 1.8 untuk kenyamanan baca
   - Text justify (rata kanan kiri)
   - Paragraf terpisah jelas

3. **Footer:**
   - Tombol "Kembali ke Beranda"
   - Tombol "Ke Dashboard"

**Query SQL:**
```sql
SELECT posts.id, posts.title, posts.content, posts.created_at, 
       categories.name as category_name
FROM posts
LEFT JOIN categories ON posts.category_id = categories.id
WHERE posts.id = ?
```

**Styling Khusus:**
- Background article dark (#252525)
- Border ungu (#667eea)
- Typography profesional

---

### 💾 **setup.sql** (File Database)

**Fungsi:** Berisi SQL script untuk membuat tabel dan data contoh

**Isi:**

1. **CREATE TABLE categories:**
   ```sql
   CREATE TABLE categories (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(100) NOT NULL,
       slug VARCHAR(100) NOT NULL UNIQUE
   );
   ```

2. **CREATE TABLE posts:**
   ```sql
   CREATE TABLE posts (
       id INT PRIMARY KEY AUTO_INCREMENT,
       category_id INT,
       title VARCHAR(200) NOT NULL,
       content TEXT NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
   );
   ```

3. **INSERT Data Contoh:**
   - 3 kategori (Teknologi, Lifestyle, Tutorial)
   - 3 artikel contoh

**Cara Menggunakan:**
1. Buat database baru di phpMyAdmin: `personal_blog`
2. Import file setup.sql
3. Database siap digunakan

---

## 🔄 Alur Kerja Aplikasi

### Alur CREATE (Membuat Artikel)

```
┌─────────────────────────────────────────────────────┐
│ User klik "Tulis Artikel Baru" di index.php         │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ posts/create.php                                     │
│ - Tampilkan form kosong                              │
│ - Query kategori dari database                       │
│ - Tampilkan dropdown kategori                        │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ User isi form & klik "Simpan"                        │
│ Form dikirim dengan method POST                      │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ posts/store.php                                      │
│ - Ambil data dari $_POST                             │
│ - Query INSERT dengan Prepared Statement             │
│ - Simpan ke tabel posts                              │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ Redirect ke posts/index.php                          │
│ Dashboard tampil dengan artikel baru                 │
└──────────────────────────────────────────────────────┘
```

### Alur READ (Membaca Artikel)

```
┌─────────────────────────────────────────────────────┐
│ User buka halaman Beranda (index.php)               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ Query JOIN: SELECT artikel + kategori               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ Tampilkan daftar artikel dalam CARD                 │
│ Setiap card ada tombol "Baca Selengkapnya"          │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ User klik "Baca Selengkapnya" atau card              │
│ Link ke: post.php?id=1                               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ post.php                                             │
│ - Ambil ID dari URL ($_GET['id'])                    │
│ - Query SELECT artikel berdasarkan ID               │
│ - Tampilkan artikel lengkap dengan styling bagus     │
└──────────────────────────────────────────────────────┘
```

### Alur UPDATE (Edit Artikel)

```
┌─────────────────────────────────────────────────────┐
│ User klik tombol "Edit" di Dashboard                 │
│ Link ke: posts/edit.php?id=1                         │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ posts/edit.php                                       │
│ - Query SELECT artikel berdasarkan ID               │
│ - Query kategori untuk dropdown                      │
│ - Tampilkan form dengan data lama (prepopulated)     │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ User edit form & klik "Simpan Perubahan"             │
│ Form dikirim dengan method POST                      │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ posts/update.php                                     │
│ - Ambil data dari $_POST                             │
│ - Query UPDATE dengan WHERE id                       │
│ - Update record di tabel posts                       │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────┐
│ Redirect ke posts/index.php                          │
│ Dashboard tampil dengan artikel terbaru              │
└──────────────────────────────────────────────────────┘
```

### Alur DELETE (Hapus Artikel)

```
┌─────────────────────────────────────────────────────┐
│ User klik tombol "Hapus" di Dashboard                │
│ Muncul confirm() dialog: "Yakin ingin menghapus?"   │
└──────────────────┬──────────────────────────────────┘
                   │
         ┌─────────┴─────────┐
         │                   │
      CANCEL                 OK
         │                   │
         ▼                   ▼
    Tidak jadi      posts/delete.php?id=1
    menghapus        - Query DELETE WHERE id
                     - Hapus dari database
                     - Redirect ke index.php
```

---

## 📚 Cara Menggunakan Setiap Modul

### 🎯 Modul 1: Kelola Kategori

**Tujuan:** Membuat kategori untuk pengelompokan artikel

**Langkah:**
1. Buka: `http://localhost/personal_blog/categories/index.php`
2. Di form "Tambah Kategori Baru", masukkan nama: "Teknologi"
3. Klik tombol "Tambah Kategori"
4. Slug otomatis menjadi: "teknologi"
5. Kategori muncul di tabel bawah

**Contoh Kategori:**
- Teknologi
- Lifestyle
- Tutorial
- Kesehatan
- Travel

**Catatan:**
- Slug tidak boleh sama (UNIQUE)
- Hapus kategori dengan tombol merah "Hapus"

---

### 🎯 Modul 2: Kelola Artikel (CRUD)

**CREATE - Membuat Artikel Baru**
1. Buka: `http://localhost/personal_blog/posts/create.php`
2. Isi form:
   - Judul: "Memulai PHP Native"
   - Kategori: (pilih dari dropdown)
   - Isi: (tulis artikel)
3. Klik "Simpan Artikel"

**READ - Melihat Artikel**
1. Dashboard: `http://localhost/personal_blog/posts/index.php`
   - Tabel menampilkan semua artikel
2. Halaman Utama: `http://localhost/personal_blog/index.php`
   - Card menampilkan artikel preview

**UPDATE - Edit Artikel**
1. Di Dashboard, klik tombol kuning "Edit"
2. Form muncul dengan data lama
3. Edit apa yang ingin diubah
4. Klik "Simpan Perubahan"

**DELETE - Hapus Artikel**
1. Di Dashboard, klik tombol merah "Hapus"
2. Konfirmasi: "Yakin ingin menghapus?"
3. Klik OK, artikel terhapus

---

### 🎯 Modul 3: Baca Artikel (Pengunjung)

**Alur untuk pembaca:**
1. Buka halaman utama: `http://localhost/personal_blog/index.php`
2. Lihat daftar artikel dalam card
3. Klik "Baca Selengkapnya" pada card
4. Baca artikel lengkap di halaman detail
5. Lihat metadata: tanggal, kategori, waktu baca
6. Klik "Kembali ke Beranda" untuk kembali

---

## 🔐 Keamanan Yang Diterapkan

| Keamanan | Penjelasan | Contoh |
|----------|-----------|--------|
| **Prepared Statement** | SQL query aman dari injection | `bind_param("i", $id)` |
| **htmlspecialchars()** | Output aman dari XSS | `echo htmlspecialchars($title)` |
| **FOREIGN KEY** | Relasi tabel terpelihara | `ON DELETE SET NULL` |
| **Input Type** | Browser validasi tipe input | `type="text"` required |

---

## 🚀 Tips Mengembangkan Lebih Lanjut

**Fitur Bisa Ditambahkan:**
1. ⭐ Rating/Like artikel
2. 💬 Comment/Komentar
3. 🔍 Search artikel
4. 🏷️ Tag artikel
5. 👤 User/Author multiple
6. 🖼️ Upload gambar artikel
7. 📄 Pagination (halaman 1, 2, 3, dst)
8. 🔐 Login/Password admin
9. 📧 Share artikel via email
10. 📱 Mobile app API

---

**Selamat belajar PHP Native! Keep Coding! 🚀**
