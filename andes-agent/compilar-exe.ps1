<#
.SYNOPSIS
    Compilador de Andes-Agent a archivo ejecutable (.exe) nativo con icono personalizado.
.DESCRIPTION
    Convierte el script AndesAgent.ps1 en un ejecutable .exe de Windows totalmente
    independiente, incrustando el codigo PowerShell y el icono seleccionado.
.PARAMETER NombreExe
    Nombre del archivo ejecutable de salida (ej. DiagnosticoTI.exe, SoporteTI.exe, AndesAgent.exe).
.PARAMETER Icono
    Ruta al archivo .ico a incrustar (ej. icono-pc.ico, icono-update.ico, icono-soporte.ico).
.PARAMETER Modo
    "Silencioso" (sin ventana de consola, 100% invisible) o "Consola" (con ventana informativa).
#>

[CmdletBinding()]
param (
    [string]$NombreExe = "agente-alpaca.exe",
    [string]$Icono     = "agente-alpaca.ico",
    [ValidateSet("Silencioso", "Consola")]
    [string]$Modo      = "Silencioso",
    [string]$ServerUrl = "http://127.0.0.1:8000",
    [string]$Token     = "andes_secret_agent_token_2026",
    [switch]$Notificar = $false
)

$scriptDir = $PSScriptRoot
$psScriptPath = Join-Path $scriptDir "AndesAgent.ps1"
$icoPath = if ([System.IO.Path]::IsPathRooted($Icono)) { $Icono } else { Join-Path $scriptDir $Icono }
$outputExe = Join-Path $scriptDir $NombreExe

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "       COMPILADOR DE ANDES-AGENT A EJECUTABLE (.EXE)      " -ForegroundColor Yellow
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  - Salida: $outputExe" -ForegroundColor White
Write-Host "  - Icono:  $icoPath" -ForegroundColor White
Write-Host "  - Modo:   $Modo" -ForegroundColor White
Write-Host "  - Server: $ServerUrl" -ForegroundColor White
Write-Host ""

if (-not (Test-Path $psScriptPath)) {
    Write-Error "No se encontro el archivo AndesAgent.ps1 en $scriptDir"
    return
}

