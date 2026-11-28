<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Equipo;
use App\Models\Hardware;
use App\Models\SistemaOperativo;
use App\Models\Modelo;
use App\Models\Marca;
use App\Models\TipoEquipo;
use App\Models\Oficina;
use App\Models\Responsable;

class AgentController extends Controller
{
    /**
     * Valida el token de autenticación del agente.
     */
    protected function authenticateAgent(Request $request): bool
    {
        $expectedToken = config('services.andes_agent.token', env('ANDES_AGENT_TOKEN', 'andes_secret_agent_token_2026'));
        
        $providedToken = $request->header('X-Andes-Token')
            ?: $request->bearerToken()
            ?: $request->input('token');

        return !empty($providedToken) && hash_equals($expectedToken, (string)$providedToken);
    }

    /**
     * Endpoint de comprobación de salud y conexión (Healthcheck).
     */
    public function ping(Request $request)
    {
        if (!$this->authenticateAgent($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso no autorizado: Token de agente inválido o ausente.'
            ], 401);
        }

        return response()->json([
            'success'   => true,
            'status'    => 'online',
            'server'    => 'AndesPC Management Server',
            'version'   => '1.0.0',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Endpoint principal de sincronización y reporte de telemetría de hardware.
     */
    public function sync(Request $request)
    {
        if (!$this->authenticateAgent($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso no autorizado: Token de agente inválido o ausente.'
            ], 401);
        }

        $validated = $request->validate([
            'hostname'            => 'required|string|max:100',
            'serial_number'       => 'nullable|string|max:100',
            'mac_address'         => 'nullable|string|max:30',
            'ip_address'          => 'nullable|string|max:45',
            'manufacturer'        => 'nullable|string|max:100',
            'model'               => 'nullable|string|max:100',
            'processor'           => 'nullable|string|max:150',
            'ram_gb'              => 'nullable|numeric',
            'storage_gb'          => 'nullable|numeric',
            'storage_type'        => 'nullable|string|max:30',
            'os_name'             => 'nullable|string|max:60',
            'os_edition'          => 'nullable|string|max:60',
            'os_version'          => 'nullable|string|max:30',
            'antivirus'           => 'nullable|string|max:100',
            'chassis_type'        => 'nullable|string|max:50',
            'installed_software'  => 'nullable|string',
            'printers'            => 'nullable',
            'logged_user'         => 'nullable|string|max:100',
        ]);

        $hostname     = mb_strtoupper(trim($validated['hostname']));
        $serialNumber = trim($validated['serial_number'] ?? '');
        $macAddress   = strtoupper(trim($validated['mac_address'] ?? ''));
        $ipAddress    = trim($validated['ip_address'] ?? '');
        $loggedUser   = trim($validated['logged_user'] ?? '');

        // Filtrar números de serie genéricos o no asignados por el fabricante OEM
        $genericSerials = [
            'TO BE FILLED BY O.E.M.', 'DEFAULT STRING', 'SYSTEM SERIAL NUMBER', 
            'NONE', 'NULL', 'UNKNOWN', '0123456789', 'CHASSIS SERIAL NUMBER', '123456789'
        ];
        if (in_array(strtoupper($serialNumber), $genericSerials) || strlen($serialNumber) < 3) {
            $serialNumber = null;
        }

        DB::beginTransaction();
        try {
            // 1. Marca y Modelo
            $marcaNombre = mb_strtoupper(trim($validated['manufacturer'] ?? 'GENÉRICO'));
            if (empty($marcaNombre) || in_array($marcaNombre, ['SYSTEM MANUFACTURER', 'TO BE FILLED BY O.E.M.'])) {
                $marcaNombre = 'GENÉRICO';
            }
            $marca = Marca::firstOrCreate(['nombre_marca' => $marcaNombre]);

            $modeloNombre = mb_strtoupper(trim($validated['model'] ?? 'ESTÁNDAR'));
            if (empty($modeloNombre) || in_array($modeloNombre, ['SYSTEM PRODUCT NAME', 'TO BE FILLED BY O.E.M.'])) {
                $modeloNombre = 'ESTÁNDAR';
            }
            $modelo = Modelo::firstOrCreate([
                'nombre_modelo' => $modeloNombre,
                'marca_id'      => $marca->id
            ]);

            // 2. Sistema Operativo
            $soNombre  = trim($validated['os_name'] ?? 'Windows');
            $soEdicion = trim($validated['os_edition'] ?? 'Pro');
            $soVersion = trim($validated['os_version'] ?? '10/11');
            $so = SistemaOperativo::firstOrCreate([
                'nombre_so' => substr($soNombre, 0, 50),
                'edicion'   => substr($soEdicion, 0, 50),
                'version'   => substr($soVersion, 0, 20),
            ]);

            // 3. Hardware (Procesador, RAM, Almacenamiento)
            $procesador = trim($validated['processor'] ?? 'Desconocido');
            $ramGb = (int) round((float) ($validated['ram_gb'] ?? 8));
            $storageGb = (int) round((float) ($validated['storage_gb'] ?? 256));
            $storageType = strtoupper(trim($validated['storage_type'] ?? 'SSD'));
            if (!in_array($storageType, ['SSD', 'HDD', 'NVME', 'M.2', 'HÍBRIDO'])) {
                $storageType = 'SSD';
            }

            $hardware = Hardware::firstOrCreate([
                'procesador'          => substr($procesador, 0, 100),
                'ram_gb'              => $ramGb,
                'almacenamiento_gb'   => $storageGb,
                'tipo_almacenamiento' => substr($storageType, 0, 20),
            ]);

            // 4. Tipo de Equipo (Desktop, Laptop, AIO, Mini)
            $chassis = strtolower(trim($validated['chassis_type'] ?? ''));
            $tipoEquipo = null;
            if (str_contains($chassis, 'laptop') || str_contains($chassis, 'notebook') || str_contains($chassis, 'portable')) {
                $tipoEquipo = TipoEquipo::where('nombre_tipo', 'like', '%LAPTOP%')->first();
            } elseif (str_contains($chassis, 'all in one') || str_contains($chassis, 'aio')) {
                $tipoEquipo = TipoEquipo::where('nombre_tipo', 'like', '%ALL IN ONE%')->first();
            } elseif (str_contains($chassis, 'mini') || str_contains($chassis, 'tiny')) {
                $tipoEquipo = TipoEquipo::where('nombre_tipo', 'like', '%MINI%')->orWhere('nombre_tipo', 'like', '%TINY%')->first();
            }
            if (!$tipoEquipo) {
                $tipoEquipo = TipoEquipo::where('nombre_tipo', 'like', '%PC%')->first() ?: TipoEquipo::first();
            }

            // 5. Oficina por defecto para nuevos equipos
            $oficinaDefault = Oficina::where('nombre_oficina', 'like', '%TI%')->first() ?: Oficina::first();

            // 6. Búsqueda y Emparejamiento de Equipo
            $equipo = null;

            // Prioridad A: Número de Serie no genérico
            if (!empty($serialNumber)) {
                $equipo = Equipo::where('numero_serie', $serialNumber)->first();
            }

            // Prioridad B: Dirección MAC
            if (!$equipo && !empty($macAddress)) {
                $equipo = Equipo::where('direccion_mac', $macAddress)->first();
            }

            // Prioridad C: Nombre de dispositivo (Hostname)
            if (!$equipo && !empty($hostname)) {
                $equipo = Equipo::where('nombre_dispositivo', $hostname)->first();
            }

            // Formatear información de impresoras detectadas
            $printersInfo = '';
            if (!empty($validated['printers'])) {
                $printers = is_array($validated['printers']) ? $validated['printers'] : json_decode($validated['printers'], true);
                if (is_array($printers)) {
                    $printersList = [];
                    foreach ($printers as $p) {
                        $pName = is_array($p) ? ($p['name'] ?? '') : (string)$p;
                        $pPort = is_array($p) ? ($p['port'] ?? '') : '';
                        $printersList[] = $pName . ($pPort ? " ({$pPort})" : '');
                    }
                    if (!empty($printersList)) {
                        $printersInfo = "\n[Impresoras detectadas]: " . implode(' | ', $printersList);
                    }
                }
            }

            $action = 'updated';
            $fechaSync = now()->format('d/m/Y H:i:s');

            if ($equipo) {
                // Actualizar equipo existente preservando oficina_id, responsable_id, estado_equipo y código patrimonial
                $updateData = [
                    'nombre_dispositivo'   => $hostname,
                    'hardware_id'          => $hardware->id,
                    'modelo_id'            => $modelo->id,
                    'sistemaoperativo_id'  => $so->id,
                ];

                if (!empty($ipAddress)) {
                    $updateData['direccion_ip'] = $ipAddress;
                }
                if (!empty($macAddress)) {
                    $updateData['direccion_mac'] = $macAddress;
                }
                if (!empty($serialNumber) && empty($equipo->numero_serie)) {
                    $updateData['numero_serie'] = $serialNumber;
                }
                if (!empty($validated['antivirus'])) {
                    $updateData['antivirus'] = substr(trim($validated['antivirus']), 0, 100);
                }
                if (!empty($validated['installed_software'])) {
                    $updateData['programas_instalados'] = $validated['installed_software'];
                }

                // Anotar última sincronización en observaciones sin borrar el contenido previo
                $obsOriginal = $equipo->observacion ?? '';
                $cleanObs = preg_replace('/\[Andes-Agent: Sincronizado.*?\]/s', '', $obsOriginal);
                $updateData['observacion'] = trim($cleanObs . "\n[Andes-Agent: Sincronizado el {$fechaSync} por {$loggedUser}]" . $printersInfo);

                $equipo->update($updateData);
                $action = 'updated';
            } else {
                // Crear nuevo equipo detectado por el agente
                $equipo = Equipo::create([
                    'nombre_dispositivo'   => $hostname,
                    'numero_serie'         => $serialNumber ?: null,
                    'direccion_ip'         => $ipAddress ?: null,
                    'direccion_mac'        => $macAddress ?: null,
                    'estado_equipo'        => 'Operativo',
                    'oficina_id'           => $oficinaDefault->id,
                    'tipoequipo_id'        => $tipoEquipo->id,
                    'hardware_id'          => $hardware->id,
                    'modelo_id'            => $modelo->id,
                    'sistemaoperativo_id'  => $so->id,
                    'antivirus'            => !empty($validated['antivirus']) ? substr(trim($validated['antivirus']), 0, 100) : null,
                    'programas_instalados' => $validated['installed_software'] ?? null,
                    'observacion'          => "[Andes-Agent: Registrado automáticamente el {$fechaSync} por {$loggedUser}]" . $printersInfo,
                ]);
                $action = 'created';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'action'  => $action,
                'equipo'  => [
                    'id'                 => $equipo->id,
                    'nombre_dispositivo' => $equipo->nombre_dispositivo,
                    'numero_serie'       => $equipo->numero_serie,
                    'ip'                 => $equipo->direccion_ip,
                    'mac'                => $equipo->direccion_mac,
                    'hardware'           => sprintf("%s | %dGB RAM | %dGB %s", $hardware->procesador, $hardware->ram_gb, $hardware->almacenamiento_gb, $hardware->tipo_almacenamiento),
                    'sistema_operativo'  => sprintf("%s %s (%s)", $so->nombre_so, $so->edicion, $so->version),
                    'modelo'             => sprintf("%s %s", $marca->nombre_marca, $modelo->nombre_modelo),
                ],
                'message' => $action === 'created' 
                    ? 'Equipo nuevo detectado y registrado automáticamente con éxito.' 
                    : 'Equipo existente sincronizado y actualizado con éxito.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en Andes-Agent sync: ' . $e->getMessage(), [
                'exception' => $e,
                'request'   => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la telemetría del equipo: ' . $e->getMessage()
            ], 500);
        }
    }
}
