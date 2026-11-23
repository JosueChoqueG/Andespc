<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nombres = [
    'PINTO VEGA FLOR DE MARIA',
    'NERIDA DAVILA GUILLEN',
    'NEPTALY A. CHOQQUE CCALLUCHI',
    'NADIE',
    'MARIA TTITO GUTIERREZ',
    'MARICRUZ THALIA ZANABRIA RODRIGUEZ',
    'LIZARDO EUDIS FLORES FERNANDEZ',
    'JOSE SALAZAR',
    'ELISABETH GUZMÁN VÁSQUEZ',
    'ESPINOZA TAIPE ROSVI',
    'CASTRO BERROCAL PAULO HAMILTHON'
];

$encontrados = [];
foreach ($nombres as $nom) {
    $r = \App\Models\Responsable::where('nombre_responsable', 'like', '%' . trim($nom) . '%')->get();
    if ($r->isEmpty()) {
        // buscar por palabras clave
        $palabras = preg_split('/\s+/', trim($nom));
        $q = \App\Models\Responsable::query();
        foreach ($palabras as $p) {
            if (mb_strlen($p) > 2) {
                $q->where('nombre_responsable', 'like', '%' . $p . '%');
            }
        }
        $r = $q->get();
    }
    $encontrados[$nom] = $r->map(function($item) {
        return [
            'id' => $item->id,
            'nombre' => $item->nombre_responsable,
            'equipos' => \App\Models\Equipo::where('responsable_id', $item->id)->count(),
            'impresoras' => \App\Models\Impresora::where('responsable_id', $item->id)->count(),
            'termicas' => \App\Models\Termica::where('responsable_id', $item->id)->count(),
            'contabilletes' => \App\Models\Contabillete::where('responsable_id', $item->id)->count()
        ];
    })->values()->all();
}

echo json_encode($encontrados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