if (-not (Test-Path $icoPath)) {
    Write-Warning "No se encontro el icono en $icoPath. Se compilara sin icono personalizado."
    $icoOption = ""
} else {
    $icoOption = "/win32icon:`"$icoPath`""
}

# Leer el script de PowerShell y codificarlo en Base64 para incrustarlo de forma 100% segura
$rawPsScript = [System.IO.File]::ReadAllText($psScriptPath, [System.Text.Encoding]::UTF8)

# Inyectar ServerUrl y Token por defecto dentro del script incrustado
$rawPsScript = $rawPsScript -replace '\[string\]\$ServerUrl\s*=\s*"[^"]*"', "[string]`$ServerUrl = `"$ServerUrl`""
$rawPsScript = $rawPsScript -replace '\[string\]\$Token\s*=\s*"[^"]*"', "[string]`$Token = `"$Token`""

$bytes = [System.Text.Encoding]::Unicode.GetBytes($rawPsScript)
$base64Script = [Convert]::ToBase64String($bytes)

# Localizar el compilador csc.exe de .NET Framework (presente en todos los Windows)
$cscCandidates = @(
    "C:\Windows\Microsoft.NET\Framework64\v4.0.30319\csc.exe",
    "C:\Windows\Microsoft.NET\Framework\v4.0.30319\csc.exe"
)
$cscPath = $cscCandidates | Where-Object { Test-Path $_ } | Select-Object -First 1

if (-not $cscPath) {
    Write-Error "No se encontro el compilador csc.exe de .NET Framework en este equipo."
    return
}

# Generar codigo fuente C# temporal
$targetType = if ($Modo -eq "Silencioso") { "winexe" } else { "exe" }
$windowStyle = if ($Modo -eq "Silencioso") { "ProcessWindowStyle.Hidden" } else { "ProcessWindowStyle.Normal" }
$createNoWindow = if ($Modo -eq "Silencioso") { "true" } else { "false" }

$notificacionSnippet = if ($Notificar) {
    'MessageBox.Show("El analisis y diagnostico del sistema se ha completado correctamente.", "Informacion del Sistema", MessageBoxButtons.OK, MessageBoxIcon.Information);'
} else {
    '// Sin notificacion emergente'
}

$csharpSource = @"
using System;
using System.Diagnostics;
using System.IO;
using System.Text;
using System.Windows.Forms;

namespace AndesAgentApp
{
    static class Program
    {
        [STAThread]
        static void Main(string[] args)
        {
            try
            {
                string scriptBase64 = "$base64Script";

                ProcessStartInfo psi = new ProcessStartInfo();
                psi.FileName = "powershell.exe";
                psi.Arguments = "-NoProfile -ExecutionPolicy Bypass -EncodedCommand " + scriptBase64;
                psi.WindowStyle = $windowStyle;
                psi.CreateNoWindow = $createNoWindow;
                psi.UseShellExecute = false;

                Process proc = Process.Start(psi);
                if (proc != null)
                {
                    proc.WaitForExit();
                }

                $notificacionSnippet
            }
            catch (Exception)
            {
                // Silencio ante excepciones no criticas
            }
        }
    }
}
"@

$tempCsFile = [System.IO.Path]::Combine($scriptDir, "_temp_agent_source.cs")
[System.IO.File]::WriteAllText($tempCsFile, $csharpSource, [System.Text.Encoding]::UTF8)

# Compilar con csc.exe
$compileArgs = @(
    "/target:$targetType",
    "/out:`"$outputExe`"",
    "/optimize+",
    "/nologo",
    "/reference:System.Windows.Forms.dll,System.dll"
)
if ($icoOption) {
    $compileArgs += $icoOption
}
$compileArgs += "`"$tempCsFile`""

$cmdLine = "$cscPath " + ($compileArgs -join " ")
Write-Host "Compilando con .NET Compiler..." -ForegroundColor Gray

$processInfo = New-Object System.Diagnostics.ProcessStartInfo
$processInfo.FileName = $cscPath
$processInfo.Arguments = $compileArgs -join " "
$processInfo.RedirectStandardOutput = $true
$processInfo.RedirectStandardError = $true
$processInfo.UseShellExecute = $false
$processInfo.CreateNoWindow = $true

$proc = [System.Diagnostics.Process]::Start($processInfo)
$stdout = $proc.StandardOutput.ReadToEnd()
$stderr = $proc.StandardError.ReadToEnd()
$proc.WaitForExit()

# Limpiar codigo fuente temporal
if (Test-Path $tempCsFile) {
    Remove-Item $tempCsFile -Force
}

if ($proc.ExitCode -eq 0 -and (Test-Path $outputExe)) {
    Write-Host "==========================================================" -ForegroundColor Green
    Write-Host "[EXITO] Ejecutable compilado exitosamente!" -ForegroundColor Green
    Write-Host "  Ruta: $outputExe" -ForegroundColor White
    $fileSizeKb = [Math]::Round(((Get-Item $outputExe).Length / 1KB), 1)
    Write-Host "  Tamano: $fileSizeKb KB" -ForegroundColor White
    Write-Host "  Icono: $(if ($icoOption) { 'Incrustado' } else { 'Por defecto' })" -ForegroundColor White
    Write-Host "  Modo: $Modo $(if ($Modo -eq 'Silencioso') { '(Completamente invisible al usuario)' } else { '(Visible en consola)' })" -ForegroundColor White
    Write-Host "==========================================================" -ForegroundColor Green
    Write-Host ""
} else {
    Write-Host "[ERROR] Fallo la compilacion del ejecutable:" -ForegroundColor Red
    Write-Host $stdout -ForegroundColor Yellow
    Write-Host $stderr -ForegroundColor Red
}
