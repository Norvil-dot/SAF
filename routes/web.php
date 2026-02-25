<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard'); // Si está logueado va al panel
    }
    // Si no está logueado, vemos la home pública con locales destacados
    $destacados = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->latest()->take(3)->get();
    
    // Obtener ubicaciones únicas (distritos) para el buscador
    $ubicaciones = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->whereNotNull('distrito')->distinct()->pluck('distrito');
    
    $tipos = [
        'Departamentos',
        'Casas / Casa completa',
        'Habitaciones',
        'Locales',
        'Oficinas y Consultorios',
        'Terrenos',
        'Depósitos',
        'Fincas',
        'Haciendas',
        'Parqueaderos'
    ];
    
    return view('welcome', compact('destacados', 'ubicaciones', 'tipos'));
});

Route::get('/local/{id}', function ($id) {
    $local = \App\Models\Local::withoutGlobalScopes()->findOrFail($id);
    return view('detalle-local', compact('local'));
})->name('detalle-local');

Route::post('/local/{local}/contactar', [\App\Http\Controllers\MensajeController::class, 'store'])->name('local.contactar');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $tenantId = auth()->user()->tenant_id;
        
        $totalLocales = \App\Models\Local::withoutGlobalScopes()->where('tenant_id', $tenantId)->count();
        $alquiladosReales = \App\Models\Local::withoutGlobalScopes()->where('tenant_id', $tenantId)
            ->whereIn('estado', ['alquilado', 'arrendado'])->count();
            
        $totalInquilinos = $alquiladosReales;
        $totalAlquiladosCalculo = $alquiladosReales;
        $totalLocalesCalculo = $totalLocales;
        
        $tasaOcupacion = $totalLocalesCalculo > 0 ? round(($totalAlquiladosCalculo / $totalLocalesCalculo) * 100) : 0;
        
        $alquilados = \App\Models\Local::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->whereIn('estado', ['alquilado', 'arrendado'])
            ->latest('alquilado_en')
            ->get();
            
        return view('dashboard', compact('totalInquilinos', 'tasaOcupacion', 'totalAlquiladosCalculo', 'totalLocalesCalculo', 'alquilados'));
    })->name('dashboard');

    Route::get('/publicar-local', function () {
        $tenant = auth()->user()->tenant;
        if ($tenant) {
            $count = \App\Models\Local::where('tenant_id', $tenant->id)->count();
            $limit = 1;
            switch ($tenant->plan) {
                case 'Gratuito': $limit = 1; break;
                case 'Básico': $limit = 10; break;
                case 'Pro': $limit = 25; break;
                case 'Elite': $limit = 50; break;
            }
            if ($count >= $limit) {
                $msgLimit = $limit == 1 ? "1 propiedad" : "{$limit} propiedades";
                return redirect()->route('dashboard')->with('error', "Límite de plan alcanzado. Estás en el plan {$tenant->plan} donde solo se permite subir {$msgLimit}. Mejora tu plan para subir más.");
            }
        }
        return view('publicar-local');
    })->name('publicar-local');
    
    Route::post('/locales', [App\Http\Controllers\LocalController::class, 'store'])->name('dashboard.locales.store');
    Route::get('/locales/{local}/edit', [App\Http\Controllers\LocalController::class, 'edit'])->name('dashboard.locales.edit');
    Route::put('/locales/{local}', [App\Http\Controllers\LocalController::class, 'update'])->name('dashboard.locales.update');
    Route::delete('/locales/{local}', [App\Http\Controllers\LocalController::class, 'destroy'])->name('dashboard.locales.destroy');
    Route::post('/locales/{local}/marcar-alquilado', [App\Http\Controllers\LocalController::class, 'marcarAlquilado'])->name('dashboard.locales.marcar-alquilado');
    Route::post('/locales/{local}/marcar-disponible', [App\Http\Controllers\LocalController::class, 'marcarDisponible'])->name('dashboard.locales.marcar-disponible');

    Route::get('/mis-inmuebles', function () {
        $locales = \App\Models\Local::withoutGlobalScopes()->where('tenant_id', auth()->user()->tenant_id)->latest()->get();
        return view('mis-inmuebles', compact('locales'));
    })->name('mis-inmuebles');

    Route::get('/mensajes', function () {
        $mensajes = \App\Models\Mensaje::where('tenant_id', auth()->user()->tenant_id)->latest()->get();
        return view('mensajes', compact('mensajes'));
    })->name('mensajes');

    Route::post('/mensajes/{mensaje}/toggle-leido', [App\Http\Controllers\MensajeController::class, 'toggleLeido'])->name('mensajes.toggle-leido');
    Route::delete('/mensajes/{mensaje}', [App\Http\Controllers\MensajeController::class, 'destroy'])->name('mensajes.destroy');

    Route::get('/notificaciones', function () {
        $notificaciones = \App\Models\Notificacion::where('tenant_id', auth()->user()->tenant_id)->latest()->get();
        return view('notificaciones', compact('notificaciones'));
    })->name('notificaciones');

    Route::post('/notificaciones/{notificacion}/toggle-leido', function (\App\Models\Notificacion $notificacion) {
        if ($notificacion->tenant_id != auth()->user()->tenant_id) abort(403);
        $notificacion->update(['leido' => !$notificacion->leido]);
        return redirect()->back();
    })->name('notificaciones.toggle-leido');

    Route::delete('/notificaciones/{notificacion}', function (\App\Models\Notificacion $notificacion) {
        if ($notificacion->tenant_id != auth()->user()->tenant_id) abort(403);
        $notificacion->delete();
        return redirect()->back();
    })->name('notificaciones.destroy');

    Route::get('/ingresos', function () {
        return view('ingresos');
    })->name('ingresos');
});
Route::get('/venta', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->where('operacion', 'venta');
    
    if ($request->has('ubicacion') && !empty($request->ubicacion)) {
        $query->whereRaw('LOWER(distrito) LIKE LOWER(?)', ['%' . $request->ubicacion . '%']);
    }

    if ($request->has('tipo') && !empty($request->tipo) && $request->tipo !== 'Tipo de propiedad') {
        $termMapping = [
            'Departamentos' => 'Departamento',
            'Casas / Casa completa' => 'Casa',
            'Habitaciones' => 'Habitaci',
            'Locales' => 'Local',
            'Oficinas y Consultorios' => 'Oficina',
            'Terrenos' => 'Terreno',
            'Depósitos' => 'Depósito',
            'Fincas' => 'Finca',
            'Haciendas' => 'Hacienda',
            'Parqueaderos' => 'Parqueadero'
        ];
        $searchTerm = $termMapping[$request->tipo] ?? rtrim($request->tipo);
        $query->where('tipo_propiedad', 'LIKE', '%' . $searchTerm . '%');
    }

    if ($request->has('min_precio') && is_numeric($request->min_precio)) {
        $query->where('precio_mensual', '>=', $request->min_precio);
    }

    if ($request->has('max_precio') && is_numeric($request->max_precio)) {
        $query->where('precio_mensual', '<=', $request->max_precio);
    }

    $sort = $request->query('sort', 'mas_recientes');
    switch ($sort) {
        case 'menor_precio':
            $query->orderBy('precio_mensual', 'asc');
            break;
        case 'mayor_precio':
            $query->orderBy('precio_mensual', 'desc');
            break;
        case 'mas_amplios':
            $query->orderBy('area', 'desc');
            break;
        case 'mas_pequenos':
            $query->orderBy('area', 'asc');
            break;
        case 'relevancia':
            $query->latest();
            break;
        case 'mas_recientes':
        default:
            $query->latest();
            break;
    }
    
    $locales = $query->get();
    $currentSort = $sort;
    $ubicaciones = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->whereNotNull('distrito')->distinct()->pluck('distrito');
    
    $ubicacionesConteo = \App\Models\Local::withoutGlobalScopes()
        ->where('estado', 'disponible')
        ->where('operacion', 'venta')
        ->whereNotNull('distrito')
        ->select('distrito', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('distrito')
        ->orderByDesc('total')
        ->get();
    
    $tipos = [
        'Departamentos',
        'Casas / Casa completa',
        'Habitaciones',
        'Locales',
        'Oficinas y Consultorios',
        'Terrenos',
        'Depósitos',
        'Fincas',
        'Haciendas',
        'Parqueaderos'
    ];

    return view('venta', compact('locales', 'currentSort', 'ubicaciones', 'ubicacionesConteo', 'tipos'));
})->name('venta');

