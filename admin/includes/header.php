<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TK Purnama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-link.active {
            background-color: #fbbf24;
            color: #4a3b00;
        }
        .sidebar-link:not(.active) {
            color: #64748b;
        }
        .sidebar-link:not(.active):hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Mobile Sidebar Drawer -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 md:hidden hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" id="mobile-sidebar-backdrop"></div>
        
        <!-- Sidebar Content -->
        <aside class="absolute top-0 left-0 w-64 bg-white h-full shadow-2xl flex flex-col transform transition-transform duration-300 -translate-x-full" id="mobile-sidebar-content">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="../assets/images/logo.png" alt="Logo" class="w-8 h-8 rounded-full">
                    <h1 class="font-bold text-slate-800 text-sm">TK PURNAMA</h1>
                </div>
                <button id="close-mobile-sidebar" class="p-1 text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <!-- Dashboard -->
                <a href="index.php" class="sidebar-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                
                <div class="pt-4 pb-2 px-4 text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">Utama</div>
                <a href="settings.php" class="sidebar-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="settings" class="w-5 h-5"></i> Pengaturan
                </a>
                <a href="hero.php" class="sidebar-link <?php echo $current_page == 'hero.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="image" class="w-5 h-5"></i> Hero & Poster
                </a>
                <a href="guru.php" class="sidebar-link <?php echo $current_page == 'guru.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="users-2" class="w-5 h-5"></i> Tenaga Pengajar
                </a>
                <a href="gallery.php" class="sidebar-link <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="image" class="w-5 h-5"></i> Galeri
                </a>

                <div class="pt-4 pb-2 px-4 text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">Lainnya</div>
                <a href="programs.php" class="sidebar-link <?php echo $current_page == 'programs.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="rocket" class="w-5 h-5"></i> Program
                </a>
                <a href="facilities.php" class="sidebar-link <?php echo $current_page == 'facilities.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="layout" class="w-5 h-5"></i> Fasilitas
                </a>
                <a href="vision_mission.php" class="sidebar-link <?php echo $current_page == 'vision_mission.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="target" class="w-5 h-5"></i> Visi Misi
                </a>
                <a href="testimonials.php" class="sidebar-link <?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="message-square" class="w-5 h-5"></i> Testimoni
                </a>
                <a href="faq.php" class="sidebar-link <?php echo $current_page == 'faq.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="help-circle" class="w-5 h-5"></i> FAQ
                </a>
                <a href="profile.php" class="sidebar-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                    <i data-lucide="user" class="w-5 h-5"></i> Profil
                </a>
                <div class="pt-4 px-4 border-t border-slate-50 mt-4">
                    <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-red-500 hover:bg-red-50 transition-all">
                        <i data-lucide="log-out" class="w-5 h-5"></i> Keluar
                    </a>
                </div>
            </nav>
        </aside>
    </div>

    <!-- Sidebar (Desktop) -->
    <aside class="w-64 bg-white border-r border-slate-200 hidden md:flex flex-col sticky top-0 h-screen shrink-0">
        <div class="p-6 border-b border-slate-100 flex items-center gap-3">
            <img src="../assets/images/logo.png" alt="Logo" class="w-8 h-8 rounded-full border border-amber-200">
            <div>
                <h1 class="font-bold text-slate-800 leading-none">TK PURNAMA</h1>
                <p class="text-[0.6rem] font-bold text-amber-600 uppercase tracking-widest mt-1">Admin Panel</p>
            </div>
        </div>
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="index.php" class="sidebar-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>
            
            <div class="pt-4 pb-2 px-4 text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Konten Utama</div>
            
            <a href="settings.php" class="sidebar-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Pengaturan Umum
            </a>
            <a href="hero.php" class="sidebar-link <?php echo $current_page == 'hero.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="image" class="w-5 h-5"></i>
                Hero & Poster
            </a>
            <a href="guru.php" class="sidebar-link <?php echo $current_page == 'guru.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="users-2" class="w-5 h-5"></i>
                Tenaga Pengajar
            </a>
            <a href="gallery.php" class="sidebar-link <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="image" class="w-5 h-5"></i>
                Galeri Foto
            </a>
            
            <div class="pt-4 pb-2 px-4 text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Informasi</div>
            
            <a href="programs.php" class="sidebar-link <?php echo $current_page == 'programs.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="rocket" class="w-5 h-5"></i>
                Program Unggulan
            </a>
            <a href="facilities.php" class="sidebar-link <?php echo $current_page == 'facilities.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="layout" class="w-5 h-5"></i>
                Fasilitas
            </a>
            <a href="vision_mission.php" class="sidebar-link <?php echo $current_page == 'vision_mission.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="target" class="w-5 h-5"></i>
                Visi & Misi
            </a>
            <a href="stats.php" class="sidebar-link <?php echo $current_page == 'stats.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                Statistik
            </a>
            <a href="testimonials.php" class="sidebar-link <?php echo $current_page == 'testimonials.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                Testimoni
            </a>
            <a href="faq.php" class="sidebar-link <?php echo $current_page == 'faq.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
                FAQ
            </a>
            
            <div class="pt-4 pb-2 px-4 text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest">Akun</div>
            <a href="profile.php" class="sidebar-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all group">
                <i data-lucide="user" class="w-5 h-5"></i>
                Profil & Password
            </a>
        </nav>
        
        <div class="p-4 border-t border-slate-100">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-red-500 hover:bg-red-50 transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2">
                <button id="open-mobile-sidebar" class="md:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-lg">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-sm font-semibold text-slate-600">
                    <?php
                    $titles = [
                        'index.php' => 'Dashboard Overview',
                        'settings.php' => 'General Settings',
                        'hero.php' => 'Hero Section & Poster',
                        'guru.php' => 'Manajemen Guru',
                        'gallery.php' => 'Galeri Foto',
                        'programs.php' => 'Program Unggulan',
                        'facilities.php' => 'Fasilitas Sekolah',
                        'vision_mission.php' => 'Visi & Misi',
                        'stats.php' => 'Statistik Sekolah',
                        'testimonials.php' => 'Testimoni Wali Murid',
                        'faq.php' => 'Frequently Asked Questions',
                        'profile.php' => 'Profil Administrator'
                    ];
                    echo $titles[$current_page] ?? 'Admin Panel';
                    ?>
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-900 leading-none"><?php echo $_SESSION['admin_username']; ?></p>
                    <p class="text-[0.6rem] text-slate-500 mt-1 uppercase font-semibold">Administrator</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm">
                    <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                </div>
            </div>
        </header>

        <!-- Scrollable Area -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
