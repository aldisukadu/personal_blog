# 📁 Folder: `posts/`

Folder ini adalah **inti dari aplikasi** — semua urusan artikel (tulis, lihat, ubah, hapus) dikelola di sini. Folder ini menerapkan pola **CRUD** secara penuh.

---

## Apa itu CRUD?

| Huruf | Kepanjangan | File di folder ini | Perintah SQL |
|-------|-------------|-------------------|-------------|
| C | Create (Buat) | `create.php` + `store.php` | `INSERT` |
| R | Read (Baca) | `index.php` | `SELECT` |
| U | Update (Ubah) | `edit.php` + `update.php` | `UPDATE` |
| D | Delete (Hapus) | `delete.php` | `DELETE` |

---

## Kenapa Satu Aksi Butuh Dua File?

Untuk Create dan Update, ada **dua file berbeda**:

```
create.php  →  hanya tampilkan form (GET)
store.php   →  hanya proses data (POST, tidak ada tampilan)

edit.php    →  hanya tampilkan form berisi data lama (GET)
update.php  →  hanya proses perubahan (POST, tidak ada tampilan)
```

Ini adalah prinsip **Separation of Concerns** — satu file, satu tanggung jawab. File tampilan tidak campur dengan logika pemrosesan data.

---

## 📄 `index.php` — Dashboard Daftar Artikel

**Fungsi:** Menampilkan semua artikel dalam bentuk tabel. Ini adalah halaman kontrol penulis.  
**URL:** `http://localhost/blogspot/posts/index.php`

### Penjelasan Kode Baris per Baris

```php
require_once '../config/database.php';
```
> Muat koneksi database. `../` = naik satu folder ke atas dari `posts/` ke root `blogspot/`, lalu masuk ke `config/`.

---

```php
$sql = "SELECT posts.id, posts.title, posts.created_at,
               categories.name AS category_name
        FROM posts
        LEFT JOIN categories ON posts.category_id = categories.id
        ORDER BY posts.created_at DESC";
```
> Query SQL dengan **JOIN** — menggabungkan dua tabel sekaligus.
>
> - `LEFT JOIN categories ON posts.category_id = categories.id` → ambil data kategori yang cocok. Kata `LEFT` berarti: ambil **semua baris dari tabel kiri** (`posts`) meski tidak ada pasangannya di tabel kanan. Artikel tanpa kategori tetap muncul.
> - `categories.name AS category_name` → `AS` memberi nama alias pada kolom. Kita akses hasilnya sebagai `$post['category_name']`, bukan `$post['name']` (karena `name` terlalu umum).
> - `ORDER BY posts.created_at DESC` → urutkan dari yang terbaru. `DESC` = descending (menurun).

---

```php
$no = 1;
while ($post = mysqli_fetch_assoc($result)):
```
> - `$no = 1` → variabel penghitung nomor urut baris tabel. Ini bukan ID database, melainkan nomor tampilan (1, 2, 3...).
> - `mysqli_fetch_assoc()` → setiap kali dipanggil, mengambil **satu baris** data dari hasil query dan mengembalikannya sebagai array asosiatif. Ketika tidak ada baris lagi, mengembalikan `false` dan loop berhenti.

---

```html
<form action="/blogspot/posts/delete.php" method="POST"
      onsubmit="return confirm('Yakin ingin menghapus?')">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
</form>
```
> Tombol hapus menggunakan **form POST**, bukan link `<a href>`.  
> Kenapa? Karena link `href` berarti request GET — siapapun bisa menghapus artikel hanya dengan menebak URL seperti `delete.php?id=5`. POST lebih aman karena butuh form sungguhan.
>
> - `type="hidden"` → input tidak terlihat di halaman tapi datanya ikut terkirim bersama form.
> - `onsubmit="return confirm(...)"` → menampilkan kotak dialog konfirmasi. Jika user klik "Batal", `confirm()` mengembalikan `false` dan form tidak jadi dikirim.

---

## 📄 `create.php` — Form Tulis Artikel Baru

**Fungsi:** Menampilkan form kosong untuk menulis artikel.  
**Penting:** File ini **tidak menyimpan data** — hanya menampilkan form. Penyimpanan dilakukan `store.php`.

### Penjelasan Kode Baris per Baris

```php
$cat_result = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name ASC");
```
> Ambil semua kategori sebelum halaman dirender, untuk mengisi opsi dropdown `<select>`.

