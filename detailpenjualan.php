<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query(
    $koneksi,
    "SELECT detailpenjualan.*,
            produk.nama_produk,
            produk.harga
     FROM detailpenjualan
     JOIN produk
     ON detailpenjualan.produk_id = produk.produk_id
     WHERE detailpenjualan.penjualan_id='$id'"
);

if (!$query) {
    echo "Query error: " . mysqli_error($koneksi);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Detail Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <h2>Detail Penjualan</h2>

    <p>
        ID Penjualan :
        <b><?php echo $id; ?></b>
    </p>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>

                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $no = 1;
        $total = 0;

        while ($data = mysqli_fetch_assoc($query)) {

            $total = $total + $data['subtotal'];

        ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>

                <td>
                    <?php echo $data['nama_produk']; ?>
                </td>

                <td>
                    Rp <?php echo number_format(
                        $data['harga'],
                        0,
                        ',',
                        '.'
                    ); ?>
                </td>

                <td>
                    <?php echo $data['jumlah_produk']; ?>
                </td>

                <td>
                    Rp <?php echo number_format(
                        $data['subtotal'],
                        0,
                        ',',
                        '.'
                    ); ?>
                </td>

            </tr>

        <?php } ?>

            <tr>

                <td colspan="4" class="text-end">

                    <b>Total</b>

                </td>

                <td>

                    <b>
                        Rp <?php echo number_format(
                            $total,
                            0,
                            ',',
                            '.'
                        ); ?>
                    </b>

                </td>

            </tr>

        </tbody>

    </table>

    <a href="penjualan.php"
       class="btn btn-secondary">

        Kembali

    </a>

</div>

</body>

</html>