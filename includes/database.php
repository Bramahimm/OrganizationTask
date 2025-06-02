<?php
$host = 'localhost';        
$user = 'root';             
$pass = '';              
$db = 'orgenius';      
$conn = mysqli_connect($host, $user, $pass, $db);
// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
