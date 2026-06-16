# 📍 Rangkuman Proyek: Kos Queen Salon Management

## 📖 Deskripsi Singkat
Proyek ini adalah sebuah aplikasi web berbasis sistem informasi manajemen yang bertujuan untuk mengelola operasional pengelolaan kost (Kos Queen Salon). Aplikasi ini dirancang untuk memudahkan pemilik atau pengurus properti dalam mengatur daftar kamar kos, mengelola status penghuni (penyewa), memantau kontrak, melacak pergerakan aset barang, serta memonitor seluruh arus kas operasional baik itu pemasukan maupun pengeluaran.

## 🚀 Teknologi yang Digunakan (Tech Stack)
Aplikasi ini dibangun menggunakan framework PHP modern dengan tumpukan teknologi utama:
- **Framework Utama**: Laravel v10 (Membutuhkan PHP 8.1 ke atas).
- **Admin Panel**: Filament PHP v3 (TALL Stack - Tailwind, Alpine.js, Laravel, Livewire). Digunakan secara ekstensif untuk membangun Dashboard Panel Admin dan semua operasi CRUD.
- **Manajemen PDF**: `barryvdh/laravel-dompdf` (Cetak dokumen tagihan, kwitansi pembayaran, maupun bentuk laporan lainnya).
- **HTTP/API Client**: Guzzle HTTP (Digunakan untuk interaksi HTTP internal atau third-party/API eksternal jika ada request API).
- **Otentikasi & Endpoint API**: Menggunakan otentikasi bawaan Filament dan `laravel/sanctum`.

## 🗂️ Arsitektur Data & Model Utama
Aplikasi ini sudah merangkum proses bisnis penyewaan properti ke dalam struktur ERD/Tabel Database via Eloquent Models berikut:

**1. Manajemen Kamar & Penghuni**
- `Kamar` (`kamar.php`): Mengelola master data aset kamar, fasilitas, dan detail harganya.
- `Penghuni` (`Penghuni.php`): Menyimpan data profil (KTP, kontak, dll) dari para penyewa.
- `KontrakSewa` (`KontrakSewa.php`): Menangani pencatatan transaksi sewa antara **Penghuni** dengan **Kamar**, beserta jangka waktu sewa, nominal pembayaran, dan status aktifnya penyewaan.

**2. Manajemen Keuangan & Transaksi**
- `MetodePembayaran` (`MetodePembayaran.php`): Referensi jenis pembayaran yang difasilitasi (cth: Transfer Bank, Cash, E-Wallet).
- `TransaksiPembayaran` (`TransaksiPembayaran.php`): Mencatat riwayat pembayaran biaya sewa kamar yang dilakukan penghuni untuk kontrak tertentu.
- `PemasukanLain` (`PemasukanLain.php`): Arus kas masuk tidak terikat sewa (misal: denda biaya keterlambatan, biaya parkir kendaran, dll).
- `PengeluaranOperasional` (`PengeluaranOperasional.php`): Beban biaya operasional keseluruhan kost (misal: bayar listrik bangunan utama, biaya internet WiFi kost, perbaikan umum).
- `PengeluaranPenghuni` (`PengeluaranPenghuni.php`): Pencatatan biaya yang keluar untuk spesifik kepentingan satu kamar/penghuni.

**3. Manajemen Aset, Inventaris & Pemasok (Supply Chain)**
- `Barang` (`barang.php`): Daftar master aset/barang properti (kasur, AC, kipas, sapu, dst).
- `Operasionalbarang` (`operasionalbarang.php`): Pelacakan status pergerakan atau perawatan dari barang inventaris tersebut.
- `Supplier` (`supplier.php`): Daftar vendor atau pemasok dari mana properti tersebut membeli perlengkapannya.

## 🛠️ Fitur Integrasi Admin Panel (Filament)
Terdapat sistem panel admin (dashboard) dengan kapabilitas penuh (Create, Read, Update, Delete) yang dikonfigurasikan di `app/Filament/Resources/` untuk melayani operasi bisnis:
- **BarangResource & OperasionalbarangResource**: Tata kelola inventaris kos / salon secara real-time.
- **KamarResource**: Penambahan kamar dan cek ketersediaan kamar.
- **PenghuniResource**: Rekap para penyewa kos.
- **KontrakSewaResource**: Mengurus perpanjangan waktu kos atau daftar pengajuan huni.
- **TransaksiPembayaranResource**: Alat administrasi untuk merekap penagihan sewa, cek bukti tagihan, dan cetak invoice.
- **PemasukanLainResource & PengeluaranOperasionalResource & PengeluaranPenghuniResource**: Pembukuan sederhana untuk memonitor margin/profit dan memantau biaya pengeluaran bulanan.

