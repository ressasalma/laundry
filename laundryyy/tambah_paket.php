<?php
include 'koneksi.php';
function upload()
{
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
            </script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'jfif'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
                alert('yang anda upload bukan gambar!');
            </script>";
        return false;
    }

    // cek apakah ukurannya terlalu besar
    if ($ukuranFile > 1000000) {
        echo "<script>
                alert('ukuran gambar terlalu besar!');
            </script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate name gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);
    return $namaFileBaru;
}
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $outlet = $_POST['outlet'];
    $nama = $_POST['nama'];
    $estimasi = $_POST['estimasi'];
    $harga = $_POST['harga'];
    $foto = upload();
    if ($foto !== false) {
        $query = "INSERT INTO tb_paket (id, id_outlet, nama_paket, estimasi, harga, foto_paket) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iissss", $id, $outlet, $nama, $estimasi, $harga, $foto);

        if ($stmt->execute()) {
            echo "<script>
alert('Paket Baru Berhasil ditambahkan!');
window.location.href = 'produk.php';
</script>";
        } else {
            echo "<script>
alert('Paket Baru Gagal ditambahkan!');
window.location.href = 'produk.php'; // Replace with the actual form page URL
</script>";
        }
    }
} ?>