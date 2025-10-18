@echo off
title O Recomeço - Iniciando Servidor
chcp 65001 >nul
cls

echo ===========================================
echo      🔹 Iniciando o jogo "O Recomeço" 🔹
echo ===========================================
echo.

setlocal enabledelayedexpansion
set PROJECT_PATH=%~dp0
set DB_NAME=bdrecomeco
set SQL_FILE=%PROJECT_PATH%banco\bdrecomeco.sql
set SITE_URL=http://localhost/O_Recomeco/index.php
set FOUND_XAMPP=

:: === DETECTAR XAMPP ===
for %%D in (C D E F G) do (
    if exist "%%D:\xampp\apache_start.bat" (
        set FOUND_XAMPP=%%D:\xampp
        goto :found
    )
)
if exist "%PROJECT_PATH%xampp\apache_start.bat" (
    set FOUND_XAMPP=%PROJECT_PATH%xampp
    goto :found
)

echo ❌ XAMPP não encontrado!
echo Instale em C:\xampp ou coloque a pasta "xampp" dentro do projeto.
pause
exit /b

:found
set XAMPP_PATH=%FOUND_XAMPP%
echo ✅ XAMPP encontrado em: %XAMPP_PATH%
echo.

:: === INICIAR SERVIÇOS (sem janelas) ===
echo 🚀 Iniciando Apache e MySQL...

:: Inicia Apache totalmente destacado (sem CMD visível)
start "" powershell -WindowStyle Hidden -Command ^
  "Start-Process '%XAMPP_PATH%\apache\bin\httpd.exe' -NoNewWindow:$false -WindowStyle Hidden"

:: Inicia MySQL com config correta e destacado
start "" powershell -WindowStyle Hidden -Command ^
  "Start-Process '%XAMPP_PATH%\mysql\bin\mysqld.exe' -ArgumentList '--defaults-file=%XAMPP_PATH%\mysql\bin\my.ini' -NoNewWindow:$false -WindowStyle Hidden"

:: Aguarda MySQL subir
set /a counter=0
:waitmysql
"%XAMPP_PATH%\mysql\bin\mysql.exe" -u root -e "status" >nul 2>&1
if errorlevel 1 (
    set /a counter+=1
    if !counter! geq 10 (
        echo ❌ MySQL nao respondeu. Verifique se o XAMPP esta ok.
        pause
        exit /b
    )
    timeout /t 1 >nul
    goto :waitmysql
)

:: === IMPORTAR BANCO ===
echo ⚙️ Verificando banco de dados...
"%XAMPP_PATH%\mysql\bin\mysql.exe" -u root -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >nul 2>&1
if exist "%SQL_FILE%" (
    "%XAMPP_PATH%\mysql\bin\mysql.exe" -u root %DB_NAME% < "%SQL_FILE%" >nul 2>&1
    echo ✅ Banco de dados carregado com sucesso!
) else (
    echo ⚠️ Arquivo SQL nao encontrado: %SQL_FILE%
)

:: === ABRIR SITE ===
echo 🌐 Abrindo o jogo no navegador...
start "" "%SITE_URL%"

:: === FECHAR CMD ===
timeout /t 2 >nul
exit
