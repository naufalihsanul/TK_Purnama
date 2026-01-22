<?php
require_once 'includes/db.php';

// Fetch all settings
$settings = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $settings[$row['site_key']] = $row['site_value'];
}

// Fetch Teachers
$featuredGuru = $pdo->query("SELECT * FROM teachers WHERE is_featured = 1 LIMIT 1")->fetch();
$otherGuru = $pdo->query("SELECT * FROM teachers WHERE is_featured = 0 ORDER BY created_at DESC")->fetchAll();

// Fetch Gallery
$galleryItems = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();

// Fetch Testimonials
$testiItems = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();

// Fetch FAQ
$faqItems = $pdo->query("SELECT * FROM faq ORDER BY created_at DESC")->fetchAll();

// Fetch Stats
$statsItems = $pdo->query("SELECT * FROM stats ORDER BY id ASC")->fetchAll();

// Fetch Vision & Mission
$visionItem = $pdo->query("SELECT * FROM vision_mission WHERE type = 'vision' LIMIT 1")->fetch();
$missionItems = $pdo->query("SELECT * FROM vision_mission WHERE type = 'mission' ORDER BY created_at ASC")->fetchAll();

// Fetch Programs
$programItems = $pdo->query("SELECT * FROM programs ORDER BY created_at ASC")->fetchAll();

// Fetch Facilities
$facilityItems = $pdo->query("SELECT * FROM facilities ORDER BY created_at ASC")->fetchAll();

