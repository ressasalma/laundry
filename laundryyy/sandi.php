<?php
include 'koneksi.php'; // Include the connection file

// Start the PHP session (no need for duplicate session_start() calls)
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION["username"];
$query = "SELECT * FROM user WHERE username='$username';";
$result = mysqli_query($conn, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Failed to fetch data: " . mysqli_error($conn);
    exit;
}
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password1 = $_POST['password1'];
    if ($password == $user["password"]) {
        $update_query = "UPDATE user SET nama_user = ?, username = ?, password= ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, 'sssi', $nama, $username, $password1, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "<script>
                        alert('Data Pengguna Berhasil diedit!');
                        window.location.href = 'sandi.php';
                      </script>";
        } else {
            echo "<script>
                        alert('Data Pengguna Gagal diedit!');
                        window.location.href = 'sandi.php';
                      </script>";
        }
    } else {
        $update_query = "UPDATE user SET nama_user = ?, username = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, 'ssi', $nama, $username, $id);

        if (mysqli_stmt_execute($stmt_update)) {
            echo "<script>
                        alert('Data Pengguna Berhasil diedit!');
                        window.location.href = 'sandi.php';
                      </script>";
        } else {
            echo "<script>
                        alert('Data Pengguna Gagal diedit!');
                        window.location.href = 'sandi.php';
                      </script>";
        }
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
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Edit Pengguna</h3>
                                    </div><!-- /.box-header -->
                                    <!-- form start //MSK-00097-->
                                    <form role="form" action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                        <div class="box-body">
                                            <div class="form-group" id="divnama">
                                                <div class="col-xs-3">
                                                    <label>Nama Pengguna</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $user['nama_user']; ?>" name="nama">
                                                </div>
                                            </div>

                                            <div class="form-group" id="divalamat">
                                                <div class="col-xs-3">
                                                    <label>Username</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $user['username']; ?>" name="username">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divtlp">
                                                <div class="col-xs-3">
                                                    <label>Password Lama</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="password" class="form-control" name="password">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divtlp">
                                                <div class="col-xs-3">
                                                    <label>Password Baru</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="password" class="form-control" name="password1">
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