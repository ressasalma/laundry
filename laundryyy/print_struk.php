<?php
include 'koneksi.php'; // Include the connection file

// Start the PHP session (no need for duplicate session_start() calls)
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id); // Sanitize the input

    $query = "SELECT 
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
    tb_member ON tb_transaksi.id_member = tb_member.id WHERE 
                tb_detail_transaksi.id_transaksi = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $tran = mysqli_fetch_assoc($result);
        $id_outlet = $tran['id_outlet'];
    } else {
        echo "Failed to fetch data: " . mysqli_error($conn);
        exit;
    }
} else {
    // Redirect or show an error message
    echo "Gagal";
    exit;
}
$username = $_SESSION["username"];
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
                <div class="center">
                    <h1>Dicuciin</h1>
                    <h3>Jl. Kenanga Rt.08 <br> Desa Sumber Mulya, Kec. Bahar Utara <br> Kab. Muaro Jambi, Jambi <br>
                        36365, Telp +62812425617899</h3><br><br>
                </div>
                <br />
                <div class="flex-container">
                    <div class="left-column">
                        <h3>Tanggal Masuk</h3>
                        <h3>
                            <?php echo date('Y-m-d', strtotime($tran['tgl'])); ?>
                        </h3>
                    </div>
                    <div class="right-column">
                        <h3>Rencana Selesai</h3>
                        <h3>
                            <?php echo date('Y-m-d', strtotime($tran['batas_waktu'])); ?>
                        </h3>
                    </div>
                </div><br><br>
                <h4>Invoice #
                    <?php echo $tran['kode_invoice']; ?>
                </h4>
                <h4>Tanggal Cetak:
                    <?php echo date("Y-m-d"); ?>
                </h4>
                <h4>Nama :
                    <?php echo $tran['nama_member']; ?>
                </h4>
                <br>
                <?php $queryOut = "SELECT * FROM tb_outlet WHERE id = '$id_outlet'";
                $resultOut = mysqli_query($conn, $queryOut);
                if ($resultOut) {
                    $outlet = mysqli_fetch_assoc($resultOut);
                } else {
                    echo "Failed to fetch data: " . mysqli_error($conn);
                    exit;
                } ?>
                <div class="center">
                    <h3> Outlet
                        -
                        <?php echo $outlet['nama_outlet']; ?>
                    </h3><br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Tarif</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Pajak</th>
                                    <th>Biya Tambahan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $tran['nama_paket']; ?>
                                    </td>
                                    <td>
                                        Rp.
                                        <?php echo number_format($tran['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <?php echo $tran['qty']; ?>
                                    </td>
                                    <td>
                                        <?php echo $tran['diskon']; ?>%
                                    </td>
                                    <td>Rp.
                                        <?php echo number_format($tran['pajak'], 0, ',', '.'); ?>
                                    </td>
                                    <td>Rp.
                                        <?php echo number_format($tran['biaya_tambahan'], 0, ',', '.'); ?>
                                    </td>
                                    <td>Rp.
                                        <?php echo number_format($tran['total'], 0, ',', '.'); ?>
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
                    <h3>Terimakasih atas kepercayaan anda</h3>
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
        window.print();
    </script>