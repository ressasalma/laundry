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

    $query = "SELECT * FROM tb_member WHERE id='$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $member = mysqli_fetch_assoc($result);
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
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jk = $_POST['jk'];
    $telepon = $_POST['telepon'];
    $update_query = "UPDATE tb_member SET nama_member = '$nama', alamat = '$alamat', jenis_kelamin = '$jk', tlp = '$telepon' WHERE id = '$id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>
alert('Data Pelanggan Berhasil diedit!');
window.location.href = 'pelanggan.php';
</script>";
    } else {
        echo "<script>
alert('Data Pelanggan Gagal diedit!');
window.location.href = 'pelanggan.php'; // Replace with the actual form page URL
</script>";
    }
} ?>




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
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Edit Pelanggan</h3>
                                    </div><!-- /.box-header -->
                                    <!-- form start //MSK-00097-->
                                    <form role="form" action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                        <div class="box-body">
                                            <div class="form-group" id="divnama">
                                                <div class="col-xs-3">
                                                    <label>Nama Pelanggan</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $member['nama_member']; ?>" name="nama">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divalamat">
                                                <div class="col-xs-3">
                                                    <label>Alamat Pelanggan</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $member['alamat']; ?>" name="alamat">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divIndexNumber">
                                                <div class="col-xs-3">
                                                    <label>Jenis Kelamin</label>
                                                </div>
                                                <div class="col-xs-6" id="divEmail1">
                                                    <select name="jk" id="jk" class="form-control">
                                                        <option value="<?php echo $member['jenis_kelamin']; ?>">
                                                            <?php echo $member['jenis_kelamin']; ?>
                                                        </option>
                                                        <option value="L">Laki-Laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divtlp">
                                                <div class="col-xs-3">
                                                    <label>Telepon Pelanggan</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $member['tlp']; ?>" name="telepon">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <input type="hidden" name="do" />
                                            <a href="javascript:history.back()" class="btn btn-danger">Back</a>
                                            <button name="submit" type="submit" class="btn btn-primary"
                                                id="btnSubmit">Submit</button>
                                        </div>
                                    </form>
                                </div><!-- /.box -->
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