# Sistem Pengelolaan Limbah & Product Reject (DTY)

Aplikasi berbasis web yang dikembangkan menggunakan **Laravel 11** untuk mendigitalisasi pencatatan data produksi, manajemen limbah (*waste*), dan produk cacat (*product reject*) pada industri tekstil Draw Textured Yarn (DTY). Sistem ini dilengkapi dengan kontrol akses berbasis peran (*Role-Based Access Control* / RBAC), kalkulator interaktif *real-time*, analitik per mesin (*lane*), dan fitur ekspor laporan PDF eksekutif.

## 🛠️ Tech Stack
* **Framework Backend:** Laravel 11 (PHP 8.2+)
* **Database:** MySQL / MariaDB
* **Frontend UI:** Tailwind CSS, Blade Templates, FontAwesome
* **Visualisasi & Dokumen:** Chart.js, DomPDF

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut di komputer lokal Anda untuk menjalankan proyek ini:

1. **Clone / Download Repository**
   ```bash
   git clone [https://github.com/username/nama-repository.git](https://github.com/username/nama-repository.git)
   cd nama-repository
Install Dependensi PHP

Bash
composer install
Konfigurasi Environment (.env)

Salin file .env.example menjadi .env:

Bash
cp .env.example .env
Generate key aplikasi:

Bash
php artisan key:generate
Sesuaikan konfigurasi database di dalam file .env:

Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_lokal_anda
DB_USERNAME=root
DB_PASSWORD=
Import Database (.sql)

Buat database baru di MySQL / Navicat sesuai dengan nama di file .env.

Import file cadangan database yang tersedia di dalam folder project (terletak di database/db_waste_reject_dty.sql) menggunakan Navicat (Execute Batch File) atau phpMyAdmin (Tab Import).

Jalankan Server Lokal

Bash
php artisan serve
Buka peramban web (browser) dan akses tautan: http://127.0.0.1:8000

🔑 Kredensial Akun Pengguna (Demo Login)
Anda dapat menggunakan akun uji coba berikut untuk masuk ke dalam aplikasi sesuai dengan peran (role) masing-masing:

Peran Aktor	Email Akses	Kata Sandi (Password)	Keterangan Akses
Operator Produksi	operator@dty.com	password	Menginput laporan shift, rincian per lane, & melihat riwayat.
Admin Produksi	admin@dty.com	password	Mengelola Master Data & memverifikasi/merevisi laporan.
Manajer Produksi	manajer@dty.com	password	Akses dashboard analitik, grafik komparasi, & ekspor PDF.
(Catatan: Anda juga dapat menggunakan tombol Quick Access Demo di halaman login untuk masuk secara instan).

📁 Struktur Penyimpanan File Penting di Repository
/app & /resources : Kode sumber logika dan antarmuka web.

/database/db_waste_reject_dty.sql : Berkas struktur dan data awal pangkalan data (database).


---

### Cara Memasukkannya ke GitHub:
1. Di halaman utama *repository* GitHub Anda, klik tombol **Add file** $\rightarrow$ **Create new file**.
2. Beri nama file tersebut: **`README.md`** (huruf kapital semua untuk ekstensi md).
3. Tempel (*paste*) seluruh teks draf di atas ke dalam kotak editor.
4. Klik tombol hijau **Commit changes...** di bagian bawah. Selesai! File catatan panduan Anda sudah resmi terpampang di halaman utama repository GitHub Anda.