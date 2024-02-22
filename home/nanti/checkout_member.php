<?php
include 'koneksi.php';
include 'sidebar.php';

if (isset($_GET['id']) && isset($_GET['qty'])) {
    $id_paket = $_GET['id'];
    $qty = $_GET['qty'];
    $query = "SELECT tb_paket.*, tb_outlet.nama_outlet FROM tb_paket INNER JOIN tb_outlet ON tb_paket.id_outlet = tb_outlet.id WHERE tb_paket.id='$id_paket';";
    $result = mysqli_query($conn, $query);
    if ($result->num_rows > 0) {
        $paket = $result->fetch_assoc();
        $harga = $paket['harga'] * $qty - ($paket['harga'] * 10 / 100);
        $outlet = $paket['id_outlet'];
    } else {
        echo "Failed to fetch data";
        exit;
    }
} else {
    echo "Missing parameters";
    exit;
}

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
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $invoice = $_POST['invoice'];
    $bayar = $_POST['bayar'];
    $diskon = 10;
    $user = "1111";

    // Mendapatkan tanggal sekarang
    $tgl_sekarang = date('Y-m-d');

    // Mendapatkan nilai estimasi dari tb_paket berdasarkan id_paket yang dipilih
    $query_estimasi = "SELECT estimasi FROM tb_paket WHERE id = ?";
    $stmt_estimasi = $conn->prepare($query_estimasi);
    $stmt_estimasi->bind_param("i", $id_paket);
    $stmt_estimasi->execute();
    $result_estimasi = $stmt_estimasi->get_result();
    $row_estimasi = $result_estimasi->fetch_assoc();
    $estimasi = $row_estimasi['estimasi'];

    // Menambahkan nilai estimasi ke tanggal sekarang untuk mendapatkan batas waktu
    $batas = date('Y-m-d', strtotime($tgl_sekarang . ' + ' . $estimasi . ' days'));

    // Mendapatkan tanggal dan waktu saat ini
    $tgl = date('Y-m-d H:i:s');

    $query = "INSERT INTO tb_transaksi (id, id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, id_user, diskon) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiissssis", $id, $outlet, $invoice, $member, $tgl, $batas, $tgl, $user, $diskon);
    if ($stmt->execute()) {
        $id_detail = $_POST['id_detail'];
        $id = $_POST['id'];
        $ket = $_POST['ket'];
        $query = "INSERT INTO tb_detail_transaksi (id, id_transaksi, id_paket, qty, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $id_detail, $id, $id_paket, $qty, $ket);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Transaksi Berhasil!');
                    window.location.href = 'print_struk_member.php?id=" . $id . "';
                  </script>";
        } else {
            echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'print_struk_member.php?id=" . $id . "'; // Replace with the actual form page URL
                  </script>";
        }
    } else {
        echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'print_struk_member.php?id=" . $id . "'; // Replace with the actual form page URL
                  </script>";
    }

}
?>
<!doctype html>
<html>

<head>
    <?php include 'head.php'; ?>
</head>

<body>

    <header>
    </header>

    <!-- Header Area End Here -->
    <!-- Inner Page Banner Area Start Here -->
    <div class="inner-page-banner-area">
        <div class="container">
            <div class="pagination-area">
                <h2>Pesan</h2>
                <ul>
                    <li><a href="index.php">Home </a> /</li>
                    <li>Pesan</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Page Banner Area End Here -->



    <!--===================================
Check Out
-==========-->
    <div class="container">
        <!--<h1 class="page-title">Checkout Order</h1>
            <p class="text-center">Sign in or Register to your <a href="#">Maxshop profile</a> to faster order checkout.</p>-->
        <div class="gap gap-small"></div>
        <div class="row">
            <div class="col-md-4">
                <div class="widget-header widget-header-sm text-center widget-header-bottom-line">
                    <h3 class="widget-title">Order info</h3>
                </div>
                <table class="table cart-table">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>QTY</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $paket['nama_paket']; ?>
                            </td>
                            <td>
                                <?php echo $qty; ?>
                            </td>
                            <td>Rp.
                                <?php echo number_format($harga, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="widget-header widget-header-sm text-center widget-header-bottom-line">
                    <h3 class="widget-title">Detail Pembayaran</h3>
                </div>
                <form action="" method="post">
                    <?php $id = mt_rand(1000, 9999); ?>
                    <?php $id_detail = mt_rand(1000, 9999); ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="id_detail" value="<?php echo $id_detail; ?>">
                    <div class="form-group">
                        <label for="invoice">Kode Invoice</label>
                        <?php $kodetransaksi = date('Ymd') . mt_rand(1000, 9999); ?>
                        <input type="text" class="form-control" name="invoice" id="invoice"
                            value="<?php echo $kodetransaksi; ?>" readonly>
                    </div>
                    <?php
                    $query_tran = "SELECT * FROM tb_member WHERE tb_member.nama_member='$nama';
                ";
                    $result_tran = mysqli_query($conn, $query_tran);
                    if ($result_tran->num_rows >= 0) {
                        $tran = $result_tran->fetch_assoc();
                    } else {
                        echo "Failed to fetch data";
                        exit;
                    } ?>
                    <div class="form-group">
                        <label>Nama</label>
                        <input name="nama" class="form-control" type="text" required
                            value="<?php echo $tran['nama_member']; ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input name="tlp" class="form-control" type="text" required value="<?php echo $tran['tlp']; ?>"
                            readonly />
                    </div>
                    <div class="form-group">
                        <label>Alamat </label>
                        <textarea name="alamat" class="form-control" type="text" required
                            readonly><?php echo $tran['alamat']; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="ket" class="form-control" type="text"></textarea>
                    </div>
            </div>
            <div class="col-md-4">
                <div class="widget-header widget-header-sm text-center widget-header-bottom-line">
                    <h3 class="widget-title">Pembayaran</h3>
                </div>
                <div class="cc-form">
                    <div class="clearfix">
                        <p>Transfer ke 1122323434454 (BRI) Sesuai tarif yang tertera</p>
                    </div>
                </div>
                <a class="btn btn-danger" data-toggle="modal" data-target="#paymentModal">Bayar</a>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Konfirmasi Pembayaran</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin melanjutkan pembayaran?</p>
                            <h3>Rp.
                                <?php echo number_format($harga, 0, ',', '.'); ?>
                            </h3>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" name="bayar" class="btn btn-primary">Ya,
                                Lanjutkan</button></form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="gap"></div>
    <!--===================================
Check Out
-==========-->
    <!-- Footer Area Start Here -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>
    <!-- Footer Area End Here -->
    </div>

    <!-- Preloader Start Here -->
    <div id="preloader"></div>
    <!-- Preloader End Here -->

    <!--=============================================
  jquery
  =====================================================-->
    <script src="js/jquery-2.2.4.min.js" type="text/javascript"></script>

    <!-- Plugins js -->
    <script src="js/plugins.js" type="text/javascript"></script>

    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>

    <!-- WOW JS -->
    <script src="js/wow.min.js"></script>

    <!-- Nivo slider js -->
    <script src="vendor/slider/js/jquery.nivo.slider.js" type="text/javascript"></script>
    <script src="vendor/slider/js/home.js" type="text/javascript"></script>

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

    <!-- Date Time Picker Js -->
    <script src="js/jquery.datetimepicker.full.min.js" type="text/javascript"></script>

    <!-- Validator js -->
    <script src="js/validator.min.js" type="text/javascript"></script>


    <!-- Custom Js -->
    <script src="js/i-check.js" type="text/javascript"> </script>
    <script src="js/main.js" type="text/javascript"></script>



</body>

</html>