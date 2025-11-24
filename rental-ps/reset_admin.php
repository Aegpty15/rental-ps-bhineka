<?php
require 'config/koneksi.php';

$password_bersih = 'admin123';

$password_hash = password_hash($password_bersih, PASSWORD_DEFAULT);

try {
    $conn->query("DELETE FROM admin WHERE username = 'admin'");

    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (:user, :pass)");
    $stmt->execute([
        ':user' => 'admin',
        ':pass' => $password_hash
    ]);

    echo "<h1>✅ SUKSES!</h1>";
    echo "<p>User Admin berhasil di-reset.</p>";
    echo "<ul>
            <li>Username: <b>admin</b></li>
            <li>Password: <b>admin123</b></li>
            <li>Hash Baru: $password_hash</li>
          </ul>";
    echo "<a href='admin/login.php'>👉 Klik disini untuk Login</a>";

} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage();
}
?>