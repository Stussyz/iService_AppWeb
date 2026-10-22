<?php
/**
 * tambah.php  (C = CREATE)
 * ---------------------------------------------------------------
 * Memproses data dari form di index.php lalu menyimpannya
 * ke tabel tb_service. Setelah selesai, kembali ke index.php.
 */

require "koneksi.php"; // Membuka koneksi database ($koneksi)
require "fungsi.php";  // Memuat fungsi bantu (ambilDataForm, dll.)

// 1. Pastikan file ini hanya diakses lewat pengiriman form (method POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// 2. Ambil & validasi data dari form
$data = ambilDataForm();
if ($data === null) {
    // Jika data tidak valid, kembali ke index dengan pesan gagal
    header("Location: index.php?pesan=gagal");
    exit;
}

// 3. Siapkan query INSERT memakai prepared statement.
//    Tanda "?" adalah placeholder yang nanti diisi data,
//    cara ini mencegah serangan SQL Injection.
$sql = "INSERT INTO tb_service
            (nama, tgl_service, jenis_device, jenis_service, keluhan, nomor_hp, alamat, total_harga)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);

// 4. Isi placeholder dengan data.
//    "sssssssi" = 7 data bertipe string (s) dan 1 data integer (i)
mysqli_stmt_bind_param(
    $stmt,
    "sssssssi",
    $data["nama"],
    $data["tgl_service"],
    $data["jenis_device"],
    $data["jenis_service"],
    $data["keluhan"],
    $data["nomor_hp"],
    $data["alamat"],
    $data["total_harga"]
);

// 5. Jalankan query, lalu tutup statement
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// 6. Kembali ke halaman utama dengan pesan sukses
header("Location: index.php?pesan=tambah");
exit;
