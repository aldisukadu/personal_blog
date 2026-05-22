# 📁 Folder: `categories/`

Folder ini mengelola **kategori** yang digunakan untuk mengelompokkan artikel. Berbeda dari `posts/` yang punya banyak file, modul ini sengaja disederhanakan menjadi dua file saja.

---

## Kenapa Hanya Dua File?

Untuk kategori, operasi yang tersedia lebih terbatas:
- **Tambah** dan **Lihat** digabung dalam satu file (`index.php`)
- **Hapus** dipisah ke file sendiri (`delete.php`)
- Tidak ada fitur Edit — di blog sederhana, nama kategori jarang berubah

Penggabungan Tambah + Lihat dalam satu file adalah teknik umum di PHP untuk form yang **ada di halaman yang sama dengan datanya**.

---

## 📄 `index.php` — Daftar + Tambah Kategori

**Fungsi:** Dua-dalam-satu — menampilkan daftar kategori sekaligus menyediakan form untuk menambah kategori baru.  
**URL:** `http://localhost/blogspot/categories/index.php`

### Teknik: Self-Submitting Form

Form di halaman ini mengirim data **ke file itu sendiri** (`action=""`).  
Artinya ketika form dikirim, halaman yang sama memproses data lalu menampilkan hasilnya — termasuk pesan sukses/error.

```
Kunjungi index.php (GET)
    → Tampilkan form kosong + daftar kategori

Kirim form (POST ke index.php)
    → Proses simpan data
    → Tampilkan form + pesan sukses + daftar kategori yang sudah diperbarui
```

---

### Penjelasan Kode Baris per Baris

```php
$pesan_sukses = '';
$pesan_error  = '';
```
> Dua variabel kosong disiapkan sebagai wadah pesan feedback. Diisi nanti jika ada kondisi yang memicu pesan.

---

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    ...
}
```
> Bagian proses hanya berjalan saat form dikirim (method POST). Saat halaman pertama kali dikunjungi (method GET), blok ini dilewati.  
> Ini pola standar untuk self-submitting form di PHP.

---

```php
$slug = strtolower($name);
$slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
$slug = trim($slug, '-');
```
> Tiga langkah membuat **slug** dari nama kategori. Slug adalah versi URL-friendly dari teks.
>
> **Contoh:** Nama `"Tips & Trik PHP"` → slug `"tips-trik-php"`
>
> **Baris 1 — `strtolower()`:**  
> Ubah semua huruf menjadi huruf kecil.  
> `"Tips & Trik PHP"` → `"tips & trik php"`
>
> **Baris 2 — `preg_replace('/[^a-z0-9]+/', '-', $slug)`:**  
> Fungsi ini mengganti pola teks menggunakan **Regular Expression (Regex)**.  
> - `/[^a-z0-9]+/` = pola: satu atau lebih karakter yang **bukan** huruf kecil a-z atau angka 0-9.  
> - Karakter yang cocok (spasi, `&`, tanda baca) diganti dengan `-`.  
> `"tips & trik php"` → `"tips---trik-php"` (tanda `&` dan spasi di sekitarnya jadi `-`)  
> Tanda `+` memastikan beberapa karakter sekaligus jadi satu `-`.  
> Hasilnya: `"tips-trik-php"`
>
> **Baris 3 — `trim($slug, '-')`:**  
> Versi `trim()` dengan argumen kedua — hapus karakter `-` di **awal dan akhir** slug saja.  
> Berguna untuk nama seperti `"#PHP"` yang awalnya jadi `"-php"` → menjadi `"php"`.

---

```php
$stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $slug);

