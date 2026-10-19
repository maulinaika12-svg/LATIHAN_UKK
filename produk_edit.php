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

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id == '') {
    echo "ID produk tidak ditemukan.";
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT * FROM produk
    WHERE produk_id='$id'
");

if (!$query) {
    echo "Query error: " . mysqli_error($koneksi);
    exit;
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data produk tidak ditemukan. ID: " . $id;
    exit;
}


if (isset($_POST['simpan'])) {

    $nama_produk = $_POST['nama_produk'];

    $harga = str_replace('.', '', $_POST['harga']);

    $stok = $_POST['stok'];

    $update = mysqli_query($koneksi, "
        UPDATE produk SET
            nama_produk='$nama_produk',
            harga='$harga',
            stok='$stok'
        WHERE produk_id='$id'
    ");

    if ($update) {
        header("Location: produk.php");
        exit;
    } else {
        echo "Data gagal diupdate: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

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

    <h2>Edit Produk</h2>

    <form method="POST">

        <div class="mb-3">

            <label>Nama Produk</label>

            <input type="text"
                   name="nama_produk"
                   class="form-control"
                   value="<?php echo $data['nama_produk']; ?>"
                   required>

        </div>


        <div class="mb-3">

            <label>Harga</label>

            <div class="input-group">

                <span class="input-group-text">
                    Rp
                </span>

                <input type="text"
                       name="harga"
                       class="form-control"
                       value="<?php echo number_format($data['harga'], 0, ',', '.'); ?>"
                       oninput="formatRupiah(this)"
                       required>

            </div>

        </div>


        <div class="mb-3">

            <label>Stok</label>

            <input type="number"
                   name="stok"
                   class="form-control"
                   value="<?php echo $data['stok']; ?>"
                   required>

        </div>


        <button type="submit"
                name="simpan"
                class="btn btn-warning">
            Update
        </button>

        <a href="produk.php"
           class="btn btn-secondary">
            Kembali
        </a>

    </form>

</div>

</body>
</html>