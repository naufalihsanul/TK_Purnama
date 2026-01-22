<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $photo = $_POST['existing_photo'] ?? '';

    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../assets/guru/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . preg_replace("/[^a-zA-Z0-9]/", "", $name) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = "assets/guru/" . $new_filename;
        }
    }

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO teachers (name, role, photo, is_featured) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $role, $photo, $is_featured]);
        $message = 'Guru added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE teachers SET name = ?, role = ?, photo = ?, is_featured = ? WHERE id = ?");
        $stmt->execute([$name, $role, $photo, $is_featured, $id]);
        $message = 'Guru updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: guru.php?deleted=1');
    exit;
}

$edit_guru = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    $edit_guru = $stmt->fetch();
}

$guru_list = $pdo->query("SELECT * FROM teachers ORDER BY is_featured DESC, created_at DESC")->fetchAll();
?>

<div class="max-w-6xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Guru</h1>
            <p class="text-slate-500 mt-1">Kelola data tenaga pengajar TK Purnama</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Guru
            </a>
        <?php else: ?>
            <a href="guru.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Batal
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'Guru deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Foto</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Jabatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($guru_list)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data guru.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($guru_list as $g): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-slate-100">
                                    <img src="../<?php echo $g['photo'] ?: 'assets/images/default-avatar.png'; ?>" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800"><?php echo $g['name']; ?></td>
                            <td class="px-6 py-4 text-slate-500 font-medium"><?php echo $g['role']; ?></td>
                            <td class="px-6 py-4">
                                <?php if ($g['is_featured']): ?>
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[0.65rem] font-bold uppercase">Kepala Sekolah</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[0.65rem] font-bold uppercase">Guru</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="?action=edit&id=<?php echo $g['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $g['id']; ?>" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus guru ini?')">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <!-- Form Add/Edit -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="existing_photo" value="<?php echo $edit_guru['photo'] ?? ''; ?>">
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" value="<?php echo $edit_guru['name'] ?? ''; ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Jabatan / Peran</label>
                    <input type="text" name="role" value="<?php echo $edit_guru['role'] ?? ''; ?>" required placeholder="Contoh: Wali Kelas A1"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Foto Guru</label>
                    <?php if (isset($edit_guru['photo']) && $edit_guru['photo']): ?>
                        <div class="mb-2">
                            <img src="../<?php echo $edit_guru['photo']; ?>" class="w-20 h-20 rounded-xl object-cover border">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
                </div>

                <div class="flex items-center gap-3 py-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo (isset($edit_guru['is_featured']) && $edit_guru['is_featured']) ? 'checked' : ''; ?>
                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                    <label for="is_featured" class="text-sm font-semibold text-slate-700">Tandai sebagai Kepala Sekolah (Ditampilkan di atas)</label>
                </div>

                <div class="pt-4">
                    <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                        <?php echo $action === 'edit' ? 'Simpan Perubahan' : 'Tambah Guru'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
