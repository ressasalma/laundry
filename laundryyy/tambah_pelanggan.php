<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $id_member = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jk = $_POST['jk'];
    $tlp = $_POST['telepon'];

    // Insert into tb_member table
    $query_member = "INSERT INTO tb_member (id, nama_member, alamat, jenis_kelamin, tlp) VALUES (?, ?, ?, ?, ?)";
    $stmt_member = $conn->prepare($query_member);
    $stmt_member->bind_param("issss", $id_member, $nama, $alamat, $jk, $tlp);

    if ($stmt_member->execute()) {
        // Insert into user table
        $id_user = mt_rand(1000, 9999);
        $peran = "member";
        $query_user = "INSERT INTO user (id, id_member, nama_user, password, username, peran) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_user = $conn->prepare($query_user);
        // Assuming nama_member is the username and password
        $stmt_user->bind_param("iissss", $id_user, $id_member, $nama, $nama, $nama, $peran);

        if ($stmt_user->execute()) {
            echo "<script>
                alert('Pelanggan Baru Berhasil ditambahkan!');
                window.location.href = 'pelanggan.php';
                </script>";
        } else {
            echo "<script>
                alert('Pelanggan Baru Gagal ditambahkan!');
                window.location.href = 'pelanggan.php'; // Replace with the actual form page URL
                </script>";
        }
    }
}
?>