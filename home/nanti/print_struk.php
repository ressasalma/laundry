<?php
include 'koneksi.php'; // Include the connection file


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
                    <h3>Dicuciin</h3>
                    <h5>Jl. Kenanga Rt.08 <br> Desa Sumber Mulya, Kec. Bahar Utara <br> Kab. Muaro Jambi, Jambi <br>
                        36365, Telp +62812425617899</h5><br><br>
                </div>
                <div class="flex-container">
                    <div class="left-column">
                        <h5>Tanggal Masuk</h5>
                        <h5>
                            <?php echo date('Y-m-d', strtotime($tran['tgl'])); ?>
                        </h5>
                    </div>
                    <div class="right-column">
                        <h5>Rencana Selesai</h5>
                        <h5>
                            <?php echo date('Y-m-d', strtotime($tran['batas_waktu'])); ?>
                        </h5>
                    </div>
                </div>
                <h5>Invoice #
                    <?php echo $tran['kode_invoice']; ?>
                </h5>
                <h5>Tanggal Cetak:
                    <?php echo date("Y-m-d"); ?>
                </h5>
                <h5>Nama :
                    <?php echo $tran['id_member']; ?>
                </h5>
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
                    <h5> Outlet
                        -
                        <?php echo $outlet['nama_outlet']; ?>
                    </h5><br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Tarif</th>
                                    <th>Jumlah</th>
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
                                    <td>Rp.
                                        <?php echo number_format($tran['total'], 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br><br>
                    <h4>Terimakasih atas kepercayaan anda</h4>
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