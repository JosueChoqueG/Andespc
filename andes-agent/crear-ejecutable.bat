@echo off
title Generador de Ejecutable (.EXE) - Andes-Agent
echo ==========================================================
echo       GENERADOR DE EJECUTABLE DISFRAZADO (.EXE)
echo ==========================================================
echo.
echo Seleccione el icono para camuflar el agente:
echo   [1] Icono de Computadora / Red (icono-pc.ico) -> Recomendado como 'agente-alpaca.exe'
echo   [2] Icono de Actualizacion de Windows (icono-update.ico) -> Como 'ActualizacionTI.exe'
echo   [3] Icono de Soporte y Ayuda (icono-soporte.ico) -> Como 'SoporteTecnico.exe'
echo.
set /p OPCION="Elija una opcion (1, 2 o 3) [Default 1]: "
if "%OPCION%"=="" set OPCION=1

set NOMBRE=agente-alpaca.exe
set ICONO=agente-alpaca.ico

if "%OPCION%"=="1" (
    set NOMBRE=agente-alpaca.exe
    set ICONO=agente-alpaca.ico
)
if "%OPCION%"=="2" (
    set NOMBRE=ActualizacionTI.exe
    set ICONO=icono-update.ico
)
if "%OPCION%"=="3" (
    set NOMBRE=SoporteTecnico.exe
    set ICONO=icono-soporte.ico
)

echo.
echo Compilando %NOMBRE% con %ICONO% en modo silencioso e invisible...
echo.

powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0compilar-exe.ps1" -NombreExe "%NOMBRE%" -Icono "%ICONO%" -Modo "Silencioso"

echo.
pause
