@extends('layouts.dashboard')

@section('title', 'Inicio')
@section('header_title', 'Hola de nuevo, ' . (Auth::user()->name ?? 'Administrador'))

@section('content')
<div class="space-y-6" x-data="{ showRenovarAlert: false, showDetalleAlert: false, selectedAlquiler: {} }">

    <!-- Modal Renovación -->
    <div x-show="showRenovarAlert" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-6 sm:p-8 max-w-sm w-full text-center border-t-4 border-emerald-500 transform transition-all" @click.away="showRenovarAlert = false">
            <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center text-3xl mb-4">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Aviso de Renovación</h3>
            <p class="text-slate-600 dark:text-slate-300 text-sm mb-6 font-medium">
                La renovación se realizará después del depósito del inquilino. Por favor anticipar pagar para poder confirmar la renovación.
            </p>
            <button @click="showRenovarAlert = false" class="w-full shadow-lg shadow-emerald-500/30 bg-emerald-500 hover:bg-emerald-400 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded-xl transition-all active:scale-95">
                Entendido
            </button>
        </div>
    </div>

    <!-- Modal Detalles -->
    <div x-show="showDetalleAlert" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-sm w-full text-center border-t-4 border-blue-500 transform transition-all" @click.away="showDetalleAlert = false">
            <div class="mx-auto w-16 h-16 rounded-full bg-white dark:bg-darkcard text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl mb-4 shadow-sm border border-blue-100 dark:border-blue-800">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <h3 class="text-xl font-bold text-blue-800 dark:text-blue-300 mb-3" x-text="selectedAlquiler.titulo"></h3>
            <p class="text-blue-700 dark:text-blue-200 text-sm mb-6 font-medium leading-relaxed">
                Local alquilado por <span class="font-bold text-blue-900 dark:text-white" x-text="selectedAlquiler.inquilino"></span>, alquilado el día <span class="font-bold text-blue-900 dark:text-white" x-text="selectedAlquiler.alquilado_en"></span> y vence el día <span class="font-bold text-blue-900 dark:text-white" x-text="selectedAlquiler.vence"></span>.
            </p>
            <button @click="showDetalleAlert = false" class="w-full shadow-lg shadow-blue-500/30 bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-xl transition-all active:scale-95 hover:shadow-blue-500/50">
                Aceptar
            </button>
        </div>
    </div>

    <!-- Mensaje de Bienvenida y Acciones Rápidas -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-white dark:bg-darkcard p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 transition-colors duration-200">
        <div>
            <h2 class="text-slate-900 dark:text-white text-2xl font-bold tracking-tight mb-2">Resumen de tus propiedades</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Aquí tienes un vistazo rápido al estado de tus alquileres y cobros de este mes.</p>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="/publicar-local" class="flex-1 md:flex-none bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 text-sm focus:ring-2 focus:ring-slate-400 focus:outline-none focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                <i class="fa-solid fa-file-contract"></i> Nuevo Contrato
            </a>
            <a href="/publicar-local" class="flex-1 md:flex-none bg-primary-600 dark:bg-primary-500 text-white hover:bg-primary-700 dark:hover:bg-primary-600 font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                <i class="fa-solid fa-plus"></i> Publicar Inmueble
            </a>
        </div>
    </div>

    <!-- Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="group bg-white dark:bg-darkcard rounded-2xl shadow-sm p-6 border border-slate-200 dark:border-slate-800 hover:border-primary-300 dark:hover:border-primary-800 transition-all duration-300 cursor-pointer">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-2 uppercase tracking-wider">Total Inquilinos</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $totalInquilinos }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xl shadow-inner border border-primary-100 dark:border-primary-800/50 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-3 font-semibold flex items-center gap-1.5"><i class="fa-solid fa-arrow-trend-up"></i> +1 este mes</p>
        </div>

        <!-- Card 2 -->
        <div class="group bg-white dark:bg-darkcard rounded-2xl shadow-sm p-6 border border-slate-200 dark:border-slate-800 hover:border-amber-300 dark:hover:border-amber-800/50 transition-all duration-300 cursor-pointer">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-2 uppercase tracking-wider">Tasa de Ocupación</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">{{ $tasaOcupacion }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center text-xl shadow-inner border border-amber-100 dark:border-amber-800/50 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-building-user"></i>
                </div>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-3 font-medium flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-amber-500/70"></i> {{ $totalAlquiladosCalculo }} de {{ $totalLocalesCalculo }} locales alquilados</p>
        </div>

        <!-- Card 3 -->
        <div class="group bg-white dark:bg-darkcard rounded-2xl shadow-sm p-6 border border-slate-200 dark:border-slate-800 hover:border-emerald-300 dark:hover:border-emerald-800/50 transition-all duration-300 cursor-pointer" title="En implementación el módulo de facturación">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-2 uppercase tracking-wider">Ingresos del Mes</p>
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2"><span class="text-xl text-slate-400 dark:text-slate-500 font-medium mr-1">S/</span>0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-xl shadow-inner border border-emerald-100 dark:border-emerald-800/50 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-3 font-semibold flex items-center gap-1.5"><i class="fa-solid fa-check-circle"></i> Todo pagado al día</p>
        </div>

        <!-- Card 4 -->
        <div class="group bg-slate-900 dark:bg-primary-900/30 text-white rounded-2xl shadow-md p-6 relative overflow-hidden ring-1 ring-slate-800 dark:ring-primary-800/50 cursor-pointer hover:shadow-lg transition-all duration-300" title="En implementación el módulo de facturación">
            <div class="absolute -right-4 -bottom-4 opacity-[0.07] dark:opacity-10 group-hover:scale-110 duration-500 transition-transform">
                <i class="fa-solid fa-triangle-exclamation text-8xl"></i>
            </div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-slate-400 dark:text-primary-300/80 text-sm font-medium mb-2 uppercase tracking-wider">Cobros Pendientes</p>
                    <h3 class="text-3xl font-extrabold text-white mb-2">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-xl shadow-inner border border-red-500/30 group-hover:rotate-12 transition-transform duration-300">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
            <p class="text-xs text-red-300 mt-3 font-medium relative z-10 flex items-center gap-1.5"><i class="fa-solid fa-clock-rotate-left"></i> S/ 0 retrasado</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Próximos Vencimientos y Tareas -->
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 xl:p-8 flex flex-col h-full transition-colors duration-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-regular fa-calendar-xmark text-amber-500"></i> Contratos por Vencer
                </h3>
                <a href="#" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold hover:underline">Ver todos</a>
            </div>
            
            <div class="space-y-4 flex-1">
                <!-- Item 1 -->
                <div class="group flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 border border-amber-200/60 dark:border-amber-900/30 bg-amber-50/50 dark:bg-amber-900/10 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                    <div class="flex items-start gap-4 mb-4 sm:mb-0">
                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-500 flex items-center justify-center font-bold text-lg border border-amber-200 dark:border-amber-800 shadow-sm shrink-0">
                            AL
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm sm:text-base leading-tight mb-1 group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">Almacén Industrial Callao</h4>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Inquilino: <span class="font-medium text-slate-600 dark:text-slate-300">Logística del Sur SAC</span></p>
                            <div class="flex items-center gap-1.5 mt-2">
                                <span class="flex w-2 h-2 rounded-full bg-amber-500"></span>
                                <p class="text-xs sm:text-sm text-amber-600 dark:text-amber-500 font-bold">Vence en 15 días (10 Mar 2026)</p>
                            </div>
                        </div>
                    </div>
                    <button @click="showRenovarAlert = true" class="w-full sm:w-auto text-amber-700 dark:text-amber-400 hover:text-white bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-600 dark:hover:bg-amber-500 px-4 py-2 rounded-lg text-sm font-bold transition-colors border border-amber-200 dark:border-amber-800/50 hover:border-transparent text-center">Renovar</button>
                </div>
                

                
                <!-- Dynamic Items -->
                @if(isset($alquilados))
                    @foreach($alquilados as $alquiler)
                        @php
                            $vence = $alquiler->alquilado_en ? $alquiler->alquilado_en->addMonth() : now()->addMonth();
                            $diasParaVencer = now()->diffInDays($vence, false);
                            $isVencido = $diasParaVencer < 0;
                            // Generar iniciales (ej. Departamento Centro -> DC)
                            $words = explode(' ', $alquiler->titulo);
                            $iniciales = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        @endphp
                        <div class="group flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $isVencido ? 'border-red-200/60 dark:border-red-900/30 bg-red-50/30' : '' }}">
                            <div class="flex items-start gap-4 mb-4 sm:mb-0">
                                <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg border border-indigo-200 dark:border-indigo-800 shadow-sm shrink-0">
                                    {{ $iniciales }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm sm:text-base leading-tight mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $alquiler->titulo }}</h4>
                                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Inquilino: <span class="font-medium text-slate-600 dark:text-slate-300">{{ $alquiler->inquilino_nombre ?? 'No registrado' }}</span></p>
                                    <div class="flex items-center gap-1.5 mt-2">
                                        <span class="flex w-2 h-2 rounded-full {{ $isVencido ? 'bg-red-500' : 'bg-indigo-400' }}"></span>
                                        <p class="text-xs sm:text-sm {{ $isVencido ? 'text-red-500 font-bold' : 'text-slate-500 dark:text-slate-400 font-medium' }}">
                                            {{ $isVencido ? 'Vencido hace ' . abs(intval($diasParaVencer)) . ' días' : 'Vence en ' . intval($diasParaVencer) . ' días (' . $vence->translatedFormat('d M Y') . ')' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <button @click="selectedAlquiler = { titulo: '{{ addslashes($alquiler->titulo) }}', inquilino: '{{ addslashes($alquiler->inquilino_nombre ?? 'No registrado') }}', alquilado_en: '{{ $alquiler->alquilado_en ? $alquiler->alquilado_en->translatedFormat('d M Y') : 'N/A' }}', vence: '{{ $vence->translatedFormat('d M Y') }}' }; showDetalleAlert = true" class="w-full sm:w-auto text-white hover:text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm hover:shadow text-center mt-3 sm:mt-0 flex items-center justify-center">Detalles</button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Última Actividad (Pagos) -->
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 xl:p-8 flex flex-col h-full transition-colors duration-200" title="En implementación el módulo de facturación">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-8 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary-500"></i> Últimos Pagos Recibidos
            </h3>
            
            <div class="space-y-0 flex-1 relative pl-2">
                <!-- Vertical Line -->
                <div class="absolute left-6 top-6 bottom-6 w-0.5 bg-slate-100 dark:bg-slate-800"></div>
                
                <!-- Activity 1 -->
                <div class="relative pl-12 py-4">
                    <div class="absolute left-0 top-5 w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border-4 border-white dark:border-darkcard z-10 shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Transferencia Bancaria BCP</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Oficina Prime San Isidro</p>
                            </div>
                            <span class="inline-flex text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 shrink-0 self-start border border-emerald-200 dark:border-emerald-800/60">+ S/ 0.00</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700/50">
                            <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Hoy, 10:30 AM</p>
                        </div>
                    </div>
                </div>

                <!-- Activity 2 -->
                <div class="relative pl-12 py-4">
                    <div class="absolute left-0 top-5 w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border-4 border-white dark:border-darkcard z-10 shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Depósito en Efectivo BBVA</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Local Av. Larco</p>
                            </div>
                            <span class="inline-flex text-xs font-bold px-2.5 py-1 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 shrink-0 self-start border border-emerald-200 dark:border-emerald-800/60">+ S/ 0.00</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700/50">
                            <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ayer, 16:45 PM</p>
                        </div>
                    </div>
                </div>
                
                <!-- Activity 3 -->
                <div class="relative pl-12 py-4">
                    <div class="absolute left-0 top-5 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400 flex items-center justify-center border-4 border-white dark:border-darkcard z-10 shadow-sm">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                    <div class="bg-red-50/50 dark:bg-red-900/10 p-4 rounded-xl border border-red-100 dark:border-red-900/30 hover:border-red-200 dark:hover:border-red-800/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2">
                            <div>
                                <h4 class="font-bold text-sm text-slate-800 dark:text-slate-200">Tarjeta Rechazada</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Stand C.C. Arenales</p>
                            </div>
                            <span class="inline-flex text-xs font-bold px-2.5 py-1 rounded-md bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 shrink-0 self-start border border-red-200 dark:border-red-800/60">S/ 0.00</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 pt-3 border-t border-red-100 dark:border-red-900/30">
                            <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                            <p class="text-[11px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">20 Feb, 09:00 AM</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="/ingresos" class="mt-6 block text-center text-sm font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 py-3 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors border border-primary-100 dark:border-primary-800/50">Ver historial completo en Billetera</a>
        </div>
        
    </div>
</div>
@endsection
