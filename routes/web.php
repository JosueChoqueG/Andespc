<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgenciaController;
use App\Http\Controllers\Admin\OficinaController;
use App\Http\Controllers\Admin\MarcaController;
use App\Http\Controllers\Admin\ModeloController;
use App\Http\Controllers\Admin\TipoequipoController;
use App\Http\Controllers\Admin\HardwareController;
use App\Http\Controllers\Admin\SistemaOperativoController;
use App\Http\Controllers\Admin\ResponsableController;
use App\Http\Controllers\Admin\EquipoController;
use App\Http\Controllers\Admin\IncidenciaController;
// Página de inicio (pública)
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas protegidas: requieren autenticación
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recursos del panel administrativo
    Route::resource('agencias', AgenciaController::class);
    Route::resource('oficinas', OficinaController::class);
    Route::resource('marcas', MarcaController::class);
    Route::resource('modelos', ModeloController::class);
    Route::resource('tipoequipos', TipoequipoController::class);
    Route::resource('hardwares', HardwareController::class);
    Route::resource('sistemaoperativos', SistemaoperativoController::class);
    Route::resource('responsables', ResponsableController::class);
    Route::resource('equipos', EquipoController::class);

    // 🔹 Panel de administración: rutas bajo /admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/incidencias/nueva', [IncidenciaController::class, 'formulario'])->name('incidencias.formulario');
        Route::post('/incidencias', [IncidenciaController::class, 'guardar'])->name('incidencias.guardar');
        Route::get('/incidencias', [IncidenciaController::class, 'listado'])->name('incidencias.listado');
    });
});

require __DIR__.'/auth.php';