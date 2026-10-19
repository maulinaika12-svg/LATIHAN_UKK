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
    "SELECT * FROM pelanggan
     WHERE nama_pelanggan LIKE '%$search%'
        OR alamat LIKE '%$search%'
        OR nomor_telepon LIKE '%$search%'
     ORDER BY pelanggan_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Pelanggan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container mt-4">

    <h2>Data Pelanggan</h2>

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
                   placeholder="Cari nama, alamat, atau nomor telepon..."
                   value="<?php echo $search; ?>">

            <button type="submit"
                    class="btn btn-primary">

                🔎 Search

            </button>

            <a href="pelanggan.php"
               class="btn btn-secondary">

                Reset

            </a>

        </div>

    </form>


    <!-- TAMBAH PELANGGAN -->

    <a href="pelanggan_tambah.php"
       class="btn btn-primary mb-3">

        + Tambah Pelanggan

    </a>


    <!-- TABEL -->

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>

                <th>No</th>
                <th>ID Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
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
                    <?php echo $data['pelanggan_id']; ?>
                </td>

                <td>
                    <?php echo $data['nama_pelanggan']; ?>
                </td>

                <td>
                    <?php echo $data['alamat']; ?>
                </td>

                <td>
                    <?php echo $data['nomor_telepon']; ?>
                </td>

                <td>

                    <?php if ($level == 'admin') { ?>

                        <a href="pelanggan_edit.php?id=<?php echo $data['pelanggan_id']; ?>"
                           class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <a href="pelanggan_hapus.php?id=<?php echo $data['pelanggan_id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">

                            Hapus

                        </a>

                    <?php } else { ?>

                        <span class="text-muted">
                            Tidak ada aksi
                        </span>

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