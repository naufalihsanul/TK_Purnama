<?php
$host = 'localhost';
$dbname = 'tk_purnama';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getSetting($key) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT site_value FROM settings WHERE site_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['site_value'] : '';
}
?>
