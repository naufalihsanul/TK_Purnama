<?php
require_once '../includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = 1"); // Assuming ID 1 is the main admin
        if ($stmt->execute([$hashed_password])) {
            $message = 'Password has been reset successfully! <a href="login.php" class="underline">Login here</a>';
        } else {
            $message = 'Error resetting password.';
        }
    } else {
        $message = 'Passwords do not match.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Password Reset - TK Purnama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Reset Password</h1>
                <p class="text-slate-500 text-sm mt-1">Gunakan file ini jika Anda lupa password admin</p>
            </div>

            <?php if ($message): ?>
                <div class="p-4 rounded-xl mb-6 text-sm <?php echo strpos($message, 'successfully') !== false ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                    <input type="password" name="new_password" required placeholder="Masukkan password baru"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="Ulangi password baru"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                </div>
                <button type="submit" 
                    class="w-full py-3 px-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                    Reset Password
                </button>
            </form>
            
            <div class="mt-8 p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-[0.7rem] text-amber-800 leading-relaxed font-medium">
                    <span class="font-bold">Keamanan:</span> Segera hapus file ini (<code>reset_password.php</code>) dari server setelah Anda selesai mereset password untuk keamanan.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
