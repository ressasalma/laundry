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

    $query = "SELECT * FROM tb_outlet WHERE id='$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $outlet = mysqli_fetch_assoc($result);
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
function upload()
{
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
            </script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'jfif'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('yang anda upload bukan gambar!');
            </script>";
        return false;
    }

    // cek apakah ukurannya terlalu besar
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('ukuran gambar terlalu besar!');
            </script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate name gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);
    return $namaFileBaru;
}
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $foto_lama = isset($_POST['foto_lama']) ? $_POST['foto_lama'] : '';

    if ($_FILES['foto']['error'] === 0) {
        // File is uploaded, handle it
        $foto = upload();
        if ($foto) {
            // If photo is uploaded successfully, update the $foto variable
            $foto_lama = $foto;
        }
    }

    // If no new photo uploaded, keep the old one
    if (empty($foto_lama)) {
        $foto_lama = $outlet['foto']; // Use the existing photo if no new photo is uploaded
    }
    $update_query = "UPDATE tb_outlet SET nama_outlet = '$nama', alamat = '$alamat', tlp = '$telepon', foto_outlet = '$foto_lama' WHERE id = '$id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>
alert('Outlet ini Berhasil diedit!');
window.location.href = 'outlet.php';
</script>";
    } else {
        echo "<script>
alert('Outlet ini Gagal diedit!');
window.location.href = 'outlet.php'; // Replace with the actual form page URL
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
                                        <h3 class="box-title">Edit Outlet</h3>
                                    </div><!-- /.box-header -->
                                    <!-- form start //MSK-00097-->
                                    <form role="form" action="" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $outlet['id']; ?>">
                                        <div class="box-body">
                                            <div class="form-group" id="divnama">
                                                <div class="col-xs-3">
                                                    <label>Nama Outlet</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $outlet['nama_outlet']; ?>" name="nama">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divalamat">
                                                <div class="col-xs-3">
                                                    <label>Alamat Outlet</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $outlet['alamat']; ?>" name="alamat">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divtlp">
                                                <div class="col-xs-3">
                                                    <label>Telepon Outlet</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <input type="text" class="form-control"
                                                        value="<?php echo $outlet['tlp']; ?>" name="telepon">
                                                </div>
                                            </div>
                                            <div class="form-group" id="divtlp">
                                                <div class="col-xs-3">
                                                    <label>Foto Outlet</label>
                                                </div>
                                                <div class="col-xs-9">
                                                    <img src="img/<?php echo $outlet['foto_outlet']; ?>" id="output"
                                                        style="width:130px;height:150px;" /><br><br>
                                                    <input type="file" class="form-control" name="foto">
                                                    <input type="hidden" name="foto_lama"
                                                        value="<?php echo $outlet['foto_outlet']; ?>">
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