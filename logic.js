const form = document.getElementById("iniForm")
const table = document.getElementById("isiTabelRiwayat")
const hasil = document.getElementById("validasiPesanan");
const selectKeluhan = document.getElementById("keluhan");
const totalHargaInput = document.getElementById("totalHarga");
let inputNomor = 1;

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
    const jenisService = document.getElementById("jenisService").value;

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

form.addEventListener("submit", function (e) {
    e.preventDefault();
    hasil.textContent = "Data berhasil diproses!";

    const inputNama = document.getElementById("nama").value;
    const inputTanggal = document.getElementById("tglService").value;
    const inputJenisDevice = document.getElementById("jenisDevice").value;
    const inputJenisService = document.getElementById("jenisService").value;
    const inputKeluhan = document.getElementById("keluhan").value;
    const inputNomorHp = document.getElementById("nomorHp").value;
    const inputAlamat = document.getElementById("alamat").value;
    const inputTotalHarga = document.getElementById("totalHarga").value;

    const baris = document.createElement("tr");
    baris.innerHTML = `
        <td>${inputNomor}</td>
        <td>${inputNama}</td>
        <td>${inputTanggal}</td>
        <td>${inputJenisDevice}</td>
        <td>${inputJenisService}</td>
        <td>${inputKeluhan}</td>
        <td>${inputNomorHp}</td>
        <td>${inputAlamat}</td>
        <td>${inputTotalHarga}</td>`;
    table.appendChild(baris);
    inputNomor++;
    form.reset();
    totalHargaInput.value = "";
});