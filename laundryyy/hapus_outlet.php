<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $query = "DELETE FROM tb_outlet WHERE id = '$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        ?>
        <script language="JavaScript">
            alert('Outlet Berhasil dihapus');
            window.location.href = 'outlet.php'; // Redirect to the outlet.php page
        </script>
        <?php
    } else {
        ?>
        <script language="JavaScript">
            alert('Outlet Gagal dihapus');
            window.location.href = 'outlet.php'; // Redirect to the outlet.php page
        </script>
        <?php
    }
} else {
    // Redirect or show an error message
    echo "Failed";
}

mysqli_close($conn);
?>