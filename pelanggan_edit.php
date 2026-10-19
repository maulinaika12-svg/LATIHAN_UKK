<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$id = $_GET['id'];

// Ambil data pelanggan
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE pelanggan_id='$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data pelanggan tidak ditemukan";
    exit;
}

// Jika tombol Update ditekan
if (isset($_POST['update'])) {

    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $update = mysqli_query($koneksi, "UPDATE pelanggan SET
        nama_pelanggan='$nama_pelanggan',
        alamat='$alamat',
        nomor_telepon='$nomor_telepon'
        WHERE pelanggan_id='$id'
    ");

    if ($update) {
        header("Location: pelanggan.php");
        exit;
    } else {
        echo "Data gagal diupdate";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>Edit Pelanggan</h2>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text"
                   name="nama_pelanggan"
                   class="form-control"
                   value="<?= $data['nama_pelanggan']; ?>"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat"
                      class="form-control"
                      rows="3"
                      required><?= $data['alamat']; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text"
                   name="nomor_telepon"
                   class="form-control"
                   value="<?= $data['nomor_telepon']; ?>"
                   required>
        </div>

        <button type="submit" name="update" class="btn btn-warning">
            Update
        </button>

        <a href="pelanggan.php" class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>