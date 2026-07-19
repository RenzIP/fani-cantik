# Deskripsi Aplikasi Inventaris Nasi Bakar

Dokumen ini berfokus pada pemenuhan kebutuhan utama aplikasi website Inventaris Nasi Bakar: halaman Home, Login, minimal tiga modul CRUD, pencarian data, basis data MySQL yang saling berelasi, serta tampilan responsif menggunakan HTML, CSS, dan PHP.

## 1. Halaman Home

Halaman Home merupakan halaman publik yang pertama kali dilihat pengguna saat membuka website. Halaman ini berfungsi untuk memperkenalkan sistem Inventaris Nasi Bakar dan mengarahkan pengguna ke halaman Login.

Komponen utama pada halaman Home:

- Identitas aplikasi dengan logo **NB** dan nama **Nasi Bakar**.
- Navigasi **Home**, **Tentang**, **Produk**, dan **Login**.
- Judul utama: *Inventaris & Pemesanan Nasi Bakar*.
- Penjelasan singkat bahwa aplikasi mengelola kasir, pesanan dapur, stok gudang, supplier, permintaan restok, dan laporan usaha.
- Tombol **Lihat Produk** untuk menuju daftar menu.
- Tombol **Login Sistem** untuk masuk ke halaman autentikasi.
- Daftar menu nasi bakar yang dibaca dari database, seperti Nasi Bakar Ayam Suwir, Cumi, Teri Kemangi, Tuna, dan Jamur.

Halaman ini dibuat sebagai pintu masuk aplikasi. Pengunjung dapat melihat gambaran usaha dan menu tanpa perlu login, sedangkan aktivitas operasional hanya dapat dilakukan setelah autentikasi.

## 2. Halaman Login

Halaman Login digunakan oleh pengguna internal untuk masuk ke dashboard aplikasi. Pengguna mengisi email dan password, kemudian sistem memverifikasi data tersebut dari tabel `users` pada database MySQL.

Fungsi halaman Login:

- Validasi email dan password sebelum data diproses.
- Pengecekan akun menggunakan *prepared statement* MySQLi.
- Verifikasi password dengan `password_verify()`.
- Pembuatan session setelah login berhasil.
- Pengalihan dashboard berdasarkan role pengguna.
- Pilihan **Remember Me** untuk menyimpan email pada cookie.

Role pengguna yang didukung:

| Role | Akses utama |
| --- | --- |
| Admin / Owner | Seluruh modul aplikasi. |
| Kasir | Kasir & Pemesanan, Menu Nasi Bakar, Riwayat Transaksi, dan Laporan. |
| Dapur | Pesanan Dapur, Bahan Baku, Stok Keluar, Permintaan Restok, dan Laporan. |
| Gudang | Bahan Baku, Supplier, Stok Masuk, Permintaan Restok, dan Laporan. |

Dengan pembagian role ini, pengguna hanya melihat fitur yang sesuai dengan tugasnya.

## 3. Modul CRUD

Aplikasi memiliki lebih dari tiga modul CRUD. Tiga modul utama yang dapat digunakan untuk memenuhi kebutuhan Create, Read, Update, dan Delete adalah **Menu Nasi Bakar**, **Bahan Baku**, dan **Supplier**.

### 3.1 CRUD Menu Nasi Bakar

Modul Menu Nasi Bakar digunakan untuk mengelola produk yang dijual.

| Operasi | Implementasi |
| --- | --- |
| **Create** | Menambah menu baru melalui form nama menu, harga, deskripsi, dan gambar. |
| **Read** | Menampilkan seluruh daftar menu dalam tabel serta pada halaman Home. |
| **Update** | Mengubah nama, harga, deskripsi, atau gambar menu melalui halaman edit. |
| **Delete** | Menghapus menu yang sudah tidak dijual melalui tombol Hapus. |

Data menu disimpan pada tabel `menu_nasi_bakar`. Data ini juga dipakai kasir saat membuat pesanan pelanggan.

### 3.2 CRUD Bahan Baku

