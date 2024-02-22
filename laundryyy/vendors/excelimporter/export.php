<?php
require 'phpspreadsheet/vendor/autoload.php'; // Include PhpSpreadsheet library
include 'db_connection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["table"])) {
    $selectedTable = $_POST["table"];

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve table columns excluding 'id'
    $columns = array();
    $columnsQuery = "SHOW COLUMNS FROM $selectedTable";
    $columnsResult = $conn->query($columnsQuery);
    if ($columnsResult->num_rows > 0) {
        while ($columnRow = $columnsResult->fetch_assoc()) {
            if ($columnRow['Field'] !== 'id') {
                $columns[] = $columnRow['Field'];
            }
        }
    }

    // Retrieve data from the selected table
    $query = "SELECT " . implode(", ", $columns) . " FROM $selectedTable";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        // Print table headers in the first row
        $colIndex = 1;
        foreach ($columns as $column) {
            $worksheet->setCellValueByColumnAndRow($colIndex, 1, $column);
            $colIndex++;
        }

        $rowIndex = 2;
        while ($row = $result->fetch_assoc()) {
            $colIndex = 1;
            foreach ($row as $value) {
                $worksheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $value);
                $colIndex++;
            }
            $rowIndex++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $selectedTable . '.xlsx"');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    } else {
        echo "No data found in the selected table.";
    }

    $conn->close();
} else {
    echo "Please select a table.";
}
?>
