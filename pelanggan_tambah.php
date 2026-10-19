<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];

    $query = mysqli_query($koneksi, "INSERT INTO pelanggan
        (nama_pelanggan, alamat, nomor_telepon)
        VALUES
        ('$nama_pelanggan', '$alamat', '$nomor_telepon')");

    if ($query) {
        header("Location: pelanggan.php");
        exit;
    } else {
        echo "Data gagal disimpan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Tambah Pelanggan</h2>

    <form method="POST">

        <div class="mb-3">
            <label>Nama Pelanggan</label>

            <input type="text"
                   name="nama_pelanggan"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>

            <textarea name="alamat"
                      class="form-control"
                      rows="3"
                      required></textarea>
        </div>

        <div class="mb-3">
            <label>Nomor Telepon</label>

            <input type="text"
                   name="nomor_telepon"
                   class="form-control"
                   required>
        </div>

        <button type="submit"
                name="simpan"
                class="btn btn-primary">
            Simpan
        </button>

        <a href="pelanggan.php"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>