---

```html
<form action="/blogspot/posts/store.php" method="POST">
```
> - `action` → kemana data dikirim saat tombol submit ditekan.
> - `method="POST"` → data dikirim secara tersembunyi di **body** HTTP request, tidak tampak di URL. Cocok untuk data yang mengubah state (create/update/delete).

---

```html
<input type="text" id="title" name="title" class="form-control"
       placeholder="Masukkan judul..." required>
```
> - `name="title"` → nama ini jadi kunci di `$_POST`. Di `store.php`, data diambil dengan `$_POST['title']`.
> - `id="title"` → harus cocok dengan atribut `for` di `<label>` di atasnya. Ini membuat klik label = fokus ke input (meningkatkan aksesibilitas).
> - `required` → validasi bawaan browser — form tidak bisa dikirim jika field ini kosong.

---

```html
<select id="category_id" name="category_id" class="form-select">
    <option value="">-- Pilih Kategori (Opsional) --</option>
    <?php while ($cat = mysqli_fetch_assoc($cat_result)): ?>
        <option value="<?= $cat['id'] ?>">
            <?= htmlspecialchars($cat['name']) ?>
        </option>
    <?php endwhile; ?>
</select>
```
> Dropdown kategori diisi secara dinamis dari database menggunakan loop.  
> Opsi pertama bernilai `""` (string kosong) — di `store.php`, ini akan dikonversi menjadi `NULL` di database.

---

```html
<textarea name="content" rows="10" required></textarea>
```
> `<textarea>` berbeda dari `<input>` — ia tidak punya atribut `value`. Nilai awalnya ditulis **di antara** tag pembuka dan penutup. Inilah kenapa di `edit.php` ada `><?= htmlspecialchars($post['content']) ?></textarea>` dan bukan di atribut value.

---

## 📄 `store.php` — Proses Simpan Artikel

**Fungsi:** Menerima data dari `create.php`, validasi, simpan ke database, lalu redirect.  
**Tidak ada tampilan HTML** — hanya PHP murni.

### Penjelasan Kode Baris per Baris

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /blogspot/posts/create.php");
    exit;
}
```
> Pengaman pertama: tolak semua request yang bukan POST.  
> Jika seseorang mengetik langsung `store.php` di browser (itu request GET), mereka langsung diarahkan balik ke form.

---

```php
$title   = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
```
> - `$_POST['title']` → ambil data field `title` dari form.
> - `?? ''` → operator *null coalescing*. Artinya: "jika `$_POST['title']` tidak ada/null, pakai string kosong `''` sebagai default". Mencegah error `Undefined index`.
> - `trim()` → hapus spasi di awal dan akhir. Mencegah user menyimpan artikel yang isinya hanya spasi.

---

```php
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
```
> Logika tiga kemungkinan:
> 1. User memilih kategori → `$_POST['category_id']` = `"3"` (string angka) → `(int)` ubah jadi `3` → disimpan ke DB.
> 2. User tidak pilih kategori → `$_POST['category_id']` = `""` → `empty()` true → simpan `null` ke DB.
>
> `(int)` adalah *type casting* — memaksa nilai menjadi integer. Ini lapisan keamanan tambahan: nilai `"3; DROP TABLE posts"` akan menjadi `3` saja.

---

```php
$stmt = $conn->prepare(
    "INSERT INTO posts (title, content, category_id) VALUES (?, ?, ?)"
);
$stmt->bind_param("ssi", $title, $content, $category_id);
$stmt->execute();
```
> **Prepared Statement** — cara aman menyisipkan data ke database.
>
> **Cara kerja:**
> 1. `prepare()` → kirim *template* query ke MySQL. MySQL menyiapkan rencana eksekusinya. Tanda `?` adalah placeholder.
> 2. `bind_param("ssi", ...)` → isi placeholder dengan nilai nyata. `"ssi"` = string, string, integer. MySQL memperlakukan nilai ini sebagai **data murni**, bukan perintah SQL, sehingga SQL Injection tidak mungkin berhasil.
> 3. `execute()` → jalankan query yang sudah siap.
>
> **Contoh mengapa ini aman:**  
> Jika user mengetik `'; DROP TABLE posts; --` di form judul, Prepared Statement memperlakukannya sebagai teks biasa, bukan perintah SQL.

