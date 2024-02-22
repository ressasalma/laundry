<?php
require 'db_connection.php';
require 'phpspreadsheet/vendor/autoload.php'; // Include the PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['table'])) {
    $selectedTable = $_GET['table'];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve data from the selected table
    $query = "SELECT * FROM $selectedTable";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add table headers
        $headers = [];
        $row = $result->fetch_assoc();
        foreach ($row as $key => $value) {
            if ($key !== 'id') {
                $headers[] = $key;
            }
        }
        $sheet->fromArray([$headers]);

        // Add table data
        while ($row) {
            $rowData = [];
            foreach ($row as $key => $value) {
                if ($key !== 'id') {
                    $rowData[] = $value;
                }
            }
            $sheet->appendRow($rowData);
            $row = $result->fetch_assoc();
        }

        // Create a temporary Excel file
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_preview_');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Set the response headers for Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: inline; filename="preview.xlsx"');
        header('Cache-Control: max-age=0');

        // Output the Excel file
        readfile($tempFile);

        // Clean up and delete the temporary Excel file
        unlink($tempFile);
    } else {
        echo "No data in the selected table.";
    }

    $conn->close();
} else {
    echo "Table parameter not provided.";
}
?>
