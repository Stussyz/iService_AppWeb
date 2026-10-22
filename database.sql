-- =====================================================================
-- database.sql
-- Skrip untuk membuat database dan tabel yang dibutuhkan aplikasi iService.
-- Cara pakai:
--   1. Buka phpMyAdmin -> tab "Import" -> pilih file ini, ATAU
--   2. Lewat terminal: mysql -u root -p < database.sql
-- =====================================================================

-- Membuat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_iservice
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Memilih database yang akan digunakan
USE db_iservice;

-- Membuat tabel untuk menyimpan data permintaan service
CREATE TABLE IF NOT EXISTS tb_service (
    id            INT AUTO_INCREMENT PRIMARY KEY,      -- ID unik, bertambah otomatis
    nama          VARCHAR(100) NOT NULL,               -- Nama pelanggan
    tgl_service   DATE         NOT NULL,               -- Tanggal service
    jenis_device  VARCHAR(100) NOT NULL,               -- Contoh: iPhone 13 Pro
    jenis_service VARCHAR(50)  NOT NULL,               -- Service Ringan / Service Berat
    keluhan       VARCHAR(50)  NOT NULL,               -- Ganti Baterai, Ganti LCD, dst.
    nomor_hp      VARCHAR(20)  NOT NULL,               -- Nomor handphone pelanggan
    alamat        TEXT         NOT NULL,               -- Alamat penjemputan
    total_harga   INT          NOT NULL,               -- Total harga (angka, tanpa "Rp")
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Waktu data dibuat
);
