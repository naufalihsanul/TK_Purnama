<?php
/**
 * FILE RESET MANUAL
 * Edit baris $new_password di bawah, lalu buka file ini di browser
 * Setelah selesai, SEGERA HAPUS file ini dari server.
 */

require_once '../includes/db.php';

$new_password = 'admin123'; // GANTI PASSWORD ANDA DISINI

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = 1");

if ($stmt->execute([$hashed_password])) {
    echo "<h1>Password Berhasil Di-reset!</h1>";
    echo "<p>Password baru Anda adalah: <b>$new_password</b></p>";
    echo "<p><a href='login.php'>Klik disini untuk Login</a></p>";
    echo "<hr>";
    echo "<p style='color:red;'><b>PERINGATAN:</b> Segera hapus file ini (fix_pass.php) untuk keamanan!</p>";
} else {
    echo "Gagal mereset password.";
}
?>
