<?php
include 'koneksi.php'; // Sertakan file koneksi.php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION["username"];

// Prepare a parameterized SQL query
$sql = "SELECT id, peran FROM user WHERE username = ?";
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
if ($peran === 'admin' || $peran === 'kasir') {
    // Allow admin to access this page

} else {
    // Redirect non-admin users to another page (e.g., login.php)
    header("Location: login.php");
    exit(); // Ensure to exit the script to prevent further execution
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $outlet = $_POST['outlet'];
    $invoice = $_POST['invoice'];
    $member = $_POST['member'];
    $paket = $_POST['paket'];
    $bayar = $_POST['bayar'];
    $biaya = $_POST['biaya'];
    $diskon = $_POST['diskon'];
    $pajak = $_POST['pajak'];
    $qty = $_POST['qty'];
    $pembayaran = $_POST['pembayaran'];
    $user = $data['id'];

    // Mendapatkan tanggal sekarang
    $tgl_sekarang = date('Y-m-d');

    // Mendapatkan nilai estimasi dari tb_paket berdasarkan id_paket yang dipilih
    $query_estimasi = "SELECT estimasi FROM tb_paket WHERE id = ?";
    $stmt_estimasi = $conn->prepare($query_estimasi);
    $stmt_estimasi->bind_param("i", $paket);
    $stmt_estimasi->execute();
    $result_estimasi = $stmt_estimasi->get_result();
    $row_estimasi = $result_estimasi->fetch_assoc();
    $estimasi = $row_estimasi['estimasi'];

    // Menambahkan nilai estimasi ke tanggal sekarang untuk mendapatkan batas waktu
    $pengerjaan = $estimasi * $qty;
    $batas = date('Y-m-d', strtotime($tgl_sekarang . ' + ' . $pengerjaan . ' days'));

    // Mendapatkan tanggal dan waktu saat ini
    $tgl = date('Y-m-d H:i:s');

    $query = "INSERT INTO tb_transaksi (id, id_outlet, kode_invoice, id_member, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, dibayar, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiisssssssi", $id, $outlet, $invoice, $member, $tgl, $batas, $bayar, $biaya, $diskon, $pajak, $pembayaran, $user);
    if ($stmt->execute()) {
        $id_detail = $_POST['id_detail'];
        $id = $_POST['id'];
        $paket = $_POST['paket'];
        $qty = $_POST['qty'];
        $ket = $_POST['ket'];
        $query = "INSERT INTO tb_detail_transaksi (id, id_transaksi, id_paket, qty, keterangan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $id_detail, $id, $paket, $qty, $ket);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Transaksi Berhasil!');
                    window.location.href = 'transaksi.php';
                </script>";
        } else {
            echo "<script>
                            alert('Transaksi Gagal!');
                            window.location.href = 'transaksi.php'; // Replace with the actual form page URL
                        </script>";
        }
    } else {
        echo "<script>
                    alert('Transaksi Gagal!');
                    window.location.href = 'transaksi.php'; // Replace with the actual form page URL
                </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
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
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Transaksi</h4>
                                    <p class="card-description">
                                        <button class="btn btn-secondary" data-toggle="modal" data-target="#tambahTran">
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
                                                tb_member ON tb_transaksi.id_member = tb_member.id;
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
                                    <div class="modal fade" id="proses" tabindex="-1" role="dialog"
                                        aria-labelledby="proses" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="proses">Proses Transaksi</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="#" id="formproses" data-userid="">
                                                        <div class="form-group">
                                                            <label for="bayar">Tanggal Bayar</label>
                                                            <input type="datetime-local" class="form-control"
                                                                name="bayar" id="bayar">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Status Pembayaran</label>
                                                            <select name="pembayaran" id="pembayaran"
                                                                class="form-control">
                                                                <option value="">Pilih Status
                                                                </option>
                                                                <?php
                                                                $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_transaksi LIKE 'dibayar'"))['Type'];
                                                                preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                                $enum = str_getcsv($matches[1], ",", "'");
                                                                foreach ($enum as $value) {
                                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <button class="btn btn-danger" data-dismiss="modal">
                                                            <i class="fa fa-times"></i> Batal
                                                        </button>

                                                        <button type="submit" name="proses" class="btn btn-success">
                                                            <i class="fa fa-check"></i> Simpan
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                                    <div class="modal fade" id="tambahTran" tabindex="-1" role="dialog"
                                        aria-labelledby="tambahOutletLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="tambahOutletLabel">Transaksi</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="" method="post">
                                                        <?php $id = mt_rand(1000, 9999); ?>
                                                        <?php $id_detail = mt_rand(1000, 9999); ?>
                                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                        <input type="hidden" name="id_detail"
                                                            value="<?php echo $id_detail; ?>">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="invoice">Kode Invoice</label>
                                                                <?php $kodetransaksi = date('Ymd') . mt_rand(1000, 9999); ?>
                                                                <input type="text" class="form-control" name="invoice"
                                                                    id="invoice" value="<?php echo $kodetransaksi; ?>"
                                                                    readonly>
                                                            </div>
                                                            <?php
                                                            $query_paket = "SELECT * FROM tb_paket";
                                                            $result_paket = mysqli_query($conn, $query_paket);
                                                            $query_outlet = "SELECT * FROM tb_outlet";
                                                            $result_outlet = mysqli_query($conn, $query_outlet);
                                                            ?>
                                                            <script type="text/JavaScript">
                                                                var paketData = [
        <?php while ($row_paket = mysqli_fetch_assoc($result_paket)) { ?> {
                                                                                                        id: '<?php echo $row_paket['id']; ?>',
                                                                                                        nama: '<?php echo $row_paket['nama_paket'] ?>',
                                                                                                        id_outlet: '<?php echo $row_paket['id_outlet']; ?>',
                                                                                                        harga: '<?php echo $row_paket['harga']; ?>'
                                                                                                    },
        <?php } ?>
    ];
    var outletData = [
        <?php while ($row_outlet = mysqli_fetch_assoc($result_outlet)) { ?> {
                                                                                                        id: '<?php echo $row_outlet['id']; ?>',
                                                                                                        nama: '<?php echo $row_outlet['nama_outlet']; ?>'
                                                                                                    },
        <?php } ?>
    ];
</script>
                                                            <div class="form-group col-md-4">
                                                                <label for="outlet">Outlet</label>
                                                                <select name="outlet" id="outlet" class="form-control"
                                                                    onchange="filterPaket()">
                                                                    <option value="">Pilih Outlet</option>
                                                                    <?php
                                                                    $queryoutlet = "SELECT * FROM tb_outlet";
                                                                    $outletResult = $conn->query($queryoutlet);
                                                                    if ($outletResult->num_rows > 0) {
                                                                        while ($outlet = $outletResult->fetch_assoc()) {
                                                                            echo '<option value="' . $outlet['id'] . '">' . $outlet['nama_outlet'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="member">Pelanggan</label>
                                                                <select name="member" id="member" class="form-control">
                                                                    <option value="">Pilih Pelanggan</option>
                                                                    <?php
                                                                    $queryPel = "SELECT * FROM tb_member";
                                                                    $pelResult = $conn->query($queryPel);
                                                                    if ($pelResult->num_rows > 0) {
                                                                        while ($member = $pelResult->fetch_assoc()) {
                                                                            echo '<option value="' . $member['id'] . '">' . $member['nama_member'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <script type="text/JavaScript">
                                                            function totalHarga() {
                                                            var paketSelect = document.getElementById("paket");
                                                            var diskonVal = parseFloat(document.getElementById("diskon").value) || 0;
                                                            var biayaVal = parseFloat(document.getElementById("biaya").value) || 0;
                                                            var pajakVal = parseFloat(document.getElementById("pajak").value) || 0;
                                                            var qtyVal = parseFloat(document.getElementById("qty").value) || 0;

                                                            var selectedPaket = paketSelect.options[paketSelect.selectedIndex];
                                                            var hargaPaket = parseFloat(selectedPaket.getAttribute("data-harga")) || 0;

                                                            // Hitung total
                                                            var total = (hargaPaket * qtyVal) + biayaVal + pajakVal - (diskonVal / 100 * hargaPaket);

                                                            // Tampilkan total jika hasil perhitungan adalah angka, jika tidak tampilkan pesan kesalahan
                                                            if (!isNaN(total)) {
                                                                document.getElementById("total").textContent = "Rp. " + total;
                                                            } else {
                                                                document.getElementById("total").textContent = "Input tidak valid";
                                                            }
                                                        }


                                                        </script>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="biaya">Biaya</label>
                                                                <select name="biaya" id="biaya" class="form-control">
                                                                    <option value="">Pilih Biaya Tambahan</option>
                                                                    <?php
                                                                    $querybiaya = "SELECT * FROM biaya_tambahan WHERE tipe = 'biaya_tambahan'";
                                                                    $biayaResult = $conn->query($querybiaya);
                                                                    if ($biayaResult->num_rows > 0) {
                                                                        while ($biaya = $biayaResult->fetch_assoc()) {
                                                                            echo '<option value="' . $biaya['biaya'] . '">' . $biaya['jenis'] . "&nbsp;-&nbsp;" . $biaya['biaya'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="diskon">Diskon</label>
                                                                <select name="diskon" id="diskon" class="form-control">
                                                                    <option value="">Pilih Diskon</option>
                                                                    <?php
                                                                    $querybiaya = "SELECT * FROM biaya_tambahan WHERE tipe = 'diskon'";
                                                                    $biayaResult = $conn->query($querybiaya);
                                                                    if ($biayaResult->num_rows > 0) {
                                                                        while ($biaya = $biayaResult->fetch_assoc()) {
                                                                            echo '<option value="' . $biaya['biaya'] . '">' . $biaya['jenis'] . "&nbsp;-&nbsp;" . $biaya['biaya'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-md-4">
                                                                <label for="pajak">Pajak</label>
                                                                <select name="pajak" id="pajak" class="form-control">
                                                                    <option value="">Pilih Pajak</option>
                                                                    <?php
                                                                    $querybiaya = "SELECT * FROM biaya_tambahan WHERE tipe = 'pajak'";
                                                                    $biayaResult = $conn->query($querybiaya);
                                                                    if ($biayaResult->num_rows > 0) {
                                                                        while ($biaya = $biayaResult->fetch_assoc()) {
                                                                            echo '<option value="' . $biaya['biaya'] . '">' . $biaya['jenis'] . "&nbsp;-&nbsp;" . $biaya['biaya'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label for="qty">Qty</label>
                                                                <input type="text" class="form-control" name="qty"
                                                                    id="qty">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="paket">Paket</label>
                                                                <select name="paket" id="paket" class="form-control"
                                                                    onchange="totalHarga()">
                                                                    <option value="">Pilih Paket</option>
                                                                    <?php
                                                                    $queryPak = "SELECT * FROM tb_paket";
                                                                    $pakResult = $conn->query($queryPak);
                                                                    if ($pakResult->num_rows > 0) {
                                                                        while ($paket = $pakResult->fetch_assoc()) {
                                                                            echo '<option value="' . $paket['id'] . '">' . $paket['nama_paket'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>


                                                            <div class="form-group col-md-4">
                                                                <label for="ket">Keterangan</label>
                                                                <input type="text" class="form-control" name="ket"
                                                                    id="ket">
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <!-- <div class="form-group col-md-4">
                                                                <label>Status</label>
                                                                <select name="status" id="status" class="form-control">
                                                                    <option value="">Pilih Status</option>
                                                                    <?php
                                                                    $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_transaksi LIKE 'status'"))['Type'];
                                                                    preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                                    $enum = str_getcsv($matches[1], ",", "'");
                                                                    foreach ($enum as $value) {
                                                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div> -->

                                                            <div class="form-group col-md-4">
                                                                <label for="bayar">Tanggal Bayar</label>
                                                                <input type="datetime-local" class="form-control"
                                                                    name="bayar" id="bayar">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label>Status Pembayaran</label>
                                                                <select name="pembayaran" id="pembayaran"
                                                                    class="form-control">
                                                                    <option value="">Pilih Status</option>
                                                                    <?php
                                                                    $enum_values = mysqli_fetch_assoc(mysqli_query($conn, "SHOW COLUMNS FROM tb_transaksi LIKE 'dibayar'"))['Type'];
                                                                    preg_match('/enum\((.*)\)$/', $enum_values, $matches);
                                                                    $enum = str_getcsv($matches[1], ",", "'");
                                                                    foreach ($enum as $value) {
                                                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <h1 id="total">Rp.</h1>
                                                            </div>


                                                            <!-- <div class="form-group col-md-4">
                                                                <label>Pengguna</label>
                                                                <select name="user" id="user" class="form-control">
                                                                    <option value="">Pilih Pengguna</option>
                                                                    <?php
                                                                    $queryPen = "SELECT * FROM user";
                                                                    $penResult = $conn->query($queryPen);
                                                                    if ($penResult->num_rows > 0) {
                                                                        while ($user = $penResult->fetch_assoc()) {
                                                                            echo '<option value="' . $user['id'] . '">' . $user['nama_user'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div> -->
                                                        </div>


                                                        <button name="submit" type="submit" class="btn btn-primary"
                                                            id="btnSubmit">Submit</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.box -->
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/JavaScript">
        function filterPaket() {
        var outletSelect = document.getElementById("outlet");
        var paketSelect = document.getElementById("paket");

        paketSelect.innerHTML = '';

        var selectedOutletId = outletSelect.value;

        var defaultOption = document.createElement("option");
        defaultOption.text = "Pilih Paket";
        defaultOption.value = "";
        paketSelect.appendChild(defaultOption);

        paketData.forEach(function (paket) {
            if (paket.id_outlet === selectedOutletId) {
                var option = document.createElement("option");
                option.text = paket.nama;
                option.value = paket.id;
                option.setAttribute("data-harga", paket.harga); // Corrected setting data attribute
                paketSelect.appendChild(option);
            }
        });
    }
    var baseUrl = "http://localhost/laundry/laundryyy";
    $(document).on('click', '.delete-btn', function () {
    var userId = $(this).data('userid');
    // Set the userId in the data-userid attribute for the "YES" button
    $("#deleteUserBtn").data("userid", userId);
    // Construct the delete URL with the userId using baseUrl
    var deleteUrl = baseUrl + "/hapus_tran.php?id=" + userId;
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
    $(document).on('click', '.process-btn', function () {
        var userId = $(this).data('userid');
        // Set the userId in the data-userid attribute for the form
        $("#formproses").data("userid", userId);
    });

    // When the form is submitted, construct the URL with userId
    $(document).on('submit', '#formproses', function (e) {
        e.preventDefault(); // Prevent default form submission
        var userId = $(this).data("userid");
        var processUrl = baseUrl + "/proses.php?id=" + userId;
        // Redirect to the constructed URL
        window.location.href = processUrl;
    });

    // Reset the userId in the form when the modal is hidden
    $('#proses').on('hidden.bs.modal', function () {
        $("#formproses").data("userid", "");
    });
    // Fungsi pencarian JavaScript
    document.getElementById('search-input').addEventListener('input', function () {
    let searchQuery = this.value.toLowerCase();
    let table = document.getElementById('outlet-table');
    let rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) { let row=rows[i]; let rowData=row.innerText.toLowerCase(); if
        (rowData.includes(searchQuery)) { row.style.display='' ; } else { row.style.display='none' ; } } }); </script>
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