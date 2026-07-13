# DOKUMENTASI DESKRIPSI PENGEMBANGAN PERANGKAT LUNAK (DPPL)
## Sistem Informasi Dapur dan Restock Warung Nasi Bakar

**untuk:**
Dini Hamidin, S.Si., MBA., MT.
Mata Kuliah Rekayasa Perangkat Lunak

**Dipersiapkan oleh:**
* Al Fanisa Basri
* Feby Mutiara
* Ratu Sinatria Ragib

**SCHOOL OF INFORMATION TECHNOLOGY**
**UNIVERSITAS LOGISTIK DAN BISNIS INTERNASIONAL**
**2026**
**Program Studi D4 Teknik Informatika**

Nomor Dokumen: DPPL-DR-01  
Halaman: 1 / -  
Revisi: B  
Tanggal: 13/07/2026

---

## DAFTAR PERUBAHAN

| Revisi | Deskripsi | Ditulis oleh | Tanggal |
|---|---|---|---|
| **A** | Initial Draft - DPPL Sistem Informasi Dapur dan Restock Warung Nasi Bakar | Al Fanisa Basri, Feby Mutiara, Ratu Sinatria Ragib | 15/06/2026 |
| **B** | Penyesuaian dokumen rancangan dengan implementasi web PHP & skema database riil | Al Fanisa Basri, Feby Mutiara, Ratu Sinatria Ragib | 13/07/2026 |

---

