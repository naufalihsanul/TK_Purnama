<?php
require_once 'includes/header.php';

$message = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
        $stmt->execute([$question, $answer]);
        $message = 'FAQ added successfully!';
    } elseif (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE faq SET question = ?, answer = ? WHERE id = ?");
        $stmt->execute([$question, $answer, $id]);
        $message = 'FAQ updated successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: faq.php?deleted=1');
    exit;
}

$edit_item = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->execute([$id]);
    $edit_item = $stmt->fetch();
}

$faq_list = $pdo->query("SELECT * FROM faq ORDER BY created_at DESC")->fetchAll();
?>

<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">FAQ Management</h1>
            <p class="text-slate-500 mt-1">Manage frequently asked questions</p>
        </div>
        <?php if ($action === 'list'): ?>
            <a href="?action=add" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Add FAQ
            </a>
        <?php else: ?>
            <a href="faq.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                Back
            </a>
        <?php endif; ?>
    </div>

    <?php if ($message || isset($_GET['deleted'])): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message ?: 'FAQ deleted successfully!'; ?>
        </div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <div class="space-y-4">
            <?php if (empty($faq_list)): ?>
                <div class="py-12 text-center text-slate-500 bg-white rounded-2xl border border-dashed border-slate-300">
                    No FAQs yet.
                </div>
            <?php endif; ?>
            <?php foreach ($faq_list as $item): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-start justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2"><?php echo $item['question']; ?></h4>
                        <p class="text-sm text-slate-500 line-clamp-2"><?php echo $item['answer']; ?></p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="?action=edit&id=<?php echo $item['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </a>
                        <a href="?action=delete&id=<?php echo $item['id']; ?>" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Delete this FAQ?')">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <form action="" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Question</label>
                    <input type="text" name="question" value="<?php echo $edit_item['question'] ?? ''; ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Answer</label>
                    <textarea name="answer" rows="5" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all"><?php echo $edit_item['answer'] ?? ''; ?></textarea>
                </div>
                <div class="pt-4">
                    <button type="submit" name="<?php echo $action === 'edit' ? 'edit' : 'add'; ?>"
                        class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                        <?php echo $action === 'edit' ? 'Save Changes' : 'Add FAQ'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
