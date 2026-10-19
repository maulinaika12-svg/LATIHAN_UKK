<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM penjualan WHERE penjualan_id='$id'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}

if (isset($_POST['update'])) {

    $tanggal = $_POST['tanggal_penjualan'];
    $total = $_POST['total_harga'];
    $pelanggan = $_POST['pelanggan_id'];

    $update = mysqli_query($koneksi, "UPDATE penjualan SET
        tanggal_penjualan='$tanggal',
        total_harga='$total',
        pelanggan_id='$pelanggan'
        WHERE penjualan_id='$id'
    ");

    if ($update) {
        header("Location: penjualan.php");
        exit;
    } else {
        echo "Gagal update data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Edit Penjualan</h2>

    <form method="POST">

        <div class="mb-3">
            <label>Tanggal Penjualan</label>

            <input type="date"
                   name="tanggal_penjualan"
                   class="form-control"
                   value="<?php echo $data['tanggal_penjualan']; ?>"
                   required>
        </div>


        <div class="mb-3">

            <label>Pelanggan</label>

            <select name="pelanggan_id"
                    class="form-control"
                    required>

                <option value="">-- Pilih Pelanggan --</option>

                <?php

                $pelanggan = mysqli_query(
                    $koneksi,
                    "SELECT * FROM pelanggan"
                );

                while ($p = mysqli_fetch_assoc($pelanggan)) {

                    $selected = "";

                    if ($p['pelanggan_id'] == $data['pelanggan_id']) {
                        $selected = "selected";
                    }

                ?>

                    <option value="<?php echo $p['pelanggan_id']; ?>"
                            <?php echo $selected; ?>>

                        <?php echo $p['nama_pelanggan']; ?>

                    </option>

                <?php } ?>

            </select>

        </div>


        <div class="mb-3">

            <label>Total Harga</label>

            <input type="number"
                   name="total_harga"
                   class="form-control"
                   value="<?php echo $data['total_harga']; ?>"
                   required>

        </div>


        <button type="submit"
                name="update"
                class="btn btn-primary">

            Update

        </button>


        <a href="penjualan.php"
           class="btn btn-secondary">

            Kembali

        </a>

    </form>

</div>

</body>

</html>