Modul Bahan Baku digunakan untuk mengelola persediaan yang dibutuhkan dalam produksi nasi bakar.

| Operasi | Implementasi |
| --- | --- |
| **Create** | Menambah bahan dengan nama, stok, stok minimum, satuan, harga, dan jenis bahan. |
| **Read** | Menampilkan daftar bahan baku dan produk jadi, termasuk indikator stok menipis. |
| **Update** | Mengubah data bahan apabila stok minimum, harga, satuan, atau informasi bahan berubah. |
| **Delete** | Menghapus data bahan yang sudah tidak digunakan. |

Data bahan dikelompokkan menjadi dua tab, yaitu **Bahan Baku (Mentah)** dan **Produk Jadi**. Modul ini juga menampilkan harga beli terlama dan harga beli terbaru berdasarkan riwayat stok masuk.

### 3.3 CRUD Supplier

Modul Supplier digunakan oleh gudang untuk menyimpan data pemasok bahan baku.

| Operasi | Implementasi |
| --- | --- |
| **Create** | Menambah nama supplier, nomor telepon, dan alamat. |
| **Read** | Menampilkan daftar seluruh supplier dalam tabel. |
| **Update** | Mengubah data kontak atau alamat supplier. |
| **Delete** | Menghapus supplier yang tidak lagi bekerja sama. |

Data supplier digunakan pada modul Stok Masuk untuk mencatat asal barang atau bahan yang diterima gudang.

### 3.4 CRUD Tambahan: Kasir dan Pesanan

Selain tiga modul di atas, aplikasi juga menyediakan CRUD pesanan pada bagian kasir.

- **Create:** kasir membuat pesanan baru.
- **Read:** pesanan terbaru dan riwayat transaksi ditampilkan dalam tabel.
- **Update:** kasir dapat mengubah pelanggan, menu, jumlah pesanan, metode pembayaran, status pesanan, dan status pembayaran.
- **Delete:** kasir dapat menghapus transaksi melalui tombol Hapus.

## 4. Fitur Pencarian

Fitur pencarian tersedia pada modul **Bahan Baku**.

Pengguna dapat memasukkan kata kunci pada kolom pencarian untuk mencari data berdasarkan:

- Nama bahan.
- Satuan bahan.

Pencarian menggunakan parameter `GET` dan query MySQL dengan kondisi `LIKE`. Hasil pencarian tetap mengikuti tab yang sedang aktif, yaitu bahan mentah atau produk jadi. Modul ini juga menggunakan pagination agar daftar bahan tetap nyaman dibaca ketika jumlah data bertambah banyak.

Contoh alur penggunaan:

1. Pengguna membuka menu Bahan Baku.
2. Pengguna memilih tab **Bahan Baku (Mentah)** atau **Produk Jadi**.
3. Pengguna mengetik kata kunci, misalnya `beras`, `ayam`, atau `kg`.
4. Sistem menampilkan data bahan yang sesuai dengan kata kunci tersebut.

## 5. Database MySQL dan Relasi Tabel

Aplikasi menggunakan MySQL dengan lebih dari lima tabel yang saling berelasi. Tabel utama yang digunakan adalah:

| No. | Tabel | Fungsi |
| --- | --- | --- |
| 1 | `users` | Menyimpan akun pengguna dan role. |
| 2 | `menu_nasi_bakar` | Menyimpan data menu dan harga jual. |
| 3 | `pesanan` | Menyimpan data utama transaksi pelanggan. |
| 4 | `pesanan_detail` | Menyimpan rincian menu dalam setiap pesanan. |
| 5 | `bahan_baku` | Menyimpan data persediaan bahan dan stok minimum. |
| 6 | `supplier` | Menyimpan data pemasok bahan. |
| 7 | `stok_masuk` | Menyimpan riwayat penerimaan bahan dari supplier. |
| 8 | `stok_keluar` | Menyimpan riwayat pemakaian bahan untuk produksi. |
| 9 | `permintaan_restok` | Menyimpan pengajuan kebutuhan restok bahan. |

### 5.1 Relasi Pesanan

