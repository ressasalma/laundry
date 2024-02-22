<?php
include 'koneksi.php';

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
?>
<ul class="nav">
    <?php if ($peran === 'admin') { ?>
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#data" aria-expanded="false" aria-controls="data">
                <i class="icon-layout menu-icon"></i>
                <span class="menu-title">Master Data</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="data">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="outlet.php">
                            <span class="menu-title">Outlet</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pengguna.php">
                            <span class="menu-title">Pengguna</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pelanggan.php">
                            <span class="menu-title">Pelanggan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">
                            <span class="menu-title">Paket</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="biaya.php">
                            <span class="menu-title">Biaya Tambahan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="transaksi.php">
                <i class="icon-file menu-icon"></i>
                <span class="menu-title">Transaksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="laporan.php">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Laporan</span>
            </a>
        </li>
    <?php } elseif ($peran === 'kasir') { ?>
        <li class="nav-item">
            <a class="nav-link" href="kasir.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pelanggan.php">
                <i class="icon-head menu-icon"></i>
                <span class="menu-title">Pelanggan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="transaksi_kasir.php">
                <i class="icon-file menu-icon"></i>
                <span class="menu-title">Transaksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="laporan-owner.php">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Laporan</span>
            </a>
        </li>
    <?php } elseif ($peran === 'owner') { ?>
        <li class="nav-item">
            <a class="nav-link" href="owner.php">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="laporan.php">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Laporan</span>
            </a>
        </li>

    <?php } ?>
</ul>