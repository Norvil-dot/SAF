@extends('layouts.public')

@section('title', 'Inicio')

@section('content')
<!-- Hero Search Section -->
<div class="relative py-24 bg-slate-900 border-b border-slate-800 flex items-center justify-center min-h-[500px] overflow-hidden">
    <!-- Abstract Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-1/2 -left-1/4 w-full h-full bg-gradient-to-br from-primary-900/40 to-transparent rounded-full blur-3xl mix-blend-screen opacity-60"></div>
        <div class="absolute bottom-0 right-1/4 w-3/4 h-3/4 bg-gradient-to-tl from-accent/20 to-transparent rounded-full blur-3xl mix-blend-screen opacity-40"></div>
    </div>
    
    <div class="relative z-10 text-center px-4 w-full max-w-5xl mx-auto">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-4 drop-shadow-sm">
            Tu próximo espacio ideal, <span class="text-primary-400">hoy.</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-300 font-light mb-10 max-w-2xl mx-auto drop-shadow-sm">
            Descubre locales y oficinas perfectamente adaptados a las necesidades de tu negocio.
        </p>
        
        <!-- Search Box (Glassmorphism) Alpine.js Component -->
        <div class="relative bg-white/10 dark:bg-darkcard/40 backdrop-blur-md p-2 rounded-2xl shadow-2xl border border-white/20 dark:border-slate-700/50 flex flex-col md:flex-row gap-2 max-w-4xl mx-auto transition-colors duration-200"
             x-data='{
                operacion: "Alquiler",
                search: "",
                tipo: "",
                open: false,
                showWarning: false,
                ubicaciones: {!! json_encode($ubicaciones ?? []) !!},
                get filtered() {
                    if(this.search === "") return [];
                    return this.ubicaciones.filter(u => u.toLowerCase().includes(this.search.toLowerCase()));
                },
                select(ubicacion) {
                    this.search = ubicacion;
                    this.open = false;
                },
                submitSearch() {
                    if (!this.tipo || this.tipo === "Tipo de propiedad" || this.tipo === "") {
                        this.showWarning = true;
                        setTimeout(() => { this.showWarning = false; }, 3000);
                        return;
                    }
                    let urlStr = "/" + this.operacion.toLowerCase() + "?ubicacion=" + encodeURIComponent(this.search);
                    urlStr += "&tipo=" + encodeURIComponent(this.tipo);
                    window.location.href = urlStr;
                }
             }'>
            <select x-model="operacion" class="p-4 bg-white dark:bg-darkcard text-slate-700 dark:text-slate-200 rounded-xl outline-none border-0 focus:ring-2 focus:ring-primary-500 w-full md:w-1/4 cursor-pointer shadow-sm transition-colors duration-200">
                <option value="Alquiler">Alquiler</option>
                <option value="Venta">Venta</option>
            </select>
            <select x-model="tipo" class="p-4 bg-white dark:bg-darkcard text-slate-700 dark:text-slate-200 rounded-xl outline-none border-0 focus:ring-2 focus:ring-primary-500 w-full md:w-1/4 cursor-pointer shadow-sm transition-colors duration-200">
                <option value="">Tipo de propiedad</option>
                @foreach($tipos as $tipo_op)
                    <option value="{{ $tipo_op }}">{{ $tipo_op }}</option>
                @endforeach
            </select>
            <div class="relative flex-grow w-full md:w-auto text-left">
                <input type="text" x-model="search" @input="open = true" @focus="open = true" @click.away="open = false" @keydown.enter="submitSearch()" placeholder="Ingresa distrito o zona..." class="p-4 bg-white dark:bg-darkcard text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl outline-none border-0 focus:ring-2 focus:ring-primary-500 w-full shadow-sm transition-colors duration-200">
                
                <div x-show="open && search !== ''" class="absolute z-50 w-full mt-2 bg-white dark:bg-darkcard rounded-xl shadow-xl overflow-hidden border border-slate-100 dark:border-slate-700" style="display: none;" x-cloak>
                    <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800 text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">Áreas</div>
                    <ul class="max-h-60 overflow-y-auto">
                        <template x-for="item in filtered" :key="item">
                            <li @click="select(item)" class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer text-slate-700 dark:text-slate-300 font-medium text-sm transition-colors" x-text="item"></li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400 text-center font-medium">No hay ubicaciones registradas con ese nombre</li>
                    </ul>
                </div>
            </div>
            <button @click="submitSearch()" class="bg-primary-600 text-white px-8 py-4 font-bold rounded-xl hover:bg-primary-500 transition shadow-md w-full md:w-auto flex items-center justify-center gap-2">
                <i class="fa-solid fa-search"></i> <span class="md:hidden">Buscar</span>
            </button>

            <!-- Warning Message -->
            <div x-show="showWarning" style="display: none;" x-transition class="absolute -bottom-14 left-1/2 transform -translate-x-1/2 w-full text-center pointer-events-none z-50">
                <span class="bg-emerald-500/95 backdrop-blur-sm text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-xl border border-emerald-400/50">
                    ⚠️ Selecciona un tipo de propiedad
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Promoted Properties -->
<div class="container mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-2 tracking-tight">Propiedades Destacados</h2>
        <p class="text-slate-500 dark:text-slate-400">Oportunidades premium seleccionadas para adquirir tu propiedad.</p>
    </div>
    
    @if ($destacados->count() > 0)
    <div class="relative w-full max-w-7xl mx-auto flex items-center gap-1 sm:gap-4 lg:gap-6" x-data="{
        showLeftButton: false,
        showRightButton: true,
        updateButtons() {
            const el = this.$refs.slider;
            this.showLeftButton = el.scrollLeft > 5;
            this.showRightButton = el.scrollLeft < (el.scrollWidth - el.clientWidth - 5);
        },
        scrollNext() { 
            const slider = this.$refs.slider;
            const scrollAmount = window.innerWidth < 640 ? slider.clientWidth * 0.9 : 360;
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        },
        scrollPrev() { 
            const slider = this.$refs.slider;
            const scrollAmount = window.innerWidth < 640 ? slider.clientWidth * 0.9 : 360;
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        },
        init() {
            this.$nextTick(() => { this.updateButtons(); });
            window.addEventListener('resize', () => { this.updateButtons(); });
        }
    }">
        <!-- Left Arrow -->

        <!-- Slider -->
        <div x-ref="slider" @scroll.debounce.10ms="updateButtons" class="flex-1 flex overflow-x-auto gap-4 sm:gap-6 lg:gap-8 pb-8 pt-4 scroll-smooth [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
            @foreach ($destacados as $local)
            <div class="w-[85vw] sm:w-[320px] lg:w-[350px] flex-shrink-0 group bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/60 overflow-hidden hover:shadow-xl hover:border-primary-200 dark:hover:border-primary-900/50 transition-all duration-300 flex flex-col">
                <!-- Carousel Container -->
                <div class="h-56 relative bg-slate-100 dark:bg-slate-800 overflow-hidden"
                     x-data="{ 
                         currentIndex: 0, 
                         images: {{ json_encode(is_string($local->imagenes) ? json_decode($local->imagenes, true) : (is_array($local->imagenes) && count($local->imagenes) > 0 ? $local->imagenes : ['https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=500&h=300'])) }},
                         next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
                         prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
                     }">
                    
                    <template x-for="(image, index) in images" :key="index">
                        <img x-show="currentIndex === index" :src="image" class="w-full h-full object-cover absolute inset-0 transition-opacity duration-500 transform group-hover:scale-105" alt="{{ $local->titulo }}">
                    </template>

                    <!-- Gradient Overlay for better badge visibility -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                    <!-- Arrows (Only show if multiple images) -->
                    <button @click.prevent="prev()" x-show="images.length > 1" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 w-8 h-8 rounded-full flex items-center justify-center transition shadow-md z-10 opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </button>
                    <button @click.prevent="next()" x-show="images.length > 1" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-slate-900/80 hover:bg-white dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 w-8 h-8 rounded-full flex items-center justify-center transition shadow-md z-10 opacity-0 group-hover:opacity-100 backdrop-blur-sm">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </button>
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4 {{ $local->operacion == 'venta' ? 'bg-primary-600' : 'bg-accent' }} text-white text-[10px] uppercase font-bold tracking-wider px-3 py-1.5 rounded-full shadow-lg z-10">
                        {{ ucfirst($local->operacion) }}
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-slate-100 mb-2 line-clamp-2 leading-tight group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $local->titulo }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-2 flex items-start gap-2">
                        <i class="fa-solid fa-location-dot mt-0.5 text-slate-400 dark:text-slate-500"></i> 
                        <span class="line-clamp-1">{{ $local->distrito ?? 'Lima' }}{{ $local->direccion ? ', ' . $local->direccion : '' }}</span>
                    </p>
                    <p class="text-slate-600 dark:text-slate-300 text-sm mb-4 flex items-start gap-2 font-medium">
                        <i class="fa-solid fa-building mt-0.5 text-slate-400 dark:text-slate-500"></i> 
                        <span class="line-clamp-1">{{ $local->tipo_propiedad ?? 'Tipo no especificado' }}</span>
                    </p>
                    
                    <!-- Space metadata -->
                    <div class="flex items-center gap-4 mb-6 text-sm text-slate-600 dark:text-slate-300 font-medium">
                        <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/50 px-2.5 py-1 rounded-md">
                            <i class="fa-solid fa-maximize text-slate-400"></i> {{ $local->area ?? '0' }} m²
                        </div>
                        @if($local->banos)
                        <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/50 px-2.5 py-1 rounded-md">
                            <i class="fa-solid fa-bath text-slate-400"></i> {{ $local->banos }}
                        </div>
                        @endif
                    </div>

                    <div class="mt-auto flex justify-between items-center border-t border-slate-100 dark:border-slate-700/50 pt-5">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider">{{ $local->operacion == 'venta' ? 'Precio de Venta' : 'Alquiler Mensual' }}</span>
                            <span class="text-primary-600 dark:text-primary-400 font-bold text-xl">
                                {{ $local->operacion == 'venta' ? 'S/ ' : 'S/ ' }}{{ number_format($local->precio_mensual, 2) }}
                            </span>
                        </div>
                        <a href="{{ route('detalle-local', $local->id) }}" class="inline-flex items-center justify-center bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white px-4 py-2 mt-auto font-semibold rounded-lg transition-colors text-sm">
                            Ver Detalles <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Right Arrow -->    </div>
    @else
    <div class="bg-slate-50 dark:bg-darkcard border border-slate-200 dark:border-slate-700 rounded-2xl p-12 text-center text-slate-500 dark:text-slate-400 max-w-2xl mx-auto shadow-sm">
        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-shop text-2xl text-slate-300 dark:text-slate-600"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-2">No hay destacados</h3>
        <p>Aún no se han definido locales destacados para mostrar en el inicio.</p>
    </div>
    @endif
