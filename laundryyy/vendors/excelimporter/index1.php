<?php include 'db_connection.php' ?>
<!DOCTYPE html>
<html>
<head>
    <title>Show Database Table</title>
</head>
<body>
    <h2>Show Database Table</h2>
    <form action="display.php" method="post">
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
        <input type="submit" value="Show Table">
    </form>
</body>
</html>
