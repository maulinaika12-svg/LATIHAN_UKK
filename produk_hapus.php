<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "koneksi.php";

if (!isset($_GET['id'])) {
    echo "ID produk tidak ditemukan";
    exit;
}

$id = $_GET['id'];

// Cek data produk
$query = mysqli_query($koneksi, "SELECT * FROM produk WHERE produk_id='$id'");

if (!$query) {
    echo "Query error: " . mysqli_error($koneksi);
    exit;
}

if (mysqli_num_rows($query) == 0) {
    echo "Data produk tidak ditemukan";
    exit;
}

// Hapus produk
$hapus = mysqli_query($koneksi, "DELETE FROM produk WHERE produk_id='$id'");

if ($hapus) {
    header("Location: produk.php");
    exit;
} else {
    echo "Data produk gagal dihapus: " . mysqli_error($koneksi);
}
?>