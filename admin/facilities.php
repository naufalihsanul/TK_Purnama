<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $icon = $_POST['icon'] ?? 'building';

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO facilities (name, description, icon) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $icon]);
        $message = 'Facility added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE facilities SET name = ?, description = ?, icon = ? WHERE id = ?");
        $stmt->execute([$name, $description, $icon, $id]);
        $message = 'Facility updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM facilities WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: facilities.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM facilities WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$facilities = $pdo->query("SELECT * FROM facilities ORDER BY created_at DESC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Fasilitas Sekolah</h1>
            <p class="text-slate-500 mt-1">Kelola daftar fasilitas yang tersedia</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Fasilitas
            </a>
        <?php else: ?>
            <a href="facilities.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Batal
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'Facility deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($facilities as $f): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center relative group">
                    <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors">
                        <i data-lucide="<?php echo $f['icon']; ?>" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm"><?php echo $f['name']; ?></h3>
                    <p class="text-[0.65rem] text-slate-400 mt-1 uppercase tracking-widest"><?php echo $f['description']; ?></p>
                    <div class="flex items-center justify-center gap-2 mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="?action=edit&id=<?php echo $f['id']; ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        </a>
                        <a href="?action=delete&id=<?php echo $f['id']; ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus fasilitas ini?')">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <form action="" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Nama Fasilitas</label>
                    <input type="text" name="name" value="<?php echo $edit_item['name'] ?? ''; ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Keterangan Singkat</label>
                    <input type="text" name="description" value="<?php echo $edit_item['description'] ?? ''; ?>" required placeholder="Contoh: Aman & Nyaman"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Icon (Nama Icon Lucide, e.g. building-2, gamepad-2, library, heart)</label>
                    <input type="text" name="icon" value="<?php echo $edit_item['icon'] ?? 'building'; ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>"
                    class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg transition-all">
                    <?php echo $action === 'edit' ? 'Simpan' : 'Tambah'; ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
