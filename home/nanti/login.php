<?php
require 'koneksi.php';
session_start();

// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // ambil username dan id berdasarkan id cookie
    $result = mysqli_query($conn, "SELECT id, username FROM user WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if ($key === $row['id']) {
        $_SESSION['login'] = true;
        $_SESSION['id'] = $row['id']; // Simpan id di sesi
        // $_SESSION['username'] = $row['username']; // Simpan username di sesi
    }
}



if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        echo "<script language='JavaScript'>
        alert('Username dan password wajib diisi');
        document.location = 'login.php';
        </script>";
        exit;
    }

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

    // cek username
    if ($result->num_rows === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row["password"]) {
            // set session
            $_SESSION["login"] = true;
            $_SESSION['username'] = $username;
            // Jika login berhasil, arahkan ke halaman berdasarkan peran
            $peran = $row['peran'];
            if ($peran == 'member') {
                header("Location: index.php");
            } else {
                echo "<script language='JavaScript'>
            alert('Pengguna tidak ditemukan');
            document.location = 'login.php';
            </script>";
            }
            exit;
        } else {
            echo "<script language='JavaScript'>
            alert('Password salah');
            document.location = 'login.php';
            </script>";
            exit;
        }
    } else {
        echo "<script language='JavaScript'>
        alert('Username tidak ditemukan');
        document.location = 'login.php';
        </script>";
        exit;

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laundry</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="Login_v1/css/util.css">
    <link rel="stylesheet" type="text/css" href="Login_v1/css/main.css">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="img/dicuciin.png" alt="IMG" width="400px">
                </div>

                <form action="" method="post" class="login">
                    <span class="login100-form-title">
                        Login
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" type="text" name="username" placeholder="Username">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" name="login">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="Login_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login_v1/vendor/bootstrap/js/popper.js"></script>
    <script src="Login_v1/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login_v1/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login_v1/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="Login_v1/js/main.js"></script>

</body>

</html>