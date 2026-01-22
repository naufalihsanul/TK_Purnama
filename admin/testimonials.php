<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $rating = $_POST['rating'];
    $message_text = $_POST['message'];
    $photo = $_POST['existing_photo'] ?? '';

    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../assets/images/testi/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $new_filename = time() . "_" . preg_replace("/[^a-zA-Z0-9]/", "", $name) . "." . pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $new_filename)) {
            $photo = "assets/images/testi/" . $new_filename;
        }
    }

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO testimonials (name, role, rating, message, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $role, $rating, $message_text, $photo]);
        $message = 'Testimoni added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE testimonials SET name = ?, role = ?, rating = ?, message = ?, photo = ? WHERE id = ?");
        $stmt->execute([$name, $role, $rating, $message_text, $photo, $id]);
        $message = 'Testimoni updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: testimonials.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$testi_list = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Testimoni Wali Murid</h1>
            <p class="text-slate-500 mt-1">Kelola testimoni dari orang tua siswa</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Testimoni
            </a>
        <?php else: ?>
            <a href="testimonials.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Batal
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'Testimoni deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if (empty($testi_list)): ?>
                <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-2xl border border-dashed border-slate-300">
                    Belum ada testimoni.
                </div>
            <?php endif; ?>
            <?php foreach ($testi_list as $item): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100">
                            <img src="../<?php echo $item['photo'] ?: 'assets/images/default-avatar.png'; ?>" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800"><?php echo $item['name']; ?></h4>
                            <p class="text-xs text-slate-500"><?php echo $item['role']; ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 mb-4 italic line-clamp-3">"<?php echo $item['message']; ?>"</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <div class="flex text-amber-400">
                            <?php for($i=0; $i<$item['rating']; $i++): ?><i data-lucide="star" class="w-3 h-3 fill-current"></i><?php endfor; ?>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="?action=edit&id=<?php echo $item['id']; ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <a href="?action=delete&id=<?php echo $item['id']; ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus testimoni ini?')">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-2xl">
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="existing_photo" value="<?php echo $edit_item['photo'] ?? ''; ?>">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Nama Wali Murid</label>
                        <input type="text" name="name" value="<?php echo $edit_item['name'] ?? ''; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">Status (e.g. Wali Murid Kelas A)</label>
                        <input type="text" name="role" value="<?php echo $edit_item['role'] ?? ''; ?>" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Rating (1-5)</label>
                    <select name="rating" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 outline-none transition-all">
                        <?php for($i=5; $i>=1; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($edit_item['rating']) && $edit_item['rating'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?> Bintang</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Pesan / Testimoni</label>
                    <textarea name="message" rows="4" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $edit_item['message'] ?? ''; ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Foto (Opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                         class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
                </div>

                <div class="pt-4">
                    <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                        <?php echo $action === 'edit' ? 'Simpan Perubahan' : 'Tambah Testimoni'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
