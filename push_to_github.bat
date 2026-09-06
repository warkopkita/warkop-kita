@echo off
setlocal
echo ===================================================
echo   PUSH WARKOP KITA KE GITHUB (OTOMATIS)
echo ===================================================
echo.

set /p REPO_URL="Masukkan URL Repository GitHub Anda (contoh: https://github.com/username/warkop-kita.git): "

if "%REPO_URL%"=="" (
    echo [ERROR] URL tidak boleh kosong!
    pause
    exit /b 1
)

echo.
echo Menghubungkan remote origin ke %REPO_URL%...
"C:\Users\moham\mingit\cmd\git.exe" remote remove origin 2>nul
"C:\Users\moham\mingit\cmd\git.exe" remote add origin %REPO_URL%
"C:\Users\moham\mingit\cmd\git.exe" branch -M main

echo.
echo Mengunggah (push) kode ke GitHub...
"C:\Users\moham\mingit\cmd\git.exe" push -u origin main

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ===================================================
    echo   BERHASIL! KODE SUDAH TER-UPLOAD KE GITHUB!
    echo   Sekarang buka https://railway.app lalu Deploy!
    echo ===================================================
) else (
    echo.
    echo [INFO] Jika diminta login, masukkan Username dan Personal Access Token GitHub Anda.
)

echo.
pause
