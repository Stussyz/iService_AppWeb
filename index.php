<?php
/**
 * index.php  (R = READ + form CREATE)
 * ---------------------------------------------------------------
 * Halaman utama: menampilkan info layanan, form tambah data
 * (dikirim ke tambah.php), dan tabel riwayat yang diambil dari database.
 */

require "koneksi.php";
require "fungsi.php";

// 1. Daftar pesan notifikasi berdasarkan parameter ?pesan= di URL
//    (dikirim oleh tambah.php, update.php, dan hapus.php setelah proses selesai)
$daftarPesan = [
    "tambah"          => ["Data berhasil ditambahkan!", "sukses"],
    "edit"            => ["Data berhasil diperbarui!", "sukses"],
    "hapus"           => ["Data berhasil dihapus!", "sukses"],
    "gagal"           => ["Data gagal diproses, periksa kembali isian formulir!", "gagal"],
    "tidak_ditemukan" => ["Data tidak ditemukan!", "gagal"],
];
$pesan = $daftarPesan[$_GET["pesan"] ?? ""] ?? null;

// 2. Ambil semua data service dari database (urut dari yang terlama)
$hasil = mysqli_query($koneksi, "SELECT * FROM tb_service ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iService</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <section>
            <h1>Selamat datang di iService</h1>
            <p class="slogan">iService "Taruh-Tunggu-Selesai"</p>
            <p>iService menyediakan layanan service iPhone dengan berbagai macam kategori. Berikut ini adalah kategori
                service yang tersedia:</p>
            <p>Menerima Antar Jemput Perbaikan iPhone se-Jabodetabek</p>
            <p>WA: +62-812-3456-7890 || Instagram: @iService.id || TikTok: @iService.id</p>
            <ul>
                <li>Service Ringan : Ganti Baterai dan Ganti LCD</li>
                <li>Service Berat : Ganti Camera, Ganti Speaker, Ganti Mic</li>
            </ul>
        </section>

        <section>
            <h2>Daftar Harga Service</h2>
            <p>Berikut ini adalah daftar harga service yang tersedia:</p>
            <ul>
                <li><strong>Service Ringan : (Jasa Service Rp. 150.000)</strong>
                    <ol>
                        <li>Ganti Baterai : Rp. 100.000</li>
                        <li>Ganti LCD : Rp. 200.000</li>
                    </ol>
                </li>
                <li><strong>Service Berat : (Jasa Service Rp. 300.000)</strong>
                    <ol>
                        <li>Ganti Camera : Rp. 300.000</li>
                        <li>Ganti Speaker : Rp. 400.000</li>
                        <li>Ganti Mic : Rp. 500.000</li>
                    </ol>
                </li>
            </ul>
        </section>

        <section>
            <h2>Formulir Permintaan Service</h2>
            <p>Silakan isi formulir berikut untuk melakukan permintaan service:</p>
            <!-- Form tambah: data dikirim ke tambah.php dengan method POST.
                 Atribut "name" adalah kunci yang dibaca PHP lewat $_POST["..."] -->
            <form id="iniForm" action="tambah.php" method="POST">
                <label for="nama">Nama:</label><br>
                <input type="text" id="nama" name="nama" required><br><br>
                <label for="tglService">Tanggal Servis:</label>
                <input type="date" id="tglService" name="tgl_service" required><br><br>
                <label for="jenisDevice">Jenis Device:</label><br>
                <input type="text" id="jenisDevice" name="jenis_device" required><br><br>
                <label for="jenisService">Jenis Layanan:</label><br>
                <!-- <input type="text" id="jenisService"><br><br> -->
                <select id="jenisService" name="jenis_service" required>
                    <option value="">Pilih jenis service</option>
                    <option value="Service Ringan">Service Ringan</option>
                    <option value="Service Berat">Service Berat</option>
                </select>
                <br>
                <br>
                <label for="keluhan">Keluhan:</label><br>
                <!-- <input type="text" id="keluhan"><br><br> -->
                <select id="keluhan" name="keluhan" required>
                    <option value="">Pilih jenis keluhan</option>
                    <option value="Ganti Baterai">Ganti Baterai</option>
                    <option value="Ganti LCD">Ganti LCD</option>
                    <option value="Ganti Camera">Ganti Camera</option>
                    <option value="Ganti Speaker">Ganti Speaker</option>
                    <option value="Ganti Mic">Ganti Mic</option>
                </select>
                <br>
                <br>
                <button type="button" id="btnHitung">Hitung Harga</button><br><br>
                <label for="totalHarga">Total Harga Service:</label><br>
                <!-- Total harga hanya untuk tampilan; harga asli dihitung ulang di server (fungsi.php) -->
                <input type="text" id="totalHarga" readonly><br><br>
                <label for="nomorHp">Nomor Handphone:</label><br>
                <input type="text" id="nomorHp" name="nomor_hp" required><br><br>
                <label for="alamat">Alamat:</label><br>
                <input type="text" id="alamat" name="alamat" required><br><br>
                <button type="submit">Submit Data</button>
            </form>

            <!-- Menampilkan notifikasi hasil proses (sukses / gagal) -->
            <?php if ($pesan): ?>
                <p id="validasiPesanan" class="pesan pesan-<?= $pesan[1] ?>"><?= e($pesan[0]) ?></p>
            <?php endif; ?>
        </section>

        <section>
            <h3>Riwayat Permintaan Service</h3>
            <table id="tabelRiwayat">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal Servis</th>
                        <th>Jenis Device</th>
                        <th>Jenis Layanan</th>
                        <th>Keluhan</th>
                        <th>Nomor Handphone</th>
                        <th>Alamat</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="isiTabelRiwayat">
                    <?php if (mysqli_num_rows($hasil) === 0): ?>
                        <!-- Jika tabel masih kosong -->
                        <tr>
                            <td colspan="10">Data masih kosong!</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; // Nomor urut tampilan (bukan id database) ?>
                        <!-- Perulangan: tampilkan setiap baris data dari database -->
                        <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= e($row["nama"]) ?></td>
                                <td><?= e(date("d-m-Y", strtotime($row["tgl_service"]))) ?></td>
                                <td><?= e($row["jenis_device"]) ?></td>
                                <td><?= e($row["jenis_service"]) ?></td>
                                <td><?= e($row["keluhan"]) ?></td>
                                <td><?= e($row["nomor_hp"]) ?></td>
                                <td><?= e($row["alamat"]) ?></td>
                                <td><?= e(formatRupiah($row["total_harga"])) ?></td>
                                <td class="aksi">
                                    <!-- Tombol Edit: membuka edit.php sambil membawa id data -->
                                    <a href="edit.php?id=<?= (int) $row["id"] ?>" class="btn btn-edit">Edit</a>

                                    <!-- Tombol Hapus: kirim id ke hapus.php, dengan konfirmasi dulu -->
                                    <form action="hapus.php" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                        <input type="hidden" name="id" value="<?= (int) $row["id"] ?>">
                                        <button type="submit" class="btn btn-hapus">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

    <script src="logic.js"></script>
</body>

</html>