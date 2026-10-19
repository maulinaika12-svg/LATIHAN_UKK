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
    "SELECT * FROM produk
     WHERE nama_produk LIKE '%$search%'
     ORDER BY produk_id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Produk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Data Produk</h2>

    <p>
        Login sebagai:
        <b><?php echo ucfirst($level); ?></b>
    </p>

    <!-- TOMBOL TAMBAH PRODUK -->
    <?php if ($level == 'admin') { ?>

        <a href="produk_tambah.php"
           class="btn btn-primary mb-3">
            + Tambah Produk
        </a>

    <?php } ?>

    <!-- SEARCH -->
    <form method="GET" class="mb-3">

        <div class="input-group">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama produk..."
                   value="<?php echo htmlspecialchars($search); ?>">

            <button type="submit" class="btn btn-primary">
                Search
            </button>

            <a href="produk.php" class="btn btn-secondary">
                Reset
            </a>

        </div>

    </form>

    <!-- TABEL DATA PRODUK -->
    <table class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {
        ?>

            <tr>
                <td><?php echo $no++; ?></td>

                <td>
                    <?php echo $data['produk_id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($data['nama_produk']); ?>
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
                    <?php echo $data['stok']; ?>
                </td>

                <td>
                    <?php if ($level == 'admin') { ?>

                        <a href="produk_edit.php?id=<?php echo $data['produk_id']; ?>"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="produk_hapus.php?id=<?php echo $data['produk_id']; ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
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

    <!-- TOMBOL KEMBALI -->
    <a href="index.php" class="btn btn-secondary">
        Kembali
    </a>

</div>

</body>
</html>