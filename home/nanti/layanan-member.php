<?php include 'koneksi.php'; ?>
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
            <?php include 'sidebar.php'; ?>
        </header>
        <!-- Header Area End Here -->
        <!-- Inner Page Banner Area Start Here -->
        <div class="inner-page-banner-area">
            <div class="container">
                <div class="pagination-area">
                    <h2>Layanan</h2>
                    <ul>
                        <li><a href="index.php">Home </a> /</li>
                        <li>Layanan</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Inner Page Banner Area End Here -->
        <!-- Food Menu 4 Area Start Here -->
        <div class="food-menu4-area section-space">
            <div class="container menu-list-wrapper">
                <div class="row menu-list">
                    <?php
                    $queryLay = "SELECT tb_paket.*, tb_outlet.nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id LIMIT 4";
                    $layResult = $conn->query($queryLay);
                    $isDisplayed = false; // Variabel untuk menandai apakah baris sudah ditampilkan di bagian atas
                    if ($layResult->num_rows > 0) {
                        while ($layanan = $layResult->fetch_assoc()) { ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6  menu-item">
                        <div class="food-menu4-box">
                            <img src="../laundryyy/img/<?php echo $layanan['foto_paket']; ?>" alt="dish"
                                class="img-responsive">
                            <span>
                                <?php echo number_format($layanan['harga'], 0, ',', '.'); ?>
                            </span>
                            <div class="food-menu4-box-title">
                                <h3>
                                    <?php echo $layanan['nama_paket']; ?>
                                </h3>
                                <a href="detail_paket_member.php?id=<?= $layanan["id"]; ?>" class="default-btn">Lihat
                                    Detail</a>
                            </div>
                        </div>
                    </div>
                    <?php
                            $isDisplayed = true; // Menandai bahwa baris sudah ditampilkan di bagian atas
                        }
                    }
                    ?>
                    <?php
                    if (!$isDisplayed) { // Jika baris belum ditampilkan di atas, tampilkan di sini
                        $queryLay = "SELECT tb_paket.*, tb_outlet.nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id";
                        $layResult = $conn->query($queryLay);
                        if ($layResult->num_rows > 0) {
                            while ($layanan = $layResult->fetch_assoc()) { ?>
                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6  menu-item">
                        <div class="food-menu4-box">
                            <img src="../laundryyy/img/<?php echo $layanan['foto_paket']; ?>" alt="dish"
                                class="img-responsive">
                            <span>
                                <?php echo number_format($layanan['harga'], 0, ',', '.'); ?>
                            </span>
                            <div class="food-menu4-box-title">
                                <h3>
                                    <?php echo $layanan['nama_paket']; ?>
                                </h3>
                                <a href="detail_paket.php?id=<?= $layanan["id"]; ?>" class="default-btn">Lihat
                                    Detail</a>
                            </div>
                        </div>
                    </div>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>

                <div class="loadmore">
                    <a href="#" class="ghost-color-btn">Load More</a>
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