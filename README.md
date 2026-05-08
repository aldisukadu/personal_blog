# 📝 Blog Pribadi - Dokumentasi Lengkap

Website blog pribadi yang dibangun dengan **PHP Native, HTML, CSS (Bootstrap 5), dan Database Relasional MySQL**.

## 🎯 Fitur Utama

✅ **Manajemen Kategori** - Buat, lihat, dan hapus kategori artikel  
✅ **CRUD Artikel** - Create (Buat), Read (Baca), Update (Edit), Delete (Hapus)  
✅ **Database Relasional** - Tabel `categories` terhubung dengan tabel `posts`  
✅ **Keamanan** - Menggunakan Prepared Statements (mencegah SQL Injection)  
✅ **Responsive Design** - Tampilan indah menggunakan Bootstrap 5  
✅ **Query JOIN** - Menampilkan nama kategori di setiap artikel  

---

## 📁 Struktur Folder

```
personal_blog/
├── config/
│   └── database.php          # ⚙️ Koneksi database
├── layout/
│   ├── header.php            # 📄 Bagian atas (Navbar + CSS)
│   └── footer.php            # 📄 Bagian bawah (Footer + JS)
├── posts/                    # 📰 Modul Artikel
│   ├── index.php             # Daftar artikel (Tabel)
│   ├── create.php            # Form buat artikel
│   ├── store.php             # Proses simpan artikel
│   ├── edit.php              # Form edit artikel
│   ├── update.php            # Proses update artikel
│   └── delete.php            # Proses hapus artikel
├── categories/               # 📂 Modul Kategori
│   ├── index.php             # Daftar kategori + form tambah
│   └── delete.php            # Proses hapus kategori
├── index.php                 # 🏠 Halaman utama (untuk pembaca)
├── post.php                  # 📖 Halaman detail artikel
├── setup.sql                 # 💾 File SQL database
└── README.md                 # 📚 File ini
```

---

## 🗃️ Struktur Database

### Tabel: `categories`
Menyimpan kategori artikel
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);
```

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | INT | ID unik (Auto Increment) |
| `name` | VARCHAR(100) | Nama kategori |
| `slug` | VARCHAR(100) | URL-friendly version |

### Tabel: `posts`
Menyimpan artikel yang terhubung dengan kategori (relasi 1-to-Many)
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

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | INT | ID unik |
| `category_id` | INT | FK ke tabel categories |
| `title` | VARCHAR(200) | Judul artikel |
| `content` | TEXT | Isi artikel |
| `created_at` | TIMESTAMP | Waktu pembuatan |

**Relasi**: Satu kategori bisa memiliki banyak artikel (1-to-Many)

---

## 🚀 Cara Menggunakan

### 1. Persiapan Database

**Langkah 1**: Buka phpMyAdmin di browser
```
http://localhost/phpmyadmin
```

**Langkah 2**: Buat database baru bernama `personal_blog`

**Langkah 3**: Import file `setup.sql`
- Pilih database `personal_blog`
- Klik menu "Import"
- Pilih file `setup.sql`
- Klik "Go"

### 2. Mulai Menggunakan Blog

**Buka di Browser:**
```
http://localhost/personal_blog/index.php
```

**Halaman yang Tersedia:**
- 🏠 **Beranda** (`index.php`) - Tampilan artikel untuk pembaca
- 📰 **Dashboard** (`posts/index.php`) - Daftar semua artikel
- ➕ **Tulis Artikel** (`posts/create.php`) - Form buat artikel baru
- 📂 **Kategori** (`categories/index.php`) - Manajemen kategori

---

## 📝 Penjelasan File Penting

### `config/database.php`
File ini menangani koneksi ke database MySQL.

**Penjelasan Baris demi Baris:**
```php
$host = 'localhost';           // Alamat server (localhost = komputer sendiri)
$user = 'root';                // Username MySQL (default: root)
$password = '';                // Password MySQL (default: kosong)
$database = 'personal_blog';   // Nama database yang digunakan

