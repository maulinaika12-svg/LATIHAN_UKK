<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['level'] != 'admin') {
    echo "<script>
        alert('Halaman ini hanya dapat diakses oleh Admin!');
        window.location='pelanggan.php';
    </script>";
    exit;
}

include "koneksi.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM pelanggan WHERE pelanggan_id='$id'");

if ($query) {
    header("Location: pelanggan.php");
    exit;
} else {
    echo "Data gagal dihapus: " . mysqli_error($koneksi);
}
?>