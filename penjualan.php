<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$level = $_SESSION['level'];

$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$query = mysqli_query(
    $koneksi,
    "SELECT penjualan.*,
            pelanggan.nama_pelanggan
     FROM penjualan
     LEFT JOIN pelanggan
     ON penjualan.pelanggan_id = pelanggan.pelanggan_id
     WHERE penjualan.penjualan_id LIKE '%$search%'
        OR pelanggan.nama_pelanggan LIKE '%$search%'
     ORDER BY penjualan.penjualan_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <h2>Data Penjualan</h2>

    <p>
        Login sebagai:
        <b><?php echo ucfirst($level); ?></b>
    </p>


    <!-- SEARCH -->

    <form method="GET" class="mb-3">

        <div class="input-group">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari ID penjualan atau nama pelanggan..."
                   value="<?php echo $search; ?>">

            <button type="submit"
                    class="btn btn-primary">

                🔎 Search

            </button>

            <a href="penjualan.php"
               class="btn btn-secondary">

                Reset

            </a>

        </div>

    </form>


    <!-- TRANSAKSI BARU -->

    <a href="penjualan_tambah.php"
       class="btn btn-primary mb-3">

        + Transaksi Baru

    </a>


    <!-- TABEL -->

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>

                <th>No</th>
                <th>ID Penjualan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Pelanggan</th>
                <th>Aksi</th>

            </tr>

        </thead>


        <tbody>

        <?php

        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {

        ?>

            <tr>

                <td>
                    <?php echo $no++; ?>
                </td>

                <td>
                    <?php echo $data['penjualan_id']; ?>
                </td>

                <td>
                    <?php echo $data['tanggal_penjualan']; ?>
                </td>

                <td>

                    Rp <?php echo number_format(
                        $data['total_harga'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </td>

                <td>

                    <?php

                    if ($data['nama_pelanggan']) {

                        echo $data['nama_pelanggan'];

                    } else {

                        echo "-";

                    }

                    ?>

                </td>

                <td>


                    <!-- DETAIL -->

                    <a href="detailpenjualan.php?id=<?php echo $data['penjualan_id']; ?>"
                       class="btn btn-info btn-sm">

                        Detail

                    </a>


                    <!-- NOTA -->

                    <a href="nota.php?id=<?php echo $data['penjualan_id']; ?>"
                       target="_blank"
                       class="btn btn-success btn-sm">

                        Cetak Nota

                    </a>


                    <!-- ADMIN -->

                    <?php if ($level == 'admin') { ?>

                        <a href="penjualan_edit.php?id=<?php echo $data['penjualan_id']; ?>"
                           class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <a href="penjualan_hapus.php?id=<?php echo $data['penjualan_id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus penjualan ini?')">

                            Hapus

                        </a>

                    <?php } ?>


                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>


    <a href="index.php"
       class="btn btn-secondary">

        Kembali

    </a>

</div>

</body>

</html>