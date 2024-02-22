<?php include 'db_connection.php';
session_start();
 ?>
<div class="message">
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']); // Hapus pesan sukses setelah ditampilkan
    } elseif (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']); // Hapus pesan kesalahan setelah ditampilkan
    } elseif (isset($_SESSION['info_message'])) {
        echo '<div class="alert alert-info">' . $_SESSION['info_message'] . '</div>';
        unset($_SESSION['info_message']); // Hapus pesan informasi setelah ditampilkan
    }
    ?>
</div>

<!DOCTYPE html>
<html>
<head>
    <title>Import Data from Excel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        .custom-card {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card-content {
            padding: 20px;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
    }
    
    .modal-content {
        background-color: white;
        width: 60%;
        margin: 10% auto;
        padding: 20px;
        border-radius: 5px;
    }
    
    .show-modal {
        display: block;
    }
     .excel-preview {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        overflow: auto;
    }
    
    .excel-preview-content {
        background-color: white;
        width: 80%;
        margin: 5% auto;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
    }
    
    .show-excel-preview {
        display: block;
    }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">
            <div class="card custom-card">
                <div class="card-content">
                    <h2 style="text-align: center;">Import Data from Excel</h2><br><br>
                <div class="modal" id="formatModal">
                    <div class="modal-content">
                        <h5 class="modal-title">Excel Format</h5>
                        <p>Ini adalah format file excel yang akan diupload:</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>header(unik)</th>
                                    <th>header</th>
                                    <th>header</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table><form action="import.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="csvFile" accept=".csv">
                        <input type="text" name="tableName" placeholder="Table Name">
                        <input type="submit" value="Import" style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; text-align: center; text-decoration: none; font-size: 14px; cursor: pointer;">
                    </form><br><br>
                <button class="btn btn-secondary" onclick="closeFormatPopup()">Close</button><br>
                        <a href="download_format.php" class="btn btn-primary">Download Format as Excel</a><br>
                        
                    </div>
                </div>
                
                <button class="btn btn-primary" onclick="openFormatPopup()">View Excel Format</button>
                    <h2>Show Database Table</h2>
                    <form action="index.php" method="post">
                        <label for="table">Choose a table:</label>
                        <select name="table" id="table">
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Mendapatkan daftar tabel dari database
                            $tablesQuery = "SHOW TABLES";
                            $tablesResult = $conn->query($tablesQuery);

                            if ($tablesResult->num_rows > 0) {
                                while ($tableRow = $tablesResult->fetch_row()) {
                                    echo "<option value='" . $tableRow[0] . "'>" . $tableRow[0] . "</option>";
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                        <input type="submit" value="Show Table" style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; text-align: center; text-decoration: none; font-size: 14px; cursor: pointer;">
                    </form><br>



                    <?php
                    // Check if a table is selected and display its content
                   // Check if a table is selected and display its content
if (isset($_POST['table'])) {
    $selectedTable = $_POST['table'];

    // Set selectedTable as a session variable
    $_SESSION['selectedTable'] = $selectedTable;

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve data from the selected table
    $query = "SELECT * FROM $selectedTable";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $numRows = $result->num_rows; // Menghitung jumlah data dalam tabel
        echo "<h2>Table: $selectedTable</h2>";
        echo "<form action='export.php' method='post'>";
        echo "<input type='hidden' name='table' value='$selectedTable'>";
        echo "<input type='submit' name='export' value='Download Excel' class='btn btn-primary'> ";
        echo "</form>";
        echo "<br>";
        echo "<a href='add_data_form.php' class='btn btn-primary'>Tambah Data CSV</a>";
        echo "<br>";
        echo "<p>Jumlah data dalam tabel ini: $numRows</p>"; // Menampilkan jumlah data dalam tabel
        echo "<table>";
        // Print table headers, excluding the "id" column
        $row = $result->fetch_assoc();
        echo "<tr>";
        foreach ($row as $key => $value) {
            if ($key !== 'id') {
                echo "<th>$key</th>";
            }
        }
        echo "</tr>";

        // Print table rows, excluding the "id" column
        while ($row) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                if ($key !== 'id') {
                    echo "<td>$value</td>";
                }
            }
            echo "</tr>";
            $row = $result->fetch_assoc();
        }
        echo "</table>";
    } else {
        echo "No data in the selected table.";
    }

    $conn->close();

                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openExcelPreview() {
        const excelPreviewModal = document.getElementById('excelPreviewModal');
        excelPreviewModal.classList.add('show-excel-preview');
    }

    function closeExcelPreview() {
        const excelPreviewModal = document.getElementById('excelPreviewModal');
        excelPreviewModal.classList.remove('show-excel-preview');
    }

    function downloadExcel() {
        const downloadLink = document.getElementById('downloadLink');
        downloadLink.click();
    }
    function openFormatPopup() {
        const formatModal = document.getElementById('formatModal');
        formatModal.classList.add('show-modal');
    }

    function closeFormatPopup() {
        const formatModal = document.getElementById('formatModal');
        formatModal.classList.remove('show-modal');
    }

</script>
</body>
</html>
