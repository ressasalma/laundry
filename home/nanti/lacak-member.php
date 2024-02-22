<?php include 'koneksi.php';
include 'sidebar.php';
$username = $_SESSION["username"];
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query returned any rows
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $nama = $data['nama_user'];
    $member = $data['id_member']; // Corrected variable name
} else {
    echo "User tidak ditemukan";
} ?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <?php include 'head.php'; ?>
</head>

<body>


    <!-- Add your site or application content here -->
    <div class="wrapper">
        <!-- Header Area Start Here -->
        <header>
        </header>
        <!-- Header Area End Here -->
        <!-- Inner Page Banner Area Start Here -->
        <div class="inner-page-banner-area">
            <div class="container">
                <div class="pagination-area">
                    <h2>Lacak</h2>
                    <ul>
                        <li><a href="index.php">Home </a> /</li>
                        <li>Lacak</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Inner Page Banner Area End Here -->
        <!-- Food Menu 4 Area Start Here -->
        <div class="food-menu4-area section-space">
            <div class="container menu-list-wrapper">
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item nav-search d-none d-lg-block">
                        <div class="input-group">
                            <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                                <span class="input-group-text" id="search">
                                </span>
                            </div>
                            <div style="display: flex;">
                                <input type="text" class="form-control" id="search-input" placeholder="Search now"
                                    aria-label="search" aria-describedby="search">&nbsp;
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table class="table table-striped" id="outlet-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode Invoice</th>
                                <th>Tanggal Masuk</th>
                                <th>Estimasi Selesai</th>
                                <th>Paket</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
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
                                                tb_member ON tb_transaksi.id_member = tb_member.id WHERE tb_member.id = '$member'
                                                ;
                                            ";
                            $tranResult = $conn->query($queryTran);
                            if ($tranResult->num_rows > 0) {
                                while ($transaksi = $tranResult->fetch_assoc()) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $transaksi['nama_member']; ?>
                                        </td>
                                        <td>
                                            <?php echo $transaksi['kode_invoice']; ?>
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
                                            <?php echo date('d-m-Y', strtotime($transaksi['batas_waktu'])); ?>
                                        </td>

                                        <td>
                                            <?php echo $transaksi['status']; ?><br>
                                            <?php echo $transaksi['dibayar']; ?>
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
        <!-- Food Menu 4 Area End Here -->
        <!-- Footer Area Start Here -->
        <footer>
            <?php include 'footer.php'; ?>
        </footer>
        <!-- Footer Area End Here -->
    </div>

    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->
    <!-- jquery-->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script>
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

    <!-- Plugins js -->
    <script src="js/plugins.js" type="text/javascript"></script>

    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <!-- WOW JS -->
    <script src="js/wow.min.js"></script>

    <!-- Nivo slider js -->
    <script src="vendor/slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
    <script src="vendor/slider/home.js" type="text/javascript"></script>

    <!-- Owl Cauosel JS -->
    <script src="vendor/OwlCarousel/owl.carousel.min.js" type="text/javascript"></script>

    <!-- Meanmenu Js -->
    <script src="js/jquery.meanmenu.min.js" type="text/javascript"></script>

    <!-- Srollup js -->
    <script src="js/jquery.scrollUp.min.js" type="text/javascript"></script>

    <!-- jquery.counterup js -->
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>

    <!-- Isotope js -->
    <script src="js/isotope.pkgd.min.js" type="text/javascript"></script>

    <!-- Switch js -->
    <script src="js/switch-style.js" type="text/javascript"></script>

    <!-- Custom Js -->
    <script src="js/main.js" type="text/javascript"></script>

</body>

</html>