<?php
include 'koneksi.php'; // Sertakan file koneksi.php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Get the username from the session
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
<html lang="en">

<head>
    <?php include 'head.php'; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <?php include 'header.php' ?>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial -->
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <?php include 'sidebar.php' ?>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Laporan</h4>
                                    <form action="cetak-owner.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <p>Dari:</p>
                                                <input type="date" name="dari" class="form-control">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <p>Sampai:</p>
                                                <input type="date" name="sampai" class="form-control">
                                            </div>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-secondary"><i
                                                class="fas fa-print"></i> print</button>
                                    </form><br>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="outlet-table">
                                            <thead>
                                                <tr>
                                                    <th>Member</th>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Invoice</th>
                                                    <th>Total Bayar</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
                                                tb_member ON tb_transaksi.id_member = tb_member.id WHERE tb_transaksi.id_outlet='$outlet';
                                            ";
                                                $tranResult = $conn->query($queryTran);
                                                if ($tranResult->num_rows > 0) {
                                                    while ($transaksi = $tranResult->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $transaksi['nama_member']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $transaksi['tgl']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $transaksi['kode_invoice']; ?></label>
                                                            </td>
                                                            <td>
                                                                <?php echo $transaksi['total']; ?>
                                                            </td>
                                                            <td><a href="print_struk.php?id=<?= $transaksi["id_transaksi"]; ?>"
                                                                    class="btn btn-secondary btn-small"><i
                                                                        class="fas fa-print"></i></a></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- /.box -->
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            <?php include 'footer.php'; ?>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
</body>

</html>