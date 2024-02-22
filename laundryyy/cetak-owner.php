<?php
include 'koneksi.php'; // Include the connection file

// Start the PHP session (no need for duplicate session_start() calls)
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
if (isset($_POST['submit'])) {
    $dari = $_POST['dari'];
    $sampai = $_POST['sampai']; // Sanitize the input
} else {
    // Redirect or show an error message
    echo "Gagal";
    exit;
}
$username = $_SESSION["username"];

// Prepare a parameterized SQL query
$sql = "SELECT id, id_outlet FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);

// Bind the username parameter
$stmt->bind_param("s", $username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query returned any rows
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $outlet = $data['id_outlet']; // Corrected variable name
} else {
    echo "User tidak ditemukan";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Struk Transaksi</title>
    <?php include 'head.php' ?>
    <style>
    .center {
        text-align: center;
    }

    .left {
        text-align: left;
    }

    .flex-container {
        display: flex;
    }

    .left-column,
    .right-column {
        flex: 1;
    }

    .left-column {
        text-align: left;
    }

    .right-column {
        text-align: right;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="flex-container">
                    <div class="left-column">
                        <img src="images/dicuciin.png" alt="IMG" width="200px">
                    </div>
                    <div class="right-column">
                        <h1>Dicuciin</h1>
                        <h3>Jl. Kenanga Rt.08 <br> Desa Sumber Mulya, Kec. Bahar Utara <br> Kab. Muaro Jambi, Jambi <br>
                            36365, Telp +62812425617899</h3><br><br>
                    </div>
                </div>
                <br />
                <div class="flex-container">
                    <div class="right-column">
                        <h3>Tanggal</h3>
                        <h3>
                            <?php echo $dari; ?> sampai
                            <?php echo $sampai; ?>
                        </h3>
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th>Order</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalSemuaTransaksi = 0;
                            $queryTran = "SELECT 
                                                tb_detail_transaksi.*,
                                                tb_transaksi.*,
                                                tb_paket.*,
                                                tb_member.*,
                                                ((tb_paket.harga * tb_detail_transaksi.qty) + tb_transaksi.biaya_tambahan + tb_transaksi.pajak - (tb_transaksi.diskon / 100 * tb_paket.harga)) AS total
                                            FROM 
                                                tb_detail_transaksi
                                            JOIN 
                                                tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id
                                            JOIN 
                                                tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
                                            LEFT JOIN 
                                                tb_member ON tb_transaksi.id_member = tb_member.id WHERE DATE(tb_transaksi.tgl) BETWEEN '$dari' AND '$sampai' AND tb_transaksi.id_outlet = '$outlet';
                                            ";
                            $tranResult = $conn->query($queryTran);
                            if ($tranResult->num_rows > 0) {
                                while ($tran = $tranResult->fetch_assoc()) {
                                    $totalSemuaTransaksi += $tran['total']; ?>
                            <tr>
                                <td>
                                    <?php echo $tran['tgl']; ?>
                                </td>
                                <td>
                                    <?php echo $tran['nama_member']; ?>
                                </td>
                                <td>
                                    <?php echo $tran['nama_paket']; ?>
                                </td>
                                <td>Rp.
                                    <?php echo number_format($tran['total'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php echo $tran['dibayar']; ?>
                                </td>
                            </tr>
                            <?php }
                            } ?>
                            <tr>
                                <th colspan="3" style="text-align: center;">Jumlah</th>
                                <td colspan="2">Rp.
                                    <?php echo number_format($totalSemuaTransaksi, 0, ',', '.'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br><br>
                <?php $queryKas = "SELECT nama_user FROM user WHERE username = '$username'";
                $resultKas = mysqli_query($conn, $queryKas);
                if ($resultKas) {
                    $kasir = mysqli_fetch_assoc($resultKas);
                } else {
                    echo "Failed to fetch data: " . mysqli_error($conn);
                    exit;
                } ?>
                <div class="right-column">
                    <h3>Kasir</h3><br><br>
                    <h3>
                        <?php echo $kasir['nama_user']; ?>
                    </h3>
                </div><br><br>
                <div class="center">
                    <h3>dicuciin - Laporan Transaksi</h3>
                    <h3>Printed on
                        <?php echo date("Y-m-d"); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $('table').DataTable();
    });
    print();
    </script>

    <script type="text/javascript">
    window.print();