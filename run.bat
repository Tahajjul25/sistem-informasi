@echo off
cd /d "%~dp0"

set "PHP_BIN="

if exist "D:\Xampp2\php\php.exe" set "PHP_BIN=D:\Xampp2\php\php.exe"
if exist "C:\xampp\php\php.exe" set "PHP_BIN=C:\xampp\php\php.exe"

if "%PHP_BIN%"=="" (
    where php >nul 2>nul
    if not errorlevel 1 for /f "delims=" %%i in ('where php') do set "PHP_BIN=%%i"
)

if "%PHP_BIN%"=="" (
    echo PHP tidak ditemukan.
    echo Pastikan XAMPP atau PHP sudah terinstall.
    echo Atau tambah path PHP ke system PATH.
    pause
    exit /b 1
)

echo Menjalankan server PHP di http://localhost:8000
start "" http://localhost:8000
"%PHP_BIN%" -S localhost:8000 -t public
