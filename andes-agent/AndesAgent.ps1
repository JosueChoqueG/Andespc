<#
.SYNOPSIS
    Andes-Agent: Agente recolector de hardware y telemetria para AndesPC.
.DESCRIPTION
    Recolecta automaticamente especificaciones de hardware, sistema operativo,
    impresoras locales/red y estado de red, y los sincroniza con el servidor AndesPC.
.EXAMPLE
    .\AndesAgent.ps1 -ServerUrl "http://192.168.1.100:8000" -Token "andes_secret_agent_token_2026"
#>

[CmdletBinding()]
param (
    [string]$ServerUrl = "http://127.0.0.1:8000",
    [string]$Token     = "andes_secret_agent_token_2026",
    [switch]$Silent    = $false,
    [switch]$DryRun    = $false
)

function Write-AgentLog {
    param ([string]$Message, [string]$Level = "INFO")
    if ($Silent) { return }

    $color = switch ($Level) {
        "SUCCESS" { "Green" }
        "WARN"    { "Yellow" }
        "ERROR"   { "Red" }
        "TITLE"   { "Cyan" }
        default   { "White" }
    }
    Write-Host "[$Level] $Message" -ForegroundColor $color
}

if (-not $Silent) {
    Write-Host ""
    Write-Host "==========================================================" -ForegroundColor Cyan
    Write-Host "            ANDES-AGENT: RECOLECTOR DE HARDWARE           " -ForegroundColor Yellow
    Write-Host "      Sistema de Gestion de Inventario TI - AndesPC       " -ForegroundColor Cyan
    Write-Host "==========================================================" -ForegroundColor Cyan
    Write-Host ""
}

Write-AgentLog "Iniciando escaneo de hardware y sistema..." "INFO"

# 1. Informacion General del Sistema
$hostname = $env:COMPUTERNAME
$loggedUser = [System.Environment]::UserName

$cs = Get-CimInstance Win32_ComputerSystem -ErrorAction SilentlyContinue
$manufacturer = if ($cs.Manufacturer) { $cs.Manufacturer.Trim() } else { "GENERICO" }
$model = if ($cs.Model) { $cs.Model.Trim() } else { "ESTANDAR" }
$totalRamGb = [Math]::Round(($cs.TotalPhysicalMemory / 1GB), 0)

# 2. Numero de Serie (BIOS)
$bios = Get-CimInstance Win32_Bios -ErrorAction SilentlyContinue
$serialNumber = if ($bios.SerialNumber) { $bios.SerialNumber.Trim() } else { "" }

# 3. Procesador
$cpu = Get-CimInstance Win32_Processor -ErrorAction SilentlyContinue | Select-Object -First 1
$processor = if ($cpu.Name) { $cpu.Name.Trim() -replace '\s+', ' ' } else { "Desconocido" }

# 4. Almacenamiento (Discos y Tipo SSD/HDD)
$diskDrives = Get-CimInstance Win32_DiskDrive -ErrorAction SilentlyContinue
$totalDiskBytes = 0
foreach ($d in $diskDrives) {
    if ($d.Size) { $totalDiskBytes += [double]$d.Size }
}
$storageGb = if ($totalDiskBytes -gt 0) { [Math]::Round(($totalDiskBytes / 1GB), 0) } else { 256 }

$storageType = "SSD"
try {
    $physDisks = Get-PhysicalDisk -ErrorAction SilentlyContinue
    if ($physDisks) {
        $types = $physDisks.MediaType
        if ($types -contains "SSD") {
            $storageType = "SSD"
        } elseif ($types -contains "HDD") {
            $storageType = "HDD"
        }
    }
} catch {
    $storageType = "SSD"
}

# 5. Sistema Operativo
$os = Get-CimInstance Win32_OperatingSystem -ErrorAction SilentlyContinue
$osCaption = if ($os.Caption) { $os.Caption.Trim() } else { "Windows" }

