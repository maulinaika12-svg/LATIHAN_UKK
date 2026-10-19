<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$level = $_SESSION['level'];

$dari = "";
$sampai = "";

$query = null;
$total_semua = 0;

if (isset($_GET['tampilkan'])) {

    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];

    $query = mysqli_query(
        $koneksi,
        "SELECT penjualan.*,
                pelanggan.nama_pelanggan
         FROM penjualan
         LEFT JOIN pelanggan
         ON penjualan.pelanggan_id = pelanggan.pelanggan_id
         WHERE tanggal_penjualan BETWEEN '$dari' AND '$sampai'
         ORDER BY penjualan_id DESC"
    );

    if (!$query) {
        echo "Query error: " . mysqli_error($koneksi);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Laporan Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        @media print {

            .no-print {
                display: none;
            }

            body {
                background: white;
            }

        }

    </style>

</head>

<body>

<div class="container mt-4">

    <h2>Laporan Penjualan</h2>

    <p>
        Login sebagai:
        <b><?php echo ucfirst($level); ?></b>
    </p>


    <!-- FORM TANGGAL -->

    <div class="no-print">

        <form method="GET">

            <div class="row">

                <div class="col-md-4">

                    <label>Dari Tanggal</label>

                    <input
                        type="date"
                        name="dari"
                        class="form-control"
                        value="<?php echo $dari; ?>"
                        required
                    >

                </div>


                <div class="col-md-4">

                    <label>Sampai Tanggal</label>

                    <input
                        type="date"
                        name="sampai"
                        class="form-control"
                        value="<?php echo $sampai; ?>"
                        required
                    >

                </div>


                <div class="col-md-4">

                    <label>&nbsp;</label>

                    <div>

                        <button
                            type="submit"
                            name="tampilkan"
                            class="btn btn-primary"
                        >
                            Tampilkan
                        </button>

                        <a
                            href="laporan_penjualan.php"
                            class="btn btn-secondary"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>


    <?php if ($query) { ?>

        <hr>


        <!-- JUDUL LAPORAN -->

        <div class="text-center">

            <h3>LAPORAN PENJUALAN</h3>

            <p>

                Periode:

                <b>
                    <?php echo $dari; ?>
                </b>

                sampai

                <b>
                    <?php echo $sampai; ?>
                </b>

            </p>

        </div>


        <!-- TABEL -->

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>

                    <th>No</th>

                    <th>ID Penjualan</th>

                    <th>Tanggal</th>

                    <th>Pelanggan</th>

                    <th>Total Harga</th>

                </tr>

            </thead>


            <tbody>

            <?php

            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {

                $total_semua =
                    $total_semua + $data['total_harga'];

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

                        <?php

                        if ($data['nama_pelanggan']) {

                            echo $data['nama_pelanggan'];

                        } else {

                            echo "-";

                        }

                        ?>

                    </td>

                    <td>

                        Rp <?php

                        echo number_format(
                            $data['total_harga'],
                            0,
                            ',',
                            '.'
                        );

                        ?>

                    </td>

                </tr>

            <?php } ?>


                <!-- TOTAL -->

                <tr>

                    <td
                        colspan="4"
                        class="text-end"
                    >

                        <b>
                            TOTAL PENJUALAN
                        </b>

                    </td>

                    <td>

                        <b>

                            Rp <?php

                            echo number_format(
                                $total_semua,
                                0,
                                ',',
                                '.'
                            );

                            ?>

                        </b>

                    </td>

                </tr>

            </tbody>

        </table>


        <!-- CETAK -->

        <div class="no-print">

            <button
                onclick="window.print()"
                class="btn btn-success"
            >
                🖨 Cetak Laporan
            </button>

        </div>


    <?php } ?>


    <br>


    <a
        href="index.php"
        class="btn btn-secondary no-print"
    >
        Kembali
    </a>

</div>

</body>

</html>