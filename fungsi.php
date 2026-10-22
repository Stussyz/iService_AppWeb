<?php
/**
 * fungsi.php
 * ---------------------------------------------------------------
 * Kumpulan data harga dan fungsi bantu (helper) yang dipakai
 * di beberapa file sekaligus, supaya kode tidak ditulis berulang.
 */

// Daftar harga sparepart (harus sama dengan yang ada di logic.js)
const HARGA_SPAREPART = [
    "Ganti Baterai" => 100000,
    "Ganti LCD"     => 200000,
    "Ganti Camera"  => 300000,
    "Ganti Speaker" => 400000,
    "Ganti Mic"     => 500000,
];

// Daftar ongkos jasa service (harus sama dengan yang ada di logic.js)
const ONGKOS_JASA = [
    "Service Ringan" => 150000,
    "Service Berat"  => 300000,
];

/**
 * Menghitung total harga = harga sparepart + ongkos jasa.
 * Perhitungan dilakukan ulang di server (PHP) agar harga tidak bisa
 * dimanipulasi dari browser. Mengembalikan null jika pilihan tidak valid.
 */
function hitungTotalHarga($jenisService, $keluhan)
{
    if (!isset(ONGKOS_JASA[$jenisService]) || !isset(HARGA_SPAREPART[$keluhan])) {
        return null;
    }
    return ONGKOS_JASA[$jenisService] + HARGA_SPAREPART[$keluhan];
}

/**
 * Mengubah angka menjadi format Rupiah. Contoh: 250000 -> "Rp 250.000"
 */
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ",", ".");
}

/**
 * Mengamankan teks sebelum ditampilkan ke HTML (mencegah serangan XSS).
 */
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, "UTF-8");
}

/**
 * Mengembalikan atribut "selected" jika nilai sama, dipakai untuk <option>
 * pada form edit agar pilihan lama otomatis terpilih.
 */
function selected($nilai, $pilihan)
{
    return $nilai === $pilihan ? "selected" : "";
}

/**
 * Mengambil dan merapikan data dari form ($_POST), lalu memvalidasinya.
 * Mengembalikan array data jika valid, atau null jika ada yang tidak valid.
 * Dipakai oleh tambah.php dan update.php.
 */
function ambilDataForm()
{
    // trim() menghapus spasi di awal/akhir, "?? ''" mencegah error jika field tidak dikirim
    $data = [
        "nama"          => trim($_POST["nama"] ?? ""),
        "tgl_service"   => trim($_POST["tgl_service"] ?? ""),
        "jenis_device"  => trim($_POST["jenis_device"] ?? ""),
        "jenis_service" => trim($_POST["jenis_service"] ?? ""),
        "keluhan"       => trim($_POST["keluhan"] ?? ""),
        "nomor_hp"      => trim($_POST["nomor_hp"] ?? ""),
        "alamat"        => trim($_POST["alamat"] ?? ""),
    ];

    // Validasi: semua field wajib diisi
    foreach ($data as $nilai) {
        if ($nilai === "") {
            return null;
        }
    }

    // Validasi: format tanggal harus YYYY-MM-DD
    $tanggal = DateTime::createFromFormat("Y-m-d", $data["tgl_service"]);
    if (!$tanggal || $tanggal->format("Y-m-d") !== $data["tgl_service"]) {
        return null;
    }

    // Validasi: jenis service & keluhan harus ada di daftar harga, sekaligus hitung total
    $total = hitungTotalHarga($data["jenis_service"], $data["keluhan"]);
    if ($total === null) {
        return null;
    }
    $data["total_harga"] = $total;

    return $data;
}
