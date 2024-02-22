<?php
include 'db_connection.php'; // Tambahkan semicolon di sini
?>

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

</style>
</head>
<body>
    <div class="container">
    <div class="row">
        <div class="col">
            <div class="card custom-card">
                <div class="card-content">
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["table"])) {
    $selectedTable = $_POST["table"];

    $query = "SELECT * FROM $selectedTable";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr>";
        while ($field = $result->fetch_field()) {
            echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . $value . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No data found in the selected table.";
    }

    $conn->close();
} else {
    echo "Please select a table.";
} ?></div></div></div></div></div>
</body>
</html>