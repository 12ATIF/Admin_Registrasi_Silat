# Sistem Manajemen Administrasi Registrasi Pencak Silat (UNPER OPEN)

## Deskripsi Proyek

Aplikasi ini adalah sistem berbasis web yang dirancang untuk membantu panitia dalam mengelola keseluruhan proses administrasi untuk acara kejuaraan pencak silat "UNPER OPEN". Sistem ini mencakup berbagai modul mulai dari manajemen data pelatih, kontingen, peserta, hingga pengaturan pertandingan dan pelaporan.

Sistem ini terdiri dari dua aplikasi Laravel terpisah:
1.  **Aplikasi Admin:** Digunakan oleh panitia untuk mengelola semua aspek acara.
2.  **Aplikasi Pelatih:** (Akan dijelaskan lebih lanjut jika ada) Digunakan oleh pelatih untuk mendaftarkan kontingen dan peserta mereka.

Kedua aplikasi ini terhubung ke satu database pusat untuk memastikan integritas dan konsistensi data.

## Fitur Utama (Aplikasi Admin)

Berdasarkan struktur kode yang terlihat (Controllers dan Models):

* **Dashboard Utama:** Menampilkan statistik ringkas mengenai jumlah peserta, kontingen, status verifikasi, dan status pembayaran.
* **Manajemen Pelatih:**
    * Melihat daftar pelatih.
    * Melihat detail pelatih beserta kontingen yang diasuh.
    * Reset password pelatih.
    * Mengaktifkan/menonaktifkan akun pelatih.
* **Manajemen Kontingen:**
    * Melihat daftar kontingen beserta pelatih dan asal daerah.
    * Melihat detail kontingen, termasuk daftar peserta dan status pembayaran.
    * Mengaktifkan/menonaktifkan kontingen.
* **Manajemen Peserta:**
    * Melihat daftar lengkap peserta.
    * Memfilter peserta berdasarkan kategori, kelompok usia, dan status verifikasi.
    * Melakukan verifikasi data peserta (valid/tidak valid).
    * Override (mengubah) kelas tanding peserta jika diperlukan.
* **Manajemen Dokumen Peserta:**
    * Melihat daftar dokumen yang diunggah peserta (KTP, Akta, Foto, dll.).
    * Memfilter dokumen berdasarkan jenis dan status verifikasi.
    * Melakukan verifikasi dokumen.
    * Melihat preview dan mengunduh dokumen.
* **Manajemen Pembayaran:**
    * Melihat daftar status pembayaran per kontingen.
    * Memfilter pembayaran berdasarkan status.
    * Melakukan verifikasi pembayaran (menunggu verifikasi, lunas, belum bayar).
    * Melihat bukti transfer.
* **Manajemen Master Data Pertandingan:**
    * **Kategori Lomba:** CRUD untuk kategori utama (misal: Seni, Tanding).
    * **Subkategori Lomba:** CRUD untuk subkategori (misal: Tunggal Putra, Ganda Putri, Tanding Kelas A Putra) beserta relasi ke kelompok usia.
    * **Kelompok Usia:** CRUD untuk kelompok usia peserta (misal: Anak-Anak, Pra Remaja, Remaja, Dewasa, Master).
    * **Kelas Tanding:** CRUD untuk kelas tanding berdasarkan kelompok usia, jenis kelamin, dan rentang berat badan.
* **Manajemen Event Pertandingan:**
    * CRUD untuk data event utama (nama event, tanggal, lokasi).
* **Manajemen Jadwal Pertandingan:**
    * CRUD untuk jadwal detail setiap subkategori dan kelompok usia dalam sebuah event.
    * Visualisasi jadwal dalam bentuk kalender (menggunakan React).
* **Pelaporan:**
    * Laporan data peserta dengan filter dan opsi ekspor ke Excel.
    * Laporan data pembayaran dengan filter dan opsi ekspor ke Excel.
* **Log Aktivitas Admin:** Mencatat aktivitas penting yang dilakukan oleh admin di sistem.
* **Visualisasi Data Peserta:** Grafik interaktif untuk menganalisis distribusi peserta (menggunakan React).

## Teknologi yang Digunakan

* **Backend:** Laravel Framework ^12.0
* **Frontend:**
    * Blade Templates
    * Bootstrap 5
    * jQuery & DataTables
    * React.js (untuk beberapa fitur visualisasi dan interaktif)
    * Vite (untuk asset bundling)
    * Tailwind CSS (konfigurasi ada, mungkin digunakan untuk komponen React atau bagian tertentu)
