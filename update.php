<?php
/**
 * update.php  (U = UPDATE - bagian proses)
 * ---------------------------------------------------------------
 * Menerima data dari form di edit.php, lalu memperbarui
 * data yang sesuai di tabel tb_service berdasarkan id.
 */

require "koneksi.php";
require "fungsi.php";

// 1. Hanya boleh diakses lewat form (method POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// 2. Ambil id data yang akan diubah, (int) memastikan nilainya angka
$id = (int) ($_POST["id"] ?? 0);

// 3. Ambil & validasi data form
$data = ambilDataForm();
if ($id <= 0 || $data === null) {
    // Jika tidak valid, kembali ke halaman edit dengan pesan gagal
    header("Location: edit.php?id=$id&pesan=gagal");
    exit;
}

// 4. Siapkan query UPDATE dengan prepared statement
$sql = "UPDATE tb_service SET
            nama = ?, tgl_service = ?, jenis_device = ?, jenis_service = ?,
            keluhan = ?, nomor_hp = ?, alamat = ?, total_harga = ?
        WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);

// 5. Isi placeholder: 7 string (s), total_harga integer (i), id integer (i)
mysqli_stmt_bind_param(
    $stmt,
    "sssssssii",
    $data["nama"],
    $data["tgl_service"],
    $data["jenis_device"],
    $data["jenis_service"],
    $data["keluhan"],
    $data["nomor_hp"],
    $data["alamat"],
    $data["total_harga"],
    $id
);

// 6. Jalankan query & tutup statement
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// 7. Kembali ke halaman utama dengan pesan sukses
header("Location: index.php?pesan=edit");
exit;
