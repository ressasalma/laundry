<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $query = "DELETE FROM user WHERE id = '$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        ?>
<script language="JavaScript">
alert('User Berhasil dihapus');
window.location.href = 'pengguna.php'; // Redirect to the outlet.php page
</script>
<?php
    } else {
        ?>
<script language="JavaScript">
alert('User Gagal dihapus');
window.location.href = 'pengguna.php'; // Redirect to the outlet.php page
</script>
<?php
    }
} else {
    // Redirect or show an error message
    echo "Failed";
}

mysqli_close($conn);
?>