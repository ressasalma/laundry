<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "databases_2023_laundry";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>