* **Database:** MySQL (diasumsikan, berdasarkan konfigurasi umum Laravel dan seeder).
* **Versi PHP:** ^8.2

## Prasyarat Sistem

* PHP >= 8.2
* Composer
* Node.js & NPM (untuk build aset frontend)
* Database Server (MySQL direkomendasikan)
* Web Server (Nginx atau Apache dengan konfigurasi yang sesuai untuk Laravel)

## Instalasi Lokal (Untuk Pengembangan)

1.  **Clone repository:**
    ```bash
    git clone [URL_REPOSITORY_ANDA]
    cd Admin_Registrasi_Silat
    ```

2.  **Install dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Buat file `.env`:**
    Salin `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi file `.env`:**
    * Atur `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai dengan konfigurasi database lokal Anda.
    * Pastikan `APP_URL` sesuai dengan URL pengembangan lokal Anda (misal: `http://localhost:8000`).

6.  **Jalankan migrasi database dan seeder:**
    Migrasi akan membuat struktur tabel yang dibutuhkan.
    Seeder akan mengisi data awal yang mungkin diperlukan (seperti akun admin default dan master data).
    ```bash
    php artisan migrate --seed
    ```

7.  **Install dependensi NPM dan build aset frontend:**
    ```bash
    npm install
    npm run dev # Untuk pengembangan, atau npm run build untuk build produksi
    ```

8.  **(Opsional) Buat symbolic link untuk storage:**
    Jika Anda menggunakan penyimpanan lokal untuk file yang diunggah dan perlu diakses publik.
    ```bash
    php artisan storage:link
    ```

9.  **Jalankan server development Laravel:**
    ```bash
    php artisan serve
    ```
    Aplikasi admin biasanya akan dapat diakses di `http://localhost:8000/admin/login`.

## Deployment

Aplikasi ini dirancang untuk dideploy ke Google Cloud Platform (GCP), khususnya menggunakan **Cloud Run** untuk setiap aplikasi (Admin dan Pelatih) yang berjalan pada subdomain berbeda. Database pusat menggunakan **Cloud SQL**.

Proses deployment diotomatisasi menggunakan **GitHub Actions**. Setiap *push* ke *branch* yang ditentukan (misalnya `main` atau `development`) akan memicu *workflow* yang akan:
1.  Membangun *container image* Docker untuk aplikasi.
2.  Mendorong *image* tersebut ke Google Artifact Registry.
3.  Mendeploy revisi baru ke layanan Cloud Run yang sesuai.

File konfigurasi untuk deployment:
* `Dockerfile`: Mendefinisikan bagaimana *image* aplikasi dibangun.
* `docker/nginx.conf`: Konfigurasi Nginx untuk melayani aplikasi di dalam container.
* `docker/supervisor.conf`: Konfigurasi Supervisor untuk mengelola proses Nginx dan PHP-FPM.
* `.github/workflows/deploy-admin-app.yml`: (Contoh nama) Workflow GitHub Actions untuk otomatisasi build dan deploy.

Variabel lingkungan penting (seperti `APP_KEY`, kredensial database) dikelola sebagai *secrets* di GitHub Actions dan diinjeksikan ke Cloud Run saat deployment.

## Kredensial Login Admin Default (dari Seeder)

* **Email:** `admin@pencaksilat.com`
* **Password:** `password`

* **Email (Pertandingan):** `pertandingan@pencaksilat.com`
* **Password (Pertandingan):** `password`

Disarankan untuk segera mengganti password default setelah login pertama kali.

## Kontribusi

(Tambahkan bagian ini jika proyek Anda terbuka untuk kontribusi, jelaskan bagaimana cara berkontribusi, standar coding, dll.)

## Lisensi

Proyek ini menggunakan lisensi MIT. Silakan merujuk ke file `LICENSE.md` (jika ada, atau sebutkan lisensi Laravel default).

---

**Catatan:**
* Anda perlu membuat file `README.md` ini di *root directory* proyek "Admin Registrasi Silat" Anda.
* Ganti `[URL_REPOSITORY_ANDA]` dengan URL repository Git Anda yang sebenarnya.
* Sesuaikan nama file workflow GitHub Actions jika berbeda.
* Tambahkan detail lain yang spesifik untuk proyek Anda, seperti arsitektur aplikasi pelatih, cara kerja API (jika ada), atau fitur khusus lainnya.
