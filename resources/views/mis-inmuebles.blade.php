@extends('layouts.dashboard')

@section('title', 'Mis Propiedades')
@section('header_title', 'Mis Propiedades')

@section('content')
@php
    $tenantInfo = auth()->user()->tenant ?? null;
    $isFreePlanFullLocal = false;
    if ($tenantInfo) {
        $count = \App\Models\Local::where('tenant_id', $tenantInfo->id)->count();
        $isFreePlanFullLocal = ($tenantInfo->plan === 'Gratuito' && $count >= 1);
    }
@endphp
<div x-data="misInmuebles()"> <!-- Grid vs List view toggle state could go here, defaulting to grid for layout compatibility -->

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Tus Propiedades Publicadas</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestiona tu portafolio de propiedades y sus contratos.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <span class="text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hidden md:inline-block">
                {{ $locales->count() }} Propiedades
            </span>
            @if($isFreePlanFullLocal)
            <button type="button" @click="$data.showUpgradeModal = true" class="w-full sm:w-auto bg-slate-300 dark:bg-slate-700 text-slate-500 dark:text-slate-400 font-bold py-2.5 px-5 rounded-xl transition-all shadow-sm flex justify-center items-center gap-2 border border-slate-300 dark:border-slate-700 cursor-not-allowed">
                <i class="fa-solid fa-plus text-sm"></i> Nuevo Local
            </button>
            @else
            <a href="/publicar-local" class="w-full sm:w-auto bg-primary-600 dark:bg-primary-500 text-white font-bold py-2.5 px-5 rounded-xl hover:bg-primary-700 dark:hover:bg-primary-600 transition-all shadow-sm flex justify-center items-center gap-2 border border-primary-600 dark:border-primary-500">
                <i class="fa-solid fa-plus text-sm"></i> Nuevo Local
            </a>
            @endif
        </div>
    </div>

    <!-- Estado: Con Inmuebles -->
    @if ($locales->count() > 0)
    <div class="space-y-6">
        
        <!-- Controls Bar -->
        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-800 p-4 rounded-xl flex flex-col md:flex-row gap-4 items-center justify-between shadow-sm">
            <div class="relative w-full md:w-auto md:min-w-[300px]">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="search" placeholder="Buscar local por nombre o dirección..." class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition-shadow">
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <select x-model="status" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer">
                    <option value="">Todos los estados</option>
                    <option value="disponible">Disponibles</option>
                    <option value="alquilado">Alquilados</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($locales as $local)
            <div x-show="isVisible({{ $local->id }})" class="group bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 hover:border-primary-300 dark:hover:border-primary-700/50 hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col pt-1">
                <div class="h-44 relative bg-slate-100 dark:bg-slate-800 overflow-hidden mx-1 rounded-t-xl"
                 x-data="{ 
                     currentIndex: 0, 
                     images: {{ json_encode($local->imagenes && count($local->imagenes) > 0 ? $local->imagenes : ['https://images.unsplash.com/photo-1541888081662-8e108502f924?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80']) }},
                     next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
                     prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
                 }">
                <template x-for="(image, index) in images" :key="index">
                    <img x-show="currentIndex === index" :src="image" class="w-full h-full object-cover absolute inset-0 transition-all duration-500 transform group-hover:scale-105" alt="{{ $local->titulo }}">
                </template>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                <button @click.prevent="prev()" x-show="images.length > 1" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white text-slate-800 dark:text-white w-7 h-7 rounded-full flex items-center justify-center transition shadow-md z-10 text-[10px] opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button @click.prevent="next()" x-show="images.length > 1" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white text-slate-800 dark:text-white w-7 h-7 rounded-full flex items-center justify-center transition shadow-md z-10 text-[10px] opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                
                @if ($local->estado === 'alquilado' || $local->estado === 'arrendado')
                <div class="absolute top-3 right-3 bg-amber-400/90 backdrop-blur-sm text-amber-950 text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm flex items-center gap-1.5 z-10 border border-amber-500/20 uppercase tracking-wider">
                    <i class="fa-solid fa-key"></i> Arrendado
                </div>
                @else
                <div class="absolute top-3 right-3 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm flex items-center gap-1.5 z-10 border border-emerald-600/20 uppercase tracking-wider">
                    <i class="fa-solid fa-circle-check"></i> Disponible
                </div>
                @endif
                
                <!-- Operation badge inside image -->
                 <div class="absolute bottom-3 left-3 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded-md shadow-sm z-10 border border-white/10 uppercase tracking-wider {{ $local->operacion == 'venta' ? 'text-primary-300' : 'text-accent' }}">
                    {{ $local->operacion }}
                </div>
            </div>
                <div class="p-5 flex-1 flex flex-col bg-white dark:bg-darkcard z-20 relative">
                    <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1.5 line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" title="{{ $local->titulo }}">{{ $local->titulo }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-xs mb-4 flex items-start gap-1.5 line-clamp-1 h-4">
                        <i class="fa-solid fa-location-dot mt-[2px] text-slate-400 opacity-70"></i> 
                        {{ $local->distrito }}, {{ $local->direccion }}
                    </p>
                    
                    <div class="flex justify-between items-center text-sm mb-4 border-b border-slate-100 dark:border-slate-800 pb-4 mt-auto">
                        <div class="font-black text-slate-800 dark:text-white text-lg tracking-tight">
                            {{ $local->operacion == 'venta' ? 'USD' : 'S/' }} {{ number_format($local->precio_mensual, 2) }} 
                            <span class="text-[10px] font-medium text-slate-500 tracking-normal uppercase ml-0.5">{{ $local->operacion == 'venta' ? 'total' : '/ mes' }}</span>
                        </div>
                        <div class="text-slate-500 dark:text-slate-400 text-xs font-semibold bg-slate-50 dark:bg-slate-800 px-2 py-1 rounded border border-slate-100 dark:border-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-maximize opacity-70"></i> {{ $local->area ?? '--' }} m²
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap justify-between gap-1.5 text-xs font-medium">
                        <a href="{{ route('detalle-local', $local->id) }}" target="_blank" class="flex-1 min-w-[30%] flex justify-center items-center gap-1.5 px-2 py-2 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <i class="fa-solid fa-eye text-xs"></i> <span class="hidden sm:inline">Ver</span>
                        </a>
                        
                        @if ($local->estado === 'disponible')
                        <a href="{{ route('dashboard.locales.edit', $local) }}" class="flex-1 min-w-[30%] flex justify-center items-center gap-1.5 px-2 py-2 bg-slate-50 dark:bg-slate-800 text-primary-600 dark:text-primary-400 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:border-primary-200 dark:hover:border-primary-800/50 transition-colors">
                            <i class="fa-solid fa-pen text-xs"></i> <span class="hidden sm:inline">Editar</span>
                        </a>
                        
                        <form id="rent-form-{{ $local->id }}" action="{{ route('dashboard.locales.marcar-alquilado', $local) }}" method="POST" class="flex-1 min-w-[30%]">
                            @csrf
                            <input type="hidden" name="inquilino_nombre" id="inquilino-input-{{ $local->id }}">
                            <button type="button" @click="promptRentar('rent-form-{{ $local->id }}', 'inquilino-input-{{ $local->id }}')" class="w-full flex justify-center items-center gap-1.5 px-2 py-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-500 border border-amber-200 dark:border-amber-800 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors" title="Marcar Alquilado">
                                <i class="fa-solid fa-key text-xs"></i> <span class="hidden sm:inline">Alquilar</span>
                            </button>
                        </form>
                        
                        <form id="delete-form-{{ $local->id }}" action="{{ route('dashboard.locales.destroy', $local) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="confirmDelete('delete-form-{{ $local->id }}')" class="flex items-center justify-center w-[34px] h-[34px] bg-red-50 dark:bg-red-900/10 text-red-500 border border-red-100 dark:border-red-900/30 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 hover:border-red-200 dark:hover:border-red-800 transition-colors shrink-0" title="Eliminar">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </button>
                        </form>

                        @elseif (in_array($local->estado, ['alquilado', 'arrendado']))
                        <form id="liberar-form-{{ $local->id }}" action="{{ route('dashboard.locales.marcar-disponible', $local) }}" method="POST" class="flex-1 min-w-[60%]">
                            @csrf
                            <button type="button" @click="promptLiberar('liberar-form-{{ $local->id }}')" class="w-full flex justify-center items-center gap-1.5 px-2 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-500 border border-emerald-200 dark:border-emerald-800 rounded-lg hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors" title="Volver a Disponible">
                                <i class="fa-solid fa-unlock text-xs"></i> <span>Liberar Propiedad</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
        
        <!-- Empty States (Alpine JS Controlled) -->
        <div x-show="filteredCount === 0" class="py-16 px-4 text-center mt-6 bg-white dark:bg-darkcard rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 shadow-sm" style="display: none;" x-cloak>
            
            <!-- Message if there are no properties based on search string -->
            <div x-show="status === '' && search !== ''">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100 dark:border-slate-800">
                    <i class="fa-solid fa-magnifying-glass text-3xl text-slate-300 dark:text-slate-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">No hay propiedades disponibles en ese lugar</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Intente más tarde o modifique los términos de su búsqueda.</p>
            </div>
            
            <!-- Message if status == disponible -->
            <div x-show="status === 'disponible'">
                <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mx-auto mb-5 border border-emerald-100 dark:border-emerald-800/50">
                    <i class="fa-regular fa-building text-3xl text-emerald-400 dark:text-emerald-500/80"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">Propiedades no disponibles</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">No tienes propiedades con estado "Disponible" configuradas actualmente.</p>
            </div>
            
            <!-- Message if status == alquilado -->
            <div x-show="status === 'alquilado'">
                <div class="w-20 h-20 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center mx-auto mb-5 border border-amber-100 dark:border-amber-800/50">
                    <i class="fa-solid fa-key text-3xl text-amber-400 dark:text-amber-500/80"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 dark:text-slate-300 mb-2">Por el momento no hay propiedades para alquilar</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">No tienes propiedades con estado "Alquilado" en tu portafolio.</p>
            </div>
            
        </div>
    </div>
    @else

    <!-- Estado: Vacío -->
    <div class="flex flex-col items-center justify-center py-20 px-4 bg-white dark:bg-darkcard rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 text-center shadow-sm">
        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-800">
            <i class="fa-solid fa-building-circle-xmark text-4xl text-slate-300 dark:text-slate-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Aún no has registrado una propiedad</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-md text-sm leading-relaxed">Parece que todavía no tienes ninguna propiedad en nuestra plataforma. Sube tu primera propiedad para empezar.</p>
        <a href="/publicar-local" class="bg-primary-600 dark:bg-primary-500 text-white font-bold py-3 px-8 rounded-xl hover:bg-primary-700 dark:hover:bg-primary-600 transition-all flex items-center gap-2 shadow-sm border border-primary-600 dark:border-primary-500 hover:shadow-md focus:ring-4 focus:ring-primary-500/20">
            <i class="fa-solid fa-plus text-sm"></i> Publicar mi primera propiedad
        </a>
    </div>
    @endif
    
    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-8 w-full max-w-md border border-slate-200 dark:border-slate-700 transform transition-all text-center" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-orange-50 dark:bg-orange-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border border-orange-100 dark:border-orange-800/50 shadow-inner">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-orange-500 dark:text-orange-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Eliminar Propiedad</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">¿Estás seguro que deseas eliminar esta propiedad? Esta acción es irreversible y se borrarán todos los datos y fotos asociadas.</p>
            
            <div class="flex items-center justify-center gap-4">
                <button type="button" @click="showDeleteModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full">
                    Cancelar
                </button>
                <button type="button" @click="executeDelete()" class="px-6 py-2.5 text-sm font-bold bg-orange-500 hover:bg-orange-600 text-white rounded-xl shadow-sm hover:shadow transition-all border border-orange-600 focus:ring-4 focus:ring-orange-500/20 w-full flex justify-center items-center gap-2">
                    <i class="fa-solid fa-trash-can"></i> Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    <!-- Rent Confirmation Modal -->
    <div x-show="showRentModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl w-full max-w-lg border border-slate-200 dark:border-slate-700 transform transition-all text-left overflow-hidden" @click.away="showRentModal = false">
            <div class="p-8">
                <div class="w-16 h-16 bg-amber-50 dark:bg-amber-900/30 rounded-full flex items-center justify-center mb-5 border border-amber-100 dark:border-amber-800/50 shadow-inner">
                    <i class="fa-solid fa-file-signature text-3xl text-amber-500 dark:text-amber-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Marcar Propiedad como Alquilada</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-6 text-sm leading-relaxed">
                    Estás a punto de ocultar esta propiedad de las listas públicas. Por favor ingresa el nombre de la persona o empresa con la que cerraste el trato.
                </p>
                
                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/30 rounded-xl p-4 mb-6 flex gap-3 items-start">
                    <i class="fa-solid fa-circle-info text-indigo-500 mt-0.5"></i>
                    <p class="text-sm font-medium text-indigo-800 dark:text-indigo-300">
                        <strong class="block mb-1 text-indigo-900 dark:text-indigo-200">Aviso importante</strong>
                        Te sugerimos verificar bien que el inquilino haya cumplido con los pagos acordados (garantía, primer mes) y que el contrato esté firmado antes de registrar esta acción.
                    </p>
                </div>
                
                <div class="mb-6">
                    <label for="tenantNameInput" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nombre del Inquilino / Arrendatario:</label>
                    <input type="text" x-model="tenantName" id="tenantNameInput" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all shadow-sm" placeholder="Ej. Juan Pérez o Empresa XYZ S.A.C." @keydown.enter="executeRentar()">
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-8">
                    <button type="button" @click="showRentModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full sm:w-auto">
                        Cancelar
                    </button>
                    <button type="button" @click="executeRentar()" class="px-6 py-2.5 text-sm font-bold bg-amber-500 hover:bg-amber-600 text-white rounded-xl shadow-sm hover:shadow transition-all border border-amber-600 focus:ring-4 focus:ring-amber-500/20 w-full sm:w-auto flex justify-center items-center gap-2">
                        <i class="fa-solid fa-check"></i> <span x-text="tenantName.trim() === '' ? 'Marcar como alquilado sin nombre' : 'Confirmar arrendamiento'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Liberar Confirmation Modal -->
    <div x-show="showLiberarModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl w-full max-w-lg border border-red-500/50 transform transition-all text-left overflow-hidden ring-4 ring-red-500/10" @click.away="showLiberarModal = false">
            <div class="bg-red-50 dark:bg-red-900/20 p-6 border-b border-red-100 dark:border-red-900/50 flex items-start gap-4">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-red-800 dark:text-red-300">¡Atención! Propiedad en Alquiler</h3>
                    <p class="text-red-600 dark:text-red-400 text-sm mt-1">
                        Estás a punto de volver a habilitar al público esta propiedad.
                    </p>
                </div>
            </div>
            
            <div class="p-6">
                <p class="text-slate-700 dark:text-slate-300 mb-4 font-medium text-sm leading-relaxed">
                    <strong>Recuerda que la propiedad está actualmente alquilada.</strong>
                </p>
                <div class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-4 rounded-xl text-sm text-slate-600 dark:text-slate-400 mb-6 space-y-2">
                    <p><i class="fa-solid fa-circle-check text-emerald-500 text-xs mr-1"></i> Si cambias de estado, asumes todas las responsabilidades ya que se asume que te han cancelado y han firmado contrato temporal.</p>
                    <p><i class="fa-solid fa-clock-rotate-left text-indigo-500 text-xs mr-1"></i> Si el periodo cumple el mes y no renuevas el contrato, en la lista de gestión del Dashboard se marcará como vencida.</p>
                    <p><i class="fa-solid fa-globe text-blue-500 text-xs mr-1"></i> Al confirmar, el inmueble se mostrará de inmediato a todos los que ingresen al portal buscando alquiler/venta.</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-4">
                    <button type="button" @click="showLiberarModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full sm:w-auto">
                        Conservar Alquilado
                    </button>
                    <button type="button" @click="executeLiberar()" class="px-6 py-2.5 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-sm hover:shadow transition-all border border-red-700 focus:ring-4 focus:ring-red-500/20 w-full sm:w-auto flex justify-center items-center gap-2">
                        <i class="fa-solid fa-unlock"></i> Sí, liberar propiedad
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('misInmuebles', () => ({
        search: '',
        status: '',
        view: 'grid',
        showDeleteModal: false,
        showRentModal: false,
        showLiberarModal: false,
        tenantName: '',
        rentFormToSubmitId: null,
        rentInquilinoInputId: null,
        liberarFormToSubmitId: null,
        formToSubmitId: null,
        locales: [
            @foreach($locales as $local)
            { 
                id: {{ $local->id }}, 
                title: {!! json_encode(strtolower($local->titulo)) !!}, 
                address: {!! json_encode(strtolower($local->distrito . ', ' . $local->direccion)) !!}, 
                state: '{{ in_array(strtolower($local->estado), ['alquilado', 'arrendado']) ? 'alquilado' : 'disponible' }}' 
            },
            @endforeach
        ],
        get filteredCount() {
            return this.locales.filter(l => {
                const searchLower = this.search.toLowerCase();
                const matchesSearch = this.search === '' || l.title.includes(searchLower) || l.address.includes(searchLower);
                const matchesStatus = this.status === '' || this.status === l.state;
                return matchesSearch && matchesStatus;
            }).length;
        },
        isVisible(id) {
            const l = this.locales.find(loc => loc.id === id);
            if (!l) return false;
            const searchLower = this.search.toLowerCase();
            const matchesSearch = this.search === '' || l.title.includes(searchLower) || l.address.includes(searchLower);
            const matchesStatus = this.status === '' || this.status === l.state;
            return matchesSearch && matchesStatus;
        },
        confirmDelete(formId) {
            this.formToSubmitId = formId;
            this.showDeleteModal = true;
        },
        executeDelete() {
            if(this.formToSubmitId) {
                document.getElementById(this.formToSubmitId).submit();
            }
        },
        promptRentar(formId, inputId) {
            this.rentFormToSubmitId = formId;
            this.rentInquilinoInputId = inputId;
            this.tenantName = '';
            this.showRentModal = true;
            // Focus on input after a slight delay to allow modal to render
            setTimeout(() => {
                const input = document.getElementById('tenantNameInput');
                if(input) input.focus();
            }, 100);
        },
        executeRentar() {
            if (this.rentFormToSubmitId && this.rentInquilinoInputId) {
                document.getElementById(this.rentInquilinoInputId).value = this.tenantName;
                document.getElementById(this.rentFormToSubmitId).submit();
            }
        },
        promptLiberar(formId) {
            this.liberarFormToSubmitId = formId;
            this.showLiberarModal = true;
        },
        executeLiberar() {
            if (this.liberarFormToSubmitId) {
                document.getElementById(this.liberarFormToSubmitId).submit();
            }
        }
    }));
});
</script>
@endsection
