<?php
require_once 'includes/header.php';

// Fetch counts for dashboard stats
$guruCount = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$galleryCount = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$testiCount = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
$faqCount = $pdo->query("SELECT COUNT(*) FROM faq")->fetchColumn();
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <i data-lucide="users-2" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-slate-500 text-[0.65rem] font-bold uppercase tracking-wider">Total Guru</p>
            <h3 class="text-2xl font-bold text-slate-800"><?php echo $guruCount; ?></h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <i data-lucide="image" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-slate-500 text-[0.65rem] font-bold uppercase tracking-wider">Galeri Foto</p>
            <h3 class="text-2xl font-bold text-slate-800"><?php echo $galleryCount; ?></h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <i data-lucide="message-square" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-slate-500 text-[0.65rem] font-bold uppercase tracking-wider">Testimoni</p>
            <h3 class="text-2xl font-bold text-slate-800"><?php echo $testiCount; ?></h3>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
            <i data-lucide="help-circle" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-slate-500 text-[0.65rem] font-bold uppercase tracking-wider">FAQ</p>
            <h3 class="text-2xl font-bold text-slate-800"><?php echo $faqCount; ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-slate-800">Quick Settings</h3>
            <a href="settings.php" class="text-xs font-bold text-amber-600 hover:text-amber-700">Manage All</a>
        </div>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <i data-lucide="globe" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-sm font-medium text-slate-600">Site Name</span>
                </div>
                <span class="text-sm font-bold text-slate-900"><?php echo getSetting('site_name'); ?></span>
            </div>
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <i data-lucide="phone" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-sm font-medium text-slate-600">WhatsApp</span>
                </div>
                <span class="text-sm font-bold text-slate-900"><?php echo getSetting('whatsapp_number'); ?></span>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-800 mb-6">Recent Activity</h3>
        <div class="space-y-6">
            <p class="text-sm text-slate-500 text-center py-8">No recent activity to show.</p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
