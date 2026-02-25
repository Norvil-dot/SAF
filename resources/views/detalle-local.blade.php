@extends('layouts.public')

@section('title', $local->titulo)

@section('content')
<div class="bg-slate-50 dark:bg-darkbg py-8 min-h-screen transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <a href="/" class="inline-flex items-center text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors mb-4 bg-white dark:bg-darkcard px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver al inicio
            </a>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 bg-white dark:bg-darkcard p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $local->operacion == 'venta' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'bg-accent/10 text-accent' }}">
                            {{ $local->operacion }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                            {{ mb_convert_case(mb_strtolower($local->tipo_propiedad ?? 'Inmueble', 'UTF-8'), MB_CASE_TITLE, 'UTF-8') }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">{{ $local->titulo }}</h1>
                    <p class="text-slate-500 dark:text-slate-400 flex items-start md:items-center gap-2 text-sm md:text-base">
                        <i class="fa-solid fa-location-dot mt-0.5 md:mt-0 text-slate-400"></i>
                        {{ $local->direccion ? $local->direccion . ', ' : '' }}{{ $local->distrito }}
                    </p>
                </div>
                <div class="text-left md:text-right border-t md:border-t-0 border-slate-100 dark:border-slate-800 pt-4 md:pt-0 w-full md:w-auto">
                    <div class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">
                        {{ $local->operacion == 'venta' ? 'Precio de Venta' : 'Alquiler Mensual' }}
                    </div>
                    <div class="text-3xl md:text-4xl font-black text-primary-600 dark:text-primary-400 tracking-tight">
                        {{ $local->operacion == 'venta' ? 'USD ' : 'S/ ' }}{{ number_format($local->precio_mensual, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Carousel & Details -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Image Carousel using Alpine.js -->
                <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-800 relative xl:aspect-[16/9] aspect-video group" 
                     x-data="{ 
                         currentIndex: 0, 
                         images: {{ json_encode($local->imagenes && count($local->imagenes) > 0 ? $local->imagenes : ['https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200&h=800']) }},
                         next() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
                         prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
                     }">
                    
                    <template x-for="(image, index) in images" :key="index">
                        <img x-show="currentIndex === index" :src="image" class="w-full h-full object-cover absolute inset-0 transition-opacity duration-500 ease-in-out" x-transition.opacity alt="Foto del local">
                    </template>
                    
                    <!-- Gradient Overlays for better contrast -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-slate-900/30 opacity-60 pointer-events-none"></div>
                    
                    <!-- Arrows -->
                    <button @click.prevent="prev()" x-show="images.length > 1" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white text-white hover:text-slate-900 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 shadow-lg backdrop-blur-md opacity-0 group-hover:opacity-100 focus:opacity-100 outline-none ring-2 ring-white/50 z-10">
                        <i class="fa-solid fa-chevron-left text-lg"></i>
                    </button>
                    <button @click.prevent="next()" x-show="images.length > 1" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white text-white hover:text-slate-900 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 shadow-lg backdrop-blur-md opacity-0 group-hover:opacity-100 focus:opacity-100 outline-none ring-2 ring-white/50 z-10">
                        <i class="fa-solid fa-chevron-right text-lg"></i>
                    </button>
                    
                    <!-- Indicator dots -->
                    <div class="absolute bottom-6 inset-x-0 flex justify-center gap-2 z-10" x-show="images.length > 1">
                        <template x-for="(image, index) in images" :key="index">
                            <button @click.prevent="currentIndex = index" :class="currentIndex === index ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/80 w-2.5'" class="h-2.5 rounded-full transition-all duration-300 shadow-sm focus:outline-none"></button>
                        </template>
                    </div>
                    
                    <!-- Counter badge -->
                    <div class="absolute top-4 right-4 bg-slate-900/60 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md backdrop-blur-md z-10 flex items-center gap-2 border border-white/10" x-show="images.length > 0">
                        <i class="fa-solid fa-camera"></i> <span x-text="(currentIndex + 1) + ' / ' + images.length"></span>
                    </div>
                </div>

                <!-- Description & Features -->
                <div class="bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 space-y-10">
                    
                    <div>
                        <h2 class="text-xl font-bold mb-4 text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-align-left text-primary-500"></i> Descripción General
                        </h2>
                        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line text-[15px]">
                            {{ $local->descripcion ?? 'El propietario no ha proporcionado una descripción detallada para este inmueble.' }}
                        </div>
                    </div>
                    
                    <!-- Mapa de Ubicación -->
                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4 text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-primary-500"></i> Ubicación
                        </h2>
                        <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm h-64 md:h-72 relative bg-slate-100 dark:bg-slate-800">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                loading="lazy" 
                                allowfullscreen 
                                referrerpolicy="no-referrer-when-downgrade" 
                                src="https://maps.google.com/maps?q={{ urlencode(($local->direccion ? $local->direccion . ', ' : '') . $local->distrito) }}&t=&z=15&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                        <p class="text-xs text-slate-500 mt-2 flex items-center gap-1"><i class="fa-solid fa-circle-info"></i> Al hacer clic en "Ampliar el mapa", se abrirá Google Maps en una nueva pestaña o ventana.</p>
                    </div>
                    
                    <hr class="border-slate-100 dark:border-slate-800 mt-8 mb-8">

                    <div>
                        <h2 class="text-xl font-bold mb-6 text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-primary-500"></i> Especificaciones
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Stat Card -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 flex items-center gap-4 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                                <div class="bg-white dark:bg-slate-700 text-primary-600 dark:text-primary-400 w-12 h-12 rounded-lg flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-600/50 text-xl">
                                    <i class="fa-solid fa-ruler-combined"></i>
                                </div>
                                <div>
                                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">Área Total</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-lg">{{ $local->area ?? '--' }} m²</span>
                                </div>
                            </div>

                            <!-- Stat Card -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 flex items-center gap-4 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                                <div class="bg-white dark:bg-slate-700 text-primary-600 dark:text-primary-400 w-12 h-12 rounded-lg flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-600/50 text-xl">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">Tipo</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ mb_convert_case(mb_strtolower($local->tipo_propiedad ?? 'Inmueble', 'UTF-8'), MB_CASE_TITLE, 'UTF-8') }}</span>
                                </div>
                            </div>

                            <!-- Stat Card -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 flex items-center gap-4 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                                <div class="bg-white dark:bg-slate-700 text-primary-600 dark:text-primary-400 w-12 h-12 rounded-lg flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-600/50 text-xl">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                                <div>
                                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">Estado</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-base leading-tight capitalize">{{ $local->estado }}</span>
                                </div>
                            </div>

                            <!-- Stat Card -->
                            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 flex items-center gap-4 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                                <div class="bg-white dark:bg-slate-700 text-primary-600 dark:text-primary-400 w-12 h-12 rounded-lg flex items-center justify-center shadow-sm border border-slate-100 dark:border-slate-600/50 text-xl">
                                    <i class="fa-solid fa-bath"></i>
                                </div>
                                <div>
                                    <span class="block text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-0.5">Baños</span>
                                    <span class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $local->banos ?? '--' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Contact form -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-darkcard p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 lg:sticky top-[100px]">
                    
                    <!-- Propietario Card snippet -->
                    <div class="flex items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-5 mb-6">
                        <div class="w-14 h-14 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 flex items-center justify-center text-xl font-bold border-2 border-primary-200 dark:border-primary-800/60 shadow-inner">
                            <i class="fa-regular fa-building"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-lg leading-tight">{{ $local->tenant->users->first()->name ?? 'Agente Tu Propiedad Cerca' }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">{{ $local->tenant->users->first()->email ?? 'Contacto Directo' }}</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Solicitar información</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">Completa el formulario para que el agente encargado de esta propiedad se ponga en contacto contigo.</p>
                    
                    @if(session('success'))
                        <div class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-xl mb-6 text-sm font-medium flex items-start gap-3 border border-green-200 dark:border-green-800/50">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 text-lg"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('local.contactar', $local->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Nombre Completo</label>
                            <input type="text" name="nombre" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-3 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-sm shadow-sm" placeholder="Tu nombre">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Teléfono / WhatsApp</label>
                            <input type="tel" name="telefono" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-3 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-sm shadow-sm" placeholder="Tu teléfono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Email Empresarial</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-3 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-sm shadow-sm" placeholder="Tu correo electrónico">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">Mensaje</label>
                            <textarea name="mensaje" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-lg px-4 py-3 outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-sm shadow-sm resize-none" rows="3">Hola, solicito más información / coordinar visita para el local: {{ $local->titulo }}.</textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3.5 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-500/30 transition-all flex justify-center items-center gap-2 shadow-md hover:shadow-lg mt-2 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i> Enviar Solicitud
                        </button>
                    </form>
                    
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center space-y-3">
                        <div class="bg-primary-50 dark:bg-primary-900/10 text-primary-700 dark:text-primary-400 p-4 rounded-xl text-xs flex items-start gap-3 text-left">
                            <i class="fa-solid fa-shield-halved text-lg mt-0.5"></i>
                            <p class="font-medium leading-relaxed">Verifica siempre la identidad de la contraparte antes de realizar cualquier adelanto monetario o firmar contratos. Tu Propiedad Cerca no interviene en las transacciones.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
