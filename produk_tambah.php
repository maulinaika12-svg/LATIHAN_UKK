<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['level'] != 'admin') {
    echo "<script>
        alert('Halaman ini hanya dapat diakses oleh Admin!');
        window.location='produk.php';
    </script>";
    exit;
}

include "koneksi.php";

if (isset($_POST['simpan'])) {

    $nama_produk = $_POST['nama_produk'];

    // Hilangkan titik sebelum masuk database
    $harga = str_replace('.', '', $_POST['harga']);

    $stok = $_POST['stok'];

    $query = mysqli_query($koneksi, "INSERT INTO produk
        (nama_produk, harga, stok)
        VALUES
        ('$nama_produk', '$harga', '$stok')");

    if ($query) {
        header("Location: produk.php");
        exit;
    } else {
        echo "Data gagal disimpan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        function formatRupiah(input) {

            let angka = input.value.replace(/\D/g, '');

            if (angka == '') {
                input.value = '';
                return;
            }

            input.value = new Intl.NumberFormat('id-ID').format(angka);
        }
    </script>

</head>

<body>

<div class="container mt-4">

    <h2>Tambah Produk</h2>

    <form method="POST">


        <!-- NAMA PRODUK -->

        <div class="mb-3">

            <label class="form-label">
                Nama Produk
            </label>

            <input type="text"
                   name="nama_produk"
                   class="form-control"
                   placeholder="Masukkan nama produk"
                   required>

        </div>


        <!-- HARGA -->

        <div class="mb-3">

            <label class="form-label">
                Harga
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    Rp
                </span>

                <input type="text"
                       name="harga"
                       class="form-control"
                       placeholder="Masukkan harga"
                       oninput="formatRupiah(this)"
                       required>

            </div>

        </div>


        <!-- STOK -->

        <div class="mb-3">

            <label class="form-label">
                Stok
            </label>

            <input type="number"
                   name="stok"
                   class="form-control"
                   placeholder="Masukkan stok"
                   required>

        </div>


        <!-- BUTTON -->

        <button type="submit"
                name="simpan"
                class="btn btn-primary">
            Simpan
        </button>

        <a href="produk.php"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>