# Script untuk membersihkan project sebelum packaging
# Jalankan dengan: powershell -ExecutionPolicy Bypass -File .\cleanup-for-client.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  CLEANUP PROJECT UNTUK KLIEN" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$projectPath = Get-Location

Write-Host "[1/8] Menghapus folder vendor..." -ForegroundColor Green
if (Test-Path "vendor") {
    Remove-Item -Recurse -Force "vendor"
    Write-Host "  [OK] Folder vendor dihapus" -ForegroundColor Gray
} else {
    Write-Host "  [-] Folder vendor tidak ada" -ForegroundColor Gray
}

Write-Host "[2/8] Menghapus folder node_modules..." -ForegroundColor Green
if (Test-Path "node_modules") {
    Remove-Item -Recurse -Force "node_modules"
    Write-Host "  [OK] Folder node_modules dihapus" -ForegroundColor Gray
} else {
    Write-Host "  [-] Folder node_modules tidak ada" -ForegroundColor Gray
}

Write-Host "[3/8] Menghapus folder .git..." -ForegroundColor Green
if (Test-Path ".git") {
    Remove-Item -Recurse -Force ".git"
    Write-Host "  [OK] Folder .git dihapus" -ForegroundColor Gray
} else {
    Write-Host "  [-] Folder .git tidak ada" -ForegroundColor Gray
}

Write-Host "[4/8] Menghapus file .env (PENTING!)..." -ForegroundColor Green
if (Test-Path ".env") {
    Remove-Item -Force ".env"
    Write-Host "  [OK] File .env dihapus (KEAMANAN)" -ForegroundColor Yellow
} else {
    Write-Host "  [-] File .env tidak ada" -ForegroundColor Gray
}

Write-Host "[5/8] Membersihkan storage/logs..." -ForegroundColor Green
if (Test-Path "storage/logs") {
    Get-ChildItem -Path "storage/logs" -Include *.log -Recurse | Remove-Item -Force
    Write-Host "  [OK] File log dihapus" -ForegroundColor Gray
}

Write-Host "[6/8] Membersihkan cache..." -ForegroundColor Green
if (Test-Path "bootstrap/cache") {
    Get-ChildItem -Path "bootstrap/cache" -Include *.php -Recurse | Remove-Item -Force -ErrorAction SilentlyContinue
}
if (Test-Path "storage/framework/cache") {
    Get-ChildItem -Path "storage/framework/cache" -Recurse | Remove-Item -Force -Confirm:$false -ErrorAction SilentlyContinue
}
if (Test-Path "storage/framework/sessions") {
    Get-ChildItem -Path "storage/framework/sessions" -Recurse | Remove-Item -Force -Confirm:$false -ErrorAction SilentlyContinue
}
Write-Host "  [OK] Cache dibersihkan" -ForegroundColor Gray

Write-Host "[7/8] Menghapus file development..." -ForegroundColor Green
$devFiles = @(
    ".phpunit.result.cache",
    ".DS_Store",
    "Thumbs.db"
)
foreach ($file in $devFiles) {
    if (Test-Path $file) {
        Remove-Item -Force $file
        Write-Host "  [OK] $file dihapus" -ForegroundColor Gray
    }
}

Write-Host "[8/8] Verifikasi file penting..." -ForegroundColor Green
$requiredFiles = @(
    ".env.example",
    "composer.json",
    "artisan",
    "README.md",
    "INSTALLATION_GUIDE.md",
    "install.bat"
)
$allOk = $true
foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "  [OK] $file" -ForegroundColor Gray
    } else {
        Write-Host "  [X] $file TIDAK DITEMUKAN!" -ForegroundColor Red
        $allOk = $false
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  CLEANUP SELESAI!" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

if (-not $allOk) {
    Write-Host "[!] PERINGATAN: Beberapa file penting tidak ditemukan!" -ForegroundColor Red
    Write-Host ""
}

Write-Host "LANGKAH SELANJUTNYA:" -ForegroundColor Yellow
Write-Host "1. Export database 'futsal_id' dari phpMyAdmin" -ForegroundColor White
Write-Host "2. Simpan file SQL sebagai: futsal_id.sql di root project" -ForegroundColor White
Write-Host "3. Verifikasi tidak ada file sensitif (.env)" -ForegroundColor White
Write-Host "4. Buat ZIP dari folder project ini" -ForegroundColor White
Write-Host ""

# Tanya apakah ingin membuat ZIP otomatis
$response = Read-Host "Apakah ingin membuat ZIP sekarang? (y/n)"
if ($response -eq "y" -or $response -eq "Y") {
    Write-Host ""
    Write-Host "Membuat ZIP file..." -ForegroundColor Green
    
    # Cek apakah file database ada
    if (-not (Test-Path "futsal_id.sql")) {
        Write-Host ""
        Write-Host "[!] PERINGATAN: File futsal_id.sql tidak ditemukan!" -ForegroundColor Red
        Write-Host "    Pastikan Anda sudah export database sebelum membuat ZIP." -ForegroundColor Yellow
        Write-Host ""
        $continueZip = Read-Host "Tetap lanjut membuat ZIP? (y/n)"
        if ($continueZip -ne "y" -and $continueZip -ne "Y") {
            Write-Host "Dibatalkan." -ForegroundColor Yellow
            pause
            exit
        }
    }
    
    $parentPath = Split-Path $projectPath -Parent
    $projectName = Split-Path $projectPath -Leaf
    $zipPath = Join-Path $parentPath "Futsal-ID-Project.zip"
    
    # Hapus ZIP lama jika ada
    if (Test-Path $zipPath) {
        Remove-Item -Force $zipPath
    }
    
    # Buat ZIP
    Compress-Archive -Path $projectPath -DestinationPath $zipPath -CompressionLevel Optimal
    
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host "  ZIP BERHASIL DIBUAT!" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Lokasi file: $zipPath" -ForegroundColor White
    Write-Host "Ukuran: $((Get-Item $zipPath).Length / 1MB) MB" -ForegroundColor Gray
    Write-Host ""
    
    # Buka folder output
    $openFolder = Read-Host "Buka folder output? (y/n)"
    if ($openFolder -eq "y" -or $openFolder -eq "Y") {
        explorer $parentPath
    }
}

Write-Host ""
Write-Host "Selesai!" -ForegroundColor Green
Write-Host ""
pause