```text
users (1) -------- (0..n) pesanan
pesanan (1) ----- (1..n) pesanan_detail
menu_nasi_bakar (1) -- (0..n) pesanan_detail
```

Penjelasan:

- Satu pengguna dapat membuat banyak pesanan.
- Satu pesanan dapat berisi beberapa menu.
- Setiap detail pesanan mengacu ke satu menu nasi bakar.

### 5.2 Relasi Persediaan

```text
supplier (1) ------ (0..n) stok_masuk
bahan_baku (1) --- (0..n) stok_masuk
bahan_baku (1) --- (0..n) stok_keluar
bahan_baku (1) --- (0..n) permintaan_restok
```

Penjelasan:

- Satu supplier dapat memasok banyak transaksi stok masuk.
- Satu bahan dapat memiliki banyak riwayat stok masuk.
- Satu bahan dapat memiliki banyak riwayat stok keluar untuk produksi.
- Satu bahan dapat memiliki banyak permintaan restok.

Relasi ini menggunakan *foreign key* agar data tetap konsisten. Contohnya, data stok masuk tidak dapat merujuk ke bahan atau supplier yang tidak ada.

## 6. HTML, CSS, PHP, dan Responsivitas

### 6.1 HTML

HTML digunakan untuk membangun struktur halaman, seperti:

- Navbar dan sidebar.
- Form Login, form pesanan, form bahan, form supplier, dan form stok.
- Tabel data untuk menu, bahan baku, supplier, transaksi, dan laporan.
- Tombol aksi Tambah, Edit, Hapus, Proses, Selesai, dan Batal.

### 6.2 CSS

CSS digunakan untuk membangun tampilan aplikasi, antara lain:

- Tampilan landing page dan halaman login.
- Dashboard dengan sidebar serta kartu statistik.
- Grid, panel, tabel, form, badge status, dan tombol aksi.
- Warna serta hierarki visual untuk membedakan kondisi normal, peringatan stok, dan status batal.

### 6.3 PHP

PHP digunakan sebagai backend aplikasi untuk:

- Menghubungkan aplikasi dengan database MySQL.
- Menjalankan proses login dan session.
- Mengatur pembatasan akses berdasarkan role.
- Menampilkan data dari database secara dinamis.
- Menyimpan, mengubah, dan menghapus data CRUD.
- Menghitung total pesanan, pendapatan, pengeluaran, dan stok.
- Mengarahkan alur kasir ke dapur melalui data pesanan.

### 6.4 Tampilan Responsif

Tampilan aplikasi dibuat responsif agar dapat digunakan pada desktop, tablet, dan ponsel.

Penerapan responsivitas meliputi:

- Menu navigasi landing page berubah menjadi tombol menu pada layar kecil.
- Kartu statistik dashboard menyesuaikan jumlah kolom berdasarkan lebar layar.
- Form grid berubah menjadi susunan vertikal pada perangkat sempit.
- Tabel dibungkus dalam area *scroll* horizontal agar data tetap dapat dibaca di ponsel.
- Sidebar dashboard dapat disesuaikan untuk penggunaan layar kecil.

## 7. Kesimpulan

Website Inventaris Nasi Bakar telah memenuhi komponen utama aplikasi web operasional, yaitu:

1. Memiliki halaman Home.
2. Memiliki halaman Login dengan role pengguna.
3. Memiliki lebih dari tiga modul CRUD, yaitu Menu Nasi Bakar, Bahan Baku, Supplier, serta Pesanan.
4. Memiliki fitur pencarian pada modul Bahan Baku.
5. Menggunakan database MySQL dengan lebih dari lima tabel yang saling berelasi.
6. Dibangun menggunakan HTML, CSS, PHP, dan memiliki tampilan responsif.

Selain memenuhi kebutuhan dasar tersebut, aplikasi juga memiliki integrasi pesanan dari kasir ke dapur, pencatatan stok masuk dan stok keluar, permintaan restok, serta laporan operasional untuk membantu proses usaha Nasi Bakar berjalan lebih terstruktur.
