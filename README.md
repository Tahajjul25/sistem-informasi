# Sistem Informasi Jadwal Tanam dan Panen Berdasarkan Pola Curah Hujan (Aceh Utara)

Aplikasi web PHP + MySQL untuk mengelola data tanaman, jadwal tanam, jadwal panen,
curah hujan, dan laporan. Tampilan: Bootstrap 5 + Chart.js.

## Cara menjalankan (XAMPP)

1. Buka XAMPP Control Panel, **Start Apache dan MySQL**.
2. Letakkan folder proyek di `htdocs` (mis. `D:\Xampp2\htdocs\Aplikasi Jadwal Panen\...`).
3. Buka di browser:
   `http://localhost/Aplikasi%20Jadwal%20Panen/sistem-informasi-jadwal-tanam-dan-panen-aceh-utara/public/`
4. Database `jadwal_tanam_panen`, semua tabel, dan data contoh dibuat **otomatis** saat halaman pertama dibuka.

### Alternatif: server bawaan PHP
Klik dua kali `run.bat` (atau jalankan `php -S localhost:8000 -t public`), lalu buka `http://localhost:8000`.
MySQL tetap harus sudah di-Start dari XAMPP.

## Akun contoh
- Username: `admin`
- Password: `admin123`

Anda juga bisa membuat akun baru lewat halaman **Daftar**. Ganti password akun contoh jika aplikasi dipublikasikan.

## Jika muncul pesan "Struktur tabel ... tidak cocok"
Itu berarti di MySQL masih ada database `jadwal_tanam_panen` versi lama. Buka phpMyAdmin,
**Drop** database tersebut, lalu refresh halaman aplikasi. Database akan dibuat ulang otomatis.

## Pengaturan
- Koneksi database: `config/database.php` (default `root`, tanpa password, host `localhost`).
- Mode debug: `config/app.php`.
- Skema SQL manual (bila ingin import lewat phpMyAdmin): `database/schema.sql`.
- Bootstrap, Font Awesome, dan Chart.js dimuat dari CDN, jadi butuh koneksi internet.

## Struktur folder
```
app/Controllers   logika tiap halaman
app/Models        akses database (PDO)
app/Helpers       Auth, Database, Url, Security
app/Views         tampilan
config/           pengaturan aplikasi dan database
database/         schema.sql
public/           titik masuk (index.php) dan aset CSS
routes/web.php    daftar route
_arsip_lama/      file lama yang tidak dipakai lagi (aman dihapus)
```
