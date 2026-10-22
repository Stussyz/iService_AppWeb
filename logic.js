// logic.js
// Dipakai di index.php dan edit.php hanya untuk tombol "Hitung Harga"
// (perkiraan harga di browser). Penyimpanan data kini ditangani PHP
// (tambah.php / update.php), dan harga dihitung ulang di server (fungsi.php).

const selectKeluhan = document.getElementById("keluhan");
const selectJenisService = document.getElementById("jenisService");
const totalHargaInput = document.getElementById("totalHarga");

// daftar harga sparepart
const hargaSparepart = {
    "Ganti Baterai": 100000,
    "Ganti LCD": 200000,
    "Ganti Camera": 300000,
    "Ganti Speaker": 400000,
    "Ganti Mic": 500000
};

// ongkos jasa service
const ongkosJasa = {
    "Service Ringan": 150000,
    "Service Berat": 300000
};

// event button hitung
const btnHitung = document.getElementById("btnHitung");
btnHitung.addEventListener("click", function () {
    const keluhan = selectKeluhan.value;
    const jenisService = selectJenisService.value;

    if (!keluhan || !hargaSparepart[keluhan]) {
        alert("Silakan pilih keluhan terlebih dahulu!");
        return;
    }
    if (!jenisService || !ongkosJasa[jenisService]) {
        alert("Silakan pilih jenis layanan terlebih dahulu!");
        return;
    }

    const total = hargaSparepart[keluhan] + ongkosJasa[jenisService];
    totalHargaInput.value = "Rp " + total.toLocaleString("id-ID");
});

// kosongkan total harga jika pilihan diubah, agar pengguna menghitung ulang
selectKeluhan.addEventListener("change", () => totalHargaInput.value = "");
selectJenisService.addEventListener("change", () => totalHargaInput.value = "");