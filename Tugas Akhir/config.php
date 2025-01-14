<?php

$host = "localhost"; //server database
$user = "root";
$pass = "";
$db = "toko_sepatu";

//untuk koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>