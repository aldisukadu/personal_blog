# 📁 Folder: `layout/`

Folder ini menyimpan **kerangka tampilan** yang dipakai berulang di semua halaman. Tujuannya adalah prinsip **DRY** (*Don't Repeat Yourself*) — daripada menulis navbar yang sama di 10 file berbeda, cukup tulis sekali di sini lalu panggil dengan `include`.

---

## Bagaimana `include` Bekerja?

Bayangkan seperti **gunting dan tempel**. Saat PHP menemukan `include 'layout/header.php'`, ia akan mengambil seluruh isi file tersebut dan "menempelkannya" tepat di posisi itu.

```
Hasil akhir HTML yang diterima browser =
  isi header.php
  + isi halaman utama (index.php, posts/index.php, dll)
  + isi footer.php
```

---

## 📄 `header.php`

**Fungsi:** Kerangka **bagian atas** setiap halaman.  
**Isi:** Tag `<!DOCTYPE html>` sampai dengan `<main>` — termasuk `<head>`, Bootstrap CSS, link CSS kustom, dan Navbar.  
**Cara pakai:** Taruh di baris paling atas setelah kode PHP, sebelum HTML konten.

```php
// Contoh di posts/index.php:
$pageTitle = "Dashboard";   // ← set dulu sebelum include
include '../layout/header.php';
```

---

### Penjelasan Kode Baris per Baris

```html
<!DOCTYPE html>
<html lang="id">
```
> `<!DOCTYPE html>` memberitahu browser bahwa ini adalah dokumen HTML5 (bukan HTML versi lama).  
> `lang="id"` memberitahu browser dan search engine bahwa bahasa halaman ini adalah Indonesia. Ini penting untuk aksesibilitas dan SEO.

---

```html
<meta charset="UTF-8">
```
> Mengatur encoding karakter dokumen. Tanpa ini, browser mungkin salah membaca karakter seperti `é`, `ñ`, atau huruf Jawa.

---

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```
> Tag **wajib** agar Bootstrap bisa bekerja responsif di HP.
> - `width=device-width` → lebar halaman = lebar layar perangkat.
> - `initial-scale=1.0` → zoom awal 100% (tidak diperbesar/diperkecil).

---

```php
<title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Solo Blog</title>
```
> Mengisi judul tab browser secara dinamis.
>
> - `<?= ... ?>` adalah singkatan dari `<?php echo ... ?>`.
> - `isset($pageTitle)` → cek apakah variabel `$pageTitle` sudah didefinisikan di file yang memanggil `include`. Jika sebuah file lupa set `$pageTitle`, tidak akan muncul error.
> - Operator ternary `? :` → format: `kondisi ? nilai_jika_true : nilai_jika_false`.
>
> Contoh hasil:
> - Jika `$pageTitle = "Dashboard"` → judul tab: **"Dashboard - Solo Blog"**
> - Jika `$pageTitle` tidak ada → judul tab: **"Solo Blog"**

---

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/blogspot/assets/style.css" rel="stylesheet">
```
> Dua file CSS dimuat berurutan.
> - Bootstrap dimuat **lebih dulu** sebagai fondasi styling.
> - `style.css` kita dimuat **sesudahnya** agar bisa menimpa rule Bootstrap jika perlu. CSS bekerja secara *cascade* (bertumpuk) — yang belakangan menang.
> - `/blogspot/assets/style.css` menggunakan **jalur absolut** (dimulai `/`) agar berfungsi dari folder manapun (root maupun subfolder seperti `posts/`).

---

```html
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
```
> Komponen Navbar dari Bootstrap.
> - `navbar` → class dasar Bootstrap untuk bilah navigasi.
> - `navbar-expand-lg` → di layar `lg` (≥992px) navbar tampil penuh; di bawah itu menjadi menu hamburger.
> - `navbar-dark` → warna teks link di navbar menjadi putih (cocok untuk background gelap).
> - `bg-dark` → background navbar menjadi hitam/abu sangat gelap.

---

```html
<button class="navbar-toggler" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarMenu">
```
> Tombol hamburger (☰) yang muncul di tampilan mobile.
> - `data-bs-toggle="collapse"` → atribut data Bootstrap yang mengaktifkan animasi collapse (buka/tutup).
> - `data-bs-target="#navbarMenu"` → ID elemen yang akan dibuka/tutup saat tombol ini ditekan.

---

```html
<div class="collapse navbar-collapse" id="navbarMenu">
    <ul class="navbar-nav ms-auto">
```
> Pembungkus menu yang bisa disembunyikan di mobile.
> - `id="navbarMenu"` → harus cocok dengan `data-bs-target` di tombol hamburger di atas.
> - `ms-auto` → `margin-start: auto` = dorong seluruh menu ke kanan.

---

```html
<main class="py-4">
```
> Tag `<main>` adalah elemen HTML semantik untuk konten utama halaman.  
> **Penting:** Tag ini **tidak ditutup** di `header.php`. Ia ditutup di `footer.php`. Konten setiap halaman masuk di antara keduanya.  
> `py-4` = padding atas dan bawah sebesar 4 unit Bootstrap (1.5rem ≈ 24px).

---

## 📄 `footer.php`

**Fungsi:** Kerangka **bagian bawah** setiap halaman.  
**Isi:** Penutup `</main>`, elemen footer HTML, dan tag Bootstrap JavaScript.  
**Cara pakai:** Taruh di baris paling bawah setiap file.

```php
// Contoh:
include '../layout/footer.php';
?>
```

---

### Penjelasan Kode Baris per Baris

```html
</main>
```
> Menutup tag `<main>` yang **dibuka** di `header.php`. Keduanya selalu berpasangan.

---

```html
<footer class="bg-dark text-white text-center py-3 mt-auto">
```
> - `bg-dark` → background hitam.
> - `text-white` → teks putih.
> - `text-center` → rata tengah.
> - `py-3` → padding atas-bawah 3 unit.
> - `mt-auto` → bersama Flexbox di `body` (dari `style.css`), ini mendorong footer selalu ke bagian paling bawah halaman.

---

```php
<p class="mb-0">© <?= date('Y') ?> Solo Blog...</p>
```
> `date('Y')` → fungsi PHP untuk mengambil **tahun saat ini** secara otomatis. Tidak perlu update manual tiap tahun.  
> `mb-0` → hapus margin bawah paragraf (agar footer tidak terlalu tinggi).

---

```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```
> Bootstrap JavaScript dimuat di **bawah halaman** (sebelum `</body>`), bukan di `<head>`.  
> Kenapa? Agar browser menyelesaikan render HTML lebih dulu sebelum mengunduh JS — halaman terasa lebih cepat dimuat.  
> `bootstrap.bundle` = sudah termasuk Popper.js di dalamnya (dibutuhkan untuk dropdown dan tooltip).

---

## 💡 Pola Standar Setiap File PHP

Semua file yang punya tampilan mengikuti pola ini:

```php
<?php
require_once '../config/database.php';   // 1. Koneksi database
$pageTitle = "Judul Halaman";           // 2. Set judul tab
// ... kode PHP lainnya ...
include '../layout/header.php';         // 3. Pasang header
?>

<!-- HTML konten halaman di sini -->

<?php include '../layout/footer.php'; ?> // 4. Pasang footer
```