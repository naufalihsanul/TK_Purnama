<?php
require_once 'includes/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Text updates
    if (isset($_POST['hero_title'])) {
        $stmt = $pdo->prepare("INSERT INTO settings (site_key, site_value) VALUES ('hero_title', ?) ON DUPLICATE KEY UPDATE site_value = ?");
        $stmt->execute([$_POST['hero_title'], $_POST['hero_title']]);
    }
    if (isset($_POST['hero_subtitle'])) {
        $stmt = $pdo->prepare("INSERT INTO settings (site_key, site_value) VALUES ('hero_subtitle', ?) ON DUPLICATE KEY UPDATE site_value = ?");
        $stmt->execute([$_POST['hero_subtitle'], $_POST['hero_subtitle']]);
    }

    // Handle Hero Background upload
    if (!empty($_FILES['hero_bg']['name'])) {
        $target_dir = "../assets/images/";
        $new_filename = "hero-bg-" . time() . "." . pathinfo($_FILES["hero_bg"]["name"], PATHINFO_EXTENSION);
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["hero_bg"]["tmp_name"], $target_file)) {
            $path = "assets/images/" . $new_filename;
            $stmt = $pdo->prepare("INSERT INTO settings (site_key, site_value) VALUES ('hero_bg', ?) ON DUPLICATE KEY UPDATE site_value = ?");
            $stmt->execute([$path, $path]);
        }
    }
    
    $message = 'Hero section updated successfully!';
}

$settings = [];
$stmt = $pdo->query("SELECT * FROM settings WHERE site_key IN ('hero_title', 'hero_subtitle', 'hero_bg')");
while ($row = $stmt->fetch()) {
    $settings[$row['site_key']] = $row['site_value'];
}
?>

<div class="max-w-4xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Hero & Poster</h1>
        <p class="text-slate-500 mt-1">Ubah tampilan utama dan poster pendaftaran</p>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <div class="grid grid-cols-1 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Hero Title (Support HTML)</label>
                <textarea name="hero_title" rows="2" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $settings['hero_title'] ?? ''; ?></textarea>
                <p class="text-[0.7rem] text-slate-400 italic">Gunakan &lt;br&gt; untuk baris baru atau &lt;span class="..."&gt; untuk warna khusus.</p>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Hero Subtitle</label>
                <textarea name="hero_subtitle" rows="3" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $settings['hero_subtitle'] ?? ''; ?></textarea>
            </div>
            
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Background Image</label>
                <?php if (isset($settings['hero_bg']) && $settings['hero_bg']): ?>
                    <div class="relative w-full h-48 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100">
                        <img src="../<?php echo $settings['hero_bg']; ?>" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/20"></div>
                    </div>
                <?php endif; ?>
                <input type="file" name="hero_bg" accept="image/*"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