$osName = "Windows"
$osEdition = "Pro"
if ($osCaption -match "Windows 11") {
    $osName = "Windows 11"
} elseif ($osCaption -match "Windows 10") {
    $osName = "Windows 10"
} elseif ($osCaption -match "Windows 8") {
    $osName = "Windows 8"
} elseif ($osCaption -match "Windows 7") {
    $osName = "Windows 7"
} else {
    $osName = $osCaption
}

if ($osCaption -match "Pro") {
    $osEdition = "Pro"
} elseif ($osCaption -match "Home") {
    $osEdition = "Home"
} elseif ($osCaption -match "Enterprise") {
    $osEdition = "Enterprise"
} elseif ($osCaption -match "Education") {
    $osEdition = "Education"
}

$osVersion = ""
try {
    $displayVersion = (Get-ItemProperty "HKLM:\SOFTWARE\Microsoft\Windows NT\CurrentVersion" -ErrorAction SilentlyContinue).DisplayVersion
    if ($displayVersion) {
        $osVersion = $displayVersion
    } else {
        $osVersion = $os.Version
    }
} catch {
    $osVersion = $os.Version
}

# 6. Red (IP Activa y Direccion MAC)
$net = Get-CimInstance Win32_NetworkAdapterConfiguration -Filter "IPEnabled=True" -ErrorAction SilentlyContinue |
       Where-Object { $_.DefaultIPGateway -ne $null } | Select-Object -First 1

if (-not $net) {
    $net = Get-CimInstance Win32_NetworkAdapterConfiguration -Filter "IPEnabled=True" -ErrorAction SilentlyContinue | Select-Object -First 1
}

$ipAddress = ""
$macAddress = ""
if ($net) {
    $ipv4 = $net.IPAddress | Where-Object { $_ -match '^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$' } | Select-Object -First 1
    $ipAddress = if ($ipv4) { $ipv4 } else { "" }
    $macAddress = if ($net.MACAddress) { $net.MACAddress } else { "" }
}

# 7. Tipo de Chasis (Laptop vs Desktop)
$battery = Get-CimInstance Win32_Battery -ErrorAction SilentlyContinue
$chassisType = if ($battery) { "Laptop" } else { "Desktop" }

# 8. Antivirus Instalado
$antivirusList = @()
try {
    $avProducts = Get-CimInstance -Namespace root\SecurityCenter2 -ClassName AntiVirusProduct -ErrorAction SilentlyContinue
    foreach ($av in $avProducts) {
        if ($av.displayName) { $antivirusList += $av.displayName }
    }
} catch {}
$antivirus = if ($antivirusList.Count -gt 0) { $antivirusList -join ", " } else { "Windows Defender" }

# 9. Impresoras Detectadas
$printers = @()
try {
    $installedPrinters = Get-CimInstance Win32_Printer -ErrorAction SilentlyContinue
    foreach ($p in $installedPrinters) {
        if ($p.Name -notmatch "OneNote|PDF|XPS|Fax|Microsoft Print") {
            $printers += @{
                name       = $p.Name
                port       = $p.PortName
                is_default = [bool]$p.Default
            }
        }
    }
} catch {}

# 10. Software Clave Instalado
$installedSoftwareList = @()
try {
    $uninstallKeys = @(
        "HKLM:\Software\Microsoft\Windows\CurrentVersion\Uninstall\*",
        "HKLM:\Software\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall\*"
    )
    $apps = Get-ItemProperty $uninstallKeys -ErrorAction SilentlyContinue |
            Where-Object { $_.DisplayName -and $_.SystemComponent -ne 1 } |
            Select-Object -ExpandProperty DisplayName -Unique |
            Sort-Object

    $keyApps = $apps | Where-Object {
        $_ -match "Office|Word|Excel|Chrome|Firefox|AnyDesk|TeamViewer|Acrobat|Kaspersky|ESET|Java|WinRAR|7-Zip"
    } | Select-Object -First 12

    if ($keyApps) {
        $installedSoftwareList = $keyApps
    } else {
        $installedSoftwareList = $apps | Select-Object -First 10
    }
} catch {}
$installedSoftwareText = $installedSoftwareList -join ", "