## 📧 Sistem Pendukung Penunjang
- **Akses dan Keamanan (Policies)**: Semua modul didukung folder `app/Policies` (seperti `BarangPolicy.php`, `KontraksewaPolicy.php`), berarti sistem telah dikunci privasinya; akses CRUD dirancang khusus untuk _Role_ tertentu dengan izin spesifik di panel admin.
- **Email/Notifikasi**: Terdapat konfigurasi kelas mailable `Tesmail.php` dan service mail yang mungkin digunakan untuk mengirimkan konfirmasi sewa kos atau slip tagihan.
- **Factory & Seeder**: Fitur pengisian data otomatis bawaan (seperti `BarangFactory`, `PenghuniFactory`) yang siap dipakai untuk _dummy data_ bagi uji coba sistem/simulasi sebelum naik produksi.

---

## 🔄 Riwayat Perbaikan & Pembaruan (Changelog Terkini)

Berikut adalah rekapitulasi penyelesaian tugas dan perbaikan _bug_ yang telah diimplementasikan dalam pengembangan proyek:

### 💳 1. Perbaikan Logika Form Transaksi (`TransaksiPembayaranResource.php`)
- **Form Dinamis (Reaktif)**: Mengubah form pencatatan transaksi menjadi pintar menggunakan `->live()`. 
  - Jika **Pemasukan**: Form hanya menampilkan pilihan *Penghuni* dan *Kontrak Sewa* (Supplier disembunyikan).
  - Jika **Pengeluaran**: Form hanya menampilkan pilihan *Supplier* (Penghuni dan Kontrak disembunyikan).
- **Otomatisasi Kontrak Aktif**: Memodifikasi _dropdown_ Kontrak Sewa agar hanya memunculkan daftar penghuni yang kondisi kontraknya masih `"Aktif"`.

### 🖨️ 2. Penambahan Fitur Cetak Invoice PDF
- **Routing (`routes/web.php`)**: Menambahkan _endpoint_ baru `/transaksi/{id}/invoice` untuk menangani pembuatan file PDF menggunakan pustaka `barryvdh/laravel-dompdf`.
- **Pembuatan Template (`resources/views/pdf/invoice.blade.php`)**: Mendesain _layout_ faktur/kwitansi HTML dengan gaya rapi pemicu cetak tagihan yang mencakup ID Transaksi, Nama Penghuni, Metode Bayar, hingga Total Bayar.
- **Tombol Admin Filament (`TransaksiPembayaranResource.php`)**: Menambahkan fitur/tombol kustom **"Cetak Invoice"** secara eksklusif pada tabel riwayat transaksi (tombol ini hanya muncul pada deret transaksi yang bertipe Pemasukan).

### 🗄️ 3. Perbaikan Database & Migrasi (Error Resolving)
- **Tabel Duplikat/Hilang (`penghuni doesn't exist`)**: Memperbaiki masalah file migration ganda. Telah dilakukan eksekusi `php artisan migrate:fresh --seed` sehingga kerangka awal database terbentuk bersih tanpa _error_.
- **Menyediakan Kredensial Login (_Seeder_)**: Memastikan tabel `users` berhasil masuk. Akun admin otomatis ter-generate dengan akses `admin@gmail.com` dan password `12345678`.
- **Memperbaiki Error Relasi Foreign Key (`Constraint fails id_penghuni`)**: Mengubah konfigurasi pada model `App\Models\Penghuni.php` dari `protected $table = 'penghuni';` menjadi standar Laravel `protected $table = 'penghunis';`, sehingga sinkronisasi relasi berhasil dijalankan dengan tepat.

### 🐛 4. Perbaikan Bug Kesalahan Input Identitas (Field Mismatch)
- **Error "Field Doesn't Have Default Value"**: Memperbaiki ketidaksesuaian penamaan input panel admin dengan database lokal. `TextInput::make('nama')` disesuaikan menjadi `nama_penghuni`, dan `no_hp` menjadi `nomor_telepon` pada file `PenghuniResource.php`. Ditambahkan pula input `nik` yang sifatnya _required_.
- **Error "Missing Parameter ID" di Laman Cetak**: Menghindari pengiriman nilai *null* saat mengeklik tombol Invoice pada Tabel Transaksi dgn mengubah fungsi pemanggilan `id` menjadi *Primary Key* _custom_ milik tabel tersebut (yaitu `$record->id_transaksi`).