---

```php
if ($stmt->execute()) {
    header("Location: /blogspot/posts/index.php?status=created");
} else {
    header("Location: /blogspot/posts/create.php");
}
$stmt->close();
exit;
```
> - `header("Location: ...")` → instruksi HTTP untuk redirect browser ke URL lain.
> - `exit` → **wajib** dipanggil setelah `header()`. Tanpa ini, PHP terus mengeksekusi kode di bawahnya meski browser sudah diarahkan.
> - `$stmt->close()` → bebaskan sumber daya memori. Kebiasaan baik, terutama jika ada banyak query dalam satu script.

---

## 📄 `edit.php` — Form Edit Artikel

**Fungsi:** Sama seperti `create.php`, tapi form sudah terisi data lama dari database.

### Penjelasan Perbedaan Penting dari `create.php`

```php
$id = (int) $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
```
> ID diambil dari URL (`$_GET`) karena halaman ini diakses via link seperti `edit.php?id=5`.  
> Data artikel diambil dulu dari database, lalu dipakai untuk mengisi form.

---

```html
<input type="hidden" name="id" value="<?= $post['id'] ?>">
```
> Input tersembunyi yang menyimpan ID artikel. Ini dikirim ke `update.php` agar tahu **artikel mana** yang harus diupdate. Tanpa ini, `update.php` tidak tahu harus mengubah baris mana.

---

```html
<input type="text" name="title"
       value="<?= htmlspecialchars($post['title']) ?>"
       required>
```
> Atribut `value` diisi dengan data lama dari database. User melihat judul lama dan bisa langsung mengeditnya.  
> `htmlspecialchars()` pada `value` penting — jika judul mengandung tanda kutip `"`, tanpa ini bisa merusak HTML: `value="Judul "Keren""`.

---

```html
<option value="<?= $cat['id'] ?>"
    <?= ($post['category_id'] == $cat['id']) ? 'selected' : '' ?>>
```
> Cara menandai opsi dropdown yang sudah dipilih sebelumnya.  
> Untuk setiap opsi kategori, dibandingkan ID-nya dengan `category_id` artikel. Jika cocok, tambahkan atribut `selected` agar opsi itu tampil terpilih.

---

## 📄 `update.php` — Proses Simpan Perubahan

**Fungsi:** Mirip `store.php` tapi menggunakan perintah `UPDATE` bukan `INSERT`.

### Perbedaan Utama dari `store.php`

```php
$id = (int) ($_POST['id'] ?? 0);
```
> ID ikut diterima dari form (via input hidden di `edit.php`).

---

```php
$stmt = $conn->prepare(
    "UPDATE posts SET title = ?, content = ?, category_id = ? WHERE id = ?"
);
$stmt->bind_param("ssii", $title, $content, $category_id, $id);
```
> Perintah SQL `UPDATE` mengubah baris yang **sudah ada**.  
> `WHERE id = ?` adalah klausa yang **wajib ada** — tanpanya, semua artikel di tabel akan berubah sekaligus!  
> `"ssii"` = string, string, integer, integer (urutan harus sama dengan urutan `?` di query).

---

## 📄 `delete.php` — Proses Hapus Artikel

**Fungsi:** Menerima ID dari form, hapus artikel, redirect kembali.

### Penjelasan Kode Baris per Baris

```php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { ... }
$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) { ... }
```
> Dua lapis validasi sebelum menghapus apapun:
> 1. Pastikan request adalah POST (bukan GET/URL langsung).
> 2. Pastikan ID valid (lebih besar dari 0).

---

```php
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```
> Perintah `DELETE FROM posts WHERE id = ?` menghapus tepat **satu baris** yang ID-nya cocok.  
> Tetap menggunakan Prepared Statement meski DELETE sederhana — konsistensi keamanan.

---

## 🔄 Alur Lengkap CRUD

```
TAMBAH ARTIKEL:
  create.php (tampil form) → [user isi form, klik Simpan]
      → store.php (validasi + INSERT) → posts/index.php

EDIT ARTIKEL:
  index.php (klik tombol ✏️) → edit.php?id=5 (tampil form terisi)
      → [user ubah, klik Simpan] → update.php (UPDATE) → posts/index.php

HAPUS ARTIKEL:
  index.php (klik tombol 🗑️, konfirmasi) → delete.php (DELETE) → posts/index.php
```