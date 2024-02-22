<?php include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id); // Sanitize the input

    $query = "SELECT tb_paket.*, tb_outlet.nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id WHERE tb_paket.id='$id';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $paket = mysqli_fetch_assoc($result);
    } else {
        echo "Failed to fetch data: " . mysqli_error($conn);
        exit;
    }
} else {
    // Redirect or show an error message
    echo "Gagal";
    exit;
} ?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <?php include 'head.php'; ?>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

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
                    <h2>Detail Paket</h2>
                    <ul>
                        <li><a href="index.php">Home </a> /</li>
                        <li>Layanan</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Inner Page Banner Area End Here -->
        <!-- Single Menu Area Start Here -->
        <div class="single-menu-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-8 col-xs-12">
                        <div class="single-menu-inner">
                            <div class="single-menu-inner-content">
                                <h2 class="inner-sub-title">
                                    <?php echo $paket['nama_paket']; ?>
                                </h2>
                                <span class="price">Rp.
                                    <?php echo number_format($paket['harga'], 0, ',', '.'); ?>
                                </span>
                                <p>Outlet :
                                    <?php echo $paket['nama_outlet']; ?>
                                </p>
                                <p>Jenis :
                                    <?php echo $paket['jenis']; ?>
                                </p>
                                <p>Estimasi Pengerjaan :
                                    <?php echo $paket['estimasi']; ?> hari
                                </p>

                                <ul class="single-menu-inner-cart">
                                    <li>
                                        <div class="input-group quantity-holder" id="quantity-holder">
                                            <input type="text" name='quantity' class="form-control quantity-input"
                                                value="1" placeholder="1">
                                        </div>
                                    </li>
                                    <li>
                                        <a href="#" class="ghost-on-hover-btn" id="checkout-link">Pesan</a>
                                    </li>
                                </ul>

                                <script>
                                // Ambil elemen-elemen yang diperlukan
                                const qtyInput = document.querySelector('.quantity-input');
                                const checkoutLink = document.getElementById('checkout-link');
                                const quantityPlusBtn = document.querySelector('.quantity-plus');
                                const quantityMinusBtn = document.querySelector('.quantity-minus');

                                // Fungsi untuk memperbarui tautan checkout dengan qty yang tepat
                                function updateCheckoutLink() {
                                    const id = <?= $paket["id"]; ?>;
                                    const qty = qtyInput.value;
                                    checkoutLink.href = `checkout_member.php?id=${id}&qty=${qty}`;
                                }

                                function increaseQuantity() {
                                    let qty = parseInt(qtyInput.value);
                                    if (!isNaN(qty)) {
                                        updateCheckoutLink();
                                    }
                                }

                                // Fungsi untuk mengurangi kuantitas
                                function decreaseQuantity() {
                                    let qty = parseInt(qtyInput.value);
                                    if (!isNaN(qty) && qty > 1) {
                                        updateCheckoutLink();
                                    }
                                }
                                // Panggil fungsi update saat input kuantitas berubah
                                qtyInput.addEventListener('input', updateCheckoutLink);

                                // Pasang event listener untuk menambah kuantitas saat tombol plus ditekan
                                quantityPlusBtn.addEventListener('click', increaseQuantity);

                                // Pasang event listener untuk mengurangi kuantitas saat tombol minus ditekan
                                quantityMinusBtn.addEventListener('click', decreaseQuantity);
                                </script>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Menu Area End Here -->
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

    <!-- Switch js -->
    <script src="js/switch-style.js" type="text/javascript"></script>

    <!-- Custom Js -->
    <script src="js/main.js" type="text/javascript"></script>

</body>

</html>