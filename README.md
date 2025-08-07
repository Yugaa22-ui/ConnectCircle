# ConnectCircle

ConnectCircle adalah platform media sosial yang dirancang untuk memfasilitasi interaksi dan koneksi antar pengguna berdasarkan minat dan komunitas (disebut "Circles"). Proyek ini memungkinkan pengguna untuk membuat profil, bergabung dengan lingkaran minat, berinteraksi melalui postingan, dan membangun jaringan pertemanan.

## Fitur Utama

### Manajemen Pengguna
* Registrasi dan otentikasi pengguna.
* Profil pengguna dengan informasi seperti nama pengguna, email, kota, profesi, dan bio.
* Unggah gambar profil.
* Peran pengguna (misalnya, `user`, `admin`).

### Circles (Komunitas)
* Buat dan kelola Circle berdasarkan berbagai minat.
* Pengaturan privasi untuk Circle (privat/publik).
* Aturan Circle yang dapat disesuaikan.
* Anggota Circle dengan peran `member` atau `moderator`.
* Permintaan bergabung ke Circle (untuk Circle privat).
* Fitur `mute` untuk anggota Circle.

### Interaksi Sosial
* Buat dan kelola pesan di dalam Circle.
* Posting dapat mencakup teks, gambar, video, audio, dan voice note.
* Menampilkan jumlah pengguna yang melihat pesan.
* Sistem pertemanan dengan permintaan pertemanan.

### Minat Pengguna
* Pengguna dapat memilih dan mengaitkan minat mereka.
* Circle juga terkait dengan minat tertentu.

### Lencana (Badges)
* Sistem lencana untuk memberi penghargaan kepada pengguna.
* Lencana dapat diberikan kepada pengguna berdasarkan pencapaian atau aktivitas tertentu.

## Struktur Database

Basis data `connectcircle_db` dirancang dengan tabel-tabel berikut untuk mendukung fungsionalitas di atas:

* `users`: Menyimpan informasi detail tentang setiap pengguna.
* `interests`: Daftar kategori minat yang tersedia.
* `user_interests`: Menghubungkan pengguna dengan minat mereka.
* `circles`: Detail tentang setiap komunitas atau "Circle" yang dibuat.
* `circle_members`: Mengelola keanggotaan pengguna dalam Circle.
* `circle_requests`: Menyimpan permintaan bergabung ke Circle privat.
* `circle_mutes`: Mencatat pengguna yang dimute dalam Circle.
* `posts`: Menyimpan semua postingan yang dibuat di dalam Circle.
* `post_views`: Melacak siapa saja yang melihat postingan tertentu.
* `friends`: Mencatat daftar pertemanan antar pengguna.
* `friend_requests`: Mengelola permintaan pertemanan yang tertunda.
* `badges`: Daftar lencana yang tersedia di platform.
* `user_badges`: Menghubungkan lencana yang diberikan kepada pengguna.

## Teknologi yang Digunakan

* **Frontend:** HTML, CSS, Bootstrap 5, JavaScript
* **Backend:** PHP
* **Database:** MySQL / MariaDB
* **Server Web:** Apache

## Instalasi dan Setup (Lokal)

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

### 1. Clone Repositori

```bash
git clone [https://github.com/Yugaa22-ui/ConnectCircle.git](https://github.com/Yugaa22-ui/ConnectCircle.git)
cd ConnectCircle
````

### 2\. Setup Database

  * Buat database baru di MySQL/MariaDB Anda (misalnya, `connectcircle_db`).

  * Impor skema database dari file `connectcircle_db (2).sql` yang telah Anda sediakan:

    ```bash
    mysql -u your_username -p connectcircle_db < connectcircle_db (2).sql
    ```

    *(Ganti `your_username` dengan nama pengguna database Anda dan masukkan kata sandi saat diminta.)*

### 3\. Konfigurasi Backend

  * Instal dependensi (misalnya, `composer install` untuk PHP, `npm install` untuk Node.js).
  * Konfigurasi file koneksi database Anda (misalnya, `config.php`, `.env`). Pastikan kredensial database sesuai.

### 4\. Jalankan Server

  * **Untuk PHP dengan XAMPP/Apache:** Pastikan server Apache dan MySQL aktif, lalu akses proyek melalui browser Anda (misalnya, `http://localhost/ConnectCircle`).
  * **Untuk Node.js:** `npm start` atau `node app.js`
  * **Untuk Python:** `python manage.py runserver`