# 11. Estructura de Datos
$payload = @{
    hostname           = $hostname
    serial_number      = $serialNumber
    mac_address        = $macAddress
    ip_address         = $ipAddress
    manufacturer       = $manufacturer
    model              = $model
    processor          = $processor
    ram_gb             = $totalRamGb
    storage_gb         = $storageGb
    storage_type       = $storageType
    os_name            = $osName
    os_edition         = $osEdition
    os_version         = $osVersion
    antivirus          = $antivirus
    chassis_type       = $chassisType
    logged_user        = $loggedUser
    installed_software = $installedSoftwareText
    printers           = $printers
}

$jsonBody = $payload | ConvertTo-Json -Depth 4 -Compress

# Muestra en consola
Write-AgentLog "Resumen recolectado:" "TITLE"
Write-AgentLog "  - Equipo: $hostname ($chassisType)" "INFO"
Write-AgentLog "  - Serie: $(if ($serialNumber) { $serialNumber } else { 'N/A' })" "INFO"
Write-AgentLog "  - Fabricante / Modelo: $manufacturer $model" "INFO"
Write-AgentLog "  - CPU: $processor" "INFO"
Write-AgentLog "  - RAM: ${totalRamGb} GB" "INFO"
Write-AgentLog "  - Disco: ${storageGb} GB ($storageType)" "INFO"
Write-AgentLog "  - Sistema Operativo: $osName $osEdition ($osVersion)" "INFO"
Write-AgentLog "  - Red: IP=$ipAddress | MAC=$macAddress" "INFO"
Write-AgentLog "  - Antivirus: $antivirus" "INFO"
Write-AgentLog "  - Impresoras encontradas: $($printers.Count)" "INFO"

if ($DryRun) {
    Write-AgentLog "Modo DryRun activado. No se enviaran datos al servidor." "WARN"
    Write-Host ""
    Write-Host $jsonBody -ForegroundColor Gray
    return
}

# 12. Envio HTTP al Servidor AndesPC
$endpoint = "$($ServerUrl.TrimEnd('/'))/api/v1/agent/sync"
Write-AgentLog "Enviando telemetria a $endpoint..." "INFO"

$headers = @{
    "Content-Type"  = "application/json; charset=utf-8"
    "Accept"        = "application/json"
    "X-Andes-Token" = $Token
}

try {
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12 -bor [Net.SecurityProtocolType]::Tls11 -bor [Net.SecurityProtocolType]::Tls

    $response = Invoke-RestMethod -Uri $endpoint -Method Post -Headers $headers -Body ([System.Text.Encoding]::UTF8.GetBytes($jsonBody)) -TimeoutSec 15

    if ($response.success) {
        $accion = if ($response.action -eq "created") { "CREADO COMO NUEVO" } else { "ACTUALIZADO CON EXITO" }
        Write-AgentLog "[OK] Sincronizacion completada: $accion" "SUCCESS"
        Write-AgentLog "  Mensaje: $($response.message)" "SUCCESS"
        Write-AgentLog "  ID en AndesPC: $($response.equipo.id)" "SUCCESS"
    } else {
        Write-AgentLog "[ALERTA] El servidor respondio pero no se pudo completar: $($response.message)" "WARN"
    }
} catch {
    Write-AgentLog "[ERROR] No se pudo conectar con el servidor AndesPC:" "ERROR"
    if ($_.Exception.Response) {
        $stream = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($stream)
        $respText = $reader.ReadToEnd()
        Write-AgentLog "  Detalle del servidor: $respText" "ERROR"
    } else {
        Write-AgentLog "  $($_.Exception.Message)" "ERROR"
    }
}

if (-not $Silent -and -not $DryRun) {
    Write-Host ""
    Write-Host "Presione Enter para salir..." -ForegroundColor Gray
    Read-Host
}
