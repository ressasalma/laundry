<?php
include 'koneksi.php';
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $outlet = $_POST['outlet'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $peran = $_POST['peran'];
    $query = "INSERT INTO user (id, id_outlet, nama_user, password, username, peran) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissss", $id, $outlet, $nama, $password, $username, $peran);

    if ($stmt->execute()) {
        echo "<script>
alert('Pengguna Baru Berhasil ditambahkan!');
window.location.href = 'pengguna.php';
</script>";
    } else {
        echo "<script>
alert('Pengguna Baru Gagal ditambahkan!');
window.location.href = 'pengguna.php'; // Replace with the actual form page URL
</script>";
    }
} ?>