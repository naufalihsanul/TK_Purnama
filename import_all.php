<?php
require_once 'includes/db.php';

try {
    // Drop and recreate tables to ensure schema matches exactly
    $pdo->exec("DROP TABLE IF EXISTS users, settings, teachers, gallery, testimonials, faq, programs, facilities, vision_mission, stats");
    
    // Re-create all tables
    $pdo->exec("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        site_key VARCHAR(50) NOT NULL UNIQUE,
        site_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE teachers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100),
        photo VARCHAR(255),
        is_featured BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_path VARCHAR(255) NOT NULL,
        title VARCHAR(100),
        aspect_ratio VARCHAR(20) DEFAULT '4/5',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100),
        photo VARCHAR(255),
        rating INT DEFAULT 5,
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE faq (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE programs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        icon VARCHAR(50),
        features TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE facilities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description VARCHAR(255),
        icon VARCHAR(50),
        color VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE vision_mission (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type ENUM('vision', 'mission') NOT NULL,
        title VARCHAR(255),
        content TEXT,
        icon VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE stats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        stat_label VARCHAR(50) NOT NULL,
        stat_value VARCHAR(20) NOT NULL,
        icon VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 1. Insert Default Admin
    $pass = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', '$pass')");

    // 2. Insert Settings
    $settings = [
        ['site_name', 'TK PURNAMA'],
        ['site_tagline', 'Mataram'],
        ['site_description', 'TK Purnama Mataram - Mewujudkan generasi berkarakter Islami, Mandiri, Sehat, Cerdas, dan Kreatif. Daftar sekarang!'],
        ['hero_title', 'Generasi Cerdas & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-yellow-200 to-primary">Berkarakter Islami</span>'],
        ['hero_subtitle', 'Membangun pondasi masa depan ananda dengan pendidikan modern, fasilitas lengkap, dan penanaman nilai-nilai Al-Qur\'an sejak dini.'],
        ['hero_bg', 'assets/images/hero-bg.png'],
        ['ppdb_poster', 'assets/images/flyer.png'],
        ['whatsapp_number', '6281353642194'],
        ['instagram_url', '#'],
        ['facebook_url', '#'],
        ['google_maps_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15782.3456789!2d116.123456!3d-8.56789!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOMKwMzQnMDQuNCJTIDExNiw3JzA0LjQiRQ!5e0!3m2!1sen!2sid!4v1234567890'],
        ['address', 'Mataram, Nusa Tenggara Barat'],
        ['google_maps_link', '#']
    ];
    $stmt = $pdo->prepare("INSERT INTO settings (site_key, site_value) VALUES (?, ?)");
    foreach ($settings as $s) $stmt->execute($s);

    // 3. Insert Teachers
    $teachers = [
        ['Ust. Fadli', 'Kepala Sekolah', 'assets/guru/Ust. Fadli.jpeg', 1],
        ['Bunda Hj. Wati', 'Guru Pengajar', 'assets/guru/Bunda Hj. Wati.jpeg', 0],
        ['Bunda Jannah', 'Guru Pengajar', 'assets/guru/Bunda Jannah.jpeg', 0],
        ['Bunda Lita', 'Guru Pengajar', 'assets/guru/Bunda Lita.jpeg', 0],
        ['Yanda zidan', 'Guru Pengajar', 'assets/guru/Yanda zidan.jpeg', 0],
    ];
    $stmt = $pdo->prepare("INSERT INTO teachers (name, role, photo, is_featured) VALUES (?, ?, ?, ?)");
    foreach ($teachers as $t) $stmt->execute($t);

    // 4. Insert Gallery (Pure Fluid Aspect Ratios)
    $gallery = [
        ['Kegiatan Belajar 1', '9/12', 'assets/galeri/WhatsApp Image 2026-01-08 at 07.32.57.jpeg'],
        ['Bermain Bersama', '1/1', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.48.jpeg'],
        ['Kegiatan Outbound', 'video', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.53.jpeg'],
        ['Makan Bersama', '4/5', 'assets/galeri/WhatsApp Image 2026-01-08 at 07.32.58.jpeg'],
        ['Kreativitas Anak', '4/5', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.54.jpeg'],
        ['Taman Bermain', '9/12', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.55.jpeg'],
        ['Belajar Agama', '1/1', 'assets/galeri/WhatsApp Image 2026-01-08 at 07.32.59.jpeg'],
        ['Kegiatan Seni', '16/9', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.56.jpeg'],
        ['Upacara Bendera', '4/5', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.57.jpeg'],
        ['Olahraga Pagi', '1/1', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.53.05.jpeg'],
        ['Eksplorasi Alam', '9/12', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.53.07.jpeg'],
        ['Keceriaan Anak', '4/5', 'assets/galeri/WhatsApp Image 2026-01-06 at 23.52.54 (1).jpeg'],
    ];
    $stmt = $pdo->prepare("INSERT INTO gallery (title, aspect_ratio, image_path) VALUES (?, ?, ?)");
    foreach ($gallery as $g) $stmt->execute($g);

    // 5. Insert Testimonials
    $testimonials = [
        ['Bu Anisa', 'Wali Murid Kelas B', '', 5, 'Alhamdulillah, anak saya jadi lebih mandiri dan rajin sholat sejak di TK Purnama. Gurunya sangat sabar dan perhatian.'],
        ['Pak Rizky', 'Wali Murid Kelas A', '', 5, 'Lingkungannya asri, anak-anak belajar sambil bermain. Field trip-nya juga edukatif banget. Recommended!'],
        ['Bu Maya', 'Wali Murid Kelas B', '', 5, 'Biayanya terjangkau tapi kualitasnya luar biasa. Anak saya sudah bisa baca Iqro\' dan hafal beberapa surat pendek.'],
    ];
    $stmt = $pdo->prepare("INSERT INTO testimonials (name, role, photo, rating, message) VALUES (?, ?, ?, ?, ?)");
    foreach ($testimonials as $testi) $stmt->execute($testi);

    // 6. Insert FAQ
    $faqs = [
        ['Kapan pendaftaran siswa baru dibuka?', 'Pendaftaran biasanya dibuka mulai bulan Januari hingga Juli setiap tahunnya.'],
        ['Bagaimana kurikulum yang digunakan di TK Purnama?', 'Kami menggunakan Kurikulum Merdeka yang diintegrasikan dengan nilai-nilai Islami.'],
        ['Berapa usia minimal untuk masuk TK?', 'Untuk jenjang TK A minimal berusia 4 tahun pada bulan Juli tahun berjalan.'],
        ['Apakah ada kegiatan ekstrakurikuler?', 'Ya, kami menyediakan berbagai ekstrakurikuler seperti Seni Lukis dan Science Kids.'],
    ];
    $stmt = $pdo->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
    foreach ($faqs as $f) $stmt->execute($f);

    // 7. Insert Programs, Facilities, Vision/Mission, Stats
    $programs = [
        ['Tahfidz Ceria', 'Metode menghafal surat pendek dan doa harian dengan cara yang menyenangkan.', 'book-open-check', 'Juz Amma Pilihan, Hadits Arba\'in'],
        ['Science Kids', 'Eksperimen sains sederhana untuk melatih logika dan rasa ingin tahu anak.', 'rocket', 'Praktikum Sederhana, Pengenalan Alam'],
        ['Art & Craft', 'Mengasah motorik halus dan kreativitas melalui kegiatan kerajinan tangan.', 'palette', 'Daur Ulang Kreatif, Melukis'],
    ];
    $stmt = $pdo->prepare("INSERT INTO programs (title, description, icon, features) VALUES (?, ?, ?, ?)");
    foreach ($programs as $p) $stmt->execute($p);

    $facilities = [
        ['Gedung Sendiri', 'Aman & Nyaman', 'building-2'],
        ['Taman Bermain', 'Indoor & Outdoor', 'gamepad-2'],
        ['Perpustakaan', 'Buku Lengkap', 'library'],
        ['Ruang UKS', 'Kesehatan Terjaga', 'heart'],
    ];
    $stmt = $pdo->prepare("INSERT INTO facilities (name, description, icon) VALUES (?, ?, ?)");
    foreach ($facilities as $f) $stmt->execute($f);

    $pdo->exec("INSERT INTO vision_mission (type, title, content) VALUES ('vision', 'Visi Utama', 'Mewujudkan generasi yang berkarakter Islami, Mandiri, Sehat, Cerdas, dan Kreatif.')");
    $missions = [
        ['Karakter Islami', 'Menanamkan adab, akhlak mulia, dan hafalan doa harian.', 'heart-handshake'],
        ['Cerdas & Kreatif', 'Metode pembelajaran aktif yang merangsang daya pikir kritis.', 'brain'],
        ['Peduli Alam', 'Mengajarkan cinta lingkungan melalui kegiatan eksplorasi alam.', 'leaf'],
    ];
    $stmt = $pdo->prepare("INSERT INTO vision_mission (type, title, content, icon) VALUES ('mission', ?, ?, ?)");
    foreach ($missions as $m) $stmt->execute($m);

    $stats = [
        ['Tahun Pengalaman', '15'],
        ['Alumni Sukses', '500'],
        ['Akreditasi Unggul', 'A'],
        ['Guru Profesional', '10'],
    ];
    $stmt = $pdo->prepare("INSERT INTO stats (stat_label, stat_value) VALUES (?, ?)");
    foreach ($stats as $s) $stmt->execute($s);

    echo "<h1>🚀 RE-IMPORT LENGKAP & FLUID BERHASIL!</h1>";
    echo "<p>Sekarang Galeri Anda menggunakan sistem <b>Masonry Layout</b>. Foto dengan ukuran beda-beda akan otomatis tersusun rapat tanpa sela putih.</p>";
    echo "<br><a href='index.php' style='padding: 15px 30px; background: #FFC107; color: #4a3b00; text-decoration: none; border-radius: 10px; font-weight: 800;'>Check Website Fluid Anda</a>";

} catch (PDOException $e) {
    echo "<h2 style='color:red;'>Error:</h2> " . $e->getMessage();
}
?>
