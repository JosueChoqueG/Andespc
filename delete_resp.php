<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// IDs a eliminar:
// 662: PINTO VEGA FLOR DE MARIA
// 669: NERIDA DAVILA GUILLEN
// 545: NEPTALY A. CHOQQUE CCALLUCHI
// 702: NADIE
// 641: MARIA TTITO GUTIERREZ
// 560: MARICRUZ THALIA ZANABRIA RODRIGUEZ
// 557: LIZARDO EUDIS FLORES FERNANDEZ
// 523: JOSE SALAZAR
// 381: ELISABETH GUZMÁN VÁSQUEZ
// 642: ESPINOZA TAIPE ROSVI
// 673: CASTRO BERROCAL PAULO HAMILTHON

$ids = [662, 669, 545, 702, 641, 560, 557, 523, 381, 642, 673];

\DB::transaction(function() use ($ids) {
    // 1. Desvincular equipos asociados (poner en NULL para no perder datos de los equipos)
    \App\Models\Equipo::whereIn('responsable_id', $ids)->update(['responsable_id' => null]);
    \App\Models\Impresora::whereIn('responsable_id', $ids)->update(['responsable_id' => null]);
    \App\Models\Termica::whereIn('responsable_id', $ids)->update(['responsable_id' => null]);
    \App\Models\Contabillete::whereIn('responsable_id', $ids)->update(['responsable_id' => null]);

    // 2. Eliminar responsables
    $eliminados = \App\Models\Responsable::whereIn('id', $ids)->delete();
    echo "Responsables eliminados correctamente: {$eliminados}" . PHP_EOL;
});
