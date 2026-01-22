CREATE DATABASE IF NOT EXISTS tk_purnama;
USE tk_purnama;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_key VARCHAR(50) NOT NULL UNIQUE,
    site_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    photo VARCHAR(255),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    title VARCHAR(100),
    layout_type VARCHAR(20) DEFAULT 'normal', -- normal, tall, wide
    aspect_ratio VARCHAR(20) DEFAULT '4/5', -- 4/5, 9/12, 1/1, etc.
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    photo VARCHAR(255),
    rating INT DEFAULT 5,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    features TEXT, -- JSON or comma separated
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    icon VARCHAR(50),
    color VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vision_mission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('vision', 'mission') NOT NULL,
    title VARCHAR(255),
    content TEXT,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stat_label VARCHAR(50) NOT NULL,
    stat_value VARCHAR(20) NOT NULL,
    icon VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Default Settings
INSERT INTO settings (site_key, site_value) VALUES 
('site_name', 'TK PURNAMA'),
('site_tagline', 'Mataram'),
('site_description', 'TK Purnama Mataram - Mewujudkan generasi berkarakter Islami, Mandiri, Sehat, Cerdas, dan Kreatif. Daftar sekarang!'),
('hero_title', 'Generasi Cerdas & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-yellow-200 to-primary">Berkarakter Islami</span>'),
('hero_subtitle', 'Membangun pondasi masa depan ananda dengan pendidikan modern, fasilitas lengkap, dan penanaman nilai-nilai Al-Qur\'an sejak dini.'),
('hero_bg', 'assets/images/hero-bg.png'),
('whatsapp_number', '6281353642194'),
('instagram_url', '#'),
('facebook_url', '#'),
('google_maps_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15782.3456789!2d116.123456!3d-8.56789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwMzQnMDQuNCJTIDExNiw3JzA0LjQiRQ!5e0!3m2!1sen!2sid!4v1234567890'),
('address', 'Jl. Pendidikan No. 1, Kota Mataram, NTB');
