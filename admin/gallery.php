<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $aspect_ratio = $_POST['aspect_ratio'] ?? '4/5';
    $image_path = $_POST['existing_image'] ?? '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/galeri/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "assets/galeri/" . $new_filename;
        }
    }

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO gallery (title, aspect_ratio, image_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $aspect_ratio, $image_path]);
        $message = 'Foto added to gallery successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE gallery SET title = ?, aspect_ratio = ?, image_path = ? WHERE id = ?");
        $stmt->execute([$title, $aspect_ratio, $image_path, $id]);
        $message = 'Foto updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: gallery.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$gallery_items = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
?>

<div class="max-w-6xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Galeri Momen</h1>
            <p class="text-slate-500 mt-1">Kelola dokumentasi kegiatan sekolah</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Upload Foto
            </a>
        <?php else: ?>
            <a href="gallery.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Batal
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'Photo deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (empty($gallery_items)): ?>
                <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-2xl border border-dashed border-slate-300">
                    Belum ada koleksi foto.
                </div>
            <?php endif; ?>
            <?php foreach ($gallery_items as $item): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group relative">
                    <div class="aspect-square relative flex items-center justify-center bg-slate-100 overflow-hidden">
                        <img src="../<?php echo $item['image_path']; ?>" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="?action=edit&id=<?php echo $item['id']; ?>" class="p-2 bg-white text-blue-600 rounded-lg shadow-lg hover:scale-110 transition-transform">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $item['id']; ?>" class="p-2 bg-white text-red-600 rounded-lg shadow-lg hover:scale-110 transition-transform" onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-3">
                        <h4 class="text-xs font-bold text-slate-800 truncate"><?php echo $item['title'] ?: 'Untitled'; ?></h4>
                        <span class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest block mt-1">Ukuran: <?php echo $item['aspect_ratio']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="existing_image" value="<?php echo $edit_item['image_path'] ?? ''; ?>">
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Judul / Deskripsi Singkat</label>
                    <input type="text" name="title" value="<?php echo $edit_item['title'] ?? ''; ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Ukuran Gambar (Aspect Ratio)</label>
                    <select name="aspect_ratio" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                        <option value="4/5" <?php echo (isset($edit_item['aspect_ratio']) && $edit_item['aspect_ratio'] == '4/5') ? 'selected' : ''; ?>>Potrait (4:5)</option>
                        <option value="9/12" <?php echo (isset($edit_item['aspect_ratio']) && $edit_item['aspect_ratio'] == '9/12') ? 'selected' : ''; ?>>Tall Potrait (9:12)</option>
                        <option value="1/1" <?php echo (isset($edit_item['aspect_ratio']) && $edit_item['aspect_ratio'] == '1/1') ? 'selected' : ''; ?>>Square (1:1)</option>
                        <option value="16/9" <?php echo (isset($edit_item['aspect_ratio']) && $edit_item['aspect_ratio'] == '16/9') ? 'selected' : ''; ?>>Landscape (16:9)</option>
                        <option value="video" <?php echo (isset($edit_item['aspect_ratio']) && $edit_item['aspect_ratio'] == 'video') ? 'selected' : ''; ?>>Ultra Wide (21:9)</option>
                    </select>
                    <p class="text-[0.7rem] text-slate-400 italic">Gambar akan otomatis <b>Zoom & Fill</b> untuk memenuhi bingkai tanpa ada gap putih sedikitpun.</p>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Pilih Foto</label>
                    <?php if (isset($edit_item['image_path']) && $edit_item['image_path']): ?>
                        <div class="mb-2">
                            <img src="../<?php echo $edit_item['image_path']; ?>" class="w-40 rounded-xl border">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" <?php echo $action === 'add' ? 'required' : ''; ?>
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                        <?php echo $action === 'edit' ? 'Simpan Perubahan' : 'Upload Foto'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