// Helper to get setting with fallback
function s($key, $fallback = '') {
    global $settings;
    return $settings[$key] ?? $fallback;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo s('site_name', 'TK Purnama'); ?> - <?php echo s('site_tagline', 'Mataram'); ?></title>
    <meta name="description" content="<?php echo s('site_description'); ?>">
    <link rel="icon" type="image/png" href="assets/images/logo.png">

    <!-- Fonts: Geist (via CDN) or Inter as fallback -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        // Radix styled colors (simulated)
                        gray: {
                            100: '#f8f9fa', // App background
                            200: '#e9ecef', // Subtle borders
                            300: '#dee2e6', // UI borders
                            400: '#ced4da',
                            500: '#adb5bd',
                            600: '#868e96',
                            700: '#495057',
                            800: '#343a40', // Headings
                            900: '#212529', // Body text
                        },
                        // Brand Colors
                        primary: {
                            DEFAULT: '#FFC107', // Amber/Yellow
                            foreground: '#4a3b00',
                            hover: '#FFD54F'
                        },
                        accent: {
                            DEFAULT: '#3B82F6', // Blue
                            foreground: '#ffffff'
                        }
                    },
                    boxShadow: {
                        'radix-sm': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        'radix-md': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'radix-lg': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                        'radix-xl': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                    },
                    borderRadius: {
                        'radix': '0.75rem', // 12px
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        body {
            font-feature-settings: "ss01", "cv01", "cv11";
            -webkit-font-smoothing: antialiased;
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .hero-canvas-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            background: linear-gradient(to bottom, #0f172a, #1e293b);
        }

        /* Marquee */
        .marquee {
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 25s linear infinite;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        /* Fluid Gallery */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 200px;
            gap: 1rem;
        }

        .gallery-grid .gallery-item:nth-child(1) {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery-grid .gallery-item:nth-child(4) {
            grid-column: span 2;
        }

        .gallery-grid .gallery-item:nth-child(7) {
            grid-column: span 2;
            grid-row: span 2;
        }

        .gallery-grid .gallery-item:nth-child(10) {
            grid-column: span 2;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-auto-rows: 150px;
            }

            .gallery-grid .gallery-item:nth-child(1),
            .gallery-grid .gallery-item:nth-child(7) {
                grid-column: span 2;
                grid-row: span 1;
            }

            .gallery-grid .gallery-item:nth-child(4),
            .gallery-grid .gallery-item:nth-child(10) {
                grid-column: span 1;
            }
        }

        /* Teacher Card */
        .teacher-card {
            transition: all 0.3s ease;
        }

        .teacher-card:hover {
            transform: translateY(-8px);
        }

        .teacher-card img {
            transition: transform 0.5s ease;
        }

        .teacher-card:hover img {
            transform: scale(1.05);
        }

        /* FAQ Smooth */
        .faq-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.3s ease;
        }

        .faq-item.active .faq-content {
            max-height: 500px;
            padding-bottom: 20px;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* Dark Mode */
        .dark body {
            background: #0f172a;
            color: #e2e8f0;
        }

        .dark .bg-white {
            background: #1e293b !important;
        }

        .dark .bg-gray-50 {
            background: #0f172a !important;
        }

        .dark .text-slate-900,
        .dark .text-slate-800 {
            color: #f1f5f9 !important;
        }

        .dark .text-slate-500,
        .dark .text-slate-600 {
            color: #94a3b8 !important;
        }

        .dark .border-gray-200,
        .dark .border-gray-100 {
            border-color: #334155 !important;
        }

        .dark .glass {
            background: rgba(30, 41, 59, 0.8);
            border-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body
    class="bg-[#fcfcfc] text-slate-800 font-sans overflow-x-hidden selection:bg-primary selection:text-primary-foreground">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full z-50 px-4 py-4 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto">
            <div class="glass rounded-full px-6 py-3 flex items-center justify-between shadow-radix-sm">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div
                        class="relative w-10 h-10 rounded-full overflow-hidden border border-gray-200 group-hover:scale-105 transition-transform">
                        <img src="assets/images/logo.png" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-bold text-lg tracking-tight text-slate-900 leading-none group-hover:text-primary transition-colors"><?php echo s('site_name', 'TK PURNAMA'); ?></span>
                        <span class="text-[0.6rem] font-medium text-slate-500 uppercase tracking-widest"><?php echo s('site_tagline', 'Mataram'); ?></span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div
                    class="hidden md:flex items-center gap-1 bg-gray-100/50 p-1 rounded-full border border-gray-200/50">
                    <a href="#beranda"
                        class="px-4 py-1.5 text-sm font-medium text-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:shadow-sm transition-all">Beranda</a>
                    <a href="#tentang"
                        class="px-4 py-1.5 text-sm font-medium text-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:shadow-sm transition-all">Tentang</a>
                    <a href="#program"
                        class="px-4 py-1.5 text-sm font-medium text-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:shadow-sm transition-all">Program</a>
                    <a href="#galeri"
                        class="px-4 py-1.5 text-sm font-medium text-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:shadow-sm transition-all">Galeri</a>
                    <a href="#ppdb"
                        class="px-4 py-1.5 text-sm font-medium text-slate-600 rounded-full hover:bg-white hover:text-slate-900 hover:shadow-sm transition-all">PPDB</a>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="dark-toggle" aria-label="Toggle Dark Mode"
                        class="p-2 text-slate-600 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-lucide="sun" class="w-5 h-5 dark-icon-sun"></i>
                        <i data-lucide="moon" class="w-5 h-5 hidden dark-icon-moon"></i>
                    </button>
                    <a href="https://wa.me/<?php echo s('whatsapp_number', '6281353642194'); ?>" target="_blank"
                        class="hidden md:flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-primary-foreground bg-primary rounded-full hover:bg-primary-hover shadow-radix-sm hover:shadow-radix-md hover:-translate-y-0.5 transition-all">
                        <span>Daftar Sekarang</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                    <button id="menu-btn" aria-label="Open Menu"
                        class="md:hidden p-2 text-slate-600 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu"
                class="hidden absolute top-24 left-4 right-4 bg-white/95 backdrop-blur-xl rounded-2xl shadow-radix-xl border border-gray-200 p-4 flex-col gap-2 md:hidden origin-top transform transition-all duration-300">
                <a href="#beranda"
                    class="p-3 font-semibold text-slate-600 hover:bg-slate-50 rounded-xl flex justify-between items-center group">
                    Beranda <i data-lucide="chevron-right"
                        class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </a>
                <a href="#tentang"
                    class="p-3 font-semibold text-slate-600 hover:bg-slate-50 rounded-xl flex justify-between items-center group">
                    Tentang <i data-lucide="chevron-right"
                        class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </a>
                <a href="#program"
                    class="p-3 font-semibold text-slate-600 hover:bg-slate-50 rounded-xl flex justify-between items-center group">
                    Program <i data-lucide="chevron-right"
                        class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </a>
                <a href="#galeri"
                    class="p-3 font-semibold text-slate-600 hover:bg-slate-50 rounded-xl flex justify-between items-center group">
                    Galeri <i data-lucide="chevron-right"
                        class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </a>
                <a href="https://wa.me/<?php echo s('whatsapp_number', '6281353642194'); ?>"
                    class="p-3 font-bold text-center text-primary-foreground bg-primary rounded-xl mt-2 shadow-md">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="<?php echo s('hero_bg', 'assets/images/hero-bg.png'); ?>" alt="TK Purnama" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-slate-900/70"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center text-white py-20" data-aos="fade-up">
            <div class="mb-8 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-sm font-medium tracking-wide text-slate-200">Pendaftaran 2025/2026 Dibuka</span>
            </div>

            <h1 class="font-extrabold text-4xl md:text-6xl lg:text-7xl mb-6 tracking-tight leading-tight">
                <?php echo s('hero_title', 'Generasi Cerdas & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-yellow-200 to-primary">Berkarakter Islami</span>'); ?>
            </h1>

            <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
                <?php echo s('hero_subtitle', "Membangun pondasi masa depan ananda dengan pendidikan modern, fasilitas lengkap, dan penanaman nilai-nilai Al-Qur'an sejak dini."); ?>
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="https://wa.me/<?php echo s('whatsapp_number', '6281353642194'); ?>" target="_blank"
                    class="w-full sm:w-auto px-8 py-4 bg-primary text-primary-foreground font-bold rounded-full hover:bg-primary-hover shadow-lg transition-all flex items-center justify-center gap-2">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    <span>Daftar via WhatsApp</span>
                </a>
                <a href="#program"
                    class="w-full sm:w-auto px-8 py-4 bg-white/10 text-white font-bold rounded-full border border-white/20 hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="sparkles" class="w-5 h-5 text-yellow-300"></i>
                    <span>Lihat Unggulan</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section (Bento Style) -->
    <section class="py-10 -mt-10 relative z-20">
        <div class="max-w-6xl mx-auto px-4">
            <div
                class="bg-white rounded-[2rem] shadow-radix-xl border border-gray-200 p-8 md:p-12 grid grid-cols-2 lg:grid-cols-4 gap-8 mx-4 md:mx-0">
                <?php if (empty($statsItems)): ?>
                    <div class="col-span-full text-center py-4 text-slate-400">Belum ada statistik.</div>
                <?php else: ?>
                    <?php foreach ($statsItems as $index => $stat): ?>
                    <div class="text-center <?php echo $index < 3 ? 'md:border-r' : ''; ?> border-gray-100 last:border-0 reveal-stat" data-aos="fade-up"
                        data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <p class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight counter"
                            data-target="<?php echo $stat['stat_value']; ?>">0</p>
                        <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2"><?php echo $stat['stat_label']; ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Visi Misi (Radix Cards) -->
    <section id="tentang" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-20 section-header">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Tentang Kami</span>
                <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 mt-2 tracking-tight">Visi & Misi Sekolah
                </h2>
                <div class="w-20 h-1.5 bg-primary/30 mx-auto rounded-full mt-4"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Vision Card -->
                <div class="lg:col-span-7 bg-slate-900 text-white rounded-radix p-10 relative overflow-hidden shadow-radix-lg hover:shadow-radix-xl transition-shadow group gsap-fade-up"
                    data-aos="fade-right">
                    <div
                        class="absolute top-0 right-0 w-80 h-80 bg-primary/20 rounded-full blur-[100px] -mr-20 -mt-20 group-hover:bg-primary/30 transition-colors duration-700">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center text-primary mb-8 border border-white/10">
                            <i data-lucide="eye" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Visi Utama</h3>
                        <p class="text-xl md:text-2xl leading-relaxed text-slate-200 font-light">"<?php echo $visionItem['content'] ?? 'Mewujudkan generasi yang berkarakter Islami.'; ?>"</p>
                    </div>
                </div>

                <!-- Mission Cards (Stacked) -->
                <div class="lg:col-span-5 flex flex-col gap-6" data-aos="fade-left">
                    <?php if (empty($missionItems)): ?>
                        <div class="bg-white p-8 rounded-radix border border-gray-200 text-slate-400">Belum ada misi.</div>
                    <?php else: ?>
                        <?php foreach ($missionItems as $m): ?>
                        <div
                            class="bg-white p-8 rounded-radix border border-gray-200 shadow-sm hover:border-primary/50 transition-colors cursor-default gsap-fade-up">
                            <div class="flex items-start gap-5">
                                <div
                                    class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 shrink-0">
                                    <i data-lucide="<?php echo $m['icon'] ?: 'check'; ?>" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-slate-900 mb-2"><?php echo $m['content']; ?></h4>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Tim Pengajar -->
    <section id="tim-guru" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 section-header">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Tim Kami</span>
                <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 mt-2 tracking-tight">Tenaga Pengajar
                    Profesional</h2>
                <p class="text-slate-500 mt-3 max-w-lg mx-auto">Didukung oleh guru-guru berpengalaman dan berdedikasi
                    tinggi</p>
            </div>

            <!-- Kepala Sekolah Featured -->
            <?php if ($featuredGuru): ?>
            <div class="mb-12">
                <div class="max-w-md mx-auto teacher-card bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white text-center shadow-2xl relative overflow-hidden"
                    data-aos="fade-up">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-primary/20 rounded-full blur-[60px]"></div>
                    <div class="relative z-10">
                        <div
                            class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-br from-primary to-yellow-300 p-1 shadow-lg overflow-hidden">
                            <img src="<?php echo $featuredGuru['photo']; ?>" alt="<?php echo $featuredGuru['name']; ?>"
                                class="w-full h-full rounded-full object-cover">
                        </div>
                        <span
                            class="inline-block px-4 py-1 bg-primary/20 text-primary text-xs font-bold uppercase tracking-wider rounded-full mb-3">Kepala
                            Sekolah</span>
                        <h3 class="text-2xl font-bold mb-1"><?php echo $featuredGuru['name']; ?></h3>
                        <p class="text-slate-400 text-sm"><?php echo $featuredGuru['role']; ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Grid Guru -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach ($otherGuru as $index => $guru): ?>
                <div class="teacher-card bg-gray-50 rounded-2xl p-6 text-center border border-gray-100 hover:shadow-lg hover:border-primary/30"
                    data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 p-0.5 overflow-hidden">
                        <img src="<?php echo $guru['photo']; ?>" alt="<?php echo $guru['name']; ?>"
                            class="w-full h-full rounded-full object-cover">
                    </div>
                    <h4 class="font-bold text-slate-800"><?php echo $guru['name']; ?></h4>
                    <p class="text-xs text-slate-500 mt-1"><?php echo $guru['role']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Facilities (Grid Cards) -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 section-header">
                <div>
                    <span class="text-primary font-bold tracking-wider uppercase text-sm">Fasilitas</span>
                    <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 mt-2 tracking-tight">Lingkungan
                        Belajar</h2>
                    <p class="text-slate-500 mt-2 max-w-md">Menunjang kenyamanan dan keamanan ananda selama di sekolah.
                    </p>
                </div>
                <a href="#galeri"
                    class="hidden md:flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">Lihat
                    Semua Galeri <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php if (empty($facilityItems)): ?>
                    <div class="col-span-full text-center py-10 text-slate-400">Belum ada fasilitas.</div>
                <?php else: ?>
                    <?php foreach ($facilityItems as $index => $f): ?>
                    <div class="group p-6 rounded-2xl bg-gray-50 hover:bg-white hover:shadow-radix-lg text-center transition-all border border-transparent hover:border-gray-100 gsap-scale-up cursor-pointer"
                        data-aos="zoom-in" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div
                            class="w-16 h-16 mx-auto bg-white rounded-2xl shadow-sm flex items-center justify-center text-amber-500 mb-4 group-hover:scale-110 transition-transform">
                            <i data-lucide="<?php echo $f['icon'] ?: 'building'; ?>" class="w-8 h-8"></i>
                        </div>
                        <h4 class="font-bold text-slate-800"><?php echo $f['name']; ?></h4>
                        <p class="text-xs text-slate-400 mt-1"><?php echo $f['description']; ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Program Unggulan (Detailed Cards) -->
    <section id="program" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(#64748b 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-16 section-header">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Kurikulum</span>
                <h2 class="font-extrabold text-3xl md:text-4xl mt-2 tracking-tight">Program Unggulan</h2>
                <div class="w-20 h-1.5 bg-primary/30 mx-auto rounded-full mt-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if (empty($programItems)): ?>
                    <div class="col-span-full text-center py-20 text-slate-400">Belum ada program unggulan.</div>
                <?php else: ?>
                    <?php foreach ($programItems as $index => $p): ?>
                    <div class="group bg-slate-800 rounded-radix p-8 border border-white/5 hover:border-primary/50 transition-all hover:-translate-y-2 gsap-fade-up"
                        data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div
                            class="w-14 h-14 bg-slate-700/50 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-primary-foreground transition-colors">
                            <i data-lucide="<?php echo $p['icon'] ?: 'star'; ?>" class="w-7 h-7"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4"><?php echo $p['title']; ?></h3>
                        <p class="text-slate-400 mb-6 leading-relaxed"><?php echo $p['description']; ?></p>
                        <?php if ($p['features']): ?>
                        <ul class="space-y-3">
                            <?php
                            $feats = explode(',', $p['features']);
                            foreach ($feats as $f):
                            ?>
                            <li class="flex items-center gap-3 text-sm text-slate-300"><i data-lucide="check-circle-2"
                                    class="w-4 h-4 text-green-400"></i> <?php echo trim($f); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Galeri Fluid Grid -->
    <section id="galeri" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16 section-header">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Dokumentasi</span>
                <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 mt-2 tracking-tight">Keseruan Kami</h2>
                <p class="text-slate-500 mt-3 max-w-lg mx-auto">Momen berharga aktivitas pembelajaran dan kegiatan seru
                    di TK Purnama</p>
            </div>

            <!-- Fluid Masonry Gallery (No Gaps) -->
            <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
                <?php if (empty($galleryItems)): ?>
                    <div class="col-span-full text-center py-20 text-slate-400">Belum ada foto galeri.</div>
                <?php else: ?>
                    <?php foreach ($galleryItems as $item): ?>
                        <?php 
                        $ar = $item['aspect_ratio'] ?? '4/5';
                        $ar_class = 'aspect-[4/5]';
                        if ($ar == '9/12') $ar_class = 'aspect-[9/12]';
                        if ($ar == '1/1') $ar_class = 'aspect-square';
                        if ($ar == '16/9') $ar_class = 'aspect-video';
                        if ($ar == 'video') $ar_class = 'aspect-[21/9]';
                        ?>
                        <div class="break-inside-avoid mb-4 rounded-2xl overflow-hidden shadow-xl group bg-slate-100 border border-gray-100">
                            <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['title']; ?>"
                                loading="lazy"
                                class="w-full <?php echo $ar_class; ?> object-cover hover:scale-110 transition-transform duration-700 block">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials (Cards) - Improved -->
    <section class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16 section-header">
                <span class="text-primary font-bold tracking-wider uppercase text-sm">Testimoni</span>
                <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 tracking-tight">Kata Wali Murid</h2>
                <p class="text-slate-500 mt-2">Apa kata mereka tentang TK Purnama</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if (empty($testiItems)): ?>
                    <div class="col-span-full text-center py-10 text-slate-400">Belum ada testimoni.</div>
                <?php else: ?>
                    <?php foreach ($testiItems as $index => $testi): ?>
                    <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border border-gray-100 relative gsap-fade-up hover:shadow-radix-lg transition-shadow"
                        data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                        <div class="flex gap-1 mb-4">
                            <?php for($i=0; $i<$testi['rating']; $i++): ?>
                                <i data-lucide="star" class="w-4 h-4 fill-yellow-400 text-yellow-400"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="text-slate-600 mb-6 leading-relaxed">"<?php echo $testi['message']; ?>"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center font-bold text-white text-lg shadow-md">
                                <?php if ($testi['photo']): ?>
                                    <img src="<?php echo $testi['photo']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo strtoupper(substr($testi['name'], 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900"><?php echo $testi['name']; ?></h4>
                                <p class="text-xs text-slate-500"><?php echo $testi['role']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FAQ (Accordion) -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-3xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="font-extrabold text-3xl md:text-4xl text-slate-900 tracking-tight">FAQ</h2>
                <p class="text-slate-500 mt-2">Pertanyaan yang sering diajukan</p>
            </div>

            <div class="space-y-4" data-aos="fade-up">
                <?php if (empty($faqItems)): ?>
                    <!-- Default FAQs if database empty -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm faq-item">
                        <button class="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                            <span>Berapa biaya pendaftaran?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="px-5 text-slate-600">Biayanya sangat terjangkau. Silakan hubungi admin kami untuk detail lebih lanjut.</div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($faqItems as $index => $faq): ?>
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm faq-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <button
                            class="w-full flex items-center justify-between p-5 text-left font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                            <span><?php echo $faq['question']; ?></span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform faq-icon"></i>
                        </button>
                        <div class="faq-content">
                            <div class="px-5 text-slate-600 border-t border-gray-100 pt-4">
                                <?php echo nl2br($faq['answer']); ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- PPDB Card -->
    <section id="ppdb" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-16 text-white overflow-hidden relative shadow-2xl skew-y-0 transform transition-transform hover:scale-[1.01] duration-500"
                data-aos="zoom-in">
                <!-- Decorative Gradients -->
                <div
                    class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/20 rounded-full blur-[120px] pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px] pointer-events-none">
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
                    <div>
                        <div
                            class="inline-block px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sm font-semibold mb-6">
                            Tahun Ajaran 2025/2026</div>
                        <h2 class="text-4xl md:text-5xl font-extrabold mb-6 tracking-tight">Bergabung Bersama <br><span
                                class="text-primary">Keluarga Besar Kami</span></h2>
                        <p class="text-slate-300 text-lg mb-8 leading-relaxed max-w-lg">Berikan pendidikan terbaik untuk
                            buah hati Anda. Kuota terbatas untuk memastikan kualitas pembelajaran yang optimal.</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="https://wa.me/<?php echo s('whatsapp_number', '6281353642194'); ?>" target="_blank"
                                class="flex items-center justify-center gap-3 px-8 py-4 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all hover:shadow-lg hover:-translate-y-1">
                                <i data-lucide="message-circle" class="w-6 h-6"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase opacity-90 tracking-wider">Chat
                                        WhatsApp</span>
                                    <span class="text-lg leading-none">Admin TK</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="relative hidden lg:block">
                        <img src="<?php echo s('ppdb_poster', 'assets/images/flyer.png'); ?>" alt="Poster Pendaftaran"
                            class="rounded-2xl shadow-2xl border-4 border-white/10 rotate-3 hover:rotate-0 transition-all duration-500 max-w-xs mx-auto hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="h-[400px] w-full relative grayscale hover:grayscale-0 transition-all duration-700">
        <iframe
            src="<?php echo s('google_maps_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126463.47787594138!2d116.0460788!3d-8.5830803!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dcc40b72f66e70d%3A0x3039d80b220d0c0!2sMataram%2C%20Kota%20Mataram%2C%20Nusa%20Tenggara%20Bar.!5e0!3m2!1sid!2sid!4v1704559200000!5m2!1sid!2sid'); ?>"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        <div
            class="absolute bottom-8 left-8 right-8 md:auto md:left-8 md:right-auto md:w-80 bg-white p-6 rounded-2xl shadow-xl">
            <h4 class="font-bold text-lg mb-2 flex items-center gap-2"><i data-lucide="map-pin"
                    class="w-5 h-5 text-primary"></i> Lokasi Kami</h4>
            <p class="text-slate-500 text-sm mb-4 leading-relaxed"><?php echo s('address', 'Mataram, Nusa Tenggara Barat'); ?></p>
            <a href="<?php echo s('google_maps_link', '#'); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all">
                <i data-lucide="navigation" class="w-4 h-4 text-primary"></i>
                Petunjuk Arah
            </a>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-white pt-16 pb-8 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="assets/images/logo.png" alt="Logo"
                            class="w-10 h-10 rounded-full grayscale hover:grayscale-0 transition-all">
                        <span class="font-bold text-xl tracking-tight text-slate-900"><?php echo s('site_name', 'TK PURNAMA'); ?></span>
                    </div>
                    <p class="text-slate-500 max-w-sm leading-relaxed mb-6"><?php echo s('site_description'); ?></p>
                    <div class="flex gap-3">
                        <a href="<?php echo s('instagram_url', '#'); ?>"
                            class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-slate-600 hover:bg-pink-500 hover:text-white transition-all"><i
                                data-lucide="instagram" class="w-5 h-5"></i></a>
                        <a href="<?php echo s('facebook_url', '#'); ?>"
                            class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-slate-600 hover:bg-blue-600 hover:text-white transition-all"><i
                                data-lucide="facebook" class="w-5 h-5"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Menu</h4>
                    <ul class="space-y-3 text-slate-500">
                        <li><a href="#beranda" class="hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-primary transition-colors">Tentang</a></li>
                        <li><a href="#program" class="hover:text-primary transition-colors">Program</a></li>
                        <li><a href="#ppdb" class="hover:text-primary transition-colors">PPDB</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Kontak</h4>
                    <ul class="space-y-3 text-slate-500">
                        <li class="flex items-start gap-3"><i data-lucide="map-pin"
                                class="w-5 h-5 text-primary shrink-0"></i> <?php echo s('address'); ?></li>
                        <li class="flex items-center gap-3"><i data-lucide="phone"
                                class="w-5 h-5 text-primary shrink-0"></i> <?php echo s('whatsapp_number'); ?></li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-400">
                <p>&copy; <?php echo date('Y'); ?> <?php echo s('site_name'); ?>. All rights reserved.</p>
                <div class="mt-4 md:mt-0 flex gap-6">
                    <a href="#" class="hover:text-slate-600">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-600">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/<?php echo s('whatsapp_number', '6281353642194'); ?>" target="_blank"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-[#25D366] rounded-full flex items-center justify-center text-white shadow-lg shadow-green-500/30 hover:scale-110 hover:-translate-y-1 transition-all">
        <i data-lucide="message-circle" class="w-7 h-7 fill-current"></i>
    </a>

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('py-2');
                navbar.classList.remove('py-4');
            } else {
                navbar.classList.add('py-4');
                navbar.classList.remove('py-2');
            }
        });

        // Simple counter animation
        const stats = document.querySelectorAll('.counter');
        const animateCounter = (stat) => {
            const target = +stat.dataset.target;
            const suffix = target > 100 ? '+' : '';
            let current = 0;
            const increment = target / 30;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.innerText = target + suffix;
                    clearInterval(timer);
                } else {
                    stat.innerText = Math.floor(current) + suffix;
                }
            }, 50);
        };

        // Trigger counter on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        });
        stats.forEach(stat => observer.observe(stat));

        // FAQ Toggle
        document.querySelectorAll('.faq-item button').forEach(button => {
            button.addEventListener('click', () => {
                const faqItem = button.parentElement;
                const wasActive = faqItem.classList.contains('active');
                document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
                if (!wasActive) faqItem.classList.add('active');
            });
        });

        // Mobile Menu
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('flex');
        });

        // Dark Mode Toggle
        const darkToggle = document.getElementById('dark-toggle');
        const html = document.documentElement;

        if (localStorage.getItem('darkMode') === 'true') {
            html.classList.add('dark');
        }

        darkToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));

            const sunIcon = darkToggle.querySelector('.dark-icon-sun');
            const moonIcon = darkToggle.querySelector('.dark-icon-moon');
            if (html.classList.contains('dark')) {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            } else {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            }
        });

        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic',
        });

    </script>
</body>

</html>