Route::get('/alquiler', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->where('operacion', 'alquiler');
    
    if ($request->has('ubicacion') && !empty($request->ubicacion)) {
        $query->whereRaw('LOWER(distrito) LIKE LOWER(?)', ['%' . $request->ubicacion . '%']);
    }

    if ($request->has('tipo') && !empty($request->tipo) && $request->tipo !== 'Tipo de propiedad') {
        $termMapping = [
            'Departamentos' => 'Departamento',
            'Casas / Casa completa' => 'Casa',
            'Habitaciones' => 'Habitaci',
            'Locales' => 'Local',
            'Oficinas y Consultorios' => 'Oficina',
            'Terrenos' => 'Terreno',
            'Depósitos' => 'Depósito',
            'Fincas' => 'Finca',
            'Haciendas' => 'Hacienda',
            'Parqueaderos' => 'Parqueadero'
        ];
        $searchTerm = $termMapping[$request->tipo] ?? rtrim($request->tipo);
        $query->where('tipo_propiedad', 'LIKE', '%' . $searchTerm . '%');
    }

    if ($request->has('min_precio') && is_numeric($request->min_precio)) {
        $query->where('precio_mensual', '>=', $request->min_precio);
    }

    if ($request->has('max_precio') && is_numeric($request->max_precio)) {
        $query->where('precio_mensual', '<=', $request->max_precio);
    }

    $sort = $request->query('sort', 'mas_recientes');
    switch ($sort) {
        case 'menor_precio':
            $query->orderBy('precio_mensual', 'asc');
            break;
        case 'mayor_precio':
            $query->orderBy('precio_mensual', 'desc');
            break;
        case 'mas_amplios':
            $query->orderBy('area', 'desc');
            break;
        case 'mas_pequenos':
            $query->orderBy('area', 'asc');
            break;
    }
    
    $locales = $query->get();
    $currentSort = $sort;
    $ubicaciones = \App\Models\Local::withoutGlobalScopes()->where('estado', 'disponible')->whereNotNull('distrito')->distinct()->pluck('distrito');
    
    $ubicacionesConteo = \App\Models\Local::withoutGlobalScopes()
        ->where('estado', 'disponible')
        ->where('operacion', 'alquiler')
        ->whereNotNull('distrito')
        ->select('distrito', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
        ->groupBy('distrito')
        ->orderByDesc('total')
        ->get();
    
    $tipos = [
        'Departamentos',
        'Casas / Casa completa',
        'Habitaciones',
        'Locales',
        'Oficinas y Consultorios',
        'Terrenos',
        'Depósitos',
        'Fincas',
        'Haciendas',
        'Parqueaderos'
    ];
    
    return view('alquiler', compact('locales', 'currentSort', 'ubicaciones', 'ubicacionesConteo', 'tipos'));
})->name('alquiler');

Route::get('/publicar', function () {
    return view('publicar');
})->name('publicar');

Route::get('/registro-plan', function () {
    return view('registro-plan');
})->name('registro-plan');

Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

Route::get('/checkout/success', function () {
    return view('checkout-success');
})->middleware('auth')->name('checkout.success');

// Rutas de paneles administrativos en Blade (Estructura Base)

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    
    // Panel Arrendatario (Cliente)
    Route::get('/mis-contratos', function () {
        return "Panel Arrendatario: Ver contratos y pagos";
    })->name('arrendatario.contratos');

    // Panel Propietario (Tenant)
    Route::middleware('tenant')->group(function () {
        Route::get('/mi-panel', function () {
            return "Panel Propietario: CRUD Locales, Contratos, Reportes";
        })->name('propietario.panel');
    });
});

require __DIR__.'/auth.php';
