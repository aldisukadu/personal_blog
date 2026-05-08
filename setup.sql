-- 1. Tabel Kategori
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Tabel Posts (Relasi ke Categories)
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Insert data contoh
INSERT INTO categories (name, slug) VALUES 
('Teknologi', 'teknologi'),
('Lifestyle', 'lifestyle'),
('Tutorial', 'tutorial');

INSERT INTO posts (category_id, title, content) VALUES 
(1, 'Memulai dengan PHP Native', 'PHP Native adalah PHP tanpa framework. Kita belajar dasar-dasar PHP sebelum menggunakan framework.'),
(2, 'Tips Produktif di Rumah', 'Bekerja dari rumah memerlukan kedisiplinan. Berikut tips agar tetap produktif...'),
(3, 'Belajar Database Relasional', 'Database relasional adalah konsep menyimpan data dengan tabel yang terhubung satu sama lain.');