if ($stmt->execute()) {
    $pesan_sukses = "Kategori <strong>" . htmlspecialchars($name) . "</strong> berhasil ditambahkan!";
} else {
    $pesan_error = "Gagal menambahkan kategori. Nama ini mungkin sudah ada.";
}
```
> Simpan ke database. Jika berhasil, isi `$pesan_sukses`. Jika gagal, isi `$pesan_error`.  
> Kegagalan paling umum: nama/slug yang sama sudah ada (karena kolom `slug` di database punya constraint `UNIQUE`).

---

```php
$sql = "SELECT categories.id, categories.name, categories.slug,
               COUNT(posts.id) AS jumlah_artikel
        FROM categories
        LEFT JOIN posts ON categories.id = posts.category_id
        GROUP BY categories.id, categories.name, categories.slug
        ORDER BY categories.name ASC";
```
> Query lebih kompleks — selain ambil data kategori, juga **menghitung jumlah artikel** di setiap kategori.
>
> - `COUNT(posts.id)` → hitung berapa baris `posts` yang cocok dengan kategori ini. Hasilnya bisa diakses sebagai `$cat['jumlah_artikel']`.
> - `LEFT JOIN posts ON categories.id = posts.category_id` → gabungkan dengan tabel posts.
> - `GROUP BY` → **wajib** dipakai bersama `COUNT`. Tanpa `GROUP BY`, COUNT akan menghitung semua baris sekaligus, bukan per kategori.

---

```php
<?php if ($pesan_sukses): ?>
    <div class="alert alert-success">
        <?= $pesan_sukses ?>
    </div>
<?php endif; ?>
```
> Tampilkan pesan hanya jika variabelnya berisi sesuatu (tidak kosong).  
> Perhatikan: `$pesan_sukses` tidak di-escape dengan `htmlspecialchars()` karena kontennya sudah kita buat sendiri dan sengaja mengandung tag `<strong>`. Namun nama kategori di dalamnya sudah di-escape sebelumnya.

---

```html
<code><?= htmlspecialchars($cat['slug']) ?></code>
```
> Tag `<code>` adalah elemen HTML semantik untuk menampilkan teks berformat kode — biasanya font *monospace* (semua huruf lebar sama). Dipakai di sini agar slug terlihat berbeda dari teks biasa.

---

## 📄 `delete.php` — Hapus Kategori

**Fungsi:** Menerima ID dari form, hapus kategori, redirect kembali.  
**Efek penting:** Artikel yang memakai kategori ini **tidak ikut terhapus** — hanya `category_id`-nya berubah menjadi `NULL`.

### Penjelasan Kode Baris per Baris

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/categories/index.php");
    exit;
}
```
> Pengecekan standar: tolak akses langsung via URL.

---

```php
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: /blogspot/categories/index.php");
    exit;
}
```
> Validasi ID: harus angka positif yang valid.

---

```php
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```
> Hapus kategori dari tabel `categories`.

---

### Efek Cascade pada Tabel Posts

```sql
-- Di blogspot_db.sql, tabel posts punya definisi ini:
FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
```

Baris `ON DELETE SET NULL` adalah instruksi ke MySQL:  
*"Jika baris di tabel `categories` dihapus, maka semua baris di `posts` yang punya `category_id` sama, ubah nilainya menjadi `NULL`."*

**Artinya:**
- Kategori "Teknologi" (id=1) punya 5 artikel.
- Kategori "Teknologi" dihapus.
- Kelima artikel itu **tetap ada** di database, tapi `category_id`-nya berubah dari `1` menjadi `NULL`.
- Di tampilan, artikel-artikel itu akan masuk badge "Umum".

Ini adalah perilaku yang **disengaja** — menghapus kategori tidak boleh menghilangkan konten artikel.

---

## 🔄 Alur Lengkap Modul Kategori

```
TAMBAH KATEGORI:
  index.php (isi form, klik Simpan)
      → [POST ke index.php itu sendiri]
      → Proses: buat slug, INSERT ke DB
      → Tampilkan ulang index.php + pesan sukses

HAPUS KATEGORI:
  index.php (klik tombol 🗑️, konfirmasi)
      → delete.php [POST]
      → DELETE dari DB (posts.category_id jadi NULL otomatis)
      → redirect kembali ke index.php
```