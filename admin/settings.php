<?php
require_once 'includes/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $pdo->prepare("INSERT INTO settings (site_key, site_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE site_value = ?");
        $stmt->execute([$key, $value, $value]);
    }
    $message = 'Settings updated successfully!';
}

$settings = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) {
    $settings[$row['site_key']] = $row['site_value'];
}
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">General Settings</h1>
            <p class="text-slate-500 mt-1">Manage your website's main information</p>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 flex items-center gap-2 border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Site Name</label>
                <input type="text" name="site_name" value="<?php echo $settings['site_name'] ?? ''; ?>" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Tagline / Location</label>
                <input type="text" name="site_tagline" value="<?php echo $settings['site_tagline'] ?? ''; ?>" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Meta Description</label>
                <textarea name="site_description" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $settings['site_description'] ?? ''; ?></textarea>
            </div>
            
            <div class="md:col-span-2 border-t border-slate-100 pt-6 mt-2">
                <h3 class="font-bold text-slate-800 mb-4">Contact & Social Media</h3>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">WhatsApp Number (e.g. 6281...)</label>
                <input type="text" name="whatsapp_number" value="<?php echo $settings['whatsapp_number'] ?? ''; ?>" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Instagram URL</label>
                <input type="text" name="instagram_url" value="<?php echo $settings['instagram_url'] ?? ''; ?>" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Address</label>
                <input type="text" name="address" value="<?php echo $settings['address'] ?? ''; ?>" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Google Maps Embed URL</label>
                <textarea name="google_maps_url" rows="2" placeholder="Paste the src attribute from iframe embed code"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $settings['google_maps_url'] ?? ''; ?></textarea>
            </div>
            <div class="md:col-span-2 space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Google Maps Link (Direct/Navigation)</label>
                <input type="text" name="google_maps_link" value="<?php echo $settings['google_maps_link'] ?? ''; ?>" placeholder="https://maps.google.com/..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                Save All Changes
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
