<?php
/**
 * hapus.php  (D = DELETE)
 * ---------------------------------------------------------------
 * Menghapus satu data dari tabel tb_service berdasarkan id.
 * Dipanggil dari tombol "Hapus" pada tabel di index.php.
 *
 * Catatan: proses hapus sengaja memakai method POST (bukan link GET
 * seperti hapus.php?id=1) agar data tidak terhapus tanpa sengaja,
 * misalnya karena link dibuka ulang atau di-prefetch oleh browser.
 */

require "koneksi.php";

// 1. Hanya boleh diakses lewat form (method POST)
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// 2. Ambil id yang akan dihapus dan pastikan berupa angka
$id = (int) ($_POST["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php?pesan=gagal");
    exit;
}

// 3. Siapkan & jalankan query DELETE dengan prepared statement
$stmt = mysqli_prepare($koneksi, "DELETE FROM tb_service WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id); // "i" = integer
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// 4. Kembali ke halaman utama dengan pesan sukses
header("Location: index.php?pesan=hapus");
exit;