## DAFTAR ISI
1. [Pendahuluan](#1-pendahuluan)
   - 1.1 [Tujuan Penulisan Dokumen](#11-tujuan-penulisan-dokumen)
   - 1.2 [Lingkup Masalah](#12-lingkup-masalah)
   - 1.3 [Definisi, Istilah dan Singkatan](#13-definisi-istilah-dan-singkatan)
   - 1.4 [Referensi](#14-referensi)
   - 1.5 [Deskripsi Umum Dokumen (Ikhtisar)](#15-deskripsi-umum-dokumen-ikhtisar)
2. [Deskripsi Umum Perangkat Lunak](#2-deskripsi-umum-perangkat-lunak)
   - 2.1 [Deskripsi Umum Desain](#21-deskripsi-umum-desain)
   - 2.2 [Matriks Penelusuran Requirements](#22-matriks-penelusuran-requirements)
     - 2.2.1 [Antarmuka Sistem](#221-antarmuka-sistem)
     - 2.2.2 [Antarmuka Pengguna](#222-antarmuka-pengguna)
     - 2.2.3 [Antarmuka Hardware](#223-antarmuka-hardware)
     - 2.2.4 [Antarmuka Software](#224-antarmuka-software)
     - 2.2.5 [Antarmuka Komunikasi](#225-antarmuka-komunikasi)
     - 2.2.6 [Batasan Memory](#226-batasan-memory)
     - 2.2.7 [Operasi Pengguna](#227-operasi-pengguna)
   - 2.3 [Fungsi Produk](#23-fungsi-produk)
   - 2.4 [Karakteristik Pengguna](#24-karakteristik-pengguna)
   - 2.5 [Batasan](#25-batasan)
   - 2.6 [Lingkungan Operasi](#26-lingkungan-operasi)
3. [Deskripsi Umum Kebutuhan](#3-deskripsi-umum-kebutuhan)
   - 3.1 [Kebutuhan Antarmuka Eksternal](#31-kebutuhan-antarmuka-eksternal)
     - 3.1.1 [Antarmuka Pemakai](#311-antarmuka-pemakai)
     - 3.1.2 [Antarmuka Perangkat Keras](#312-antarmuka-perangkat-keras)
     - 3.1.3 [Antarmuka Perangkat Lunak](#313-antarmuka-perangkat-lunak)
     - 3.1.4 [Antarmuka Komunikasi](#314-antarmuka-komunikasi)
   - 3.2 [Deskripsi Fungsional](#32-deskripsi-fungsional)
     - 3.2.1 [Context Diagram](#321-context-diagram)
       - 3.2.1.1 [DFD Level 1](#3211-dfd-level-1)
   - 3.3 [Data Requirement](#33-data-requirement)
     - 3.3.1 [E-R Diagram](#331-e-r-diagram)
   - 3.4 [Non Functional Requirement](#34-non-functional-requirement)
   - 3.5 [Batasan Perancangan](#35-batasan-perancangan)
   - 3.6 [Kerunutan (Traceability)](#36-kerunutan-traceability)
     - 3.6.1 [Data Store vs E-R](#361-data-store-vs-e-r)
   - 3.7 [Ringkasan Kebutuhan](#37-ringkasan-kebutuhan)
     - 3.7.1 [Functional Requirement Summary](#371-functional-requirement-summary)
     - 3.7.2 [Non Functional Requirement Summary](#372-non-functional-requirement-summary)
4. [Deskripsi Perancangan Global](#4-deskripsi-perancangan-global)
   - 4.1 [Rancangan Lingkungan Implementasi](#41-rancangan-lingkungan-implementasi)
   - 4.2 [Deskripsi Data](#42-deskripsi-data)
     - 4.2.1 [Definisi Domain/Type](#421-definisi-domaintype)
     - 4.2.2 [Conceptual Data Model](#422-conceptual-data-model)
     - 4.2.3 [Physical Data Model](#423-physical-data-model)
     - 4.2.4 [Daftar Tabel Aplikasi](#424-daftar-tabel-aplikasi)
   - 4.3 [Dekomposisi Fungsional Modul](#43-dekomposisi-fungsional-modul)
5. [Deskripsi Perancangan Rinci](#5-deskripsi-perancangan-rinci)
   - 5.1 [Deskripsi Rinci Tabel](#51-deskripsi-rinci-tabel)
     - 5.1.1 [Tabel users](#511-tabel-users)
     - 5.1.2 [Tabel bahan_baku](#512-tabel-bahan_baku)
     - 5.1.3 [Tabel menu_nasi_bakar](#513-tabel-menu_nasi_bakar)
     - 5.1.4 [Tabel supplier](#514-tabel-supplier)
     - 5.1.5 [Tabel stok_masuk](#515-tabel-stok_masuk)
     - 5.1.6 [Tabel stok_keluar](#516-tabel-stok_keluar)
     - 5.1.7 [Tabel permintaan_restok](#517-tabel-permintaan_restok)
     - 5.1.8 [Tabel pesanan](#518-tabel-pesanan)
     - 5.1.9 [Tabel pesanan_detail](#519-tabel-pesanan_detail)
   - 5.2 [Deskripsi Fungsional secara Rinci](#52-deskripsi-fungsional-secara-rinci)
     - 5.2.1 [Spesifikasi Fungsi: Kelola Bahan Baku & Menu (FR-001 s.d. FR-004)](#521-spesifikasi-fungsi-kelola-bahan-baku--menu-fr-001-sd-fr-004)
     - 5.2.2 [Spesifikasi Fungsi: Kelola Pesanan (FR-005, FR-006)](#522-spesifikasi-fungsi-kelola-pesanan-fr-005-fr-006)
     - 5.2.3 [Spesifikasi Fungsi: Kelola Produksi & Dapur (FR-007, FR-008)](#523-spesifikasi-fungsi-kelola-produksi--dapur-fr-007-fr-008)
     - 5.2.4 [Spesifikasi Fungsi: Kelola Restok & Stok Masuk/Keluar (FR-009)](#524-spesifikasi-fungsi-kelola-restok--stok-masukkeluar-fr-009)
     - 5.2.5 [Spesifikasi Fungsi: Cetak Laporan (FR-010 s.d. FR-012)](#525-spesifikasi-fungsi-cetak-laporan-fr-010-sd-fr-012)
   - 5.3 [Dekomposisi Fisik Modul](#53-dekomposisi-fisik-modul)
   - 5.4 [Matriks Kerunutan](#54-matriks-kerunutan)
6. [Pengujian Perangkat Lunak](#6-pengujian-perangkat-lunak)
   - 6.1 [Lingkungan Pengujian](#61-lingkungan-pengujian)
     - 6.1.1 [Perangkat Lunak Pengujian](#611-perangkat-lunak-pengujian)
     - 6.1.2 [Perangkat Keras Pengujian](#612-perangkat-keras-pengujian)
   - 6.2 [Material Pengujian](#62-material-pengujian)
   - 6.3 [Sumber Daya Manusia](#63-sumber-daya-manusia)
   - 6.4 [Prosedur Umum Pengujian](#64-prosedur-umum-pengujian)
     - 6.4.1 [Pengenalan dan Latihan](#641-pengenalan-dan-latihan)
     - 6.4.2 [Persiapan Awal](#642-persiapan-awal)
       - 6.4.2.1 [Persiapan Prosedural](#6421-persiapan-prosedural)
       - 6.4.2.2 [Persiapan Perangkat Keras](#6422-persiapan-perangkat-keras)
       - 6.4.2.3 [Persiapan Perangkat Lunak](#6423-persiapan-perangkat-lunak)
     - 6.4.3 [Pelaksanaan](#643-pelaksanaan)
     - 6.4.4 [Pelaporan Hasil](#644-pelaporan-hasil)
   - 6.5 [Identifikasi dan Rencana Pengujian](#65-identifikasi-dan-rencana-pengujian)
   - 6.6 [Deskripsi dan Hasil Uji](#66-deskripsi-dan-hasil-uji)
     - 6.6.1 [Kelas Uji: Kelola Bahan Baku (KB)](#661-kelas-uji-kelola-bahan-baku-kb)
     - 6.6.2 [Kelas Uji: Input Pesanan (IP)](#662-kelas-uji-input-pesanan-ip)
     - 6.6.3 [Kelas Uji: Produksi (PR)](#663-kelas-uji-produksi-pr)
   - 6.7 [Keterunutan Pengujian](#67-keterunutan-pengujian)
7. [Spesifikasi Produk Perangkat Lunak](#7-spesifikasi-produk-perangkat-lunak)
   - 7.1 [Perangkat Lunak Siap Eksekusi](#71-perangkat-lunak-siap-eksekusi)
   - 7.2 [Berkas Sumber](#72-berkas-sumber)
   - 7.3 [Syarat Pemaketan](#73-syarat-pemaketan)
   - 7.4 [Prosedur Konstruksi](#74-prosedur-konstruksi)
8. [Panduan Instalasi](#8-panduan-instalasi)
   - 8.1 [Instalasi Program Siap Eksekusi](#81-instalasi-program-siap-eksekusi)
   - 8.2 [Instalasi Kode Program Sumber](#82-instalasi-kode-program-sumber)
9. [Penutup](#9-penutup)

---

## 1. Pendahuluan

### 1.1 Tujuan Penulisan Dokumen
Dokumen Deskripsi Pengembangan Perangkat Lunak (DPPL) ini disusun sebagai panduan teknis komprehensif untuk pengembangan Sistem Informasi Dapur dan Restock Warung Nasi Bakar. Dokumen ini mendeskripsikan seluruh aspek perancangan dan pengembangan perangkat lunak, mulai dari deskripsi umum sistem, kebutuhan fungsional dan non-fungsional, desain arsitektur, perancangan data, hingga rencana pengujian dan spesifikasi produk.
Dokumen ini ditujukan untuk digunakan oleh:
* Pengembang perangkat lunak sebagai acuan teknis implementasi sistem.
* Manajer proyek untuk memantau cakupan dan kemajuan pengembangan.
* Penguji sistem untuk merancang dan melaksanakan skenario pengujian.
* Dosen pengampu sebagai evaluasi tugas besar mata kuliah Rekayasa Perangkat Lunak.
* Pemilik Warung Nasi Bakar sebagai pengguna akhir dan pemangku kepentingan.

### 1.2 Lingkup Masalah
Perangkat lunak yang dikembangkan adalah Sistem Informasi Dapur dan Restock Warung Nasi Bakar. Sistem ini merupakan aplikasi berbasis web yang dirancang khusus untuk menggantikan seluruh proses manual pengelolaan dapur dan stok bahan baku pada Warung Nasi Bakar dengan alur digital yang terstruktur.
Sistem ini akan:
* Mendigitalisasi pencatatan stok bahan baku, rekap keluar masuk barang, dan proses restock secara terintegrasi.
* Mengintegrasikan alur pesanan dari kasir ke dapur melalui antrian pesanan digital secara real-time.
* Menyediakan laporan stok, produksi harian, dan restok yang dapat diakses pemilik melalui dashboard.
* Mengurangi risiko operasional akibat proses manual dan meningkatkan efisiensi kerja dapur.

Sistem ini tidak mencakup integrasi dengan platform pemesanan online eksternal, fitur pemesanan mandiri pelanggan (self-ordering), fitur akuntansi keuangan lanjutan, integrasi e-faktur, manajemen supplier otomatis, maupun aplikasi mobile.

### 1.3 Definisi, Istilah dan Singkatan
* **DPPL**: Deskripsi Pengembangan Perangkat Lunak - dokumen teknis perancangan sistem.
* **BRD**: Business Requirements Document - dokumen kebutuhan bisnis.
* **DFD**: Data Flow Diagram - diagram yang menggambarkan aliran data dalam sistem.
* **UI**: User Interface - antarmuka pengguna.
* **FR**: Functional Requirement - kebutuhan fungsional sistem.
* **NFR**: Non-Functional Requirement - kebutuhan non-fungsional sistem.
* **Dashboard**: Antarmuka visual yang menyajikan ringkasan data operasional secara terpusat.
* **FIFO**: *First In First Out* - pesanan yang masuk pertama diproses lebih dahulu.
* **Status Pesanan**: Kondisi pesanan (Menunggu / Diproses / Selesai / Batal).
* **HTTP Polling**: Jeda muat ulang halaman (auto-refresh) secara dinamis untuk mensimulasikan pemantauan data real-time.

### 1.4 Referensi
* Business Requirements Document (BRD) Sistem Informasi Dapur dan Restock Warung Nasi Bakar - ULBI, 2026.
* Tugas Besar Desain Terstruktur Nasi Bakar - ULBI, 2026.
* Template DPPL 2022 - Program Studi Teknik Informatika, Universitas Logistik dan Bisnis Internasional.
* Script SQL Database `database.sql` dan Source Code Web Aplikasi PHP - Warung Nasi Bakar, 2026.

### 1.5 Deskripsi Umum Dokumen (Ikhtisar)
Dokumen DPPL ini disusun mengikuti sistematika sebagai berikut:
* **Bab 1 - Pendahuluan**: Menjelaskan tujuan, lingkup, definisi istilah, referensi, dan ikhtisar dokumen.
* **Bab 2 - Deskripsi Umum Perangkat Lunak**: Menjelaskan pendekatan desain, matriks kebutuhan, fungsi produk, karakteristik pengguna, batasan, dan lingkungan operasi.
* **Bab 3 - Deskripsi Umum Kebutuhan**: Mendeskripsikan kebutuhan antarmuka eksternal, deskripsi fungsional (Context Diagram dan DFD), data requirement, non-functional requirement, batasan perancangan, kerunutan, dan ringkasan kebutuhan.
* **Bab 4 - Deskripsi Perancangan Global**: Memuat rancangan lingkungan implementasi, deskripsi data (CDM, PDM, daftar tabel), dan dekomposisi fungsional modul.
* **Bab 5 - Deskripsi Perancangan Rinci**: Memuat deskripsi rinci tabel, deskripsi fungsional rinci, dekomposisi fisik modul, dan matriks kerunutan.
* **Bab 6 - Pengujian Perangkat Lunak**: Menjelaskan lingkungan pengujian, material, prosedur, identifikasi rencana uji, deskripsi dan hasil uji, serta keterunutan pengujian.
* **Bab 7 - Spesifikasi Produk Perangkat Lunak**: Menjelaskan perangkat lunak siap eksekusi, berkas sumber, syarat pemaketan, dan prosedur konstruksi.
* **Bab 8 - Panduan Instalasi**: Menjelaskan cara instalasi program dan kode sumber.
* **Bab 9 - Penutup**: Berisi catatan penutup.

---

## 2. Deskripsi Umum Perangkat Lunak

### 2.1 Deskripsi Umum Desain
Sistem Informasi Dapur dan Restock Warung Nasi Bakar dirancang dengan pendekatan desain terstruktur berbasis aliran data (Data Flow-based Design). Pendekatan ini dipilih karena sistem ini bersifat transaction-processing dengan aliran data yang jelas dan terdefinisi antara kasir, dapur, gudang, dan admin/pemilik.
Fitur-fitur penting yang menjadi inti perancangan meliputi:
* **Modul Kelola Bahan Baku & Menu**: Pencatatan dan monitoring stok bahan baku serta kelola varian menu makanan.
* **Modul Notifikasi/Alert**: Peringatan visual pada dashboard jika stok di bawah minimum.
* **Modul Kelola Pesanan**: Form input pesanan digital oleh kasir dengan pengiriman data ke antrian dapur.
* **Modul Kelola Dapur**: Pemrosesan status pesanan oleh dapur (Menunggu / Diproses / Selesai).
* **Modul Kelola Restok & Pemakaian**: Pencatatan penambahan stok (Stok Masuk) oleh gudang dan pemakaian stok (Stok Keluar) oleh dapur.
* **Modul Permintaan Restok**: Formulir dapur untuk meminta restok bahan baku yang disetujui oleh gudang.
* **Modul Laporan**: Halaman monitoring admin dengan rekap transaksi, rekap stok masuk, dan stok keluar.
* **Modul Keamanan**: Autentikasi berbasis session role (Kasir / Dapur / Gudang / Admin / Owner).

### 2.2 Matriks Penelusuran Requirements

| Requirement | Modul Bahan | Modul Pesanan | Modul Dapur | Modul Restok | Modul Laporan | Modul Auth |
|---|:---:|:---:|:---:|:---:|:---:|:---:|
| **FR-001**: Pencatatan Bahan Baku | X | | | | | |
| **FR-002**: Monitoring Stok Real-time | X | | | | X | |
| **FR-003**: Notifikasi Stok Minimum | X | | | | | |
| **FR-004**: Input Pesanan Digital | | X | | | | |
| **FR-005**: Antrian Pesanan Dapur | | | X | | | |
| **FR-006**: Update Status Produksi | | | X | | | |
| **FR-007**: Pencatatan Restok & Pemakaian | | | | X | | |
| **FR-008**: Permintaan Restok | | | | X | | |
| **FR-009**: Laporan Stok Bahan | | | | | X | |
| **FR-010**: Laporan Transaksi Harian | | | | | X | |
| **FR-011**: Laporan Restok Masuk | | | | | X | |
| **NFR-003**: Autentikasi Pengguna | | | | | | X |

#### 2.2.1 Antarmuka Sistem
* **Antarmuka Layar Dapur**: Sistem menampilkan antrian pesanan aktif ke perangkat display dapur dengan mekanisme HTTP Polling setiap 20 detik secara berkala.
* **Antarmuka Notifikasi**: Sistem menampilkan alarm visual pada halaman dashboard jika stok bahan berada di bawah nilai stok minimum.

#### 2.2.2 Antarmuka Pengguna
* Navigasi berbasis peran: tampilan menu sidebar berbeda untuk kasir, dapur, gudang, dan admin/owner.
* Tabel responsif (class `table-fit` & padding dinamis) pada form stok untuk kemudahan penggunaan di tablet dapur dan HP kasir.
* Formulir pesanan menyajikan pilihan menu, input kuantitas, dan catatan khusus pesanan.

#### 2.2.3 Antarmuka Hardware
* **Perangkat Kasir**: PC/Tablet/Laptop dengan browser modern untuk menginput pesanan.
* **Layar Dapur**: Monitor/Tablet yang terhubung ke jaringan LAN lokal server untuk melihat antrian.
* **Perangkat Admin/Owner**: PC/Laptop untuk melihat dashboard dan laporan.

#### 2.2.4 Antarmuka Software

| Nama Software | Versi | Tujuan |
|---|---|---|
| **Sistem Operasi** | Windows 10/11 atau Linux Ubuntu | Platform utama server dan klien |
| **Browser** | Chrome 110+, Firefox 110+, Edge 110+ | Antarmuka pengguna berbasis web |
| **DBMS** | MySQL 8.0+ atau MariaDB 10.4+ | Manajemen basis data operasional |
| **Web Server** | Apache 2.4+ | Hosting aplikasi web lokal |
| **Runtime** | PHP 8.1+ | Backend server aplikasi |

#### 2.2.5 Antarmuka Komunikasi
* Protokol HTTP/HTTPS untuk pemuatan halaman web.
* TCP/IP sebagai protokol jaringan LAN lokal penghubung seluruh perangkat.

#### 2.2.6 Batasan Memory
* Kapasitas penyimpanan database sangat efisien, disarankan ruang penyimpanan minimal 10GB untuk operasional bertahun-tahun.

#### 2.2.7 Operasi Pengguna
* **Kasir**: Login, menginput pesanan pelanggan harian.
* **Dapur**: Login, memantau antrian pesanan, mengubah status produksi pesanan, mencatat stok keluar bahan baku, serta mengajukan restok.
* **Gudang/Restok**: Login, mengelola data bahan baku, memverifikasi permintaan restok dari dapur, serta mencatat stok masuk (restok).
* **Owner/Admin**: Mengakses seluruh master data, mengelola user, dan mengunduh laporan bulanan.

### 2.3 Fungsi Produk

| No | Fungsi Utama | Deskripsi Singkat |
|---|---|---|
| 1 | Pencatatan Bahan Baku | Input data bahan baku mencakup nama, stok, satuan, harga, dan stok minimum. |
| 2 | Monitoring Stok Real-time | Menampilkan kondisi stok bahan dengan indikator visual jika stok di bawah minimum. |
| 3 | Input Pesanan Digital | Kasir memasukkan pesanan pelanggan mencakup pilihan menu, kuantitas, dan catatan. |
| 4 | Antrian Pesanan Dapur | Sistem meneruskan pesanan ke monitor dapur secara otomatis berbasis urutan masuk (FIFO). |
| 5 | Update Status Produksi | Dapur memperbarui status pesanan (`menunggu` -> `diproses` -> `selesai`). |
| 6 | Pencatatan Stok Keluar | Dapur mencatat pemakaian bahan baku riil untuk pemotongan stok bahan baku di database. |
| 7 | Permintaan Restok | Dapur membuat pengajuan permintaan barang jika bahan baku habis. |
| 8 | Pencatatan Stok Masuk | Gudang menyetujui permintaan restok dan mencatat barang masuk dari supplier untuk menambahkan stok. |
| 9 | Laporan Operasional | Sistem menghasilkan laporan stok harian, transaksi pesanan kasir, dan riwayat keluar masuk barang. |
| 10| Autentikasi User | Login terproteksi untuk masing-masing hak akses peran user. |

### 2.4 Karakteristik Pengguna

| Kategori Pengguna | Tugas/Pekerjaan | Hak Akses | Tingkat Keahlian IT |
|---|---|---|---|
| **Admin/Owner** | Mengelola seluruh sistem, melihat laporan pendapatan dan stok. | Hak akses penuh (All menus, user management, reports). | Dasar - Menengah |
| **Gudang/Restok** | Mengelola bahan baku, supplier, stok masuk, dan approval permintaan. | Akses menu bahan baku, supplier, transaksi stok masuk, dan status permintaan restok. | Dasar |
| **Kasir** | Menginput transaksi pemesanan harian pelanggan. | Akses menu kasir (input pesanan, riwayat pesanan kasir). | Dasar |
| **Dapur** | Memproses pesanan, rekap stok keluar, mengajukan restok. | Akses antrian dapur, input stok keluar, form permintaan restok, monitor stok. | Dasar |

### 2.5 Batasan
* Sistem harus berjalan pada perangkat browser modern standar tanpa perlu instalasi rumit di sisi client.
* Penyusunan kode program berbasis PHP native terstruktur agar mudah dipahami untuk tugas perkuliahan Rekayasa Perangkat Lunak.
* Sistem harus mampu menangani minimal 150 transaksi pesanan harian.

### 2.6 Lingkungan Operasi
* **Server**: PC lokal dengan XAMPP (Windows/Linux), MySQL 8.0+, PHP 8.1+.
* **Client**: Browser web modern pada resolusi PC/Tablet/Mobile.

---

## 3. Deskripsi Umum Kebutuhan

### 3.1 Kebutuhan Antarmuka Eksternal

#### 3.1.1 Antarmuka Pemakai
* Keyboard dan mouse untuk input data teks.
* Layar sentuh (touchscreen) pada perangkat mobile/tablet kasir dan dapur.

#### 3.1.2 Antarmuka Perangkat Keras
* Perangkat jaringan LAN (Router/Wi-Fi) untuk menghubungkan client ke server lokal.

#### 3.1.3 Antarmuka Perangkat Lunak
* Web Browser API untuk merender layout HTML, CSS responsif, dan fungsionalitas Javascript.

#### 3.1.4 Antarmuka Komunikasi
* HTTP/HTTPS (port 80/443) untuk pemanggilan web dan data submisi form.

### 3.2 Deskripsi Fungsional

#### 3.2.1 Context Diagram
Context diagram menggambarkan aliran data utama di mana Kasir mengirim data pesanan, Dapur memproses status pesanan dan mencatat stok keluar, Gudang mengelola bahan baku dan menyetujui restok, serta Owner/Admin menerima visualisasi laporan operasional secara terpusat.

##### 3.2.1.1 DFD Level 1

| No | Nama Proses | Input | Output | Data Store |
|---|---|---|---|---|
| 1.0 | Autentikasi | Form Login email/password | Sesi login disetujui/ditolak | `users` |
| 2.0 | Kelola Pesanan | Input pesanan kasir | Simpan ke pesanan detail | `pesanan`, `pesanan_detail` |
| 3.0 | Kelola Dapur | Update status pesanan | Mengubah status antrian | `pesanan` |
| 4.0 | Kelola Stok Keluar | Input bahan digunakan dapur | Mengurangi kuantitas bahan | `stok_keluar`, `bahan_baku` |
| 5.0 | Kelola Permintaan | Dapur meminta restok bahan | Status Menunggu persetujuan | `permintaan_restok` |
| 6.0 | Kelola Stok Masuk | Gudang input restok supplier | Menambah kuantitas bahan | `stok_masuk`, `bahan_baku` |
| 7.0 | Cetak Laporan | Request filter periode | Tampilan rekap data | Semua Data Store |

### 3.3 Data Requirement

#### 3.3.1 E-R Diagram
Hubungan relasi antar entitas digambarkan secara fisik pada struktur database relasional MySQL yang menghubungkan tabel `users`, `bahan_baku`, `menu_nasi_bakar`, `supplier`, `stok_masuk`, `stok_keluar`, `permintaan_restok`, `pesanan`, dan `pesanan_detail`.

### 3.4 Non Functional Requirement
* **NFR-001 (Response Time)**: Waktu render pemuatan halaman lokal kurang dari 2 detik.
* **NFR-002 (Availability)**: Uptime server lokal 99% selama jam operasional warung.
* **NFR-003 (Security)**: Hak akses dinamis berbasis peran, password tersimpan menggunakan enkripsi bcrypt.
* **NFR-004 (Usability)**: Layout antarmuka responsif ramah layar mobile.

### 3.5 Batasan Perancangan
* Basis data harus dikelola secara relasional menggunakan MySQL dengan foreign key constraint (ENGINE=InnoDB) untuk menjaga konsistensi data referensial.

### 3.6 Kerunutan (Traceability)

#### 3.6.1 Data Store vs E-R

| Data Store | Entity terkait |
|---|---|
| **DS-User** | `users` |
| **DS-Bahan** | `bahan_baku` |
| **DS-Menu** | `menu_nasi_bakar` |
| **DS-Supplier** | `supplier` |
| **DS-StokMasuk** | `stok_masuk` |
| **DS-StokKeluar** | `stok_keluar` |
| **DS-Permintaan** | `permintaan_restok` |
| **DS-Pesanan** | `pesanan`, `pesanan_detail` |

### 3.7 Ringkasan Kebutuhan

#### 3.7.1 Functional Requirement Summary
* **FR-001**: CRUD Bahan Baku & Menu Nasi Bakar.
* **FR-002**: Monitoring Stok minimum otomatis secara visual.
* **FR-003**: Input data pemesanan harian (Kasir).
* **FR-004**: Monitor antrian monitor dapur.
* **FR-005**: Kelola status produksi pesanan dapur.
* **FR-006**: Transaksi stok keluar pemakaian dapur.
* **FR-007**: Permintaan restok bahan baku oleh dapur.
* **FR-008**: Transaksi stok masuk penambahan gudang.
* **FR-009**: Rekap cetak laporan operasional per periode.

#### 3.7.2 Non Functional Requirement Summary
* Keamanan data login lewat session PHP.
* Desain adaptif responsif (`table-fit`) untuk layar mobile/tablet.

---

## 4. Deskripsi Perancangan Global

### 4.1 Rancangan Lingkungan Implementasi
* **Sistem Database**: MySQL 8.0+
* **Bahasa Backend**: PHP 8.1+ native terstruktur
* **Desain CSS**: Vanilla CSS responsif terstandar (`dashboard.css`)

### 4.2 Deskripsi Data

#### 4.2.1 Definisi Domain/Type
* `role` ENUM('admin', 'owner', 'kasir', 'dapur', 'gudang')
* `metode_bayar` ENUM('tunai', 'qris', 'transfer')
* `status_pesanan` ENUM('menunggu', 'diproses', 'selesai', 'batal')
* `status_restok` ENUM('Menunggu', 'Disetujui', 'Ditolak')

#### 4.2.2 Conceptual Data Model
CDM memodelkan entitas relasional utama seperti pengguna membuat pesanan, pesanan memiliki detail menu, bahan baku dipasok oleh supplier, serta log keluar masuk stok bahan harian.

#### 4.2.3 Physical Data Model
PDM diwujudkan ke dalam tabel fisik database relational `inventaris_nasi_bakar` yang mengimplementasikan index, tipe data DECIMAL untuk stok presisi, serta ON UPDATE/DELETE cascade constraint.

#### 4.2.4 Daftar Tabel Aplikasi
* `users`
* `bahan_baku`
* `menu_nasi_bakar`
* `supplier`
* `stok_masuk`
* `stok_keluar`
* `permintaan_restok`
* `pesanan`
* `pesanan_detail`

### 4.3 Dekomposisi Fungsional Modul
Modul dibagi secara fisik ke dalam folder `/dashboard/` yang masing-masing sub-foldernya mewakili satu fungsionalitas tabel data master atau data transaksi operasional.

---

## 5. Deskripsi Perancangan Rinci

### 5.1 Deskripsi Rinci Tabel

#### 5.1.1 Tabel `users`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `nama` | VARCHAR(100) | NO | | Nama Lengkap Pengguna |
| `email` | VARCHAR(120) | NO | | Email untuk login (UNIQUE) |
| `password` | VARCHAR(255) | NO | | Bcrypt hash password |
| `role` | ENUM | NO | 'admin' | Hak peran user |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | Waktu pendaftaran |

#### 5.1.2 Tabel `bahan_baku`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `nama_bahan` | VARCHAR(120) | NO | | Nama Bahan Baku |
| `stok` | DECIMAL(10,2) | NO | 0.00 | Kuantitas tersedia |
| `stok_minimum` | DECIMAL(10,2) | NO | 10.00 | Limit minimal pemicu warning |
| `satuan` | VARCHAR(30) | NO | | Satuan (kg, gram, pcs, dll) |
| `harga` | DECIMAL(12,2) | NO | 0.00 | Perkiraan harga beli |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | |

#### 5.1.3 Tabel `menu_nasi_bakar`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `nama_menu` | VARCHAR(120) | NO | | Varian produk menu |
| `harga` | DECIMAL(12,2) | NO | 0.00 | Harga jual per porsi |
| `deskripsi` | TEXT | NO | | Keterangan isian menu |
| `gambar` | VARCHAR(180) | YES | NULL | Gambar thumbnail menu |

#### 5.1.4 Tabel `supplier`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `nama_supplier` | VARCHAR(120) | NO | | Nama Agen Pemasok |
| `no_telp` | VARCHAR(30) | NO | | Nomor Telepon Aktif |
| `alamat` | TEXT | NO | | Alamat Supplier |

#### 5.1.5 Tabel `stok_masuk`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `bahan_id` | INT | NO | | FK -> `bahan_baku`(`id`) |
| `supplier_id` | INT | NO | | FK -> `supplier`(`id`) |
| `jumlah` | DECIMAL(10,2) | NO | | Jumlah stok masuk |
| `tanggal` | DATE | NO | | Tanggal barang masuk |
| `keterangan` | TEXT | NO | | Catatan stok masuk |

#### 5.1.6 Tabel `stok_keluar`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `bahan_id` | INT | NO | | FK -> `bahan_baku`(`id`) |
| `jumlah` | DECIMAL(10,2) | NO | | Jumlah stok keluar |
| `keterangan` | TEXT | NO | | Tujuan pengeluaran |
| `tanggal` | DATE | NO | | Tanggal barang keluar |

#### 5.1.7 Tabel `permintaan_restok`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `bahan_id` | INT | NO | | FK -> `bahan_baku`(`id`) |
| `jumlah` | DECIMAL(10,2) | NO | | Kuantitas diminta dapur |
| `tanggal` | DATE | NO | | Tanggal pengajuan restok |
| `status` | ENUM | NO | 'Menunggu' | Status approval restok |
| `keterangan` | TEXT | NO | | Catatan dapur |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | |

#### 5.1.8 Tabel `pesanan`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `kode_pesanan` | VARCHAR(40) | NO | | Kode unik transaksi (UNIQUE) |
| `nama_pelanggan` | VARCHAR(120) | NO | | Nama Pembeli |
| `total` | DECIMAL(12,2) | NO | 0.00 | Total biaya pesanan |
| `metode_bayar` | ENUM | NO | 'tunai' | Pembayaran (tunai, qris, dll) |
| `status` | ENUM | NO | 'menunggu' | Status antrian pesanan |
| `catatan` | TEXT | YES | NULL | Catatan level pedas/topping |
| `user_id` | INT | YES | NULL | FK -> `users`(`id`) (Kasir) |
| `created_at` | TIMESTAMP | NO | CURRENT_TIMESTAMP | |

#### 5.1.9 Tabel `pesanan_detail`
* **Primary Key**: `id`

| Field | Tipe | Null | default | Keterangan |
|---|---|---|---|---|
| `id` | INT | NO | AUTO_INCREMENT | PK |
| `pesanan_id` | INT | NO | | FK -> `pesanan`(`id`) |
| `menu_id` | INT | NO | | FK -> `menu_nasi_bakar`(`id`) |
| `qty` | INT | NO | | Kuantitas porsi |
| `harga` | DECIMAL(12,2) | NO | 0.00 | Harga beli item |
| `subtotal` | DECIMAL(12,2) | NO | 0.00 | Qty * Harga |

---

### 5.2 Deskripsi Fungsional secara Rinci

#### 5.2.1 Spesifikasi Fungsi: Kelola Bahan Baku & Menu (FR-001 s.d. FR-004)
* **Algoritma Tambah Bahan**:
  1. Pengguna (Gudang/Admin) membuka `/dashboard/bahan/form.php`.
  2. Input: `nama_bahan`, `stok`, `stok_minimum`, `satuan`, `harga`.
  3. Mengklik simpan. Data diproses di `/dashboard/bahan/simpan.php`.
  4. Sistem melakukan query insert ke `bahan_baku`.
  5. Kembali ke `index.php` dengan flash message sukses.

#### 5.2.2 Spesifikasi Fungsi: Kelola Pesanan (FR-005, FR-006)
* **Algoritma Input Pesanan**:
  1. Kasir membuka `/dashboard/kasir/form.php`.
  2. Mengisi nama pelanggan, metode pembayaran, catatan, dan memilih varian menu beserta kuantitasnya.
  3. Mengklik simpan, diproses di `/dashboard/kasir/simpan.php`.
  4. Query insert dijalankan ke `pesanan` untuk membuat header transaksi, disusul insert baris detail pesanan ke `pesanan_detail` menggunakan transaction block.
  5. Kasir diarahkan kembali ke daftar pesanan kasir.

#### 5.2.3 Spesifikasi Fungsi: Kelola Produksi & Dapur (FR-007, FR-008)
* **Algoritma Monitor Antrian**:
  1. Dapur membuka `/dashboard/dapur/index.php`. Halaman di-refresh otomatis setiap 20 detik untuk mengecek pesanan baru dari kasir.
  2. Dapur mengklik **Proses** untuk menandai pesanan sedang dibuat. Status pesanan diupdate di database menjadi `diproses`.
  3. Dapur mengklik **Selesai** setelah pesanan matang. Status pesanan diupdate di database menjadi `selesai`.

#### 5.2.4 Spesifikasi Fungsi: Kelola Restok & Stok Masuk/Keluar (FR-009)
* **Algoritma Input Transaksi Stok**:
  1. Dapur mencatat pemakaian bahan baku dapur melalui `/dashboard/keluar/form.php` untuk mengurangi stok pada database `bahan_baku` secara riil.
  2. Jika stok bahan menipis, dapur mengajukan permohonan di `/dashboard/permintaan/form.php` (status `Menunggu`).
  3. Gudang membuka daftar permintaan restok, meninjau kuantitas, lalu mengklik **Setujui** (status permintaan terupdate menjadi `Disetujui`).
  4. Gudang mencatat fisik barang masuk dari supplier di `/dashboard/masuk/form.php` untuk menambahkan stok barang secara riil di `bahan_baku`.

#### 5.2.5 Spesifikasi Fungsi: Cetak Laporan (FR-010 s.d. FR-012)
* **Algoritma Tampilan Laporan**:
  1. Admin/Owner membuka menu Laporan (`/dashboard/laporan/index.php`).
  2. Halaman memproses query summary stok bahan menipis, rekap transaksi harian, serta daftar keluar masuk stok terbaru dari database dan merendernya dalam bentuk tabel responsif.

---

### 5.3 Dekomposisi Fisik Modul

* **`/config/koneksi.php`**: Berkas koneksi MySQL.
* **`/includes/functions.php`**: Kumpulan pembantu otorisasi peranan user, pagination, rekap jumlah baris database.
* **`/includes/header.php`**: Pemuatan kerangka layout HTML5, font Google, dan file CSS `dashboard.css?v=<?= filemtime(...) ?>` (cache-buster).
* **`/includes/sidebar.php`**: Menu bar navigasi dinamis berbasis role.
* **`/includes/footer.php`**: Layout penutup standard.
* **`/auth/`**: Modul autentikasi login user (`login.php`, `proses_login.php`, `logout.php`).
* **`/dashboard/index.php`**: Landing page dashboard utama.
* **`/dashboard/bahan/`**: Modul master data inventaris bahan baku (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/menu/`**: Modul master data produk variasi nasi bakar (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/supplier/`**: Modul master data pemasok barang (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/masuk/`**: Modul transaksi pencatatan penambahan stok gudang (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/keluar/`**: Modul transaksi pencatatan pengurangan stok pemakaian dapur (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/permintaan/`**: Modul alur permintaan restok dapur (`index.php`, `form.php`, `simpan.php`, `status.php`).
* **`/dashboard/kasir/`**: Modul input pemesanan makanan kasir (`index.php`, `form.php`, `simpan.php`, `hapus.php`).
* **`/dashboard/dapur/`**: Modul monitoring antrian monitor dapur (`index.php`, `status.php`).
* **`/dashboard/laporan/`**: Modul monitoring laporan admin (`index.php`).

---

### 5.4 Matriks Kerunutan

| SRS-Id | Fungsi Terkait | File Modul |
|---|---|---|
| **FR-001** | Kelola Bahan | `/dashboard/bahan/simpan.php` |
| **FR-002** | Monitoring Stok | `/dashboard/index.php` |
| **FR-003** | Notifikasi Stok | `/dashboard/laporan/index.php` |
| **FR-004** | Input Pesanan | `/dashboard/kasir/simpan.php` |
| **FR-005** | Antrian Dapur | `/dashboard/dapur/index.php` |
| **FR-006** | Update Produksi | `/dashboard/dapur/status.php` |
| **FR-007** | Transaksi Stok | `/dashboard/masuk/simpan.php`, `/dashboard/keluar/simpan.php` |
| **FR-008** | Permintaan Restok| `/dashboard/permintaan/simpan.php`, `status.php` |
| **FR-009** | Laporan Stok | `/dashboard/laporan/index.php` |
| **FR-010** | Laporan Transaksi | `/dashboard/laporan/index.php` |
| **FR-011** | Laporan Restok | `/dashboard/laporan/index.php` |
| **NFR-003**| Autentikasi User | `/auth/proses_login.php` |

---

## 6. Pengujian Perangkat Lunak

### 6.1 Lingkungan Pengujian

#### 6.1.1 Perangkat Lunak Pengujian
* **OS Server**: Windows 11 (XAMPP v3.3.0)
* **Web Browser**: Google Chrome 124.0 (Device Emulation iPhone 12 Pro)
* **DBMS Client**: phpMyAdmin v5.2.0

#### 6.1.2 Perangkat Keras Pengujian
* Laptop Server: Intel Core i5, RAM 8GB, SSD 256GB.
* Layar Dapur: Tablet Android layar sentuh 10 inci.

### 6.2 Material Pengujian
* Data master bahan baku uji: Beras Pulen, Ayam Suwir, Cumi Asin, Daun Pisang.
* Akun peran uji: Kasir (`kasir@nasibakar.test`), Dapur (`dapur@nasibakar.test`), Gudang (`gudang@nasibakar.test`).

### 6.3 Sumber Daya Manusia
* Penguji internal: Tim Pengembang (Al Fanisa, Feby, Ratu).
* Dosen Reviewer: Dini Hamidin, S.Si., MBA., MT.

### 6.4 Prosedur Umum Pengujian

#### 6.4.1 Pengenalan dan Latihan
Tim melakukan demo sistem mandiri selama 1 jam untuk mengecek kesesuaian navigasi link antar halaman.

#### 6.4.2 Persiapan Awal

##### 6.4.2.1 Persiapan Prosedural
Memastikan database MySQL dalam keadaan menyala dan koneksi di `koneksi.php` terhubung dengan benar.

##### 6.4.2.2 Persiapan Perangkat Keras
Menghubungkan tablet dapur dan PC kasir ke jaringan Wi-Fi lokal server XAMPP.

##### 6.4.2.3 Persiapan Perangkat Lunak
Mengimpor data seed awal `database.sql` untuk mendapatkan master data resep nasi bakar dan akun user.

#### 6.4.3 Pelaksanaan
Menguji alur pemesanan kasir hingga masuk ke monitor dapur (FIFO), pemrosesan status produksi pesanan, rekap stok keluar dapur, dan restok masuk gudang.

#### 6.4.4 Pelaporan Hasil
Hasil pengujian divalidasi dan dicatat untuk memastikan seluruh fungsionalitas berjalan normal.

### 6.5 Identifikasi dan Rencana Pengujian

| Kelas Uji | Butir Uji | ID DPPL | Jenis |
|---|---|---|---|
| **Autentikasi** | Login valid user & proteksi halaman | AU_01 | Black Box |
| **Kelola Bahan** | Tambah data bahan & warning stok minimum | KB_01 | Black Box |
| **Input Pesanan** | Kasir membuat pesanan & detail pesanan | IP_01 | Black Box |
| **Dapur/Antrian** | Antrian real-time monitor dapur & ubah status | PR_01 | Black Box |
| **Restok/Stok** | Permintaan restok & tambah/kurang kuantitas | RS_01 | Black Box |
| **Laporan** | Filter periode laporan transaksi & rekap stok | LP_01 | Black Box |

### 6.6 Deskripsi dan Hasil Uji

#### 6.6.1 Kelas Uji: Kelola Bahan Baku (KB)

##### 6.6.1.1 KB_03 - Notifikasi Stok Minimum
* **Masukan**: Stok Bumbu Pedas di-update menjadi 1 (stok minimum = 2).
* **Keluaran yang Diharapkan**: Dashboard & halaman laporan memicu warning alert merah penanda stok bahan menipis.
* **Hasil**: SESUAI (Diterima).

#### 6.6.2 Kelas Uji: Input Pesanan (IP)

##### 6.6.2.1 IP_01 - Input Pesanan Lengkap
* **Masukan**: Memilih menu "Nasi Bakar Cumi" x2, pelanggan "Rina", bayar "qris".
* **Keluaran yang Diharapkan**: Pesanan berhasil tersimpan di database `pesanan` dan rinciannya di `pesanan_detail`.
* **Hasil**: SESUAI (Diterima).

##### 6.6.2.1 IP_02 - Validasi Pesanan Tidak Lengkap
* **Masukan**: Form pesanan kasir dikirim tanpa mengisi nama pelanggan.
* **Keluaran yang Diharapkan**: Validasi error tampil di layar, transaksi dibatalkan oleh sistem.
* **Hasil**: SESUAI (Diterima).

#### 6.6.3 Kelas Uji: Dapur & Stok (PR)

##### 6.6.3.1 PR_03 - Pengurangan Stok Bahan Baku
* **Masukan**: Dapur mencatat pengeluaran bahan "Ayam Suwir" sebanyak 2 kg di modul Stok Keluar.
* **Keluaran yang Diharapkan**: Nilai stok bahan "Ayam Suwir" di database terpotong otomatis dari 18 kg menjadi 16 kg.
* **Hasil**: SESUAI (Diterima).

### 6.7 Keterunutan Pengujian
Seluruh modul fungsional (autentikasi, pemesanan, dapur, restok, rekap keluar masuk barang, laporan) telah diuji secara manual dan memenuhi kriteria acceptance test.

---

## 7. Spesifikasi Produk Perangkat Lunak

### 7.1 Perangkat Lunak Siap Eksekusi
* Folder web project PHP `/nasi-bakar-rpl` yang siap di-deploy ke web server Apache lokal.

### 7.2 Berkas Sumber
* Seluruh berkas source code PHP native, file gambar menu, stylesheet `dashboard.css`, script dump database `database.sql`.

### 7.3 Syarat Pemaketan
* Source code dikompresi dalam arsip ZIP untuk instalasi mandiri.

### 7.4 Prosedur Konstruksi
Jalankan XAMPP, tempatkan berkas di htdocs, impor `database.sql` lewat phpMyAdmin, lalu buka url localhost di browser.

---

## 8. Panduan Instalasi

### 8.1 Instalasi Program Siap Eksekusi
1. Pasang aplikasi XAMPP (Apache & MySQL) di server komputer lokal Anda.
2. Buat database baru bernama `inventaris_nasi_bakar` melalui phpMyAdmin.
3. Impor berkas `database.sql` ke dalam database baru tersebut.
4. Letakkan folder project web `/nasi-bakar-rpl` di dalam direktori `C:\xampp\htdocs\`.
5. Sesuaikan detail login database di `/config/koneksi.php`.

### 8.2 Instalasi Kode Program Sumber
Gunakan Git client untuk melakukan clone repositori program ke htdocs lokal Anda:
`git clone <url_repositori> /xampp/htdocs/nasi-bakar-rpl`

---

## 9. Penutup
Dokumen DPPL ini menyajikan deskripsi perancangan teknis riil yang diterapkan langsung pada Sistem Informasi Dapur dan Restock Warung Nasi Bakar. Penyesuaian dokumen ini dengan codebase PHP dan struktur data MySQL harian menjamin integritas rekayasa perangkat lunak tetap terpelihara dengan baik sepanjang siklus operasional sistem.

Bandung, Juli 2026

**Tim Pengembang**
Al Fanisa Basri | Feby Mutiara | Ratu Sinatria Ragib
Program Studi D4 Teknik Informatika
Universitas Logistik dan Bisnis Internasional
