<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    $query = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = '$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $id = $_GET['id'];
        $query_tran = "DELETE FROM tb_transaksi WHERE id = '$id';";
        $result_tran = mysqli_query($conn, $query_tran);

        if ($result_tran) {
            ?>
            <script language="JavaScript">
                alert('Transaksi Berhasil dihapus');
                window.location.href = 'transaksi.php'; // Redirect to the outlet.php page
            </script>
            <?php
        } else {
            ?>
            <script language="JavaScript">
                alert('Transasi Gagal dihapus');
                window.location.href = 'transaksi.php'; // Redirect to the outlet.php page
            </script>
            <?php
        }
        ?>
        <script language="JavaScript">
            alert('Transaksi Berhasil dihapus');
            window.location.href = 'transaksi.php'; // Redirect to the outlet.php page
        </script>
        <?php
    } else {
        ?>
        <script language="JavaScript">
            alert('Transasi Gagal dihapus');
            window.location.href = 'transaksi.php'; // Redirect to the outlet.php page
        </script>
        <?php
    }
} else {
    // Redirect or show an error message
    echo "Failed";
}

mysqli_close($conn);
?>