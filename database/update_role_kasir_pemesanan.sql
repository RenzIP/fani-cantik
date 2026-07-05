USE inventaris_nasi_bakar;

ALTER TABLE users
    MODIFY role ENUM('admin', 'owner', 'kasir', 'dapur', 'restok', 'gudang') NOT NULL DEFAULT 'admin';

UPDATE users SET role = 'gudang' WHERE role = 'restok';

ALTER TABLE users
    MODIFY role ENUM('admin', 'owner', 'kasir', 'dapur', 'gudang') NOT NULL DEFAULT 'admin';

ALTER TABLE bahan_baku
    ADD COLUMN IF NOT EXISTS stok_minimum DECIMAL(10,2) NOT NULL DEFAULT 10 AFTER stok;

ALTER TABLE stok_masuk
    ADD COLUMN IF NOT EXISTS keterangan TEXT NOT NULL AFTER tanggal;

CREATE TABLE IF NOT EXISTS pesanan (
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

ALTER TABLE pesanan
    MODIFY status ENUM('baru', 'menunggu', 'diproses', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu';

UPDATE pesanan SET status = 'menunggu' WHERE status = 'baru';

ALTER TABLE pesanan
    MODIFY status ENUM('menunggu', 'diproses', 'selesai', 'batal') NOT NULL DEFAULT 'menunggu';

CREATE TABLE IF NOT EXISTS pesanan_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT NOT NULL,
    menu_id INT NOT NULL,
    qty INT NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_menu FOREIGN KEY (menu_id) REFERENCES menu_nasi_bakar(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS permintaan_restok (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bahan_id INT NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('Menunggu', 'Disetujui', 'Ditolak') NOT NULL DEFAULT 'Menunggu',
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_permintaan_bahan FOREIGN KEY (bahan_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO users (nama, email, password, role) VALUES
('Kasir Nasi Bakar', 'kasir@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'kasir'),
('Dapur Nasi Bakar', 'dapur@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'dapur'),
('Gudang Nasi Bakar', 'gudang@nasibakar.test', '$2y$10$28r6t3P6RP2pZI6sLJBmBOIoL2mfaDbaE.47qxMFVX1A2ckLzI7qe', 'gudang')
ON DUPLICATE KEY UPDATE
    nama = VALUES(nama),
    role = VALUES(role);
