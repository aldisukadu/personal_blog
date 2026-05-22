-- ============================================================
-- FILE: blogspot_db.sql
-- FUNGSI: Script SQL untuk membuat database dan tabel
-- CARA PAKAI:
--   1. Buka phpMyAdmin di browser: http://localhost/phpmyadmin
--   2. Klik tab "SQL" di bagian atas
--   3. Copy-paste seluruh isi file ini
--   4. Klik tombol "Go" / "Eksekusi"
-- ============================================================

-- Buat database baru bernama blogspot_db
-- IF NOT EXISTS = tidak error jika database sudah ada
CREATE DATABASE IF NOT EXISTS blogspot_db
CHARACTER SET utf8mb4          -- Mendukung semua karakter termasuk emoji
COLLATE utf8mb4_unicode_ci;    -- Aturan pengurutan teks

-- Pilih database yang akan dipakai
USE blogspot_db;

-- ============================================================
-- TABEL 1: categories
-- Menyimpan kategori artikel
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id   INT PRIMARY KEY AUTO_INCREMENT,  -- ID unik, otomatis bertambah
    name VARCHAR(100) NOT NULL,           -- Nama kategori, wajib diisi
    slug VARCHAR(100) NOT NULL UNIQUE     -- Versi URL (unik, tidak boleh sama)
);

-- ============================================================
-- TABEL 2: posts
-- Menyimpan artikel, terhubung ke tabel categories
-- ============================================================
CREATE TABLE IF NOT EXISTS posts (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,                           -- Boleh NULL (tidak wajib pilih kategori)
    title       VARCHAR(200) NOT NULL,
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Otomatis isi waktu saat INSERT
    
    -- FOREIGN KEY = hubungan ke tabel lain
    -- ON DELETE SET NULL = jika kategori dihapus, category_id artikel jadi NULL
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ============================================================
-- DATA AWAL (OPSIONAL) — Untuk langsung punya data percobaan
-- ============================================================

-- Masukkan beberapa kategori contoh
INSERT INTO categories (name, slug) VALUES
    ('Teknologi', 'teknologi'),
    ('Lifestyle', 'lifestyle'),
    ('Tutorial', 'tutorial');

-- Masukkan satu artikel contoh
INSERT INTO posts (category_id, title, content) VALUES
    (3, 'Selamat Datang di Blog Saya', 
     'Halo! Ini adalah artikel pertama di blog ini.\n\nBlog ini dibuat menggunakan PHP Native dan Bootstrap 5 sebagai proyek belajar.\n\nSemoga bermanfaat!');
