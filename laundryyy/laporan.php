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
$sql = "SELECT id, peran FROM user WHERE username = ?";
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
    $peran = $data['peran']; // Corrected variable name
} else {
    echo "User tidak ditemukan";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $outlet = $_POST['outlet'];
    $invoice = $_POST['invoice'];
    $member = $_POST['member'];
    $paket = $_POST['paket'];
    $bayar = $_POST['bayar'];
    $biaya = $_POST['biaya'];
    $diskon = $_POST['diskon'];
    $pajak = $_POST['pajak'];
    $pembayaran = $_POST['pembayaran'];
    $user = $data['id'];

    // Mendapatkan tanggal sekarang
    $tgl_sekarang = date('Y-m-d');

    // Mendapatkan nilai estimasi dari tb_paket berdasarkan id_paket yang dipilih
    $query_estimasi = "SELECT estimasi FROM tb_paket WHERE id = ?";
    $stmt_estimasi = $conn->prepare($query_estimasi);
    $stmt_estimasi->bind_param("i", $paket);
    $stmt_estimasi->execute();
    $result_estimasi = $stmt_estimasi->get_result();
    $row_estimasi = $result_estimasi->fetch_assoc();
    $estimasi = $row_estimasi['estimasi'];

    // Menambahkan nilai estimasi ke tanggal sekarang untuk mendapatkan batas waktu
    $batas = date('Y-m-d', strtotime($tgl_sekarang . ' + ' . $estimasi . ' days'));

    // Mendapatkan tanggal dan waktu saat ini
    $tgl = date('Y-m-d H:i:s');

    $query = "INSERT INTO tb_transaksi (id, id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, dibayar, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiisssssssi", $id, $outlet, $invoice, $member, $tgl, $batas, $bayar, $biaya, $diskon, $pajak, $pembayaran, $user);
    if ($stmt->execute()) {
        $id_detail = $_POST['id_detail'];
        $id = $_POST['id'];
        $paket = $_POST['paket'];
        $qty = $_POST['qty'];
        $ket = $_POST['ket'];
        $query = "INSERT INTO tb_detail_transaksi (id, id_transaksi, id_paket, qty, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $id_detail, $id, $paket, $qty, $ket);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Transaksi Berhasil!');
                    window.location.href = 'transaksi.php';
                </script>";
        } else {
            echo "<script>
                            alert('Transaksi Gagal!');
                            window.location.href = 'transaksi.php'; // Replace with the actual form page URL
                        </script>";
        }
    } else {
        echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'transaksi.php'; // Replace with the actual form page URL
                </script>";
    }
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
                                    <form action="cetak.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-2">
                                                <p>Dari:</p><input type="date" name="dari" class="form-control">
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
                                                    <th>ID Outlet</th>
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
                                                tb_member ON tb_transaksi.id_member = tb_member.id;
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
                                                    <td>
                                                        <?php echo $transaksi['id_outlet']; ?>
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