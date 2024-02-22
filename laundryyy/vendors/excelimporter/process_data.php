<?php
session_start();
require 'db_connection.php';
require 'phpspreadsheet/vendor/autoload.php'; // Sesuaikan dengan path yang sesuai untuk autoload.php Anda

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["table"])) {
    $selectedTable = $_POST["table"];

    // Periksa apakah file CSV diunggah
    if (isset($_FILES["csvFile"]) && $_FILES["csvFile"]["error"] == UPLOAD_ERR_OK) {
        $csvFilePath = $_FILES["csvFile"]["tmp_name"];

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Baca file CSV menggunakan PhpSpreadsheet
        $spreadsheet = IOFactory::load($csvFilePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $headerRow = $worksheet->getRowIterator(1)->current();
        $sql = "SHOW COLUMNS FROM " . $selectedTable;
        $q2 = $conn->query($sql);
        $columnNames = [];

        // Ekstrak nama-nama kolom dari baris header
        foreach ($q2 as $cell) {
            if ($cell['Field'] != 'id') {
                $columnNames[] = $cell['Field'];
            }
        }

        // Siapkan query INSERT INTO
        $query = "INSERT IGNORE INTO $selectedTable (" . implode(", ", $columnNames) . ") VALUES ";

        $dataRows = iterator_to_array($worksheet->getRowIterator());
        array_shift($dataRows); // Hapus baris header

        // Bangun bagian nilai data dari query
        $dataValues = [];
        foreach ($dataRows as $dataRow) {
            $rowData = [];
            foreach ($dataRow->getCellIterator() as $cell) {
                $rowData[] = "'" . $conn->real_escape_string($cell->getValue()) . "'";
            }
            $dataValues[] = "(" . implode(", ", $rowData) . ")";
        }

        // Gabungkan dan jalankan query
        $query .= implode(", ", $dataValues);

        if ($conn->query($query) === TRUE) {
            $_SESSION['success_message'] = " Data berhasil ditambahkan.";
        } else {
            $_SESSION['error_message'] = "Error inserting data: " . $conn->error;
        }

        $conn->close();
    } else {
        $_SESSION['error_message'] = "Please select a CSV file to upload.";
    }
} else {
    $_SESSION['error_message'] = "Please select a table.";
}

// Alihkan kembali ke halaman index atau halaman tujuan
header("Location: index.php");
?>
