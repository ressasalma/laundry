<?php
include("koneki.php"); // Include your database connection script

if (isset($_GET['outletId'])) {
    $outletId = $_GET['outletId'];

    $query = "SELECT * FROM tb_paket WHERE id_outlet = '$outletId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($paket = $result->fetch_assoc()) {
            echo '<option value="' . $paket['id'] . '">' . $paket['nama_paket'] . '</option>';
        }
    } else {
        echo '<option value="">No packages available</option>';
    }
} else {
    echo '<option value="">Invalid request</option>';
}

$conn->close();
?>