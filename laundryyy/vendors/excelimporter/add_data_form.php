<?php include 'db_connection.php'; 
session_start();?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Data from CSV</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <?php if (isset($_SESSION['selectedTable'])) {
    $selectedTable = $_SESSION['selectedTable'];
} else {
   echo "pilih tabel";
} ?>
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
        </style>
</head>
<body>
    <div class="container">
    <div class="row">
        <div class="col">
            <div class="card custom-card">
                <div class="card-content">

        <h2>Add Data from CSV to <?php echo $selectedTable; ?></h2>
        <br>
        <form action="process_data.php" method="post" enctype="multipart/form-data">
            <label for="csvFile">Choose a CSV file:</label>
            <input type="file" name="csvFile" accept=".csv" required>
            <input type="hidden" name="table" value="<?php echo $selectedTable; ?>">
            <input type="submit" value="Add Data" class="btn btn-primary">
        </form>
        <br>
        <a href="index.php" class="btn btn-primary">Back to Index</a>
    </div>
</div>
</div>
</div>
</div>
</body>
</html>