</div>

<!-- Quick Links Sections -->
<div class="container mx-auto px-4 pb-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-800 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
            <h3 class="font-bold text-lg mb-6 text-slate-800 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                    <i class="fa-solid fa-store"></i> 
                </div>
                Zonas Populares (Venta)
            </h3>
            <ul class="text-sm space-y-3">
                <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 flex items-center justify-between group transition"><span>Locales comerciales</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
                <li class="border-t border-slate-100 dark:border-slate-800 pt-3"><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 flex items-center justify-between group transition"><span>Oficinas</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
                <li class="border-t border-slate-100 dark:border-slate-800 pt-3"><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 flex items-center justify-between group transition"><span>Terrenos</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
            </ul>
        </div>
        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-800 p-8 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
            <h3 class="font-bold text-lg mb-6 text-slate-800 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-accent/10 text-accent flex items-center justify-center">
                    <i class="fa-solid fa-key"></i> 
                </div>
                Zonas Populares (Alquiler)
            </h3>
            <ul class="text-sm space-y-3">
                <li><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-accent flex items-center justify-between group transition"><span>Consultorios</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
                <li class="border-t border-slate-100 dark:border-slate-800 pt-3"><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-accent flex items-center justify-between group transition"><span>Stand comerciales</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
                <li class="border-t border-slate-100 dark:border-slate-800 pt-3"><a href="#" class="text-slate-600 dark:text-slate-400 hover:text-accent flex items-center justify-between group transition"><span>Depósitos</span> <i class="fa-solid fa-arrow-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i></a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
