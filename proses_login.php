<?php

session_start();

include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($query) == 0) {
    die("Username tidak ditemukan di database.");
}

$data = mysqli_fetch_assoc($query);

if ($password != $data['password']) {
    die("Password salah.");
}

$_SESSION['login'] = true;
$_SESSION['user_id'] = $data['user_id'];
$_SESSION['username'] = $data['username'];
$_SESSION['nama'] = $data['nama'];
$_SESSION['level'] = $data['level'];

header("Location: index.php");
exit;

?>