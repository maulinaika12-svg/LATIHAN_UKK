```php
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

$id = $_GET['id'];


/* DATA PENJUALAN */

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM penjualan WHERE penjualan_id='$id'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data penjualan tidak ditemukan";
    exit;
}


/* DATA PELANGGAN */

$id_pelanggan = $data['pelanggan_id'];

$query_pelanggan = mysqli_query(
    $koneksi,
    "SELECT * FROM pelanggan 
     WHERE pelanggan_id='$id_pelanggan'"
);

$pelanggan = mysqli_fetch_assoc($query_pelanggan);


/* DATA BARANG */

$query_barang = mysqli_query(
    $koneksi,
    "SELECT 
        detailpenjualan.*,
        produk.nama_produk,
        produk.harga
     FROM detailpenjualan
     JOIN produk 
        ON detailpenjualan.produk_id = produk.produk_id
     WHERE detailpenjualan.penjualan_id='$id'"
);


/* TOTAL HARGA */

$total = $data['total_harga'];


/* UANG BAYAR */

$bayar = 0;
$kembalian = 0;
$status_bayar = "";


/* HITUNG PEMBAYARAN */

if (isset($_POST['hitung'])) {

    $bayar = $_POST['uang_bayar'];

    if ($bayar > $total) {

        $kembalian = $bayar - $total;

        $status_bayar = "kembalian";

    } elseif ($bayar == $total) {

        $status_bayar = "pas";

    } else {

        $status_bayar = "kurang";
    }
}

?>


<!DOCTYPE html>
<html>

<head>

    <title>Nota Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .nota {
            width: 400px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border: 1px solid #ddd;
        }

        .judul {
            text-align: center;
        }

        .garis {
            border-top: 1px dashed black;
            margin: 15px 0;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
        }

        .kembalian {
            font-size: 20px;
            font-weight: bold;
        }

        .barang {
            font-size: 14px;
        }

        @media print {

            body {
                background: white;
            }

            .nota {
                width: 100%;
                margin: 0;
                border: none;
            }

            .no-print {
                display: none;
            }

        }

    </style>

</head>


<body>


<div class="nota">


    <!-- JUDUL -->

    <div class="judul">

        <h3>SISTEM KASIR</h3>

        <p>Nota Penjualan</p>

    </div>


    <div class="garis"></div>


    <!-- INFORMASI TRANSAKSI -->

    <table width="100%">

        <tr>

            <td>ID Penjualan</td>

            <td>:</td>

            <td>
                <?php echo $data['penjualan_id']; ?>
            </td>

        </tr>


        <tr>

            <td>Tanggal</td>

            <td>:</td>

            <td>
                <?php echo $data['tanggal_penjualan']; ?>
            </td>

        </tr>


        <!-- JAM -->

        <tr>

            <td>Jam</td>

            <td>:</td>

            <td id="jam">
                00:00:00 WIB
            </td>

        </tr>


        <!-- PELANGGAN -->

        <tr>

            <td>Pelanggan</td>

            <td>:</td>

            <td>

                <?php

                if ($pelanggan) {

                    echo $pelanggan['nama_pelanggan'];

                } else {

                    echo "-";

                }

                ?>

            </td>

        </tr>

    </table>


    <div class="garis"></div>


    <!-- BARANG -->

    <b>Barang yang Dibeli</b>


    <table width="100%" class="barang mt-2">


        <?php while ($barang = mysqli_fetch_assoc($query_barang)) { ?>


            <!-- NAMA BARANG -->

            <tr>

                <td colspan="2">

                    <b>
                        <?php echo $barang['nama_produk']; ?>
                    </b>

                </td>

            </tr>


            <!-- JUMLAH DAN HARGA -->

            <tr>

                <td>

                    <?php echo $barang['jumlah_produk']; ?>

                    x

                    Rp <?php echo number_format(
                        $barang['harga'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </td>


                <!-- SUBTOTAL -->

                <td class="text-end">

                    Rp <?php echo number_format(
                        $barang['subtotal'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </td>

            </tr>


        <?php } ?>


    </table>


    <div class="garis"></div>


    <!-- TOTAL -->

    <table width="100%">

        <tr>

            <td class="total">

                Total Harga

            </td>


            <td class="text-end total">

                Rp <?php echo number_format(
                    $total,
                    0,
                    ',',
                    '.'
                ); ?>

            </td>

        </tr>

    </table>


    <!-- FORM UANG BAYAR -->

    <div class="no-print mt-4">


        <form method="POST">


            <label>

                <b>Uang Bayar</b>

            </label>


            <input type="number"
                   name="uang_bayar"
                   class="form-control"
                   placeholder="Contoh: 50000"
                   value="<?php echo $bayar; ?>"
                   required>


            <button type="submit"
                    name="hitung"
                    class="btn btn-primary mt-2">

                Hitung Pembayaran

            </button>


        </form>


    </div>


    <!-- HASIL PEMBAYARAN -->

    <?php if (isset($_POST['hitung'])) { ?>


        <div class="garis"></div>


        <table width="100%">


            <!-- UANG BAYAR -->

            <tr>

                <td>

                    Uang Bayar

                </td>


                <td class="text-end">

                    <b>

                        Rp <?php echo number_format(
                            $bayar,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </b>

                </td>

            </tr>


            <!-- JIKA KEMBALIAN -->

            <?php if ($status_bayar == "kembalian") { ?>


                <tr>

                    <td class="kembalian">

                        Kembalian

                    </td>


                    <td class="text-end kembalian">

                        Rp <?php echo number_format(
                            $kembalian,
                            0,
                            ',',
                            '.'
                        ); ?>

                    </td>

                </tr>


            <!-- JIKA UANG PAS -->

            <?php } elseif ($status_bayar == "pas") { ?>


                <tr>

                    <td colspan="2"
                        class="text-center">

                        <b>

                            UANG PAS

                        </b>

                    </td>

                </tr>


            <!-- JIKA UANG KURANG -->

            <?php } else { ?>


                <tr>

                    <td colspan="2"
                        class="text-center text-danger">

                        <b>

                            UANG BAYAR KURANG

                        </b>

                    </td>

                </tr>


            <?php } ?>


        </table>


        <!-- TOMBOL CETAK -->

        <?php if ($status_bayar != "kurang") { ?>


            <div class="no-print mt-4">


                <button onclick="window.print()"
                        class="btn btn-success w-100">

                    Cetak Nota

                </button>


            </div>


        <?php } ?>


    <?php } ?>


    <div class="garis"></div>


    <!-- PESAN -->

    <div class="text-center">

        <small>

            Terima kasih sudah berbelanja

        </small>

    </div>


    <!-- KEMBALI -->

    <div class="no-print mt-3">


        <a href="penjualan.php"
           class="btn btn-secondary w-100">

            Kembali

        </a>


    </div>


</div>


<!-- JAM REAL-TIME -->

<script>

function tampilkanJam() {

    let sekarang = new Date();


    let jam = String(
        sekarang.getHours()
    ).padStart(2, '0');


    let menit = String(
        sekarang.getMinutes()
    ).padStart(2, '0');


    let detik = String(
        sekarang.getSeconds()
    ).padStart(2, '0');


    document.getElementById("jam").innerHTML =
        jam + ":" +
        menit + ":" +
        detik +
        " WIB";

}


tampilkanJam();


setInterval(
    tampilkanJam,
    1000
);

</script>


</body>

</html>