<?php
// Include file for database connection
include 'koneksi.php';

// Start the PHP session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];

// Prepare a parameterized SQL query
$sql = "SELECT peran FROM user WHERE username = ?";
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

// Check the user's role
if ($peran === 'admin') {
    // Allow admin to access this page

} else {
    // Redirect non-admin users to another page (e.g., login.php)
    header("Location: login.php");
    exit(); // Ensure to exit the script to prevent further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
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
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <?php include 'sidebar.php' ?>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">List Paket</h4>
                                    <p class="card-description">
                                        <button class="btn btn-secondary" data-toggle="modal"
                                            data-target="#tambahOutlet">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </p>
                                    <ul class="navbar-nav mr-lg-2">
                                        <li class="nav-item nav-search d-none d-lg-block">
                                            <div class="input-group">
                                                <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                                    <span class="input-group-text" id="search">
                                                        <i class="icon-search"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="search-input"
                                                    placeholder="Search now" aria-label="search"
                                                    aria-describedby="search">
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="table-responsive" style="max-height: 500px;">
                                        <table class="table table-striped" id="outlet-table">
                                            <thead>
                                                <tr>
                                                    <th>Outlet</th>
                                                    <th>Jenis</th>
                                                    <th>Nama Paket</th>
                                                    <th>Estimasi</th>
                                                    <th>Harga</th>
                                                    <th>Foto Paket</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $queryUser = "SELECT tb_paket.*, tb_outlet.nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id";
                                                $userResult = $conn->query($queryUser);
                                                if ($userResult->num_rows > 0) {
                                                    while ($user = $userResult->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $user['nama_outlet']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['jenis']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['nama_paket']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['estimasi']; ?>
                                                            </td>
                                                            <td> Rp.
                                                                <?php echo number_format($user['harga'], 0, ',', '.'); ?>
                                                            </td>
                                                            <td><img src="img/<?php echo $user['foto_paket']; ?>" alt="outlet">
                                                            <td>
                                                                <a href="edit_paket.php?id=<?= $user["id"]; ?>"
                                                                    class="btn btn-success btn-small"><i
                                                                        class="fas fa-edit"></i></a>
                                                                <a class="btn btn-danger btn-small delete-btn"
                                                                    data-toggle="modal" data-target="#hapus"
                                                                    data-userid="<?= $user["id"]; ?>">
                                                                    <i class="icon-trash"></i>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>


                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal fade" id="hapus" tabindex="-1" role="dialog"
                                        aria-labelledby="hapus" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h1>HAPUS</h1>
                                                    <p style="font-size: 19px;">Apakah anda ingin hapus data ini..?</p>

                                                    <button class="btn btn-danger" data-dismiss="modal"><i
                                                            class="fa fa-times"></i> NO</button>

                                                    <!-- Use data attributes to store userId -->
                                                    <a href="#" id="deleteUserBtn" class="btn btn-success"
                                                        data-userid=""><i class="fa fa-check"></i> YES</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="tambahOutlet" tabindex="-1" role="dialog"
                                        aria-labelledby="tambahOutletLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="tambahOutletLabel">Data Paket</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="tambah_paket.php" method="post"
                                                        enctype="multipart/form-data">
                                                        <?php
                                                        $id = mt_rand(1000, 9999);
                                                        ?>
                                                        <input type="hidden" name="id" name="id"
                                                            value="<?php echo $id; ?>">
                                                        <div class="form-group" id="divIndexNumber">
                                                            <div class="col-xs-3">
                                                                <label>Outlet</label>
                                                            </div>
                                                            <div class="col-xs-6" id="divEmail1">
                                                                <select name="outlet" id="outlet" class="form-control">
                                                                    <option value="">Pilih Outlet</option>
                                                                    <?php
                                                                    $queryOutlet = "SELECT * FROM tb_outlet";
                                                                    $outletResult = $conn->query($queryOutlet);
                                                                    $counter = 1;
                                                                    if ($outletResult->num_rows > 0) {
                                                                        while ($outlet = $outletResult->fetch_assoc()) { ?>

                                                                            <option value="<?php echo $outlet['id']; ?>">
                                                                                <?php echo $outlet['nama_outlet']; ?>
                                                                            </option>
                                                                        <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" id="divIndexNumber">
                                                            <div class="col-xs-3">
                                                                <label>Jenis</label>
                                                            </div>
                                                            <div class="col-xs-6" id="divEmail1">
                                                                <select name="jenis" id="jenis" class="form-control">
                                                                    <option value="">Pilih Jenis Paket</option>
                                                                    <?php
                                                                    $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_paket LIKE 'jenis'"))['Type'];
                                                                    preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                                    $enum = str_getcsv($matches[1], ",", "'");
                                                                    foreach ($enum as $value) {
                                                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telpPelanggan">Nama Paket:</label>
                                                            <input type="text" class="form-control" name="nama"
                                                                id="nama" placeholder="Masukkan Nama Paket">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="estimasi">Estimasi:</label>
                                                            <input type="text" class="form-control" name="estimasi"
                                                                id="estimasi"
                                                                placeholder="Masukkan Estimasi Pengerjaan">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="telpPelanggan">Harga:</label>
                                                            <input type="number" class="form-control" name="harga"
                                                                id="harga" placeholder="Masukkan Harga Paket">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="foto">Foto Paket:</label>
                                                            <input type="file" class="form-control" name="foto"
                                                                id="foto">
                                                        </div>
                                                        <button type="submit" name="submit"
                                                            class="btn btn-primary">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <?php include "footer.php"; ?>
                </footer>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        var baseUrl = "http://localhost/laundry/laundryyy";
        $(document).on('click', '.delete-btn', function () {
            var userId = $(this).data('userid');
            // Set the userId in the data-userid attribute for the "YES" button
            $("#deleteUserBtn").data("userid", userId);
            // Construct the delete URL with the userId using baseUrl
            var deleteUrl = baseUrl + "/hapus_paket.php?id=" + userId;
            // Set the href attribute of the "YES" button with the constructed URL
            $("#deleteUserBtn").attr("href", deleteUrl);
        });

        // Jika tombol "YES" ditekan, arahkan langsung ke URL yang sudah dikonstruksi
        $(document).on('click', '#deleteUserBtn', function () {
            var deleteUrl = $(this).attr("href");
            window.location.href = deleteUrl;
        });

        // Reset the userId in the "YES" button when the modal is hidden
        $('#hapus').on('hidden.bs.modal', function () {
            $("#deleteUserBtn").data("userid", "");
            // Reset the href attribute to avoid unintentional clicks
            $("#deleteUserBtn").attr("href", "#");
        });
        // Fungsi pencarian JavaScript
        document.getElementById('search-input').addEventListener('input', function () {
            let searchQuery = this.value.toLowerCase();
            let table = document.getElementById('outlet-table');
            let rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let rowData = row.innerText.toLowerCase();

                if (rowData.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
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