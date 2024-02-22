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
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Welcome
                                        <?php echo $username; ?>!
                                        <!-- Menampilkan nama pengguna -->
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-tale">
                                <div class="card-body">
                                    <p class="mb-4">Total Pengguna</p>
                                    <?php
                                    $queryUser = "SELECT COUNT(*) AS hasil FROM user";
                                    $hasilUser = mysqli_query($conn, $queryUser);
                                    $user = mysqli_fetch_array($hasilUser);
                                    ?>
                                    <p class="fs-30 mb-2">
                                        <?php echo $user['hasil']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">Total Pelanggan</p>
                                    <?php
                                    $queryPel = "SELECT COUNT(*) AS hasil FROM tb_member";
                                    $hasilPel = mysqli_query($conn, $queryPel);
                                    $pel = mysqli_fetch_array($hasilPel);
                                    ?>
                                    <p class="fs-30 mb-2">
                                        <?php echo $pel['hasil']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-blue">
                                <div class="card-body">
                                    <p class="mb-4">Total outlet</p>
                                    <?php
                                    $queryPak = "SELECT COUNT(*) AS hasil FROM tb_outlet";
                                    $hasilPak = mysqli_query($conn, $queryPak);
                                    $outlet = mysqli_fetch_array($hasilPak);
                                    ?>
                                    <p class="fs-30 mb-2">
                                        <?php echo $outlet['hasil']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4 stretch-card transparent">
                            <div class="card card-light-danger">
                                <div class="card-body">
                                    <p class="mb-4">Total Paket</p>
                                    <?php
                                    $queryPak = "SELECT COUNT(*) AS hasil FROM tb_paket";
                                    $hasilPak = mysqli_query($conn, $queryPak);
                                    $paket = mysqli_fetch_array($hasilPak);
                                    ?>
                                    <p class="fs-30 mb-2">
                                        <?php echo $paket['hasil']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Transaksi</h4>
                                            <div class="table-responsive">
                                                <table class="table table-striped" id="outlet-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Member</th>
                                                            <th>Tanggal Masuk</th>
                                                            <th>Paket</th>
                                                            <th>Total Bayar</th>
                                                            <th>Status</th>
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
                                                tb_member ON tb_transaksi.id_member = tb_member.id WHERE tb_transaksi.status='baru';
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
                                                                <?php echo $transaksi['nama_paket']; ?></label>
                                                            </td>
                                                            <td>
                                                                <?php echo $transaksi['total']; ?>
                                                            </td>
                                                            <td>
                                                                <?php echo $transaksi['status']; ?><br>
                                                                <?php echo $transaksi['dibayar']; ?>
                                                            </td>
                                                            <td>
                                                                <a href="edit_tran.php?id=<?= $transaksi["id_transaksi"]; ?>"
                                                                    class="btn btn-success btn-small"><i
                                                                        class="fas fa-edit"></i></a>
                                                                <a href="print_struk.php?id=<?= $transaksi["id_transaksi"]; ?>"
                                                                    class="btn btn-secondary btn-small"><i
                                                                        class="fas fa-print"></i></a>
                                                                <!-- <a class="btn btn-primary btn-small process-btn"
                                                            data-toggle="modal" data-target="#proses"
                                                            data-userid="<?= $transaksi["id_transaksi"]; ?>"><i
                                                                class="far fa-check-circle"></i></a> -->
                                                                <a class="btn btn-danger btn-small delete-btn"
                                                                    data-toggle="modal" data-target="#hapus"
                                                                    data-userid="<?= $transaksi["id_transaksi"]; ?>">
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card">
                                        <div class="card-body">
                                            <canvas id="transaksi-pie"></canvas>
                                            <!-- Elemen canvas untuk Pie Chart -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            // Mengambil data transaksi untuk bulan ini
                            $bulan_ini = date('Y-m');
                            $query_pie = "SELECT tb_transaksi.id_outlet, tb_outlet.nama_outlet, COUNT(*) as total_transaksi 
                                    FROM tb_transaksi 
                                    JOIN tb_outlet ON tb_transaksi.id_outlet = tb_outlet.id
                                    WHERE DATE_FORMAT(tb_transaksi.tgl, '%Y-%m') = '$bulan_ini' 
                                    GROUP BY tb_transaksi.id_outlet;
                                    ";
                            $result_pie = mysqli_query($conn, $query_pie);

                            // Array untuk menyimpan data outlet dan jumlah transaksinya
                            $outlets_pie = array();
                            $total_transaksis_pie = array();

                            // Memasukkan data ke dalam array
                            while ($row = mysqli_fetch_assoc($result_pie)) {
                                $outlets_pie[] = $row['nama_outlet'];
                                $total_transaksis_pie[] = $row['total_transaksi'];
                            }


                            ?>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                            // Data outlet dan jumlah transaksi
                            var outlets_pie = <?php echo json_encode($outlets_pie); ?>;
                            var total_transaksis_pie =
                                <?php echo json_encode($total_transaksis_pie); ?>;

                            // Inisialisasi Chart.js untuk Pie Chart
                            var ctx_pie = document.getElementById('transaksi-pie').getContext('2d');
                            var pieChart = new Chart(ctx_pie, {
                                type: 'pie',
                                data: {
                                    labels: outlets_pie,
                                    datasets: [{
                                        label: 'Jumlah Transaksi Bulan <?php echo $bulan_ini; ?>',
                                        data: total_transaksis_pie,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(255, 159, 64, 0.2)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false
                                }
                            });
                            </script>

                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <?php include 'footer.php' ?>
                </footer>
                <!-- partial -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
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