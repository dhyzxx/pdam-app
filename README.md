# Aplikasi PDAM Pribadi (Personal Water Utility Billing)

Aplikasi web sederhana berbasis Laravel untuk pencatatan tagihan PDAM pribadi. Dibuat untuk penggunaan individual guna melacak pemakaian air, tagihan, dan status pembayaran pelanggan.

## Fitur Utama

* **Manajemen Data Pelanggan:**
    * Tambah, lihat, edit, dan hapus data pelanggan (ID Pelanggan Kustom, Nama).
    * Menampilkan semua pelanggan dalam satu halaman (tanpa paginasi).
    * Pencarian pelanggan berdasarkan ID atau Nama.
* **Pencatatan Pemakaian Air:**
    * Mencatat pemakaian air bulanan per pelanggan (Bulan, Meter Awal, Meter Akhir).
    * **Meter Awal Otomatis:** Form input meter awal otomatis terisi dari meter akhir bulan sebelumnya.
    * **Saran Bulan Otomatis:** Form input bulan otomatis disarankan ke bulan berikutnya setelah pencatatan terakhir.
    * Volume pemakaian dihitung otomatis.
* **Manajemen Tagihan:**
    * Pembuatan tagihan otomatis saat pemakaian air dicatat.
    * Perhitungan tagihan berdasarkan:
        * Volume Pemakaian x Tarif per Volume (default Rp 1.500/m³)
        * Ditambah Pajak Tetap Bulanan (default Rp 2.000).
    * Lihat daftar semua tagihan dengan filter (berdasarkan pelanggan, bulan, status).
* **Manajemen Pembayaran:**
    * Mencatat pembayaran untuk tagihan.
    * Status tagihan otomatis berubah menjadi "Lunas" setelah pembayaran.
* **Riwayat Pembayaran:**
    * Melihat daftar semua transaksi pembayaran yang telah dilakukan.
    * Filter riwayat pembayaran.
* **Dashboard Ringkasan:**
    * Menampilkan statistik kunci: total pelanggan, tagihan belum lunas (jumlah & nominal), pembayaran bulan ini.
    * Daftar tagihan terbaru yang belum lunas dan pembayaran terakhir.
    * Grafik tren pemakaian air (6 bulan terakhir).
* **Ekspor Data:**
    * Ekspor daftar semua pelanggan ke format Excel.
    * Ekspor data tagihan bulanan (termasuk detail pemakaian dan pelanggan) ke format Excel, dengan pilihan bulan dan tahun.
* **Cetak Dokumen (PDF):**
    * Cetak detail tagihan individual ke format PDF.
    * Cetak bukti pembayaran ke format PDF.
* **Antarmuka Pengguna:**
    * Desain responsif menggunakan Bootstrap 5.
    * Sidebar navigasi untuk akses mudah ke berbagai fitur.
    * Favicon untuk identitas aplikasi di tab browser.

## Teknologi yang Digunakan

* **Backend:** PHP, Laravel Framework
* **Frontend:** HTML, CSS, JavaScript, Bootstrap 5
* **Database:** MySQL
* **Pembuatan PDF:** `barryvdh/laravel-dompdf`
* **Ekspor Excel/CSV:** `maatwebsite/excel`
* **Grafik:** Chart.js

## Prasyarat Instalasi Lokal

* PHP (versi yang sesuai dengan proyek Laravel Anda, misal ^8.1 atau lebih baru)
* Composer
* Node.js dan NPM (opsional, jika Anda ingin memodifikasi aset frontend)
* Web Server (misalnya Apache, Nginx) atau bisa menggunakan `php artisan serve`
* Database MySQL
* Ekstensi PHP yang dibutuhkan Laravel dan paket lainnya (misalnya, `pdo_mysql`, `mbstring`, `xml`, `gd`, `zip`, `intl`, dll.)

## Panduan Instalasi Lokal

1.  **Clone Repositori (atau Unduh ZIP dan Ekstrak):**
    ```bash
    git clone [https://github.com/dhyzxx/pdam-app](https://github.com/dhyzxx/pdam-app) pdam-app
    cd pdam-app
    ```

2.  **Instal Dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Salin File Environment:**
    Salin `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

4.  **Generate Kunci Aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database di `.env`:**
    Buka file `.env` dan sesuaikan pengaturan database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pdam_pribadi_db  # Ganti dengan nama database Anda
    DB_USERNAME=root            # Ganti dengan username MySQL Anda
    DB_PASSWORD=                # Ganti dengan password MySQL Anda
    ```
    Pastikan Anda sudah membuat database dengan nama yang sesuai di MySQL Anda.

6.  **Jalankan Migrasi Database:**
    Perintah ini akan membuat semua tabel yang dibutuhkan di database Anda:
    ```bash
    php artisan migrate
    ```

7.  **(Opsional) Seed Database (Jika Ada Seeder):**
    Jika Anda memiliki data awal (seeder) yang ingin dimasukkan:
    ```bash
    php artisan db:seed
    ```

8.  **(Opsional) Instal Dependensi NPM dan Kompilasi Aset:**
    Jika Anda menggunakan Laravel Mix atau Vite untuk aset frontend:
    ```bash
    npm install
    npm run dev # atau npm run build untuk produksi
    ```
    (Jika tidak ada modifikasi aset frontend yang signifikan, langkah ini bisa dilewati untuk penggunaan pribadi sederhana).

9.  **Jalankan Server Pengembangan Laravel:**
    ```bash
    php artisan serve
    ```
    Aplikasi akan tersedia di `http://127.0.0.1:8000` (atau port lain jika 8000 sudah terpakai).

## Struktur Proyek (Poin Penting)

* **Models:** `app/Models/` (Pelanggan, PemakaianAir, Tagihan, Pembayaran)
* **Controllers:** `app/Http/Controllers/` (PelangganController, PemakaianAirController, TagihanController, DashboardController)
* **Views:** `resources/views/` (dengan subfolder untuk pelanggan, pemakaian_air, tagihan, dashboard, pdf, layouts)
* **Routes:** `routes/web.php`
* **Migrations:** `database/migrations/`
* **Exports:** `app/Exports/` (PelangganExport, TagihanBulanExport)
* **Artisan Commands:** `app/Console/Commands/` (ImportPdamData)

## Kontribusi (Jika Dibuka untuk Umum)

Saat ini proyek ini untuk penggunaan pribadi. Jika Anda ingin berkontribusi, silakan fork repositori ini dan buat pull request.
