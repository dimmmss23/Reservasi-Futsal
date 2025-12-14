@echo off
color 0A
title Futsal ID - Instalasi Otomatis
echo ========================================
echo   FUTSAL ID - INSTALASI OTOMATIS
echo ========================================
echo.

echo [1/7] Memeriksa Composer...
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer tidak ditemukan!
    echo Silakan install Composer terlebih dahulu dari https://getcomposer.org
    pause
    exit /b 1
)
echo [OK] Composer ditemukan.
echo.

echo [2/7] Install dependencies...
call composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorlevel% neq 0 (
    echo [ERROR] Gagal install dependencies!
    pause
    exit /b 1
)
echo [OK] Dependencies berhasil diinstall.
echo.

echo [3/7] Setup environment file...
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo [OK] File .env berhasil dibuat dari .env.example
    ) else (
        echo [ERROR] File .env.example tidak ditemukan!
        pause
        exit /b 1
    )
) else (
    echo [INFO] File .env sudah ada, skip...
)
echo.

echo [4/7] Generate application key...
call php artisan key:generate --ansi
if %errorlevel% neq 0 (
    echo [ERROR] Gagal generate key!
    pause
    exit /b 1
)
echo [OK] Application key berhasil di-generate.
echo.

echo [5/7] Create storage link...
call php artisan storage:link
echo [OK] Storage link berhasil dibuat.
echo.

echo [6/7] Clear cache...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear
echo [OK] Cache berhasil dibersihkan.
echo.

echo ========================================
echo   INSTALASI SELESAI!
echo ========================================
echo.
echo LANGKAH SELANJUTNYA:
echo.
echo 1. Edit file .env dan sesuaikan konfigurasi database:
echo    - DB_DATABASE=futsal_id
echo    - DB_USERNAME=root
echo    - DB_PASSWORD=(kosongkan jika tidak ada password)
echo.
echo 2. Buat database 'futsal_id' di phpMyAdmin
echo.
echo 3. Import file 'futsal_id.sql' ke database
echo.
echo 4. Jalankan command: php artisan serve
echo.
echo 5. Akses aplikasi di: http://127.0.0.1:8000
echo.
echo ========================================
echo   AKUN DEFAULT
echo ========================================
echo.
echo ADMIN:
echo Email    : admin@futsalid.com
echo Password : password
echo.
echo MEMBER:
echo Email    : diki@example.com
echo Password : password123
echo.
echo ========================================
pause
