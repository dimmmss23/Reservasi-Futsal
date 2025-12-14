# 🏟️ Futsal ID - Panduan Instalasi

## 📋 Persyaratan Sistem
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Web Server (Apache/Nginx) atau Laravel Artisan
- Node.js & NPM (opsional, untuk compile assets)

## 🚀 Langkah Instalasi

### 1. Extract File Project
Extract file ZIP project ke folder web server Anda (contoh: `C:\xampp\htdocs\Reservasi-Futsal` atau `C:\laragon\www\Reservasi-Futsal`)

### 2. Install Dependencies
Buka terminal/command prompt di folder project, jalankan:
```bash
composer install
```

### 3. Konfigurasi Environment
- Copy file `.env.example` menjadi `.env`
- Generate application key:
```bash
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`, sesuaikan dengan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=futsal_id
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Import Database
- Buka phpMyAdmin
- Buat database baru dengan nama `futsal_id`
- Import file `futsal_id.sql` yang disertakan

### 6. Konfigurasi Storage
Jalankan command untuk membuat symbolic link ke folder storage:
```bash
php artisan storage:link
```

### 7. Set Permission (Linux/Mac)
Jika menggunakan Linux/Mac, set permission folder:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 8. Jalankan Aplikasi

#### Opsi A: Menggunakan Laravel Artisan (Development)
```bash
php artisan serve
```
Akses aplikasi di: `http://127.0.0.1:8000`

#### Opsi B: Menggunakan Web Server (Apache/Nginx)
- Arahkan document root ke folder `public/`
- Pastikan mod_rewrite aktif (Apache)
- Restart web server

### 9. Akun Default

#### Admin:
- **Email**: admin@futsalid.com
- **Password**: password

#### Member (contoh):
- **Email**: diki@example.com
- **Password**: password123

---

## 🔧 Troubleshooting

### Error "No application encryption key"
```bash
php artisan key:generate
```

### Error "Class not found"
```bash
composer dump-autoload
```

### Error "Permission denied" pada storage/logs
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### File upload tidak muncul
```bash
php artisan storage:link
```

### Clear cache jika ada masalah
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📞 Dukungan
Jika mengalami masalah instalasi, hubungi developer.

## 📄 Lisensi
Project ini dibuat untuk keperluan tugas/komersial.
