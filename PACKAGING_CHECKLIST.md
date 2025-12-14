# 📦 CHECKLIST SEBELUM MENGIRIM PROJECT

## ✅ File yang WAJIB DIHAPUS (Keamanan & Ukuran)

### 🚫 Folder yang HARUS DIHAPUS:
- [ ] `node_modules/` (jika ada) - sangat besar, bisa di-install ulang
- [ ] `vendor/` - besar, akan di-generate oleh `composer install`
- [ ] `storage/logs/*.log` - file log tidak perlu dikirim
- [ ] `.git/` (jika ada) - tidak perlu version control history

### 🚫 File Konfigurasi Sensitif:
- [ ] `.env` - JANGAN kirim file ini! Berisi konfigurasi sensitif
- [ ] `storage/framework/sessions/*` - hapus semua session
- [ ] `storage/framework/cache/*` - hapus semua cache
- [ ] `bootstrap/cache/*.php` - hapus compiled files

### 🚫 File Development:
- [ ] `.phpunit.result.cache`
- [ ] `tests/` (opsional - jika klien tidak perlu unit test)

---

## ✅ File yang WAJIB DISERTAKAN

### 📄 Dokumentasi:
- [x] `README.md` - informasi umum
- [x] `INSTALLATION_GUIDE.md` - panduan instalasi
- [x] `install.bat` - script instalasi otomatis
- [x] `.env.example` - template konfigurasi

### 💾 Database:
- [ ] `futsal_id.sql` - Export database dari phpMyAdmin

### 🔑 File Penting:
- [x] `composer.json` - untuk install dependencies
- [x] `artisan` - CLI Laravel
- [x] Semua folder `app/`, `config/`, `database/`, `public/`, `resources/`, `routes/`

---

## 🛠️ CARA EXPORT DATABASE

1. Buka phpMyAdmin
2. Pilih database `futsal_id`
3. Klik tab **"Export"**
4. Pilih method **"Quick"** atau **"Custom"**
5. Format: **SQL**
6. Checklist opsi:
   - ✅ Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER
   - ✅ Add CREATE TABLE
   - ✅ Add CREATE DATABASE
7. Klik **"Go"**
8. Save file sebagai `futsal_id.sql` di root project

---

## 📦 CARA MEMBUAT ZIP

### Windows (Manual):
1. Klik kanan folder project
2. Pilih **"Send to" → "Compressed (zipped) folder"**
3. Rename menjadi `Futsal-ID-Project.zip`

### Windows (PowerShell):
```powershell
# Jalankan di folder PARENT dari project
Compress-Archive -Path "Reservasi-Futsal" -DestinationPath "Futsal-ID-Project.zip"
```

### Menggunakan 7-Zip (Recommended):
1. Klik kanan folder project
2. Pilih **7-Zip → Add to archive...**
3. Archive format: **ZIP**
4. Compression level: **Normal** atau **Maximum**
5. Nama: `Futsal-ID-Project.zip`

---

## 📋 STRUKTUR AKHIR ZIP

```
Futsal-ID-Project.zip
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
│   ├── app/
│   ├── framework/ (kosong)
│   └── logs/ (kosong)
├── .env.example
├── artisan
├── composer.json
├── composer.lock
├── futsal_id.sql ← FILE DATABASE
├── INSTALLATION_GUIDE.md
├── install.bat
├── package.json
├── phpunit.xml
├── README.md
└── vite.config.js
```

---

## 📧 INFORMASI UNTUK KLIEN

Sertakan informasi ini dalam email/pesan:

```
Halo,

Terlampir project Futsal ID dalam bentuk ZIP.

CARA INSTALASI:
1. Extract file ZIP ke folder web server (contoh: C:\laragon\www\)
2. Buka terminal/command prompt di folder project
3. Jalankan file install.bat (double-click) atau manual:
   - composer install
   - copy .env.example ke .env
   - php artisan key:generate
   - php artisan storage:link
4. Import database futsal_id.sql ke MySQL
5. Edit file .env, sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD
6. Jalankan: php artisan serve
7. Akses: http://127.0.0.1:8000

AKUN LOGIN:
- Admin: admin@futsalid.com / password
- Member: diki@example.com / password123

Panduan lengkap ada di file INSTALLATION_GUIDE.md

Terima kasih!
```

---

## ⚠️ PENTING!

### Sebelum Zip:
```powershell
# Pastikan tidak ada file sensitif
Get-ChildItem -Recurse -Include *.env,*.log | Remove-Item

# Hapus folder besar
Remove-Item -Recurse -Force vendor, node_modules, .git
```

### Ukuran File:
- **Tanpa vendor/node_modules**: ~5-10 MB
- **Dengan vendor**: ~50-80 MB
- **Dengan node_modules**: +100 MB

**REKOMENDASI**: Kirim TANPA folder `vendor/` dan `node_modules/`, biarkan klien install sendiri dengan `composer install`.

---

## ✔️ FINAL CHECK

Sebelum kirim, pastikan:
- [ ] File `.env` sudah dihapus
- [ ] Folder `vendor/` sudah dihapus
- [ ] File `futsal_id.sql` sudah ada
- [ ] File `install.bat` berfungsi
- [ ] `README.md` dan `INSTALLATION_GUIDE.md` sudah ada
- [ ] Tidak ada file log atau cache
- [ ] Test extract dan install di komputer lain (optional)

---

**Semua checklist sudah? Siap kirim! 🚀**
