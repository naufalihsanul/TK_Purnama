<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'];
    $type = $_POST['type']; // 'vision' or 'mission'
    $icon = $_POST['icon'] ?? '';

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO vision_mission (type, title, content, icon) VALUES (?, ?, ?, ?)");
        $stmt->execute([$type, $title, $content, $icon]);
        $message = 'Entry added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE vision_mission SET type = ?, title = ?, content = ?, icon = ? WHERE id = ?");
        $stmt->execute([$type, $title, $content, $icon, $id]);
        $message = 'Entry updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM vision_mission WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: vision_mission.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM vision_mission WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$vision = $pdo->query("SELECT * FROM vision_mission WHERE type = 'vision' LIMIT 1")->fetch();
$missions = $pdo->query("SELECT * FROM vision_mission WHERE type = 'mission' ORDER BY created_at ASC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Visi & Misi</h1>
            <p class="text-slate-500 mt-1">Kelola visi utama dan daftar misi sekolah</p>
        </div>
        <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Misi
        </a>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            Data updated successfully!
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <!-- Section Visi -->
        <div class="mb-12">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Visi Utama</h2>
            <?php if ($vision): ?>
                <div class="bg-slate-900 text-white p-8 rounded-2xl relative overflow-hidden group">
                    <div class="relative z-10 flex justify-between items-start">
                        <div class="max-w-xl">
                            <p class="text-xl font-light italic leading-relaxed">"<?php echo $vision['content']; ?>"</p>
                        </div>
                        <a href="?action=edit&id=<?php echo $vision['id']; ?>" class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                            <i data-lucide="edit-3" class="w-5 h-5 text-amber-400"></i>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white border-2 border-dashed border-slate-200 p-8 rounded-2xl text-center text-slate-500">
                    Belum ada Visi. <a href="?action=add&type=vision" class="text-amber-500 font-bold hover:underline">Tambah Visi</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Section Misi -->
        <div>
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Daftar Misi</h2>
            <div class="grid grid-cols-1 gap-4">
                <?php foreach ($missions as $m): ?>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                <i data-lucide="<?php echo $m['icon'] ?: 'check'; ?>" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm"><?php echo $m['title']; ?></h4>
                                <p class="text-[0.7rem] text-slate-500 italic mt-0.5"><?php echo $m['content']; ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="?action=edit&id=<?php echo $m['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $m['id']; ?>" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus misi ini?')">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <h3 class="font-bold text-slate-800 mb-6"><?php echo $action === 'edit' ? 'Edit Entry' : 'Tambah Baru'; ?></h3>
            <form action="" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Tipe</label>
                    <select name="type" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                        <option value="vision" <?php echo (isset($edit_item['type']) && $edit_item['type'] == 'vision') || (isset($_GET['type']) && $_GET['type'] == 'vision') ? 'selected' : ''; ?>>Visi Utama</option>
                        <option value="mission" <?php echo (isset($edit_item['type']) && $edit_item['type'] == 'mission') || (isset($_GET['type']) && $_GET['type'] == 'mission') ? 'selected' : ''; ?>>Misi</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Judul (e.g. Karakter Islami)</label>
                    <input type="text" name="title" value="<?php echo $edit_item['title'] ?? ''; ?>" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Detail / Deskripsi</label>
                    <textarea name="content" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all"><?php echo $edit_item['content'] ?? ''; ?></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Icon (Opsional, untuk Misi) e.g. heart-handshake, brain, leaf</label>
                    <input type="text" name="icon" value="<?php echo $edit_item['icon'] ?? ''; ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                </div>
                <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>" class="w-full py-3 bg-amber-500 text-white font-bold rounded-xl shadow-lg transition-all">
                    Simpan
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
