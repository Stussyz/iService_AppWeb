<?php
/**
 * edit.php  (U = UPDATE - bagian tampilan form)
 * ---------------------------------------------------------------
 * Menampilkan form yang sudah terisi data lama berdasarkan id.
 * Saat form dikirim, data diproses oleh update.php.
 */

require "koneksi.php";
require "fungsi.php";

// 1. Ambil id dari URL (contoh: edit.php?id=3) dan pastikan berupa angka
$id = (int) ($_GET["id"] ?? 0);

// 2. Ambil data lama dari database berdasarkan id
$stmt = mysqli_prepare($koneksi, "SELECT * FROM tb_service WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$row   = mysqli_fetch_assoc($hasil); // Hasilnya array asosiatif, atau null jika tidak ada
mysqli_stmt_close($stmt);

// 3. Jika data tidak ditemukan, kembali ke index
if (!$row) {
    header("Location: index.php?pesan=tidak_ditemukan");
    exit;
}

// 4. Pesan gagal (dikirim dari update.php jika validasi gagal)
$pesanGagal = (($_GET["pesan"] ?? "") === "gagal")
    ? "Data gagal diperbarui, periksa kembali isian formulir!"
    : "";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Service - iService</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <section>
            <h1>Edit Data Permintaan Service</h1>

            <!-- Menampilkan pesan error jika ada -->
            <?php if ($pesanGagal): ?>
                <p class="pesan pesan-gagal"><?= e($pesanGagal) ?></p>
            <?php endif; ?>

            <!-- Form edit: data dikirim ke update.php dengan method POST -->
            <form id="iniForm" action="update.php" method="POST">
                <!-- id disembunyikan, dipakai update.php untuk tahu data mana yang diubah -->
                <input type="hidden" name="id" value="<?= (int) $row["id"] ?>">

                <label for="nama">Nama:</label><br>
                <input type="text" id="nama" name="nama" value="<?= e($row["nama"]) ?>" required><br><br>

                <label for="tglService">Tanggal Servis:</label>
                <input type="date" id="tglService" name="tgl_service" value="<?= e($row["tgl_service"]) ?>" required><br><br>

                <label for="jenisDevice">Jenis Device:</label><br>
                <input type="text" id="jenisDevice" name="jenis_device" value="<?= e($row["jenis_device"]) ?>" required><br><br>

                <label for="jenisService">Jenis Layanan:</label><br>
                <select id="jenisService" name="jenis_service" required>
                    <option value="">Pilih jenis service</option>
                    <!-- Opsi dibuat dari daftar ONGKOS_JASA, pilihan lama otomatis "selected" -->
                    <?php foreach (ONGKOS_JASA as $nama => $harga): ?>
                        <option value="<?= e($nama) ?>" <?= selected($row["jenis_service"], $nama) ?>><?= e($nama) ?></option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <label for="keluhan">Keluhan:</label><br>
                <select id="keluhan" name="keluhan" required>
                    <option value="">Pilih jenis keluhan</option>
                    <!-- Opsi dibuat dari daftar HARGA_SPAREPART -->
                    <?php foreach (HARGA_SPAREPART as $nama => $harga): ?>
                        <option value="<?= e($nama) ?>" <?= selected($row["keluhan"], $nama) ?>><?= e($nama) ?></option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <button type="button" id="btnHitung">Hitung Harga</button><br><br>
                <label for="totalHarga">Total Harga Service:</label><br>
                <input type="text" id="totalHarga" value="<?= e(formatRupiah($row["total_harga"])) ?>" readonly><br><br>

                <label for="nomorHp">Nomor Handphone:</label><br>
                <input type="text" id="nomorHp" name="nomor_hp" value="<?= e($row["nomor_hp"]) ?>" required><br><br>

                <label for="alamat">Alamat:</label><br>
                <input type="text" id="alamat" name="alamat" value="<?= e($row["alamat"]) ?>" required><br><br>

                <button type="submit">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-batal">Batal</a>
            </form>
        </section>
    </main>

    <!-- logic.js dipakai untuk tombol "Hitung Harga" -->
    <script src="logic.js"></script>
</body>

</html>
