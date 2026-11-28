<div align="center">

# 🏔️ AndesPC — IT Asset Management & Help Desk System
### *Sistema Integral de Gestión de Activos Tecnológicos, Mesa de Ayuda y Agente de Telemetría*

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PowerShell](https://img.shields.io/badge/PowerShell-5.1+-5391FE?style=for-the-badge&logo=powershell&logoColor=white)](https://microsoft.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

<p align="center">
  <b>Una plataforma empresarial centralizada diseñada específicamente para la gestión de activos informáticos, equipos bancarios/financieros especializados, trazabilidad de mantenimiento, incidencias TI y sincronización desatendida mediante agente ligero.</b>
</p>

[Características](#-características-principales) •
[Módulos](#-módulos-del-sistema) •
[Andes-Agent](#-andes-agent-agente-de-telemetría) •
[Arquitectura](#-arquitectura-del-sistema) •
[Instalación](#-guía-de-instalación-rápida) •
[Endpoints API](#-api-rest-de-telemetría)

---

</div>

## 📌 ¿Por qué AndesPC?

A diferencia de soluciones genéricas como GLPI u OCS Inventory —que resultan lentas, complejas y están desarticuladas de la realidad operativa de agencias e instituciones financieras— **AndesPC** fue concebido desde cero para:

1. **Modelar activos no convencionales:** Soporte nativo para *Contadoras de Billetes* (sensores UV/MG/IR) e *Impresoras Térmicas de Ventanilla/Caja*, no solo PCs estándar.
2. **Sincronización desatendida con Andes-Agent:** Un agente propio en PowerShell/C# de tan solo **58 KB**, sin software invasivo ni sobrecostos de memoria, camuflable con icono oficial.
3. **Mesa de Ayuda (Help Desk) Dinámica:** Dashboard compacto e interactivo con métricas en tiempo real sobre incidentes abiertos, en curso y resueltos.
4. **Control estricto de custodia:** Integración completa de responsables con DNI, Cargo, Oficinas y Agencias distribuidas.

---

## ✨ Características Principales

```
├── 🖥️ Inventario de Cómputo (PC, Laptops, All-In-One, Mini PC, Tiny)
├── 🖨️ Equipos de Impresión (Láser, Tinta, Multifuncionales)
├── 🧾 Impresoras Térmicas de Tickets (Cajas y Ventanillas)
├── 💵 Contadoras de Billetes (Sensores UV, MG, IR, Calibración)
├── 🤖 Andes-Agent (Agente ejecutable .EXE / PowerShell de 58 KB)
├── 🎫 Mesa de Ayuda (Help Desk & Gestión de Incidencias)
├── 📊 Dashboard Dinámico con Gráficos y Métricas en Tiempo Real
├── 🛠️ Historial de Mantenimientos Preventivos y Correctivos
├── 👥 Control de Custodios con DNI y Cargo Normalizado
└── 📄 Exportación de Reportes Ejecutivos en PDF y Excel
```

---

## 📦 Módulos del Sistema

### 1. 💻 Equipos de Cómputo y Hardware
* Ficha técnica detallada: Procesador, memoria RAM (GB), almacenamiento SSD/HDD (GB), Sistema Operativo, Edición, Versión, IP y MAC.
* Estado operativo: *Operativo, Con Observación, En Mantenimiento, Fuera de Servicio, De Baja*.
* Conectividad: Registro de VPNs corporativas, antivirus y configuración de red.

### 2. 🧾 Impresoras y Térmicas de Ventanilla
* Control de impresoras departamentales e impresoras térmicas de tickets.
* Registro de interfaz de conexión (USB, Serial, Red Ethernet, Bluetooth).
* Historial de cambios de cabezal térmico, rodillos y guillotina.

### 3. 💵 Contadoras de Billetes (Contabilletes)
* Módulo especializado para instituciones financieras y de recaudación.
* Monitoreo del estado de sensores ultravioleta (UV), magnéticos (MG) e infrarrojos (IR).
* Alertas de calibración periódica y mantenimiento de rodillos de tracción.

### 4. 🎫 Help Desk & Gestión de Incidencias TI
* Flujo completo de tickets: Creación, asignación a técnicos de TI, categorización por severidad y resolución con notas técnicas.
* **Dashboard interactivo:** Métricas visuales, tarjetas compactas de estado y tiempos de atención.

### 5. 👥 Responsables y Estructura Organizacional
* Directorio sincronizado de colaboradores con **DNI**, **Cargo institucional**, **Oficina** y **Agencia**.
* Trazabilidad completa: Al reasignar un equipo, queda registrado el historial de entrega y custodia.

---

## 🤖 Andes-Agent: Agente de Telemetría

**Andes-Agent** es el componente cliente que recolecta en **menos de 2 segundos** el hardware completo de cualquier máquina con Windows y lo sincroniza automáticamente con el servidor AndesPC mediante API REST.

```
┌────────────────────────────────────────┐                ┌───────────────────────────────────┐
│              CLIENTE PC                │                │         SERVIDOR ANDESPC          │
│ ┌────────────────────────────────────┐ │                │ ┌───────────────────────────────┐ │
│ │ DiagnosticoTI.exe (58 KB)          │ │  POST /api/v1  │ │ AgentController.php           │ │
│ │  - Icono oficial de Windows        │ │ ─────────────> │ │  - Valida X-Andes-Token       │ │
│ │  - 100% Silencioso (winexe)        │ │  agent/sync    │ │  - Match por Serie / MAC / IP │ │
│ │  - PowerShell WMI/CIM nativo       │ │                │ │  - Upsert seguro sin alterar  │ │
│ └────────────────────────────────────┘ │                │ │    oficina ni custodio        │ │
│                                        │                │ └───────────────────────────────┘ │
└────────────────────────────────────────┘                └───────────────────────────────────┘
```

### Capacidades del Agente:
* **Cero Dependencias Externas:** No requiere instalar programas de terceros ni librerías pesadas.
* **Detección Profunda:** Extrae Número de Serie BIOS, Procesador exacto, Memoria física, Discos y detección automática de tecnología (SSD vs HDD), Sistema Operativo, IP, MAC y Antivirus.
* **Detección de Impresoras Locales:** Detecta impresoras USB locales conectadas a la estación de trabajo (incluidas térmicas).
* **Camuflaje Visual:** Compilable como un ejecutable `.exe` independiente de 58 KB con iconos oficiales (`DiagnosticoTI.exe`, `ActualizacionTI.exe`, `SoporteTecnico.exe`).

---

## 🏗️ Arquitectura del Sistema

```
andespc/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controladores Web (Equipos, Impresoras, Incidencias, etc.)
│   │   └── Api/
│   │       └── AgentController.php  # Receptor seguro de telemetría de agentes
│   └── Models/                # Eloquent Models (Equipo, Hardware, SO, Responsable, etc.)
├── andes-agent/               # Suite del Agente de Telemetría
│   ├── AndesAgent.ps1         # Script colector en PowerShell
│   ├── DiagnosticoTI.exe      # Ejecutable compilado independiente (58 KB)
│   ├── compilar-exe.ps1       # Compilador nativo C# (.NET csc.exe)
│   ├── crear-ejecutable.bat   # Asistente interactivo de compilación
│   └── ejecutar-agente.bat    # Lanzador en 1 clic para soporte técnico
├── database/                  # Migraciones, seeders y esquemas
├── resources/views/           # Vistas Blade estructuradas (Tailwind + Bootstrap)
└── routes/
    ├── web.php                # Rutas del panel administrativo
    └── api.php                # Rutas de sincronización para Andes-Agent
```

---

## 🚀 Guía de Instalación Rápida

### Requisitos Previos
* **PHP** >= 8.2 con extensiones `pdo_mysql`, `mbstring`, `openssl`, `gd`
* **Composer** >= 2.x
* **Node.js** >= 18.x & **NPM**
* **MySQL** >= 8.0 o MariaDB

### 1. Clonar el Repositorio
```bash
git clone https://github.com/tu-organizacion/andespc.git
cd andespc
```

### 2. Instalar Dependencias
```bash
composer install
npm install
```

### 3. Configurar Entorno
```bash
cp .env.example .env
php artisan key:generate
```

Configura tus variables en el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_base_de_datos
DB_USERNAME=tu_usuario_db
DB_PASSWORD=tu_password_seguro

# Token aleatorio y seguro para la sincronización con agentes
ANDES_AGENT_TOKEN=generar_token_secreto_aqui
```

### 4. Ejecutar Migraciones
```bash
php artisan migrate
```

### 5. Compilar Assets y Levantar Servidor
```bash
# Compilar frontend
npm run build

# Iniciar servidor de desarrollo
php artisan serve
```
El panel estará disponible en: **`http://127.0.0.1:8000`**

---

## 📡 API REST de Telemetría

El servidor expone endpoints dedicados para la comunicación con los agentes clientes:

| Método | Endpoint | Cabecera Requerida | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/v1/agent/ping` | `X-Andes-Token: <TOKEN>` | Comprueba el estado y versión del servidor API. |
| `POST` | `/api/v1/agent/sync` | `X-Andes-Token: <TOKEN>` | Recibe telemetría completa y realiza el Upsert inteligente. |

#### Ejemplo de Carga Útil (Payload JSON de Ejemplo):
```json
{
  "hostname": "PC-AREA-01",
  "serial_number": "SN-MOCK-123456",
  "mac_address": "00:1B:44:11:3A:B7",
  "ip_address": "10.0.0.50",
  "manufacturer": "OEM Manufacturer",
  "model": "Workstation Series",
  "processor": "Intel / AMD Multi-Core Processor",
  "ram_gb": 16,
  "storage_gb": 512,
  "storage_type": "SSD",
  "os_name": "Windows 11",
  "os_edition": "Pro",
  "os_version": "23H2",
  "antivirus": "Antivirus Corporativo",
  "chassis_type": "Desktop",
  "logged_user": "usuario.ejemplo",
  "printers": [
    { "name": "Generic Thermal Receipt Printer", "port": "USB001", "is_default": true }
  ]
}
```

---

## 🛠️ Compilación y Uso de Andes-Agent

Para generar tu propio ejecutable personalizado camuflado con icono:

1. Ve a la carpeta `andes-agent/`:
   ```bash
   cd andes-agent
   ```
2. Ejecuta el asistente interactivo:
   ```cmd
   crear-ejecutable.bat
   ```
3. O compila directamente por comando:
   ```powershell
   powershell.exe -ExecutionPolicy Bypass -File .\compilar-exe.ps1 -NombreExe "DiagnosticoTI.exe" -Icono "icono-pc.ico" -Modo "Silencioso"
   ```

*Para distribución masiva:* Puedes desplegar `DiagnosticoTI.exe` o `instalar-tarea-programada.ps1` a través de directivas de grupo (**GPO**) de Active Directory.

---

## 🛡️ Seguridad y Buenas Prácticas

* **Headers de Seguridad:** Implementación de middleware con cabeceras HTTP restrictivas (CSP, X-Frame-Options, X-Content-Type-Options).
* **Integridad Referencial:** Transacciones ACID en Laravel (`DB::transaction`) para evitar inconsistencias durante el matching masivo de inventario.
* **Token Hashing Seguro:** Comparación de credenciales mediante `hash_equals()` contra ataques de temporización.

---

## 📄 Licencia

Este proyecto está bajo la Licencia **MIT**. Consulta el archivo [LICENSE](LICENSE) para más detalles.

---

<div align="center">
  <sub>Desarrollado con ❤️ para la gestión tecnológica eficiente y profesional.</sub>
</div>
