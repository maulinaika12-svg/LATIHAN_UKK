<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$nama = $_SESSION['nama'];
$level = $_SESSION['level'];


// ===============================
// TOTAL PENDAPATAN
// ===============================

$query_pendapatan = mysqli_query(
    $koneksi,
    "SELECT SUM(total_harga) AS total_pendapatan FROM penjualan"
);

$data_pendapatan = mysqli_fetch_assoc($query_pendapatan);

$total_pendapatan = $data_pendapatan['total_pendapatan'];

if ($total_pendapatan == null) {
    $total_pendapatan = 0;
}


// ===============================
// JUMLAH TRANSAKSI
// ===============================

$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah_transaksi FROM penjualan"
);

$data_transaksi = mysqli_fetch_assoc($query_transaksi);

$jumlah_transaksi = $data_transaksi['jumlah_transaksi'];


// ===============================
// PENJUALAN TERBARU
// ===============================

$query_laporan = mysqli_query(
    $koneksi,
    "SELECT penjualan.*,
            pelanggan.nama_pelanggan
     FROM penjualan
     LEFT JOIN pelanggan
     ON penjualan.pelanggan_id = pelanggan.pelanggan_id
     ORDER BY penjualan.penjualan_id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Kasir</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icon -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        body {
            background-color: #f5f7fb;
        }


        /* NAVBAR */

        .navbar {
            background: #0d6efd;
        }

        .navbar-brand,
        .navbar-text {
            color: white !important;
        }


        /* WELCOME */

        .welcome {
            margin-top: 35px;
            margin-bottom: 30px;
        }


        /* MENU CARD */

        .menu-card {
            height: 240px;
            border: none;
            border-radius: 18px;
        }


        .menu-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 25px;
        }


        /* ICON */

        .icon-box {
            width: 55px;
            height: 55px;
            border-radius: 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
            margin-bottom: 15px;
        }


        .icon-blue {
            background: #e7f1ff;
            color: #0d6efd;
        }


        .icon-green {
            background: #e8f8ef;
            color: #198754;
        }


        .icon-orange {
            background: #fff3cd;
            color: #fd7e14;
        }


        .icon-purple {
            background: #eee5ff;
            color: #6f42c1;
        }


        .menu-btn {
            width: 100%;
        }


        /* STAT CARD */

        .stat-card {
            border: none;
            border-radius: 18px;
            min-height: 130px;
        }


        .stat-icon {
            width: 50px;
            height: 50px;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;
        }


        /* LAPORAN */

        .laporan-card {
            border: none;
            border-radius: 18px;
        }


        .table th {
            white-space: nowrap;
        }

    </style>

</head>


<body>


<!-- =============================== -->
<!-- NAVBAR -->
<!-- =============================== -->

<nav class="navbar navbar-expand-lg">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >

            <i class="bi bi-shop"></i>

            Sistem Kasir

        </a>


        <div class="d-flex align-items-center">

            <span class="navbar-text me-3">

                <i class="bi bi-person-circle"></i>

                <?= htmlspecialchars($nama); ?>

                (<?= htmlspecialchars(ucfirst($level)); ?>)

            </span>


            <a
                href="logout.php"
                class="btn btn-light btn-sm"
                onclick="return confirm('Yakin ingin keluar?')"
            >

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </a>

        </div>

    </div>

</nav>



<!-- =============================== -->
<!-- CONTENT -->
<!-- =============================== -->

