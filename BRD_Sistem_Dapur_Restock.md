# BUSINESS REQUIREMENTS DOCUMENT (BRD)
## Sistem Informasi Dapur dan Restock Warung Nasi Bakar

**Disusun untuk memenuhi salah satu tugas Mata Kuliah**  
**Praktikum Rekayasa Perangkat Lunak**  
**Dosen Pengampu: Dini Hamidin, S.Si., MBA., MT.**

**Disusun Oleh:**
* Al Fanisa Basri
* Feby Mutiara
* Ratu Sinatria Ragib

**SCHOOL OF INFORMATION TECHNOLOGY**  
**UNIVERSITAS LOGISTIK DAN BISNIS INTERNASIONAL**  
**2026**

---

## 1. Document Revisions

| Date | Version | Document Changes | Author |
|---|---|---|---|
| 2026-06-15 | 1.0 | Initial Draft - BRD Sistem Informasi Dapur dan Restock Warung Nasi Bakar | Al Fanisa Basri, Feby Mutiara, Ratu Sinatria Ragib |
| 2026-07-13 | 2.0 | Penyesuaian BRD dengan implementasi web PHP & skema database MySQL riil | Al Fanisa Basri, Feby Mutiara, Ratu Sinatria Ragib |

---

## 2. Approvals

| Role | Name | Title | Signature | Date |
|---|---|---|---|---|
| Dosen Pengampu / Reviewer | Dini Hamidin, S.Si., MBA., MT. | Lecturer | | 13/07/2026 |
| Project Sponsor / Owner | Pemilik Warung Nasi Bakar | Business Owner | | 13/07/2026 |
| Development Team Lead | Al Fanisa Basri | Student | | 13/07/2026 |

---

