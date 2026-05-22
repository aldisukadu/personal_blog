# 📁 Folder: `config/`

Folder ini berisi **pengaturan dasar** aplikasi. Saat ini hanya ada satu file, tapi di proyek yang lebih besar folder ini bisa berisi pengaturan lain seperti konstanta aplikasi, konfigurasi email, dll.

---

## 📄 `database.php`

**Fungsi:** Menyimpan kode koneksi ke database MySQL.  
**Siapa yang memanggilnya:** Semua file PHP yang butuh mengambil atau menyimpan data.  
**Cara memanggilnya:** `require_once '../config/database.php';` (dari dalam subfolder) atau `require_once 'config/database.php';` (dari root).

---

### Penjelasan Kode Baris per Baris

```php
<?php
```
> Tag pembuka PHP. Semua kode PHP ditulis setelah tanda ini.

---

```php
$conn = mysqli_connect("localhost", "root", "", "blogspot_db");
```
> **`mysqli_connect()`** adalah fungsi bawaan PHP untuk membuka koneksi ke MySQL.  
> Empat parameternya:
> - `"localhost"` → alamat server database. Di komputer lokal selalu `localhost`.
> - `"root"` → nama pengguna MySQL. Default XAMPP/Laragon adalah `root`.
> - `""` → kata sandi MySQL. Default XAMPP kosong, Laragon bisa berbeda.
> - `"blogspot_db"` → nama database yang akan dipakai.
>
> Hasilnya disimpan di variabel `$conn`. Variabel ini yang akan dipakai di semua file lain untuk berkomunikasi dengan database.

---

```php
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
```
> **`mysqli_connect_errno()`** mengembalikan angka kode error. Jika koneksi sukses, nilainya `0` (artinya kondisi `if` tidak terpenuhi, kode di dalam tidak dijalankan).
>
> **`die()`** menghentikan seluruh eksekusi PHP sekarang juga dan menampilkan pesan. Dipakai di sini karena tidak ada gunanya melanjutkan jika database tidak bisa terhubung.
>
> **`mysqli_connect_error()`** mengembalikan pesan error dalam bentuk teks agar mudah dibaca saat debugging.
>
> Operator **`.`** (titik) di PHP berarti menggabungkan dua string.

---

```php
mysqli_set_charset($conn, "utf8mb4");
```
> Mengatur encoding karakter koneksi menjadi `utf8mb4`.
>
> Kenapa penting? Tanpa ini, karakter seperti huruf Indonesia beraksara khusus, atau bahkan emoji, bisa tersimpan sebagai karakter rusak (`â€`) di database.
>
> `utf8mb4` adalah versi lebih lengkap dari `utf8` biasa — mendukung semua karakter Unicode termasuk emoji.

---

### Kenapa `require_once`, bukan `include`?

| Fungsi | Perilaku jika file tidak ditemukan | Bisa dipanggil dua kali? |
|--------|-----------------------------------|--------------------------|
| `include` | Hanya peringatan, skrip lanjut | Ya |
| `require` | Error fatal, skrip berhenti | Ya |
| `require_once` | Error fatal, skrip berhenti | Tidak (dicek dulu) |

Koneksi database bersifat **wajib** dan **hanya perlu dibuka sekali** per request. Maka `require_once` adalah pilihan paling tepat.

---

### Alur Penggunaan di Seluruh Proyek

```
posts/index.php
    └── require_once '../config/database.php'
            └── $conn terbuat
                    └── mysqli_query($conn, "SELECT ...")
```

Semua file yang butuh database tinggal memanggil `require_once` lalu langsung memakai variabel `$conn`.