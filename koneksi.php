<?php
/**
 * koneksi.php
 * ---------------------------------------------------------------
 * File ini bertugas membuat koneksi ke database MySQL.
 * File ini akan di-"require" oleh semua file yang butuh akses database
 * (index.php, tambah.php, edit.php, update.php, hapus.php).
 */

// Pengaturan koneksi database (sesuaikan dengan server lokal kamu, misal XAMPP/Laragon)
$host     = "localhost";    // Alamat server database
$user     = "iservice";     // Username MySQL (user khusus aplikasi)
$password = "rahasia123";   // Password MySQL untuk user di atas
$database = "db_iservice";  // Nama database (lihat file database.sql)

// Mengaktifkan mode error berupa exception, supaya error database mudah dilacak
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Membuat koneksi ke database
    $koneksi = mysqli_connect($host, $user, $password, $database);

    // Mengatur charset agar karakter khusus (huruf/emoji) tersimpan dengan benar
    mysqli_set_charset($koneksi, "utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Jika koneksi gagal, hentikan program dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $e->getMessage());
}
