<?php
require_once 'includes/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current user
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $user = $stmt->fetch();

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $_SESSION['admin_id']]);
                $message = 'Password berhasil diperbarui!';
            } else {
                $error = 'Password baru minimal 6 karakter.';
            }
        } else {
            $error = 'Konfirmasi password tidak cocok.';
        }
    } else {
        $error = 'Password saat ini salah.';
    }
}
?>

<div class="max-w-2xl">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Profil & Keamanan</h1>
        <p class="text-slate-500 mt-1">Kelola akun administrator Anda</p>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 border border-green-100 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-8 border border-red-100 flex items-center gap-2 text-sm">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600 text-2xl font-bold">
                    <?php echo strtoupper(substr($_SESSION['admin_username'], 0, 1)); ?>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800"><?php echo $_SESSION['admin_username']; ?></h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Administrator</p>
                </div>
            </div>
        </div>
        
        <form action="" method="POST" class="p-8 space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Password Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Password Baru</label>
                    <input type="password" name="new_password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Ulangi Password Baru</label>
                    <input type="password" name="confirm_password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" 
                    class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