$conn = new mysqli(...);       // Membuat koneksi baru dengan MySQLi
$conn->set_charset("utf8mb4"); // Set encoding agar mendukung bahasa Indonesia
```

### `posts/store.php` - Keamanan dengan Prepared Statement

**Cara BENAR (Prepared Statement - AMAN dari SQL Injection):**
```php
$stmt = $conn->prepare("INSERT INTO posts (category_id, title, content) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $category_id, $title, $content);
// ? = placeholder (tempat kosong untuk data)
// "iss" = i (integer), s (string), s (string)
$stmt->execute(); // Jalankan query dengan data yang aman
```

**Cara SALAH (Query Mentah - BAHAYA dari SQL Injection):**
```php
// ❌ JANGAN PERNAH PAKAI INI!
$conn->query("INSERT INTO posts VALUES ($id, '$title', '$content')");
// Hacker bisa memanipulasi $title untuk merusak database
```

---

## 🛠️ Tips Troubleshooting

### ❌ Error: Koneksi ke Database Gagal
**Solusi:**
- Pastikan MySQL sudah running (XAMPP Control Panel - MySQL hijau)
- Cek username/password di `config/database.php`
- Pastikan database `personal_blog` sudah dibuat

### ❌ Error: Variable Undefined
**Solusi:**
- Pastikan sudah `include '../config/database.php'` di baris paling atas
- Cek path file (gunakan `../` untuk naik satu folder)

### ❌ Artikel tidak muncul di kategori tertentu
**Solusi:**
- Pastikan kategori sudah dibuat di halaman Kategori
- Saat membuat artikel, pilih kategori yang tepat

### ❌ Data dari form tidak terkirim
**Solusi:**
- Gunakan `var_dump($_POST);` untuk debug
- Cek apakah method form adalah `POST`
- Cek apakah input `name` attribute benar

---

## 📚 Query SQL Penting

### 1. JOIN - Menampilkan artikel beserta kategorinya
```sql
SELECT posts.id, posts.title, categories.name 
FROM posts
LEFT JOIN categories ON posts.category_id = categories.id;
```

**Penjelasan:**
- `LEFT JOIN` = ambil semua data dari `posts`, meskipun kategori kosong
- `ON` = hubungan antara dua tabel

### 2. ORDER BY - Mengurutkan artikel
```sql
SELECT * FROM posts ORDER BY created_at DESC;
```

**Penjelasan:**
- `DESC` = Descending (terbaru di atas)
- `ASC` = Ascending (terlama di atas)

### 3. WHERE - Filter data
```sql
SELECT * FROM posts WHERE category_id = 1;
```

---

## 🎨 Bootstrap Class yang Digunakan

| Class | Kegunaan | Contoh |
|-------|----------|--------|
| `.container` | Bungkus konten agar tidak mepet | `<div class="container">` |
| `.btn .btn-primary` | Tombol biru | `<button class="btn btn-primary">` |
| `.card` | Kotak putih cantik | `<div class="card">` |
| `.table` | Tabel rapi | `<table class="table">` |
| `.badge` | Label kecil | `<span class="badge bg-primary">` |
| `.alert` | Pesan (success/error) | `<div class="alert alert-success">` |
| `.row .col` | Grid layout | `<div class="row"><div class="col-md-6">` |

---

## ✅ Checklist Belajar

- [ ] Pahami struktur folder dan fungsi setiap file
- [ ] Buat 3 kategori artikel
- [ ] Buat 5 artikel dengan kategori berbeda
- [ ] Edit satu artikel dan lihat perubahannya
- [ ] Hapus satu artikel
- [ ] Pahami Prepared Statement dan kenapa penting
- [ ] Lihat hasil JOIN di halaman index.php
- [ ] Coba edit `layout/header.php` dan ubah warna navbar
- [ ] Buat query SQL sendiri di phpMyAdmin
- [ ] Pahami bedanya GET dan POST

---

## 🔒 Keamanan

✅ **Prepared Statements** - Semua query menggunakan `?` placeholder  
✅ **HTML Escaping** - `htmlspecialchars()` untuk mencegah XSS  
✅ **FOREIGN KEY** - Kategori tidak bisa dihapus jika masih dipakai artikel  
✅ **Auto Increment** - ID otomatis, user tidak bisa edit  

---

## 📖 Referensi

- **PHP MySQLi**: https://www.php.net/manual/en/book.mysqli.php
- **Bootstrap 5**: https://getbootstrap.com/docs/5.3/
- **SQL Basics**: https://www.w3schools.com/sql/

---

**Dibuat untuk belajar Database Relasional & Web Development dengan PHP Native**  
Happy Coding! 🚀