## DAFTAR ISI
1. [Document Revisions](#1-document-revisions)
2. [Approvals](#2-approvals)
3. [Introduction](#3-introduction)
   - 3.1 [Project Summary](#31-project-summary)
     - 3.1.1 [Objectives](#311-objectives)
     - 3.1.2 [Background](#312-background)
   - 3.2 [Project Scope](#32-project-scope)
     - 3.2.1 [In Scope Functionality](#321-in-scope-functionality)
     - 3.2.2 [Out of Scope Functionality](#322-out-of-scope-functionality)
   - 3.3 [System Perspective](#33-system-perspective)
   - 3.4 [Stakeholders](#34-stakeholders)
     - 3.4.1 [Assumptions](#341-assumptions)
     - 3.4.2 [Constraints](#342-constraints)
     - 3.4.3 [Risks](#343-risks)
4. [Business Process Overview](#4-business-process-overview)
   - 4.1 [Current Business Process (As-Is)](#41-current-business-process-as-is)
   - 4.2 [Proposed Business Process (To-Be)](#42-proposed-business-process-to-be)
   - 4.3 [Impact of Proposed Changes on Business Services and Processes](#43-impact-of-proposed-changes-on-business-services-and-processes)
5. [Business Requirements](#5-business-requirements)
   - 5.1 [Functional Requirements](#51-functional-requirements)
   - 5.2 [Non-Functional Requirements](#52-non-functional-requirements)
   - 5.3 [Business Rules](#53-business-rules)
   - 5.4 [Reporting Requirement](#54-reporting-requirement)
6. [Appendices](#6-appendices)
   - 6.1 [List of Acronyms](#61-list-of-acronyms)
   - 6.2 [Glossary of Terms](#62-glossary-of-terms)
   - 6.3 [Data Dictionary](#63-data-dictionary)

---

## 3. Introduction

### 3.1 Project Summary

#### 3.1.1 Objectives
Proyek ini bertujuan untuk mengembangkan Sistem Informasi Dapur dan Restock berbasis digital guna menggantikan proses manual pada Warung Nasi Bakar. Sistem yang akan dibangun diharapkan dapat:
* Mendigitalisasi pencatatan stok bahan baku, rekap keluar masuk barang, dan proses restock secara terintegrasi.
* Mengintegrasikan alur pesanan dari kasir ke dapur melalui antrian pesanan digital (HTTP Polling refresh harian) secara berkala.
* Menyediakan laporan stok, transaksi kasir, dan keluar masuk barang harian yang dapat diakses oleh admin atau pemilik melalui dashboard.
* Mengurangi risiko kesalahan operasional akibat proses manual dan meningkatkan efisiensi kerja dapur.
* Menyelaraskan sistem informasi dengan kebutuhan operasional warung yang berkembang secara berkelanjutan.

#### 3.1.2 Background
Warung Nasi Bakar adalah usaha kuliner skala kecil-menengah yang beroperasi melayani pesanan makan siang dan sore secara langsung, dengan kapasitas rata-rata 50-150 porsi per hari. Seluruh proses operasional pada bagian dapur dan restock saat ini masih dilakukan secara manual dan berdasarkan kebiasaan kerja harian.
Berdasarkan hasil analisis menggunakan Fishbone Diagram, teridentifikasi sejumlah permasalahan operasional, yaitu: 
1. Pengecekan stok bahan masih dilakukan secara visual sehingga berisiko terjadi kehabisan stok pada jam sibuk.
2. Pengecekan keluar masuk barang belum tercatat secara sistematis.
3. SOP koordinasi kasir-dapur belum terdokumentasi secara tertulis sehingga bergantung pada kebiasaan lisan petugas.
4. Alur pesanan dari kasir ke dapur masih dilakukan secara verbal atau catatan tulis tangan sehingga rawan salah pesanan.
5. Laporan stok dan restock belum tersedia secara sistematis sehingga pemilik kesulitan memantau kebutuhan bahan harian.

Berdasarkan permasalahan tersebut, proyek pengembangan Sistem Informasi Dapur dan Restock ini diinisiasi untuk meningkatkan efisiensi operasional, akurasi data stok, dan kualitas layanan pada Warung Nasi Bakar.

### 3.2 Project Scope

#### 3.2.1 In Scope Functionality
* Pencatatan dan monitoring stok bahan baku secara digital.
* Peringatan visual otomatis untuk stok bahan baku di bawah batas minimum (`stok <= stok_minimum`).
* Kelola data master varian menu makanan nasi bakar dan data supplier.
* Input pesanan kasir digital (pencatatan data pelanggan, total biaya, detail menu yang dipesan, dan status).
* Halaman antrian pesanan monitor dapur digital secara FIFO dengan auto-refresh 20 detik.
* Fitur pencatatan pengeluaran bahan dapur (Stok Keluar) untuk memotong kuantitas stok bahan baku.
* Pengajuan permohonan bahan baku dari dapur (Permintaan Restok) dengan status (Menunggu, Disetujui, Ditolak).
* Fitur pencatatan barang masuk dari supplier (Stok Masuk) oleh bagian gudang untuk menambahkan kuantitas stok bahan baku.
* Modul laporan terintegrasi (laporan transaksi harian, laporan stok menipis, laporan stok masuk, dan laporan stok keluar).
* Proteksi login keamanan berbasis role (*admin*, *owner*, *kasir*, *dapur*, *gudang*).

#### 3.2.2 Out of Scope Functionality
* Integrasi dengan API platform pemesanan online komersial (GoFood, ShopeeFood, GrabFood).
* Fitur pemesanan mandiri oleh pelanggan (self-ordering).
* Fitur akuntansi keuangan lanjutan (neraca keuangan, laporan laba rugi bulanan otomatis).
* Integrasi dengan sistem perpajakan negara (e-faktur).
* Aplikasi mobile untuk pelanggan (Android/iOS standalone).

### 3.3 System Perspective
Sistem Informasi Dapur dan Restock Warung Nasi Bakar adalah aplikasi berbasis web native sederhana (PHP/MySQL) yang beroperasi secara client-server lokal di lingkungan LAN warung. Sistem menggantikan proses manual dengan alur digital dan dirancang dengan batasan:
* **Teknis**: Sistem harus ringan, dapat diakses dari browser PC, tablet, atau handphone dengan spesifikasi RAM minimum 2GB.
* **Infrastruktur**: Dijalankan di localhost lokal (menggunakan web server XAMPP Apache) tanpa biaya hosting cloud premium.

### 3.4 Stakeholders

| No. | Stakeholders | Keterangan |
|---|---|---|
| 1 | **Admin** | Hak akses penuh untuk mengelola master data (bahan baku, menu, supplier, user) dan laporan. |
| 2 | **Owner** | Hak akses penuh untuk memantau dashboard notifikasi stok dan mengunduh laporan operasional. |
| 3 | **Kasir** | Menginput transaksi pemesanan harian pelanggan dan melihat status antrian pesanan. |
| 4 | **Dapur** | Memantau antrian pesanan masuk harian, mencatat pemakaian bahan dapur (stok keluar), dan mengajukan restok bahan. |
| 5 | **Gudang** | Memproses persetujuan permintaan restok dapur dan mencatat transaksi barang masuk (stok masuk) dari supplier. |

#### 3.4.1 Assumptions
* Sistem digunakan pada lingkungan warung dengan koneksi lokal LAN stabil.
* Petugas kasir dan dapur dapat mengoperasikan perambah web (browser) standar.
* Admin/Gudang bertanggung jawab atas akurasi input data stok bahan baku awal ke dalam sistem.

#### 3.4.2 Constraints
* Sistem harus dapat berjalan lancar di browser Chrome/Firefox pada RAM minimal 2GB.
* Dikembangkan menggunakan PHP native dengan konektor MySQLi agar mempermudah proses evaluasi mata kuliah Rekayasa Perangkat Lunak.
* Pengembangan diselesaikan dalam jangka waktu satu semester akademik.

#### 3.4.3 Risks
* **Resistensi Pengguna**: Staf kasir/dapur lambat mengadopsi sistem baru; diantisipasi dengan pembuatan antarmuka sederhana (tabel responsif `.table-fit` yang mudah dipahami).
* **Downtime Perangkat**: Gangguan listrik lokal menghambat operasional; diantisipasi dengan penyiapan backup database lokal secara teratur.

---

## 4. Business Process Overview

### 4.1 Current Business Process (As-Is)
Proses operasional saat ini bergantung penuh pada pencatatan fisik dan komunikasi verbal:
* Kasir menulis pesanan pelanggan di nota kertas, lalu digantung di dapur atau disampaikan secara lisan.
* Dapur menyiapkan menu pesanan, lalu menyisihkannya tanpa pencatatan kuantitas bahan yang terbuang/digunakan.
* Dapur mengecek sisa bahan baku di gudang secara visual langsung ke rak penyimpanan.
* Permintaan pembelian bahan baru (restok) disampaikan dapur ke gudang secara lisan atau kertas catatan tidak terstruktur.
* Laporan stok akhir tidak tersedia secara tertulis kecuali saat stok benar-benar habis di tengah produksi.

### 4.2 Proposed Business Process (To-Be)
Penggantian proses berbasis web PHP/MySQL:
* Kasir menginput pemesanan lewat halaman pesanan digital.
* Sistem meneruskan data secara real-time ke monitor dapur (menggunakan HTTP Auto-refresh 20s).
* Dapur memproses pesanan dan mengupdate statusnya (`menunggu` -> `diproses` -> `selesai`).
* Dapur mencatat pemakaian bahan baku harian lewat menu **Stok Keluar** untuk memotong kuantitas stok bahan baku.
* Jika stok bahan baku menyentuh angka stok minimum, sistem memicu notifikasi visual. Dapur lalu mengajukan **Permintaan Restok** lewat sistem.
* Gudang/Admin memantau permintaan restok aktif, mengklik tombol **Setujui**, lalu mencatat fisik barang masuk dari supplier via menu **Stok Masuk** untuk menambah stok bahan baku.
* Owner melihat dashboard monitor stok dan mencetak laporan transaksi / rekap stok harian kapan saja.

### 4.3 Impact of Proposed Changes on Business Services and Processes

| Proses As-Is | Proses To-Be | Unit Terdampak | Dampak |
|---|---|---|---|
| Pengecekan stok bahan visual manual harian. | Monitoring stok terotomasi dengan notifikasi warna saat kuantitas kurang dari stok minimum. | Dapur, Gudang, Admin | Mengurangi risiko kehabisan bahan mendadak pada jam sibuk pelayanan. |
| Pengiriman pesanan lewat verbal / kertas tulis tangan. | Input pesanan digital oleh kasir yang otomatis tampil pada monitor antrian dapur secara FIFO. | Kasir, Dapur | Mengurangi kesalahan pembuatan varian menu akibat tulisan tidak terbaca. |
| Permintaan restok disampaikan secara lisan / kertas coretan. | Alur formal digital lewat permintaan restok yang memerlukan persetujuan (approval) admin/gudang. | Dapur, Gudang | Transparansi kebutuhan pembelian barang, terdokumentasi dengan rapi. |
| Laporan operasional tidak tersedia terstruktur. | Laporan transaksi, rekap stok masuk/keluar harian siap cetak di dashboard admin/owner. | Owner, Admin | Pengambilan keputusan bisnis dan audit kebutuhan operasional berbasis data nyata. |

---

## 5. Business Requirements

### Prioritas Persyaratan
* **1 - Critical**: Sangat penting untuk fungsionalitas inti proyek.
* **2 - High**: Berprioritas tinggi, tetapi sistem bisa berjalan minimal tanpanya.
* **3 - Medium**: Fitur pelengkap/nilai tambah.

### 5.1 Functional Requirements

| Req ID | Kategori | Nama | Deskripsi | Prioritas | Owner |
|---|---|---|---|---|---|
| **FR-001** | Stok Bahan | Kelola Data Bahan Baku | Gudang/Admin dapat mengelola data bahan baku (nama, stok, satuan, harga, dan stok minimum). | 1 - Critical | Gudang, Admin |
| **FR-002** | Stok Bahan | Monitoring Stok Real-time | Sistem menampilkan kuantitas stok bahan secara real-time di dashboard utama. | 1 - Critical | Owner, Gudang |
| **FR-003** | Notifikasi | Peringatan Stok Minimum | Sistem memicu warning visual jika `stok <= stok_minimum`. | 1 - Critical | Sistem |
| **FR-004** | Pesanan | Input Pesanan Digital | Kasir menginput pemesanan (nama pelanggan, metode bayar, pilihan menu makanan, qty, dan catatan). | 1 - Critical | Kasir |
| **FR-005** | Dapur | Antrian FIFO Dapur | Sistem menampilkan antrian pesanan aktif pada monitor dapur secara berurutan. | 1 - Critical | Dapur |
| **FR-006** | Dapur | Update Status Antrian | Dapur dapat memperbarui status antrian (`menunggu`, `diproses`, `selesai`, `batal`). | 1 - Critical | Dapur |
| **FR-007** | Transaksi | Rekap Stok Keluar | Dapur menginput pemakaian bahan baku riil harian untuk pemotongan stok bahan baku di database. | 1 - Critical | Dapur |
| **FR-008** | Transaksi | Permintaan Restok | Dapur membuat pengajuan permintaan pembelian bahan baku baru ke bagian gudang. | 1 - Critical | Dapur |
| **FR-009** | Transaksi | Rekap Stok Masuk | Gudang mencatat fisik barang masuk dari supplier untuk menambahkan stok bahan baku di database. | 1 - Critical | Gudang |
| **FR-010** | Laporan | Rekap Laporan Transaksi | Sistem merangkum rekap data transaksi pesanan kasir berdasarkan filter periode. | 2 - High | Owner, Admin |
| **FR-011** | Laporan | Rekap Keluar Masuk | Sistem merangkum rekap histori barang masuk dan barang keluar bahan baku. | 2 - High | Owner, Admin |
| **FR-012** | Akses | Autentikasi Pengguna | Proteksi menu login dengan session role untuk menjaga keamanan halaman aplikasi. | 1 - Critical | Semua |

### 5.2 Non-Functional Requirements

| Req ID | Kategori | Nama | Deskripsi | Prioritas |
|---|---|---|---|---|
| **NFR-001** | Performance | Response Time | Sistem merespons aksi klik pengguna dalam waktu kurang dari 2 detik. | 1 - Critical |
| **NFR-002** | Availability | Uptime Sistem | Aplikasi lokal tersedia minimal 99% selama jam buka warung. | 1 - Critical |
| **NFR-003** | Security | Autentikasi & Otorisasi | Proteksi hak akses halaman dashboard menggunakan fungsi `require_role()`. | 1 - Critical |
| **NFR-004** | Usability | Desain Responsif | Tampilan tabel menggunakan utility class `.table-fit` agar tidak meluap (*overflow*) di layar HP. | 2 - High |
| **NFR-005** | Compatibility| Kompatibilitas Perangkat| Berjalan lancar di browser Chrome 110+, Safari, dan Firefox. | 3 - Medium |

### 5.3 Business Rules
* **BR-001 (Validasi Stok Minimum)**: Sistem memicu status warning pada dashboard admin jika kuantitas bahan baku berada di bawah batas stok minimum.
* **BR-002 (Pencatatan Stok Keluar & Masuk)**: Perubahan stok bahan baku riil dilakukan melalui transaksi stok masuk (menambah stok) dan transaksi stok keluar (mengurangi stok) untuk menjaga integritas audit barang.
* **BR-003 (Persetujuan Restok)**: Permintaan restok bahan baku yang dibuat dapur harus disetujui (diubah statusnya menjadi `Disetujui` oleh gudang) sebelum gudang memasukkan transaksi stok masuk baru dari supplier.
* **BR-004 (Urutan Antrian Dapur)**: Antrian pesanan di monitor dapur diurutkan berdasarkan waktu pembuatan pesanan (*First In First Out*).
* **BR-005 (Validasi Login)**: Pengguna tidak diperbolehkan mengakses halaman di luar kewenangan perannya (misal: Kasir tidak boleh masuk ke menu Laporan).

### 5.4 Reporting Requirement
* **R-001 (Laporan Stok Bahan)**: Menampilkan nama bahan, stok saat ini, satuan, stok minimum, dan status menipis. Dapat diakses oleh Owner dan Gudang kapan saja.
* **R-002 (Laporan Transaksi Kasir)**: Rekap pesanan pelanggan harian mencakup tanggal, kode pesanan, total biaya, metode bayar, dan status transaksi.
* **R-003 (Laporan Restok Masuk & Keluar)**: Histori keluar masuk barang berdasarkan filter rentang tanggal terpilih.

---

## 6. Appendices

### 6.1 List of Acronyms
* **BRD**: Business Requirements Document
* **DFD**: Data Flow Diagram
* **ERD**: Entity Relationship Diagram
* **SOP**: Standard Operating Procedure
* **UMKM**: Usaha Mikro, Kecil, dan Menengah
* **UI**: User Interface
* **FIFO**: First In First Out
* **FK / PK**: Foreign Key / Primary Key

### 6.2 Glossary of Terms
* **As-Is System**: Sistem operasional manual yang sedang berjalan sebelum perbaikan.
* **To-Be System**: Sistem operasional digital baru yang diusulkan untuk digunakan.
* **Stok Minimum**: Angka batas terendah kuantitas aman bahan baku di gudang.
* **Stok Masuk**: Transaksi pencatatan penambahan stok bahan baku yang dibeli dari supplier.
* **Stok Keluar**: Transaksi pencatatan pemotongan stok bahan baku akibat pemakaian produksi dapur.
* **Permintaan Restok**: Formulir pengajuan pengisian ulang bahan baku dari dapur ke gudang.

### 6.3 Data Dictionary

#### Data `users` (Pengguna)
* `id`: INT (11) - ID unik pengguna, auto-increment (PK).
* `nama`: VARCHAR (100) - Nama lengkap pengguna.
* `email`: VARCHAR (120) - Email login (UNIQUE).
* `password`: VARCHAR (255) - Hash Bcrypt password.
* `role`: ENUM ('admin', 'owner', 'kasir', 'dapur', 'gudang') - Peran otorisasi akses.
* `created_at`: TIMESTAMP - Waktu akun dibuat.

#### Data `bahan_baku` (Bahan Baku)
* `id`: INT (11) - ID unik bahan baku, auto-increment (PK).
* `nama_bahan`: VARCHAR (120) - Nama bahan baku.
* `stok`: DECIMAL (10,2) - Kuantitas sisa stok bahan baku.
* `stok_minimum`: DECIMAL (10,2) - Nilai limit aman bahan baku.
* `satuan`: VARCHAR (30) - Satuan barang (kg, gram, pcs, liter, lembar, dll).
* `harga`: DECIMAL (12,2) - Estimasi harga beli bahan.
* `created_at`: TIMESTAMP - Tanggal pembuatan data.

#### Data `menu_nasi_bakar` (Menu Makanan)
* `id`: INT (11) - ID unik menu makanan, auto-increment (PK).
* `nama_menu`: VARCHAR (120) - Nama varian nasi bakar.
* `harga`: DECIMAL (12,2) - Harga jual per porsi.
* `deskripsi`: TEXT - Detail isian topping menu.
* `gambar`: VARCHAR (180) - Nama file gambar menu.

#### Data `supplier` (Pemasok)
* `id`: INT (11) - ID unik supplier, auto-increment (PK).
* `nama_supplier`: VARCHAR (120) - Nama agen pemasok.
* `no_telp`: VARCHAR (30) - Nomor telepon aktif.
* `alamat`: TEXT - Alamat fisik supplier.

#### Data `stok_masuk` (Barang Masuk)
* `id`: INT (11) - ID unik barang masuk, auto-increment (PK).
* `bahan_id`: INT (11) - Relasi ke tabel bahan baku (FK).
* `supplier_id`: INT (11) - Relasi ke tabel supplier (FK).
* `jumlah`: DECIMAL (10,2) - Kuantitas barang ditambahkan.
* `tanggal`: DATE - Tanggal pencatatan.
* `keterangan`: TEXT - Deskripsi transaksi.

#### Data `stok_keluar` (Barang Keluar)
* `id`: INT (11) - ID unik barang keluar, auto-increment (PK).
* `bahan_id`: INT (11) - Relasi ke tabel bahan baku (FK).
* `jumlah`: DECIMAL (10,2) - Kuantitas barang dikurangi.
* `keterangan`: TEXT - Alasan pengeluaran bahan.
* `tanggal`: DATE - Tanggal pencatatan.

#### Data `permintaan_restok` (Pengajuan Restok Dapur)
* `id`: INT (11) - ID unik permintaan, auto-increment (PK).
* `bahan_id`: INT (11) - Relasi ke tabel bahan baku (FK).
* `jumlah`: DECIMAL (10,2) - Kuantitas yang diminta.
* `tanggal`: DATE - Tanggal pengajuan.
* `status`: ENUM ('Menunggu', 'Disetujui', 'Ditolak') - Status persetujuan gudang.
* `keterangan`: TEXT - Penjelasan alasan pengajuan restok.
* `created_at`: TIMESTAMP - Waktu pembuatan data.

#### Data `pesanan` (Transaksi Pemesanan)
* `id`: INT (11) - ID unik pesanan, auto-increment (PK).
* `kode_pesanan`: VARCHAR (40) - Kode transaksi unik harian (UNIQUE).
* `nama_pelanggan`: VARCHAR (120) - Nama pembeli.
* `total`: DECIMAL (12,2) - Total tagihan belanja.
* `metode_bayar`: ENUM ('tunai', 'qris', 'transfer') - Pembayaran.
* `status`: ENUM ('menunggu', 'diproses', 'selesai', 'batal') - Status antrian pesanan.
* `catatan`: TEXT - Catatan pesanan kasir.
* `user_id`: INT (11) - Relasi ke tabel users pembuat pesanan (FK).
* `created_at`: TIMESTAMP - Tanggal pembuatan pesanan.

#### Data `pesanan_detail` (Rincian Menu Dipesan)
* `id`: INT (11) - ID unik baris detail, auto-increment (PK).
* `pesanan_id`: INT (11) - Relasi ke tabel pesanan (FK).
* `menu_id`: INT (11) - Relasi ke tabel menu (FK).
* `qty`: INT (11) - Jumlah porsi menu dipesan.
* `harga`: DECIMAL (12,2) - Harga beli per porsi.
* `subtotal`: DECIMAL (12,2) - Qty * Harga.
