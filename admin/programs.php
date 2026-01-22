<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'] ?? 'star';
    $features = $_POST['features'] ?? '';

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO programs (title, description, icon, features) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $icon, $features]);
        $message = 'Program added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE programs SET title = ?, description = ?, icon = ?, features = ? WHERE id = ?");
        $stmt->execute([$title, $description, $icon, $features, $id]);
        $message = 'Program updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM programs WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: programs.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$programs = $pdo->query("SELECT * FROM programs ORDER BY created_at DESC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Program Unggulan</h1>
            <p class="text-slate-500 mt-1">Kelola program pendidikan dan kurikulum</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Program
            </a>
        <?php else: ?>
            <a href="programs.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Batal
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'Program deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($programs as $p): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4">
                        <i data-lucide="<?php echo $p['icon']; ?>" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2"><?php echo $p['title']; ?></h3>
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2"><?php echo $p['description']; ?></p>
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-50">
                        <a href="?action=edit&id=<?php echo $p['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>
                        <a href="?action=delete&id=<?php echo $p['id']; ?>" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus program ini?')">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <form action="" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Judul Program</label>
                    <input type="text" name="title" value="<?php echo $edit_item['title'] ?? ''; ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                    <textarea name="description" rows="3" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all"><?php echo $edit_item['description'] ?? ''; ?></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Icon (Nama Icon Lucide, e.g. rocket, book-open, palette)</label>
                    <input type="text" name="icon" value="<?php echo $edit_item['icon'] ?? 'star'; ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Fitur (Pisahkan dengan koma)</label>
                    <input type="text" name="features" value="<?php echo $edit_item['features'] ?? ''; ?>" placeholder="Juz Amma Pilihan, Hadits Arba'in"
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
