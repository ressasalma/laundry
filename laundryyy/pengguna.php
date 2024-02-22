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
                                    <h4 class="card-title">List Pengguna</h4>
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
                                                    <th>Id</th>
                                                    <th>Outlet</th>
                                                    <th>Nama</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Peran</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $queryUser = "SELECT * FROM user";
                                                $userResult = $conn->query($queryUser);
                                                $counter = 1;
                                                if ($userResult->num_rows > 0) {
                                                    while ($user = $userResult->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $user['id']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['id_outlet']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['nama_user']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['username']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['password']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $user['peran']; ?>
                                                            </td>
                                                            <td>
                                                                <a href="edit_pengguna.php?id=<?= $user["id"]; ?>"
                                                                    class="btn btn-success btn-small">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>

                                                                <a href="ganti_pass.php?id=<?= $user["id"]; ?>"
                                                                    class="btn btn-warning btn-small">
                                                                    <i class="fas fa-key"></i>
                                                                </a>

                                                                <a class="btn btn-danger btn-small delete-btn"
                                                                    data-toggle="modal" data-target="#hapus"
                                                                    data-userid="<?= $user["id"]; ?>">
                                                                    <i class="icon-trash"></i>
                                                                </a>
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
                                                    <h5 class="modal-title" id="tambahOutletLabel">Tambah Pengguna</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="tambah_pengguna.php" method="post">
                                                        <?php
                                                        $id = mt_rand(1000, 9999);
                                                        ?>
                                                        <div class="form-group" style="display: flex;">
                                                            <div class="mr-2">
                                                                <div class="form-group">
                                                                    <label for="namaPengguna">Id :</label>
                                                                    <input type="text" class="form-control" readonly
                                                                        name="id" value="<?php echo $id; ?>">
                                                                </div>
                                                            </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div class="form-group">
                                                                <label for="namaPengguna">Nama :</label>
                                                                <input type="text" class="form-control" name="nama"
                                                                    id="namaPengguna" placeholder="Masukkan Nama"
                                                                    onchange="loadUser()" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="peranPengguna">Peran :</label>
                                                            <select name="peran" id="peranPengguna" class="form-control"
                                                                onchange="loadperan()">
                                                                <?php
                                                                $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM user LIKE 'peran'"))['Type'];
                                                                preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                                $enum = str_getcsv($matches[1], ",", "'");
                                                                foreach ($enum as $value) {
                                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" style="display: none;" id="outlet">
                                                            <label for="outlet">Outlet :</label>
                                                            <select name="outlet" id="outlet" class="form-control">
                                                                <option value="">Pilih Outlet</option>
                                                                <?php
                                                                $queryoutlet = "SELECT * FROM tb_outlet";
                                                                $outletResult = $conn->query($queryoutlet);
                                                                if ($outletResult->num_rows > 0) {
                                                                    while ($outlet = $outletResult->fetch_assoc()) { ?>
                                                                        <option value="<?php echo $outlet['id']; ?>">
                                                                            <?php echo $outlet['nama_outlet']; ?>
                                                                        </option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" style="display: flex;">
                                                            <div class="mr-2">
                                                                <label for="usernamePengguna">Username:</label>
                                                                <input type="text" class="form-control" name="username"
                                                                    id="usernamePengguna" placeholder="Username"
                                                                    readonly>
                                                            </div>&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <div>
                                                                <label for="passwordPengguna">Password :</label>
                                                                <input type="text" class="form-control" name="password"
                                                                    id="passwordPengguna" placeholder="Password"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <p style="color: red;">*Password dan Username auto generate</p>
                                                        <p style="color: red;">*Catat atau Ingat Password dan Username
                                                            untuk akses login</p>
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
            var deleteUrl = baseUrl + "/hapus_pengguna.php?id=" + userId;
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

        function loadUser() {
            var selectedName = document.getElementById("namaPengguna").value;
            var randomNum = Math.floor(Math.random() * 1000);
            var firstName = selectedName.split(' ')[0].toLowerCase();
            var username = firstName + randomNum;
            document.getElementById("usernamePengguna").value = username;
            document.getElementById("passwordPengguna").value = username;
        }

        function loadperan() {
            var peran = document.getElementById("peranPengguna").value;
            var outletDropdown = document.getElementById("outlet");

            // Jika peran adalah kasir atau owner, tampilkan dropdown outlet
            if (peran === "kasir") {
                outletDropdown.style.display = "block";
            } else {
                // Jika peran selain kasir atau owner, sembunyikan dropdown outlet
                outletDropdown.style.display = "none";
            }
        }
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