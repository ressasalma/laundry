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
    tb_member.*
FROM 
    tb_detail_transaksi
JOIN 
    tb_transaksi ON tb_detail_transaksi.id_transaksi = tb_transaksi.id
JOIN 
    tb_paket ON tb_detail_transaksi.id_paket = tb_paket.id
LEFT JOIN 
    tb_member ON tb_transaksi.id_member = tb_member.id 
WHERE 
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

// Get the username from the session
$username = $_SESSION["username"];

if (isset($_POST['submit'])) {

    $qty = $_POST['qty'];
    $ket = $_POST['ket'];
    $paket = $_POST['paket'];
    $query = "UPDATE tb_detail_transaksi SET keterangan = '$ket', id_paket = '$paket', qty = '$qty' WHERE id_transaksi='$id'";
    if (mysqli_query($conn, $query)) {
        $biaya = $_POST['biaya'];
        $diskon = $_POST['diskon'];
        $pajak = $_POST['pajak'];
        $status = $_POST['status'];
        $bayar = $_POST['bayar'];
        $pembayaran = $_POST['pembayaran'];
        $query = "UPDATE tb_transaksi SET tgl_bayar='$bayar', biaya_tambahan='$biaya', diskon='$diskon', pajak='$pajak', status='$status', dibayar='$pembayaran' WHERE id='$id'";
        if (mysqli_query($conn, $query)) {
            echo "<script>
                    alert('Transaksi Berhasil!');
                    window.location.href = 'transaksi_kasir.php';
                </script>";
        } else {
            echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'transaksi_kasir.php'; // Replace with the actual form page URL
                </script>";
        }
    } else {
        echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'transaksi_kasir.php'; // Replace with the actual form page URL
                </script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'head.php' ?>
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
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- general form elements -->
                                    <div class="box box-primary">
                                        <form action="" method="post">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Edit Transaksi -
                                                    <?php echo $tran['nama_member']; ?>
                                                    (
                                                    <?php echo $tran['kode_invoice']; ?>)
                                                </h3>
                                            </div>

                                            <script type="text/JavaScript">
                                                function totalHarga() {
        var paketSelect = document.getElementById("paket");
        var diskonVal = parseFloat(document.getElementById("diskon").value) || 0;
        var biayaVal = parseFloat(document.getElementById("biaya").value) || 0;
        var pajakVal = parseFloat(document.getElementById("pajak").value) || 0;
        var qtyVal = parseFloat(document.getElementById("qty").value) || 0;

        var selectedPaket = paketSelect.options[paketSelect.selectedIndex];
        var hargaPaket = parseFloat(selectedPaket.getAttribute("data-harga")) || 0;

        // Hitung total
        var total = (hargaPaket * qtyVal) + biayaVal + pajakVal - (diskonVal / 100 * hargaPaket);;

        // Tampilkan total jika hasil perhitungan adalah angka, jika tidak tampilkan pesan kesalahan
        if (!isNaN(total)) {
            document.getElementById("total").textContent = "Rp. " + total;
        } else {
            document.getElementById("total").textContent = "Input tidak valid";
        }
    }
</script>

                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="biaya">Biaya</label>
                                                    <select name="biaya" id="biaya" class="form-control">
                                                        <option value="<?php echo $tran['biaya_tambahan']; ?>">
                                                            <?php echo $tran['biaya_tambahan']; ?>
                                                        </option>
                                                        <?php
                                                        $querybiaya = "SELECT * FROM biaya_tambahan";
                                                        $biayaResult = $conn->query($querybiaya);
                                                        if ($biayaResult->num_rows > 0) {
                                                            while ($biaya = $biayaResult->fetch_assoc()) {
                                                                echo '<option value="' . $biaya['biaya'] . '">' . $biaya['jenis'] . "&nbsp;-&nbsp;" . $biaya['biaya'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="diskon">Diskon</label>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $tran['diskon']; ?>" name="diskon"
                                                        id="diskon">
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="pajak">Pajak</label>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $tran['pajak']; ?>" name="pajak" id="pajak">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="qty">Qty</label>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $tran['qty']; ?>" name="qty" id="qty">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="paket">Paket</label>
                                                    <select name="paket" id="paket" class="form-control"
                                                        onchange="totalHarga()">
                                                        <option value="<?php echo $tran['id_paket']; ?>"
                                                            data-harga="<?php echo $tran['harga']; ?>">
                                                            <?php echo $tran['nama_paket']; ?>
                                                        </option>
                                                        <?php
                                                        $queryPak = "SELECT id, nama_paket, harga FROM tb_paket WHERE id_outlet = '$id_outlet'";
                                                        $pakResult = $conn->query($queryPak);
                                                        if ($pakResult->num_rows > 0) {
                                                            while ($paket = $pakResult->fetch_assoc()) {
                                                                echo '<option value="' . $paket['id'] . '" data-harga="' . $paket['harga'] . '">' . $paket['nama_paket'] . '</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="ket">Keterangan</label>
                                                    <input type="text" name="ket" id="ket" class="form-control"
                                                        value="<?php echo $tran['keterangan']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label>Status</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="<?php echo $tran['status']; ?>">
                                                            <?php echo $tran['status']; ?>
                                                        </option>
                                                        <?php
                                                        $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_transaksi LIKE 'status'"))['Type'];
                                                        preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                        $enum = str_getcsv($matches[1], ",", "'");
                                                        foreach ($enum as $value) {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="bayar">Tanggal Bayar</label>
                                                    <input type="datetime-local" class="form-control" name="bayar"
                                                        value="<?php echo $tran['tgl_bayar']; ?>" id="bayar">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Status Pembayaran</label>
                                                    <select name="pembayaran" id="pembayaran" class="form-control">
                                                        <option value="<?php echo $tran['dibayar']; ?>">
                                                            <?php echo $tran['dibayar']; ?>
                                                        </option>
                                                        <?php
                                                        $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_transaksi LIKE 'dibayar'"))['Type'];
                                                        preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                        $enum = str_getcsv($matches[1], ",", "'");
                                                        foreach ($enum as $value) {
                                                            echo '<option value="' . $value . '">' . $value . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <h1 id="total">Rp. </h1>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <a href="javascript:history.back()" class="btn btn-danger">Back</a>
                                                    <button name="submit" type="submit" class="btn btn-primary"
                                                        id="btnSubmit">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- /.box -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <?php include 'footer.php'; ?>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

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