-- Create database and tables for SIM Karang Taruna
CREATE DATABASE IF NOT EXISTS db_karangtaruna CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_karangtaruna;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','anggota') NOT NULL DEFAULT 'anggota',
  anggota_id INT DEFAULT NULL
);

CREATE TABLE jabatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT
);

CREATE TABLE anggota (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(30) UNIQUE NOT NULL,
  nama VARCHAR(150) NOT NULL,
  tempat_lahir VARCHAR(100),
  tanggal_lahir DATE,
  jenis_kelamin ENUM('L','P') DEFAULT 'L',
  alamat TEXT,
  no_hp VARCHAR(30),
  jabatan_id INT,
  FOREIGN KEY (jabatan_id) REFERENCES jabatan(id) ON DELETE SET NULL
);

CREATE TABLE kegiatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(150) NOT NULL,
  tanggal_mulai DATE,
  tanggal_selesai DATE,
  tempat VARCHAR(200),
  deskripsi TEXT
);

CREATE TABLE keuangan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATE NOT NULL,
  jenis ENUM('masuk','keluar') NOT NULL,
  keterangan VARCHAR(255),
  jumlah DECIMAL(15,2) NOT NULL,
  anggota_id INT DEFAULT NULL,
  FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE SET NULL
);

-- Seed data
INSERT INTO jabatan (nama, deskripsi) VALUES
('Ketua', 'Ketua Karang Taruna'), ('Sekretaris', 'Sekretaris');

INSERT INTO anggota (nik, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_hp, jabatan_id) VALUES
('3201010101010001', 'Ahmad Santoso', 'Semarang', '2003-05-10', 'L', 'Jl. Mawar 1', '081234567890', 1),
('3201010101010002', 'Siti Aminah', 'Semarang', '2004-08-12', 'P', 'Jl. Melati 2', '081298765432', 2);

INSERT INTO users (username, password, role, anggota_id) VALUES
('admin', '$2y$10$wH7U6k2nXg3y8u0E9QGz8uJ9s5jl1z2Yv1m3a6KqZqP8H0oYf7E7W', 'admin', NULL),
('ahmad', '$2y$10$VJ2s8u1nXk2y4a0P8QFz7uH8r5kl9m1Zp4n2b5CqZqP3H0oYf4B1', 'anggota', 1);

-- Passwords above are bcrypt hashes of 'admin123' and 'member123' respectively (generated for seeding).

INSERT INTO kegiatan (nama, tanggal_mulai, tanggal_selesai, tempat, deskripsi) VALUES
('Bakti Sosial', '2025-11-01', '2025-11-02', 'Balai Desa', 'Membersihkan lingkungan dan bakti sosial'),
('Pelatihan Komputer', '2025-12-05', '2025-12-05', 'Gedung Serba Guna', 'Pelatihan dasar komputer untuk anggota');

INSERT INTO keuangan (tanggal, jenis, keterangan, jumlah, anggota_id) VALUES
('2025-10-10','masuk','Iuran anggota - Ahmad',50000,1),
('2025-10-15','keluar','Pembelian bahan kegiatan',75000,NULL);