<div class="container">


    <!-- =============================== -->
    <!-- WELCOME -->
    <!-- =============================== -->

    <div class="welcome">

        <h3 class="fw-bold">
            Dashboard Kasir
        </h3>

        <p class="text-muted mb-0">

            Selamat datang,

            <strong>
                <?= htmlspecialchars($nama); ?>
            </strong>.

            Silakan pilih menu yang ingin digunakan.

        </p>

    </div>



    <!-- =============================== -->
    <!-- STATISTIK -->
    <!-- =============================== -->

    <div class="row g-4 mb-4">


        <!-- TOTAL PENDAPATAN -->

        <div class="col-md-6">

            <div class="card stat-card shadow-sm">

                <div class="card-body d-flex align-items-center">

                    <div
                        class="stat-icon bg-success-subtle text-success me-3"
                    >

                        <i class="bi bi-cash-stack"></i>

                    </div>


                    <div>

                        <p class="text-muted mb-1">
                            Total Pendapatan
                        </p>

                        <h4 class="fw-bold mb-0">

                            Rp
                            <?= number_format(
                                $total_pendapatan,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h4>

                    </div>

                </div>

            </div>

        </div>



        <!-- JUMLAH TRANSAKSI -->

        <div class="col-md-6">

            <div class="card stat-card shadow-sm">

                <div class="card-body d-flex align-items-center">

                    <div
                        class="stat-icon bg-primary-subtle text-primary me-3"
                    >

                        <i class="bi bi-receipt"></i>

                    </div>


                    <div>

                        <p class="text-muted mb-1">
                            Jumlah Transaksi
                        </p>

                        <h4 class="fw-bold mb-0">

                            <?= $jumlah_transaksi; ?>

                            Transaksi

                        </h4>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =============================== -->
    <!-- MENU -->
    <!-- =============================== -->

    <div class="row g-4 mb-5">


        <!-- =============================== -->
        <!-- PRODUK -->
        <!-- =============================== -->

        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body">

                    <div>

                        <div class="icon-box icon-blue">

                            <i class="bi bi-box-seam"></i>

                        </div>


                        <h5 class="card-title fw-bold">
                            Data Produk
                        </h5>


                        <p class="card-text text-muted">

                            Melihat dan mengelola
                            data produk.

                        </p>

                    </div>


                    <!-- TOMBOL MASUK -->

                    <a
                        href="produk.php"
                        class="btn btn-primary menu-btn"
                    >

                        <i class="bi bi-arrow-right"></i>

                        Masuk

                    </a>

                </div>

            </div>

        </div>



        <!-- =============================== -->
        <!-- PELANGGAN -->
        <!-- =============================== -->

        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body">

                    <div>

                        <div class="icon-box icon-green">

                            <i class="bi bi-people"></i>

                        </div>


                        <h5 class="card-title fw-bold">
                            Data Pelanggan
                        </h5>


                        <p class="card-text text-muted">

                            Menambahkan dan melihat
                            data pelanggan.

                        </p>

                    </div>


                    <a
                        href="pelanggan.php"
                        class="btn btn-success menu-btn"
                    >

                        <i class="bi bi-arrow-right"></i>

                        Masuk

                    </a>

                </div>

            </div>

        </div>



        <!-- =============================== -->
        <!-- PENJUALAN -->
        <!-- =============================== -->

        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body">

                    <div>

                        <div class="icon-box icon-orange">

                            <i class="bi bi-cart-check"></i>

                        </div>


                        <h5 class="card-title fw-bold">
                            Penjualan
                        </h5>


                        <p class="card-text text-muted">

                            Melakukan transaksi
                            penjualan produk.

                        </p>

                    </div>


                    <a
                        href="penjualan.php"
                        class="btn btn-warning menu-btn"
                    >

                        <i class="bi bi-arrow-right"></i>

                        Masuk

                    </a>

                </div>

            </div>

        </div>



        <!-- =============================== -->
        <!-- LAPORAN -->
        <!-- =============================== -->

        <div class="col-md-4">

            <div class="card menu-card shadow-sm">

                <div class="card-body">

                    <div>

                        <div class="icon-box icon-purple">

                            <i class="bi bi-file-earmark-bar-graph"></i>

                        </div>


                        <h5 class="card-title fw-bold">
                            Laporan Penjualan
                        </h5>


                        <p class="card-text text-muted">

                            Melihat dan mencetak
                            laporan penjualan.

                        </p>

                    </div>


                    <a
                        href="laporan_penjualan.php"
                        class="btn btn-dark menu-btn"
                    >

                        <i class="bi bi-arrow-right"></i>

                        Masuk

                    </a>

                </div>

            </div>

        </div>


    </div>



    <!-- =============================== -->
    <!-- LAPORAN TERBARU -->
    <!-- =============================== -->

    <div class="card laporan-card shadow-sm mb-5">

        <div class="card-body p-4">


            <div
                class="d-flex justify-content-between align-items-center mb-3"
            >

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-bar-chart"></i>

                        Laporan Penjualan Terbaru

                    </h5>

                    <p class="text-muted mb-0">

                        Menampilkan 5 transaksi terbaru.

                    </p>

                </div>


                <a
                    href="laporan_penjualan.php"
                    class="btn btn-primary"
                >

                    <i class="bi bi-eye"></i>

                    Lihat Semua

                </a>

            </div>



            <!-- =============================== -->
            <!-- TABEL -->
            <!-- =============================== -->

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped align-middle"
                >

                    <thead class="table-primary">

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                ID Penjualan
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Pelanggan
                            </th>

                            <th>
                                Total Harga
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        $no = 1;

                        if (mysqli_num_rows($query_laporan) > 0) {

                            while ($data = mysqli_fetch_assoc($query_laporan)) {

                        ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= $data['penjualan_id']; ?>
                            </td>

                            <td>
                                <?= date(
                                    'd-m-Y',
                                    strtotime(
                                        $data['tanggal_penjualan']
                                    )
                                ); ?>
                            </td>

                            <td>

                                <?php

                                if (
                                    !empty(
                                        $data['nama_pelanggan']
                                    )
                                ) {

                                    echo htmlspecialchars(
                                        $data['nama_pelanggan']
                                    );

                                } else {

                                    echo "Umum";

                                }

                                ?>

                            </td>

                            <td>

                                Rp
                                <?= number_format(
                                    $data['total_harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </td>

                        </tr>

                        <?php

                            }

                        } else {

                        ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted"
                            >

                                Belum ada data penjualan.

                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


</div>



<!-- =============================== -->
<!-- BOOTSTRAP JS -->
<!-- =============================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>