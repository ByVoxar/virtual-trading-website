<?php
$host     = "localhost";
$kullanici = "root";
$sifre     = "";
$veritabani = "mobilya"; 

$conn = new mysqli($host, $kullanici, $sifre, $veritabani);

if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>