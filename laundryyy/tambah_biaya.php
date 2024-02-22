<?php
include 'koneksi.php';
if (isset($_POST['submit'])) {
    $jenis = $_POST['jenis'];
    $biaya = $_POST['biaya'];
    $tipe = $_POST['tipe'];
    $query = "INSERT INTO biaya_tambahan (jenis, biaya, tipe) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sis", $jenis, $biaya, $tipe);

    if ($stmt->execute()) {
        echo "<script>
alert('Biaya Tambahan Berhasil ditambahkan!');
window.location.href = 'biaya.php';
</script>";
    } else {
        echo "<script>
alert('Biaya Tambahan Gagal ditambahkan!');
window.location.href = 'biaya.php'; // Replace with the actual form page URL
</script>";
    }
} ?>