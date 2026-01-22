<?php
require_once 'includes/header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['stats'] as $id => $data) {
        $stmt = $pdo->prepare("UPDATE stats SET stat_value = ?, stat_label = ? WHERE id = ?");
        $stmt->execute([$data['value'], $data['label'], $id]);
    }
    $message = 'Statistics updated successfully!';
}

$stats = $pdo->query("SELECT * FROM stats ORDER BY id ASC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Statistik Sekolah</h1>
        <p class="text-slate-500 mt-1">Kelola angka-angka pencapaian di halaman depan</p>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($stats as $index => $s): ?>
                <div class="space-y-4 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-[0.6rem] font-bold"><?php echo $index + 1; ?></span>
                        <h3 class="font-bold text-slate-800 text-sm italic">Counter Item</h3>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Label Teks</label>
                        <input type="text" name="stats[<?php echo $s['id']; ?>][label]" value="<?php echo $s['stat_label']; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Angka (Tanpa +)</label>
                        <input type="text" name="stats[<?php echo $s['id']; ?>][value]" value="<?php echo $s['stat_value']; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 border-t border-slate-100 pt-8 flex justify-end">
            <button type="submit" 
                class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
