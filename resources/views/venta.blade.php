@extends('layouts.public')

@section('title', 'Locales en Venta')

@section('content')
<!-- Filter Bar -->
<div class="bg-white dark:bg-darkcard border-b border-slate-200 dark:border-slate-800 sticky top-16 z-30 shadow-sm transition-colors duration-200">
    <div class="container mx-auto px-4 py-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-grow md:max-w-xs" x-data='{
            search: "{!! addslashes(request("ubicacion")) !!}",
            open: false,
            ubicaciones: {!! json_encode($ubicaciones ?? []) !!},
            get filtered() {
                if(this.search === "") return [];
                return this.ubicaciones.filter(u => u.toLowerCase().includes(this.search.toLowerCase()));
            },
            select(ubicacion) {
                this.search = ubicacion;
                this.open = false;
                this.submitSearch();
            },
            submitSearch() {
                const url = new URL(window.location.href);
                url.searchParams.set("ubicacion", this.search);
                if(!this.search) url.searchParams.delete("ubicacion");
                window.location.href = url.toString();
            }
        }'>
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 z-10" style="top: 20px;"></i>
            <input type="text" x-model="search" @input="open = true" @focus="open = true" @click.away="open = false" @keydown.enter="submitSearch()" placeholder="Introduce ubicación" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all shadow-sm">
            
            <div x-show="open && search !== ''" class="absolute z-50 w-full mt-2 bg-white dark:bg-darkcard rounded-xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700 top-full" style="display: none;" x-cloak>
                <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">Áreas</div>
                <ul class="max-h-60 overflow-y-auto">
                    <template x-for="item in filtered" :key="item">
                        <li @click="select(item)" class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer text-slate-700 dark:text-slate-300 font-medium text-sm transition-colors" x-text="item"></li>
                    </template>
                    <li x-show="filtered.length === 0" class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400 text-center font-medium">No hay ubicaciones</li>
                </ul>
            </div>
        </div>
        <select onchange="window.location.href=this.value" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500 shadow-sm cursor-pointer font-bold border-l-4 border-l-primary-500">
            <option value="{{ route('venta') }}" selected>Venta</option>
            <option value="{{ route('alquiler') }}">Alquiler</option>
        </select>
        <select @change="const url = new URL(window.location.href); if($event.target.value) url.searchParams.set('tipo', $event.target.value); else url.searchParams.delete('tipo'); window.location.href = url.toString();" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500 shadow-sm cursor-pointer">
            <option value="">Tipo de propiedad</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
            @endforeach
        </select>
        <div class="relative" x-data="{ open: false, min: '{{ request('min_precio') }}', max: '{{ request('max_precio') }}' }" @click.away="open = false">
            <button @click="open = !open" class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-4 py-2 text-sm outline-none hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2 shadow-sm transition-colors">
                Precio <i class="fa-solid fa-chevron-down text-[10px]" x-show="!open"></i><i class="fa-solid fa-chevron-up text-[10px]" x-show="open" style="display: none;"></i>
            </button>
            <div x-show="open" style="display: none;" class="absolute top-full mt-2 left-0 bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl p-5 z-50 w-80 ring-1 ring-black ring-opacity-5">
                <div class="flex gap-4 border-b border-slate-100 dark:border-slate-800 pb-5 mb-5">
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Mínimo</label>
                        <input type="number" x-model="min" placeholder="Mínimo" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Máximo</label>
                        <input type="number" x-model="max" placeholder="Máximo" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <button @click="min = ''; max = '';" class="text-slate-500 dark:text-slate-400 text-sm font-semibold hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Limpiar</button>
                    <button @click="const url = new URL(window.location.href); if(min) url.searchParams.set('min_precio', min); else url.searchParams.delete('min_precio'); if(max) url.searchParams.set('max_precio', max); else url.searchParams.delete('max_precio'); window.location.href = url.toString();" class="bg-primary-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 shadow-sm transition-colors">Aplicar</button>
                </div>
            </div>
        </div>
        <div x-data="{ openFilters: false }">
            <button @click="openFilters = true" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition-colors flex items-center gap-2 font-medium">
                <i class="fa-solid fa-sliders text-primary-500"></i> Filtros
            </button>
            
            <!-- Modal Overlay -->
            <div x-show="openFilters" style="display: none;" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center">
                <form action="{{ route('venta') }}" method="GET" @click.away="openFilters = false" class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-lg shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
                    @if(request('ubicacion')) <input type="hidden" name="ubicacion" value="{{ request('ubicacion') }}"> @endif
                    @if(request('tipo')) <input type="hidden" name="tipo" value="{{ request('tipo') }}"> @endif
                    @if(request('min_precio')) <input type="hidden" name="min_precio" value="{{ request('min_precio') }}"> @endif
                    @if(request('max_precio')) <input type="hidden" name="max_precio" value="{{ request('max_precio') }}"> @endif
                    
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">MÁS FILTROS</h2>
                        <button type="button" @click="openFilters = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Scrollable Content -->
                    <div class="p-6 overflow-y-auto flex-1 space-y-8 text-sm text-slate-700 dark:text-slate-300">
                        <!-- Baños -->
                        <div>
                            <h3 class="font-bold text-lg mb-3 border-b border-slate-200 dark:border-slate-800 pb-2 text-slate-800 dark:text-white">Baños</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> 1 baño</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> 2 baños</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> 3 baños</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> 4 a más baños</label>
                            </div>
                        </div>

                        <!-- Superficie -->
                        <div>
                            <h3 class="font-bold text-lg mb-3 border-b border-slate-200 dark:border-slate-800 pb-2 text-slate-800 dark:text-white">Superficie (m²)</h3>
                            <div class="flex gap-6 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="superficie_tipo" class="w-4 h-4 text-[#ef3b46] border-gray-300 dark:border-slate-600 dark:bg-slate-800 focus:ring-[#ef3b46]" checked> 
                                    <span class="text-[#ef3b46] font-bold">Área techada</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="superficie_tipo" class="w-4 h-4 border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-slate-600 dark:text-slate-400 focus:ring-slate-500"> 
                                    <span class="text-slate-600 dark:text-slate-400">Área total</span>
                                </label>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label class="block text-sm font-semibold mb-1 text-slate-600 dark:text-slate-400">Mínimo</label>
                                    <input type="number" placeholder="Desde m²" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-sm font-semibold mb-1 text-slate-600 dark:text-slate-400">Máximo</label>
                                    <input type="number" placeholder="Hasta m²" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Características Principales -->
                        <div>
                            <h3 class="font-bold text-lg mb-3 border-b border-slate-200 dark:border-slate-800 pb-2 text-slate-800 dark:text-white">Características Principales</h3>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> Estacionamiento</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> Seguridad / Vigilancia</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> Aire acondicionado</label>
                                <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 dark:bg-slate-800 text-primary-600 focus:ring-primary-500"> Amoblado</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer Buttons -->
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl flex justify-between items-center">
                        <button type="button" @click="openFilters = false" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-medium transition-colors">Cancelar</button>
                        <button type="submit" class="bg-primary-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors shadow-md">Ver resultados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal content styling (omitted complete inner replace for brevity since it's just classes, but will ensure it renders decently with base resets) -->

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 border-b border-slate-200 dark:border-slate-800 pb-4 gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="bg-primary-600/10 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400 text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">Venta</span>
                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Inversiones Inmobiliarias</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Locales en Venta</h1>
        </div>
        @php
            $sortMap = [
                'relevancia' => 'Relevancia',
                'mas_recientes' => 'Más recientes',
                'menor_precio' => 'Menor precio',
                'mayor_precio' => 'Mayor precio',
                'mas_amplios' => 'Más amplios',
                'mas_pequenos' => 'Más pequeños',
            ];
            $currentLabel = $sortMap[$currentSort ?? 'mas_recientes'] ?? 'Más recientes';
        @endphp
        <div class="relative" x-data="{ openSort: false, selected: '{{ $currentLabel }}' }">
            <button @click="openSort = !openSort" @click.away="openSort = false" class="bg-white dark:bg-darkcard border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-sm transition-colors font-medium">
                <i class="fa-solid fa-arrow-down-up-across-line text-primary-500"></i>
                Ordenar: <span x-text="selected" class="font-bold"></span>
            </button>
            <div x-show="openSort" style="display: none;" class="absolute right-0 mt-2 bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-700 shadow-xl py-2 w-56 z-20 rounded-xl text-sm text-left overflow-hidden ring-1 ring-black ring-opacity-5">
                <a href="?sort=relevancia" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Relevancia', 'text-slate-600 dark:text-slate-300': selected !== 'Relevancia' }">Relevancia</a>
                <a href="?sort=mas_recientes" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Más recientes', 'text-slate-600 dark:text-slate-300': selected !== 'Más recientes' }">Más recientes</a>
                <a href="?sort=menor_precio" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Menor precio', 'text-slate-600 dark:text-slate-300': selected !== 'Menor precio' }">Menor precio</a>
                <a href="?sort=mayor_precio" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Mayor precio', 'text-slate-600 dark:text-slate-300': selected !== 'Mayor precio' }">Mayor precio</a>
                <a href="?sort=mas_amplios" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Más amplios', 'text-slate-600 dark:text-slate-300': selected !== 'Más amplios' }">Más amplios</a>
                <a href="?sort=mas_pequenos" class="block px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors" :class="{ 'text-primary-600 dark:text-primary-400 font-bold bg-primary-50 dark:bg-primary-900/10': selected === 'Más pequeños', 'text-slate-600 dark:text-slate-300': selected !== 'Más pequeños' }">Más pequeños</a>
            </div>
        </div>
    </div>

    <!-- Results count & locations text (dynamic) -->
    <div class="flex gap-4 mb-8 text-sm overflow-x-auto pb-2 whitespace-nowrap hide-scrollbar">
        @foreach($ubicacionesConteo as $index => $ubicacionConteo)
        @php 
            $isActive = request('ubicacion') === $ubicacionConteo->distrito || (!request('ubicacion') && $index === 0);
        @endphp
        <a href="?ubicacion={{ urlencode($ubicacionConteo->distrito) }}" class="bg-white dark:bg-darkcard border {{ $isActive ? 'border-primary-500 dark:border-primary-500 shadow-md ring-1 ring-primary-500/20' : 'border-slate-200 dark:border-slate-800 shadow-sm' }} hover:border-primary-300 dark:hover:border-primary-700 rounded-lg px-6 py-3 flex items-center gap-3 transition-all cursor-pointer">
            <div class="w-2 h-2 rounded-full {{ $isActive ? 'bg-primary-500' : 'bg-slate-300 dark:bg-slate-600' }}"></div>
            <div>
                <div class="font-bold text-slate-800 dark:text-slate-200 capitalize">{{ $ubicacionConteo->distrito }}</div>
                <div class="text-slate-500 dark:text-slate-400 text-xs">{{ $ubicacionConteo->total }} {{ $ubicacionConteo->total == 1 ? 'Inmueble' : 'Inmuebles' }}</div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Listings -->
    <div class="space-y-6 max-w-5xl">
        @forelse($locales as $local)
        <div class="group bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col md:flex-row shadow-sm hover:shadow-xl hover:border-primary-200 dark:hover:border-primary-900/50 transition-all duration-300 overflow-hidden">
            <div class="md:w-2/5 relative h-56 md:h-auto bg-slate-100 dark:bg-slate-800 overflow-hidden"
                 x-data="{ 
                     currentIndex: 0, 
                     images: {{ json_encode($local->imagenes && count($local->imagenes) > 0 ? $local->imagenes : ['https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=600&h=400']) }},
                     next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
                     prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
                 }">
                <template x-for="(image, index) in images" :key="index">
                    <img x-show="currentIndex === index" :src="image" class="w-full h-full object-cover absolute inset-0 transition-all duration-500 transform group-hover:scale-105" alt="{{ $local->titulo }}">
                </template>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <button @click.prevent="prev()" x-show="images.length > 1" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 w-8 h-8 rounded-full flex items-center justify-center transition shadow-md z-10 opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </button>
                <button @click.prevent="next()" x-show="images.length > 1" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 w-8 h-8 rounded-full flex items-center justify-center transition shadow-md z-10 opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </button>
            </div>
            
            <div class="p-6 md:w-3/5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('detalle-local', $local->id) }}" class="block pr-6">
                            <h2 class="font-bold text-xl text-slate-800 dark:text-white line-clamp-2 leading-tight group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $local->titulo }}</h2>
                        </a>
                        <button class="text-slate-400 hover:text-primary-500 dark:hover:text-primary-400 transition-colors flex-shrink-0 mt-1">
                            <i class="fa-regular fa-bookmark text-lg"></i>
                        </button>
                    </div>
                    
                    <p class="text-2xl font-extrabold text-primary-600 dark:text-primary-400 my-2">USD {{ number_format($local->precio_mensual, 2) }} <span class="text-sm font-medium text-slate-400">total</span></p>
                    
                    <p class="text-sm text-slate-500 dark:text-slate-400 flex items-start gap-2 mb-2">
                        <i class="fa-solid fa-location-dot mt-0.5 text-slate-400"></i>
                        <span>{{ $local->distrito ?? 'Perú' }}{{ $local->direccion ? ', ' . $local->direccion : '' }}</span>
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 flex items-start gap-2 mb-4 font-medium">
                        <i class="fa-solid fa-building text-slate-400 mt-0.5"></i>
                        <span>{{ $local->tipo_propiedad ?? 'Tipo no especificado' }}</span>
                    </p>
                </div>
                
                <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-4 mt-auto">
                    <div class="flex items-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                        @if($local->area)
                            <span class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/50 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-maximize text-slate-400"></i> {{ $local->area }} m²</span>
                            @if($local->banos)
                            <span class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/50 px-3 py-1.5 rounded-lg"><i class="fa-solid fa-bath text-slate-400"></i> {{ $local->banos }}</span>
                            @endif
                        @endif
                    </div>
                    <a href="{{ route('detalle-local', $local->id) }}" class="inline-flex items-center justify-center bg-slate-900 dark:bg-primary-600 text-white hover:bg-slate-800 dark:hover:bg-primary-500 px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-colors">
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="w-full bg-white dark:bg-darkcard border-2 border-dashed border-slate-300 dark:border-slate-700/60 rounded-[2rem] p-12 md:p-16 flex flex-col items-center justify-center text-center transition-all hover:border-primary-400 dark:hover:border-primary-500/50 shadow-sm col-span-full my-8">
            <div class="relative w-24 h-24 mb-8">
                <!-- Outer glowing ring -->
                <div class="absolute inset-0 bg-primary-100 dark:bg-primary-900/30 rounded-full animate-ping opacity-75 duration-1000"></div>
                <!-- Inner solid circle -->
                <div class="relative w-full h-full bg-primary-50 dark:bg-primary-900/40 rounded-full flex items-center justify-center border border-primary-200 dark:border-primary-800/50 shadow-inner z-10 transition-transform hover:scale-105 duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary-500 dark:text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <path d="M11 8a2 2 0 0 0-2 2"></path>
                        <path d="M11 11h.01"></path>
                    </svg>
                </div>
            </div>
            
            @if(request()->has('tipo') && request('tipo') !== '' && request('tipo') !== 'Tipo de propiedad')
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-3 tracking-tight">Sin resultados para {{ request('tipo') }}</h3>
                <p class="text-base text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">Actualmente no contamos con <strong>{{ strtolower(request('tipo')) }}</strong> registrados bajo estos criterios. Intenta expandir tu búsqueda seleccionando otro tipo de inmueble.</p>
            @elseif(request()->has('min_precio') || request()->has('max_precio'))
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-3 tracking-tight">Cero resultados en este rango</h3>
                <p class="text-base text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">No hemos encontrado propiedades con los precios especificados. Es posible que ajustando el rango mínimo y máximo encuentres excelentes opciones.</p>
            @elseif(count(request()->all()) > 0 && !request()->has('sort'))
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-3 tracking-tight">No hay coincidencias exactas</h3>
                <p class="text-base text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">No logramos encontrar propiedades que cumplan con todos los filtros que has aplicado. Intenta relajar algunas condiciones de tu búsqueda.</p>
            @else
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-3 tracking-tight">Aún no hay propiedades en venta</h3>
                <p class="text-base text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">Por el momento no tenemos inmuebles publicados en esta sección. Vuelve pronto para descubrir nuevas oportunidades de inversión.</p>
            @endif

            @if(count(request()->all()) > 0)
            <div class="mt-2">
                <a href="{{ route('venta') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-6 py-3.5 rounded-xl font-bold transition-all shadow-sm hover:shadow-md">
                    <i class="fa-solid fa-filter-circle-xmark text-slate-400 dark:text-slate-500"></i> Limpiar todos los filtros
                </a>
            </div>
            @endif
        </div>
        @endforelse
    </div>
</div>
@endsection
