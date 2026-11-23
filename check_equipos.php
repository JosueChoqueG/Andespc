<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ids = [662, 702, 641, 381];
$equipos = \App\Models\Equipo::whereIn('responsable_id', $ids)->get();

foreach ($equipos as $e) {
    echo "Equipo ID: {$e->id} | Nombre: {$e->nombre_dispositivo} | Serie: {$e->numero_serie} | Resp ID: {$e->responsable_id} | Resp: " . ($e->responsable?->nombre_responsable) . PHP_EOL;
}
