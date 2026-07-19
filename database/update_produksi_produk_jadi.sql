-- Jalankan skrip ini pada database yang sudah ada sebelum memakai fitur Produksi Dapur.

ALTER TABLE menu_nasi_bakar
    ADD COLUMN IF NOT EXISTS produk_jadi_id INT NULL AFTER gambar,
    ADD CONSTRAINT fk_menu_produk_jadi FOREIGN KEY (produk_jadi_id)
        REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS produksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_produksi VARCHAR(40) NOT NULL UNIQUE,
    produk_id INT NOT NULL,
    jumlah_hasil DECIMAL(10,2) NOT NULL,
    tanggal DATE NOT NULL,
    keterangan TEXT NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produksi_produk FOREIGN KEY (produk_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_produksi_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produksi_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produksi_id INT NOT NULL,
    bahan_id INT NOT NULL,
    jumlah_pakai DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_produksi_detail_header FOREIGN KEY (produksi_id) REFERENCES produksi(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_produksi_detail_bahan FOREIGN KEY (bahan_id) REFERENCES bahan_baku(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Setelah migrasi, buat produk jadi melalui menu Bahan Baku dengan jenis "Produk Jadi"
-- lalu hubungkan setiap menu nasi bakar ke produk jadi yang sesuai melalui form Menu Nasi Bakar.
