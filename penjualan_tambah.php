<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

if (isset($_POST['simpan'])) {

    $tanggal_penjualan = $_POST['tanggal_penjualan'];
    $pelanggan_id = $_POST['pelanggan_id'];

    $produk_id = $_POST['produk_id'];
    $jumlah_produk = $_POST['jumlah_produk'];

    $total_harga = 0;
    $data_produk = [];
    $jumlah_data = [];

    // CEK PRODUK DAN JUMLAH
    for ($i = 0; $i < count($produk_id); $i++) {

        $id_produk = $produk_id[$i];
        $jumlah = $jumlah_produk[$i];

        if ($id_produk == '' || $jumlah <= 0) {
            continue;
        }

        // Jika produk yang sama dipilih lebih dari sekali
        if (isset($jumlah_data[$id_produk])) {
            $jumlah_data[$id_produk] += $jumlah;
        } else {
            $jumlah_data[$id_produk] = $jumlah;
        }
    }

    if (count($jumlah_data) == 0) {
        echo "<script>
            alert('Silakan pilih produk terlebih dahulu!');
            window.history.back();
        </script>";
        exit;
    }

    // CEK STOK DAN HITUNG TOTAL
    foreach ($jumlah_data as $id_produk => $jumlah) {

        $query_produk = mysqli_query($koneksi,
            "SELECT * FROM produk WHERE produk_id='$id_produk'"
        );

        $produk = mysqli_fetch_assoc($query_produk);

        if (!$produk) {
            echo "<script>
                alert('Produk tidak ditemukan!');
                window.history.back();
            </script>";
            exit;
        }

        if ($jumlah > $produk['stok']) {
            echo "<script>
                alert('Stok {$produk['nama_produk']} tidak mencukupi! Stok tersedia: {$produk['stok']}');
                window.history.back();
            </script>";
            exit;
        }

        $subtotal = $produk['harga'] * $jumlah;

        $total_harga += $subtotal;

        $data_produk[$id_produk] = [
            'nama_produk' => $produk['nama_produk'],
            'harga' => $produk['harga'],
            'stok' => $produk['stok'],
            'jumlah' => $jumlah,
            'subtotal' => $subtotal
        ];
    }


    // MULAI TRANSAKSI DATABASE
    mysqli_begin_transaction($koneksi);

    try {

        // SIMPAN PENJUALAN
        $query_penjualan = mysqli_query($koneksi, "INSERT INTO penjualan
            (tanggal_penjualan, total_harga, pelanggan_id)
            VALUES
            ('$tanggal_penjualan', '$total_harga', '$pelanggan_id')
        ");

        if (!$query_penjualan) {
            throw new Exception("Gagal menyimpan penjualan.");
        }

        // AMBIL ID PENJUALAN
        $penjualan_id = mysqli_insert_id($koneksi);


        // SIMPAN DETAIL DAN KURANGI STOK
        foreach ($data_produk as $id_produk => $data) {

            $jumlah = $data['jumlah'];
            $subtotal = $data['subtotal'];

            // SIMPAN DETAIL PENJUALAN
            $query_detail = mysqli_query($koneksi, "INSERT INTO detailpenjualan
                (penjualan_id, produk_id, jumlah_produk, subtotal)
                VALUES
                ('$penjualan_id', '$id_produk', '$jumlah', '$subtotal')
            ");

            if (!$query_detail) {
                throw new Exception("Gagal menyimpan detail penjualan.");
            }


            // KURANGI STOK
            $query_stok = mysqli_query($koneksi, "UPDATE produk SET
                stok = stok - $jumlah
                WHERE produk_id='$id_produk'
            ");

            if (!$query_stok) {
                throw new Exception("Gagal mengurangi stok.");
            }
        }


        // SIMPAN SEMUA PERUBAHAN
        mysqli_commit($koneksi);

        echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.location='penjualan.php';
        </script>";
        exit;

    } catch (Exception $e) {

        // BATALKAN JIKA ADA ERROR
        mysqli_rollback($koneksi);

        echo "<script>
            alert('Transaksi gagal disimpan!');
            window.history.back();
        </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Transaksi Penjualan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <script>

        function hitungTotal() {

            let total = 0;

            let harga = document.querySelectorAll('.harga');
            let jumlah = document.querySelectorAll('.jumlah');
            let subtotal = document.querySelectorAll('.subtotal');

            for (let i = 0; i < harga.length; i++) {

                let h = parseInt(harga[i].value) || 0;
                let j = parseInt(jumlah[i].value) || 0;

                let sub = h * j;

                subtotal[i].value =
                    new Intl.NumberFormat('id-ID').format(sub);

                total += sub;
            }

            document.getElementById('total_harga').value =
                new Intl.NumberFormat('id-ID').format(total);
        }


        function pilihProduk(select) {

            let option = select.options[select.selectedIndex];

            let harga = option.getAttribute('data-harga');

            let baris = select.closest('tr');

            baris.querySelector('.harga').value = harga;

            hitungTotal();
        }


        function tambahProduk() {

            let tabel = document.getElementById('tabel_produk');

            let baris = tabel.rows[1].cloneNode(true);

            baris.querySelector('.produk').value = '';

            baris.querySelector('.harga').value = '';

            baris.querySelector('.jumlah').value = 1;

            baris.querySelector('.subtotal').value = '';

            tabel.appendChild(baris);

            hitungTotal();
        }


        function hapusProduk(button) {

            let tabel = document.getElementById('tabel_produk');

            if (tabel.rows.length > 2) {

                button.closest('tr').remove();

                hitungTotal();

            } else {

                alert('Minimal harus ada 1 produk.');

            }
        }

    </script>

</head>


<body>

<div class="container mt-4">

    <h2>Transaksi Penjualan</h2>

    <form method="POST">


        <!-- TANGGAL -->

        <div class="mb-3">

            <label class="form-label">
                Tanggal Penjualan
            </label>

            <input type="date"
                   name="tanggal_penjualan"
                   class="form-control"
                   value="<?php echo date('Y-m-d'); ?>"
                   required>

        </div>


        <!-- PELANGGAN -->

        <div class="mb-3">

            <label class="form-label">
                Pelanggan
            </label>

            <select name="pelanggan_id"
                    class="form-control"
                    required>

                <option value="">
                    -- Pilih Pelanggan --
                </option>

                <?php

                $pelanggan = mysqli_query($koneksi,
                    "SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC"
                );

                while ($data = mysqli_fetch_assoc($pelanggan)) {

                ?>

                    <option value="<?php echo $data['pelanggan_id']; ?>">

                        <?php echo $data['nama_pelanggan']; ?>

                    </option>

                <?php
                }
                ?>

            </select>

        </div>


        <!-- PRODUK -->

        <h5 class="mt-4">
            Daftar Produk
        </h5>


        <table class="table table-bordered"
               id="tabel_produk">

            <thead class="table-dark">

                <tr>

                    <th width="35%">
                        Produk
                    </th>

                    <th width="20%">
                        Harga
                    </th>

                    <th width="15%">
                        Jumlah
                    </th>

                    <th width="20%">
                        Subtotal
                    </th>

                    <th width="10%">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>

                        <select name="produk_id[]"
                                class="form-control produk"
                                onchange="pilihProduk(this)"
                                required>

                            <option value="">
                                -- Pilih Produk --
                            </option>

                            <?php

                            $produk = mysqli_query($koneksi,
                                "SELECT * FROM produk
                                 WHERE stok > 0
                                 ORDER BY nama_produk ASC"
                            );

                            while ($data = mysqli_fetch_assoc($produk)) {

                            ?>

                                <option value="<?php echo $data['produk_id']; ?>"
                                        data-harga="<?php echo $data['harga']; ?>">

                                    <?php echo $data['nama_produk']; ?>

                                    - Stok:
                                    <?php echo $data['stok']; ?>

                                </option>

                            <?php
                            }
                            ?>

                        </select>

                    </td>


                    <td>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input type="text"
                                   class="form-control harga"
                                   readonly>

                        </div>

                    </td>


                    <td>

                        <input type="number"
                               name="jumlah_produk[]"
                               class="form-control jumlah"
                               value="1"
                               min="1"
                               onchange="hitungTotal()"
                               oninput="hitungTotal()"
                               required>

                    </td>


                    <td>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input type="text"
                                   class="form-control subtotal"
                                   readonly>

                        </div>

                    </td>


                    <td>

                        <button type="button"
                                class="btn btn-danger"
                                onclick="hapusProduk(this)">
                            X
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>


        <!-- TAMBAH PRODUK -->

        <button type="button"
                class="btn btn-info mb-4"
                onclick="tambahProduk()">

            + Tambah Produk

        </button>


        <!-- TOTAL -->

        <div class="row justify-content-end">

            <div class="col-md-4">

                <label class="form-label">
                    Total Harga
                </label>

                <div class="input-group mb-3">

                    <span class="input-group-text">
                        Rp
                    </span>

                    <input type="text"
                           id="total_harga"
                           class="form-control"
                           value="0"
                           readonly>

                </div>

            </div>

        </div>


        <!-- BUTTON -->

        <button type="submit"
                name="simpan"
                class="btn btn-primary">

            Simpan Transaksi

        </button>

        <a href="penjualan.php"
           class="btn btn-secondary">

            Kembali

        </a>


    </form>

</div>

</body>

</html>