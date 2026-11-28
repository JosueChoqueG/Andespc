<#
.SYNOPSIS
    Instala Andes-Agent como una Tarea Programada de Windows (ejecución silenciosa).
.DESCRIPTION
    Configura Windows para que ejecute Andes-Agent silenciosamente al inicio de sesión
    y una vez al día para mantener el inventario siempre actualizado.
#>

[CmdletBinding()]
param (
    [string]$ServerUrl = "http://127.0.0.1:8000",
    [string]$Token     = "andes_secret_agent_token_2026",
    [string]$TaskName  = "AndesPCAgentSync"
)

$scriptDir = $PSScriptRoot
$agentScript = Join-Path $scriptDir "AndesAgent.ps1"

if (-not (Test-Path $agentScript)) {
    Write-Error "No se encontró el script AndesAgent.ps1 en $scriptDir"
    return
}

$action = New-ScheduledTaskAction -Execute "powershell.exe" -Argument "-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File `"$agentScript`" -ServerUrl `"$ServerUrl`" -Token `"$Token`" -Silent"

$triggerAtLogon = New-ScheduledTaskTrigger -AtLogOn
$triggerDaily   = New-ScheduledTaskTrigger -Daily -At 9:00AM

$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable -RunOnlyIfNetworkAvailable

try {
    Register-ScheduledTask -TaskName $TaskName -Action $action -Trigger @($triggerAtLogon, $triggerDaily) -Settings $settings -Description "Sincronizador automático de inventario TI para AndesPC" -User "SYSTEM" -Force
    Write-Host "✓ Tarea programada '$TaskName' instalada exitosamente para ejecutarse como SYSTEM." -ForegroundColor Green
} catch {
    Write-Warning "No se pudo registrar como SYSTEM (requiere permisos de Administrador). Intentando para el usuario actual..."
    Register-ScheduledTask -TaskName $TaskName -Action $action -Trigger @($triggerAtLogon, $triggerDaily) -Settings $settings -Description "Sincronizador automático de inventario TI para AndesPC" -Force
    Write-Host "✓ Tarea programada '$TaskName' instalada exitosamente para el usuario actual." -ForegroundColor Green
}
