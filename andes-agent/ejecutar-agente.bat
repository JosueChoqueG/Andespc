@echo off
title Andes-Agent - Sincronizador de Hardware TI
echo ==========================================================
echo          Iniciando Andes-Agent para AndesPC...
echo ==========================================================
echo.

:: Cambiar URL si el servidor está en otra IP o dominio (ej. http://192.168.1.50:8000)
set SERVER_URL=http://127.0.0.1:8000
set AGENT_TOKEN=andes_secret_agent_token_2026

powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0AndesAgent.ps1" -ServerUrl "%SERVER_URL%" -Token "%AGENT_TOKEN%"

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Ocurrio un problema al ejecutar el agente.
    pause
)
exit /b %errorlevel%
