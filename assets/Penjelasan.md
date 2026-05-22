# 📁 Folder: `assets/`

Folder ini menyimpan semua **file statis** — file yang tidak diproses PHP, melainkan langsung dikirim ke browser apa adanya. Dalam proyek ini berisi satu file CSS kustom.

> **Kenapa dipisahkan?**  
> CSS yang ditulis langsung di dalam file HTML/PHP (disebut *inline style* atau tag `<style>`) membuat kode sulit dikelola — bayangkan harus mengubah warna tombol di 10 file berbeda. Dengan file CSS terpisah, cukup ubah **satu tempat**, semua halaman ikut berubah.

---

## 📄 `style.css`

**Fungsi:** Menyimpan semua aturan tampilan kustom untuk Solo Blog.  
**Dipanggil oleh:** `layout/header.php` via tag `<link>`.  
**Posisinya:** Di-load **setelah** Bootstrap agar bisa menimpa (override) style Bootstrap bila perlu.

```html
<!-- Di dalam layout/header.php -->
<link href="https://cdn.jsdelivr.net/.../bootstrap.min.css" rel="stylesheet">
<link href="/blogspot/assets/style.css" rel="stylesheet">  ← file ini
```

Urutan penting: CSS yang dimuat **belakangan** akan menang jika ada aturan yang sama.

---

### Penjelasan Kode Baris per Baris

#### Seksi 1 — Global / Body

```css
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: #f8f9fa;
}
```
> Mengatur `body` agar berperilaku seperti kotak yang bisa diregangkan secara vertikal.
>
> - `display: flex` → aktifkan Flexbox. Flexbox adalah sistem tata letak CSS modern yang membuat pengaturan posisi elemen jadi lebih mudah.
> - `flex-direction: column` → susun anak-anak elemen secara vertikal (dari atas ke bawah): Navbar → Konten → Footer.
> - `min-height: 100vh` → tinggi minimum body harus setinggi 100% viewport (layar). Ini memastikan footer tidak "menggantung" di tengah layar saat konten sedikit.
> - `background-color: #f8f9fa` → warna abu-abu sangat muda sebagai latar belakang halaman. `#f8f9fa` adalah warna `gray-100` milik Bootstrap.

```css
main {
    flex: 1;
}
```
> `flex: 1` artinya elemen `<main>` akan **mengambil semua ruang kosong yang tersisa** di antara navbar dan footer. Ini adalah trik untuk membuat footer selalu di bawah meski konten halaman pendek.

---

#### Seksi 2 — Navbar

```css
.navbar-brand {
    font-weight: 700;
    font-size: 1.4rem;
    letter-spacing: 0.5px;
}
```
> Mengatur tampilan nama blog di pojok kiri navbar.
> - `font-weight: 700` → tebal (bold). Skala Bootstrap: 400=normal, 700=bold.
> - `font-size: 1.4rem` → ukuran font. `rem` = relatif terhadap ukuran font root HTML (biasanya 16px). Jadi 1.4rem ≈ 22px.
> - `letter-spacing: 0.5px` → sedikit jarak antar huruf agar terlihat lebih "bernapas".

```css
.navbar .btn {
    border-radius: 20px;
}
```
> Selector `.navbar .btn` artinya: "tombol (`.btn`) yang berada **di dalam** navbar (`.navbar`)".  
> `border-radius: 20px` → membuat sudut tombol sangat membulat — efek *pill button*.

---

#### Seksi 3 — Kartu Artikel

```css
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
}
```
> - `transition` → animasi perubahan properti. Format: `nama-properti durasi fungsi-waktu`. Di sini dua properti dianimasikan sekaligus: `transform` dan `box-shadow`, keduanya selama `0.2s` dengan kurva `ease` (mulai cepat, akhir melambat).
> - `border: none` → hapus garis tepi default kartu Bootstrap.
> - `box-shadow: 0 1px 4px rgba(0,0,0,0.08)` → bayangan tipis. Format: `offset-x offset-y blur warna`. `rgba(0,0,0,0.08)` = hitam dengan transparansi 8%.

```css
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}
```
> `:hover` = aturan ini aktif saat kursor mouse berada di atas elemen.
> - `transform: translateY(-4px)` → geser kartu ke **atas** 4 piksel. Nilai negatif = ke atas, positif = ke bawah. Kombinasi dengan `transition` di atas menghasilkan efek mengambang saat di-hover.
> - `box-shadow` yang lebih besar → bayangan jadi lebih gelap dan menyebar, memperkuat ilusi kartu terangkat.

---

#### Seksi 4 — Badge Kategori

```css
.badge-kategori {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.4px;
    padding: 4px 8px;
    border-radius: 4px;
}
```
> Class kustom untuk badge label kategori di kartu artikel.
> - `padding: 4px 8px` → format dua nilai: `atas-bawah kiri-kanan`. Jadi badge punya ruang napas 4px vertikal dan 8px horizontal.
> - `border-radius: 4px` → sudut sedikit membulat, tidak terlalu tajam, tidak terlalu bulat.

---

#### Seksi 5 — Konten Artikel

```css
.article-content {
    font-size: 1.1rem;
    line-height: 1.9;
    color: #333;
}
```
> Dipakai di `post.php` untuk membungkus isi artikel.
> - `font-size: 1.1rem` → sedikit lebih besar dari default agar nyaman dibaca.
> - `line-height: 1.9` → jarak antar baris. Nilai tanpa satuan = dikalikan dengan font-size. Standar keterbacaan artikel adalah antara 1.5–2.0.
> - `color: #333` → abu-abu sangat gelap, lebih lembut dari hitam murni (`#000`) untuk mengurangi kelelahan mata.

---

#### Seksi 6 — Tabel Dashboard

```css
.table td,
.table th {
    vertical-align: middle;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}
```
> Selector dipisah koma artinya aturan ini berlaku untuk **keduanya**: sel data (`td`) dan sel header (`th`).
> - `vertical-align: middle` → konten di dalam sel rata tengah secara vertikal. Penting agar tombol aksi tidak menempel di atas sel.
> - `padding-top/bottom` → jarak dalam sel, membuat baris tabel terasa lebih lapang.

---

#### Seksi 7 — Footer

```css
footer {
    margin-top: auto;
    font-size: 0.875rem;
}
```
> - `margin-top: auto` → bekerja bersama Flexbox di `body`. Artinya "dorong footer sejauh mungkin ke bawah". Ini adalah pasangan dari `flex: 1` di `main`.
> - `font-size: 0.875rem` → sedikit lebih kecil dari teks normal (0.875 × 16px ≈ 14px), lazim untuk teks footer.