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
    "DELETE FROM penjualan WHERE penjualan_id='$id'"
);

if ($query) {
    header("Location: penjualan.php");
    exit;
} else {
    echo "Data gagal dihapus";
}
?>