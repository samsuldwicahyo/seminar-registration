# Seminar Registration

Prototipe PHP/MySQL untuk pendaftaran peserta seminar. Source memuat tambah, daftar, edit, dan hapus peserta dengan nama, instansi, dan pilihan seminar.

Export hanya mengambil aplikasi seminar dari folder lama `cv`; artikel pribadi dan foto yang tidak terkait tidak disertakan. Koneksi database dipindahkan ke environment. Input database menggunakan prepared statements, keluaran peserta di-escape, dan penghapusan menggunakan POST dengan token CSRF.

**Batas fungsi:** belum ada login atau otorisasi pengguna. Seluruh antarmuka CRUD masih berada pada satu halaman pengelolaan; jalankan hanya secara lokal untuk peninjauan source. Tidak mengklaim aplikasi pendaftaran publik siap produksi.

## Status source dan kebutuhan setup

Arsip source prototipe portfolio. **Schema/migration database asli tidak tersedia** dalam folder sumber, sehingga clone ini belum dapat dijalankan penuh tanpa schema yang sesuai. Tidak menyertakan dump database, akun, data pelanggan, maupun schema hasil tebakan. Jangan menganggap pemeriksaan sintaks sebagai bukti alur database sudah berfungsi.

PHP Native tidak memuat `.env` otomatis. `.env.example` adalah referensi nama variabel. Setelah memperoleh schema database yang benar, siapkan PHP 8.2+ dan MySQL/MariaDB, lalu atur environment pada terminal sebelum `php -S 127.0.0.1:8000`. Gunakan DB_HOST, DB_PORT, DB_NAME, DB_USER, dan DB_PASSWORD dari konfigurasi lokal sendiri. Tidak ada kredensial yang dibundel.

Pemeriksaan yang dapat dilakukan tanpa database: `php -l nama-file.php`. Pengujian login/CRUD end-to-end belum dilakukan karena schema asli belum tersedia. Source ini untuk pembelajaran dan portfolio; jangan langsung mengeksposnya sebagai layanan publik.
