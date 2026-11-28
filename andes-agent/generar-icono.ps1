Add-Type -TypeDefinition @"
using System;
using System.Drawing;
using System.Runtime.InteropServices;

public class WinIconExtractor {
    [DllImport("shell32.dll", CharSet = CharSet.Auto)]
    public static extern uint ExtractIconEx(string szFileName, int nIconIndex, IntPtr[] phiconLarge, IntPtr[] phiconSmall, uint nIcons);

    public static Icon Extract(string file, int index) {
        IntPtr[] large = new IntPtr[1];
        IntPtr[] small = new IntPtr[1];
        ExtractIconEx(file, index, large, small, 1);
        if (large[0] != IntPtr.Zero) {
            return Icon.FromHandle(large[0]);
        }
        return null;
    }
}
"@ -ReferencedAssemblies System.Drawing

$outputDir = $PSScriptRoot

# Extraer icono 15 de shell32.dll (Icono oficial de Computadora / Red de Windows)
$icon = [WinIconExtractor]::Extract("C:\Windows\System32\shell32.dll", 15)
if ($icon) {
    $path = Join-Path $outputDir "agente-alpaca.ico"
    $fs = New-Object System.IO.FileStream($path, [System.IO.FileMode]::Create)
    $icon.Save($fs)
    $fs.Close()
    Write-Host "Icono de Computadora guardado en: $path" -ForegroundColor Green
}

# Extraer icono 47 de shell32.dll (Icono de Actualizacion / Escudo / Herramientas de Windows)
$icon2 = [WinIconExtractor]::Extract("C:\Windows\System32\shell32.dll", 47)
if ($icon2) {
    $path2 = Join-Path $outputDir "icono-update.ico"
    $fs2 = New-Object System.IO.FileStream($path2, [System.IO.FileMode]::Create)
    $icon2.Save($fs2)
    $fs2.Close()
    Write-Host "Icono de Actualizacion guardado en: $path2" -ForegroundColor Green
}

# Extraer icono 238 de shell32.dll (Icono de Soporte / Ayuda de Windows)
$icon3 = [WinIconExtractor]::Extract("C:\Windows\System32\shell32.dll", 238)
if ($icon3) {
    $path3 = Join-Path $outputDir "icono-soporte.ico"
    $fs3 = New-Object System.IO.FileStream($path3, [System.IO.FileMode]::Create)
    $icon3.Save($fs3)
    $fs3.Close()
    Write-Host "Icono de Soporte guardado en: $path3" -ForegroundColor Green
}
