CREATE DATABASE IF NOT EXISTS inventaris_nasi_bakar
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE inventaris_nasi_bakar;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pesanan_detail;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS permintaan_restok;
DROP TABLE IF EXISTS stok_keluar;
DROP TABLE IF EXISTS stok_masuk;
DROP TABLE IF EXISTS supplier;
DROP TABLE IF EXISTS menu_nasi_bakar;
DROP TABLE IF EXISTS bahan_baku;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'owner', 'kasir', 'dapur', 'gudang') NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE bahan_baku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_bahan VARCHAR(120) NOT NULL,
    stok DECIMAL(10,2) NOT NULL DEFAULT 0,
    stok_minimum DECIMAL(10,2) NOT NULL DEFAULT 10,
    satuan VARCHAR(30) NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE menu_nasi_bakar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(120) NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(180) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(120) NOT NULL,
    no_telp VARCHAR(30) NOT NULL,
    alamat TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE stok_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bahan_id INT NOT NULL,
    supplier_id INT NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    keterangan TEXT NOT NULL,
    CONSTRAINT fk_masuk_bahan FOREIGN KEY (bahan_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_masuk_supplier FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE stok_keluar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bahan_id INT NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    keterangan TEXT NOT NULL,
    tanggal DATE NOT NULL,
    CONSTRAINT fk_keluar_bahan FOREIGN KEY (bahan_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE permintaan_restok (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bahan_id INT NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Menunggu', 'Disetujui', 'Ditolak') NOT NULL DEFAULT 'Menunggu',
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_permintaan_bahan FOREIGN KEY (bahan_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_pesanan VARCHAR(40) NOT NULL UNIQUE,
    nama_pelanggan VARCHAR(120) NOT NULL,
    total DECIMAL(12,2) NOT NULL DEFAULT 0,
    metode_bayar ENUM('tunai', 'qris', 'transfer') NOT NULL DEFAULT 'tunai',
    status ENUM('menunggu', 'diproses', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu',
    catatan TEXT NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pesanan_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE pesanan_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT NOT NULL,
    menu_id INT NOT NULL,
    qty INT NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_menu FOREIGN KEY (menu_id) REFERENCES menu_nasi_bakar(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO users (nama, email, password, role) VALUES
('Admin Nasi Bakar', 'admin@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'admin'),
('Kasir Nasi Bakar', 'kasir@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'kasir'),
('Dapur Nasi Bakar', 'dapur@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'dapur'),
('Gudang Nasi Bakar', 'gudang@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'gudang');

INSERT INTO bahan_baku (nama_bahan, stok, stok_minimum, satuan, harga) VALUES
('Beras Pulen', 55.00, 15.00, 'kg', 14500),
('Ayam Suwir', 18.00, 8.00, 'kg', 42000),
('Cumi Asin', 9.00, 5.00, 'kg', 78000),
('Teri Medan', 7.50, 5.00, 'kg', 68000),
('Daun Pisang', 160.00, 50.00, 'lembar', 750),
('Kemangi', 12.00, 6.00, 'ikat', 3500),
('Cabai Merah', 14.00, 7.00, 'kg', 36000),
('Bawang Merah', 11.00, 6.00, 'kg', 32000),
('Bawang Putih', 8.00, 5.00, 'kg', 30000),
('Minyak Goreng', 22.00, 8.00, 'liter', 17500);

INSERT INTO menu_nasi_bakar (nama_menu, harga, deskripsi, gambar) VALUES
('Nasi Bakar Ayam Suwir', 18000, 'Nasi gurih berisi ayam suwir pedas, kemangi, dan sambal khas.', 'nasi-bakar-ayam.jpg'),
('Nasi Bakar Cumi', 22000, 'Nasi bakar dengan cumi asin pedas dan aroma daun pisang.', 'nasi-bakar-cumi.jpg'),
('Nasi Bakar Teri Kemangi', 17000, 'Teri medan gurih, kemangi segar, dan bumbu pedas harum.', 'nasi-bakar-teri.jpg'),
('Nasi Bakar Tuna', 21000, 'Isian tuna berbumbu kuning pedas dengan nasi gurih.', 'nasi-bakar-tuna.jpg'),
('Nasi Bakar Jamur', 16000, 'Pilihan tanpa daging dengan jamur berbumbu dan kemangi.', 'nasi-bakar-jamur.jpg');

INSERT INTO supplier (nama_supplier, no_telp, alamat) VALUES
('CV Pangan Segar', '0812-4400-1100', 'Jl. Pasar Induk No. 12'),
('Toko Ayam Makmur', '0821-3311-8822', 'Jl. Kenanga Raya No. 9'),
('Supplier Daun Pisang Lestari', '0857-1200-5533', 'Jl. Kebun Hijau No. 4'),
('Bumbu Nusantara', '0813-7788-9900', 'Jl. Rempah Selatan No. 18');

INSERT INTO stok_masuk (bahan_id, supplier_id, jumlah, tanggal, keterangan) VALUES
(1, 1, 25.00, '2026-06-18', 'Restok beras mingguan'),
(2, 2, 10.00, '2026-06-19', 'Restok ayam suwir'),
(5, 3, 80.00, '2026-06-20', 'Restok daun pisang'),
(7, 4, 6.00, '2026-06-21', 'Restok cabai merah');

INSERT INTO stok_keluar (bahan_id, jumlah, keterangan, tanggal) VALUES
(1, 12.00, 'Produksi nasi bakar harian', '2026-06-21'),
(2, 4.00, 'Produksi menu ayam suwir', '2026-06-21'),
(5, 45.00, 'Pembungkus produksi akhir pekan', '2026-06-22'),
(7, 3.00, 'Sambal dan bumbu isian', '2026-06-22');

INSERT INTO pesanan (kode_pesanan, nama_pelanggan, total, metode_bayar, status, catatan, user_id) VALUES
('NB-20260630-001', 'Rina', 40000, 'qris', 'menunggu', 'Ayam pedas sedang, cumi pedas', 2),
('NB-20260630-002', 'Budi', 34000, 'tunai', 'selesai', 'Dibungkus', 2);

INSERT INTO pesanan_detail (pesanan_id, menu_id, qty, harga, subtotal) VALUES
(1, 1, 1, 18000, 18000),
(1, 2, 1, 22000, 22000),
(2, 3, 2, 17000, 34000);

INSERT INTO permintaan_restok (bahan_id, jumlah, tanggal, status, keterangan) VALUES
(3, 5.00, '2026-06-30', 'Menunggu', 'Cumi asin mulai menipis untuk produksi besok');
