<?php
$host = "localhost";
$user = "root";     
$pass = "";          
$db   = "rental_ps"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi Gagal: " . $e->getMessage();
    die();
}
?>