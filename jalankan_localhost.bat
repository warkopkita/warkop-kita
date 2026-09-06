@echo off
title Warkop Kita - All-in-One Localhost
color 0b
cls
echo ======================================================================
echo          WARKOP KITA ECOSYSTEM - SATU LOCALHOST (PORT 8000)
echo ======================================================================
echo.
echo Menjalankan semua layanan (Web, POS, KDS, Admin, Database, API)...
echo.

docker compose up -d

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [INFO] Jika Docker Desktop belum aktif di Windows Anda, 
    echo silakan buka Docker Desktop terlebih dahulu atau jalankan di GitHub Codespaces.
    echo.
    pause
    exit /b 1
)

echo.
echo [1/2] Menyiapkan database dan data awal menu...
docker compose exec app php artisan migrate --seed --force
docker compose exec app php artisan optimize:clear

echo.
echo [2/2] Membuka website di browser...
start http://localhost:8000

echo.
echo ======================================================================
echo                 SEMUA LAYANAN AKTIF DALAM SATU PORT:
echo ======================================================================
echo  * Halaman Utama / Menu   : http://localhost:8000
echo  * Self-Order Pelanggan   : http://localhost:8000/self-order
echo  * POS Kasir              : http://localhost:8000/pos
echo  * Kitchen Display (KDS)  : http://localhost:8000/kds
echo  * Admin Panel / Owner    : http://localhost:8000/admin
echo  * REST API Mobile        : http://localhost:8000/api
echo  * Database (phpMyAdmin)  : http://localhost:8080
echo ======================================================================
echo.
echo Tekan tombol apa saja untuk menutup jendela ini (server tetap berjalan di latar belakang).
pause >nul
