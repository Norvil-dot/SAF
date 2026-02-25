@extends('layouts.public')

@section('title', 'Publicar Propiedades')

@section('content')
<div class="bg-primary-600 dark:bg-darkbg py-20 relative overflow-hidden transition-colors duration-200">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    
    <div class="container mx-auto px-4 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">Publica con nosotros</h1>
        <p class="text-primary-100 mb-16 max-w-2xl mx-auto text-lg">Llega a miles de empresas e inversores en todo el Perú y maximiza tus oportunidades de cierre.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch max-w-7xl mx-auto">
            <!-- Plan Gratuito -->
            <div class="bg-white dark:bg-darkcard rounded-3xl p-6 shadow-xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between transition-all hover:-translate-y-2 relative group overflow-hidden h-full">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-slate-300 dark:bg-slate-700 transition-colors group-hover:bg-slate-400"></div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-widest mb-4">Pack Inicial</p>
                    <h3 class="font-black text-slate-900 dark:text-white text-2xl mb-2 flex items-baseline gap-2 justify-center">1 <span class="text-lg font-bold text-slate-500 dark:text-slate-400">propiedad</span></h3>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-6 bg-slate-50 dark:bg-slate-900/20 py-1.5 px-3 rounded-full inline-block border border-slate-200 dark:border-slate-800/50">Plan Gratuito</p>
                    
                    <div class="my-6">
                        <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-start justify-center">
                            <span class="text-xl mt-1.5 mr-1 font-bold">S/</span>0
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Siempre gratis</p>
                    </div>
                </div>
                
                <div>
                    <a href="/registro-plan?plan=0" class="block w-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 font-bold py-3 px-4 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shadow-sm mb-2 text-sm">Publicar Gratis</a>
                </div>
            </div>

            <!-- Plan Básico -->
            <div class="bg-white dark:bg-darkcard rounded-3xl p-6 shadow-xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between transition-all hover:-translate-y-2 relative group overflow-hidden h-full">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-slate-300 dark:bg-slate-700 transition-colors group-hover:bg-primary-400"></div>
                <div>
                    <p class="text-primary-500 dark:text-primary-400 text-sm font-bold uppercase tracking-widest mb-4">Pack Básico</p>
                    <h3 class="font-black text-slate-900 dark:text-white text-2xl mb-2 flex items-baseline gap-2 justify-center">10 <span class="text-lg font-bold text-slate-500 dark:text-slate-400">propiedades</span></h3>
                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mb-6 bg-primary-50 dark:bg-primary-900/20 py-1.5 px-3 rounded-full inline-block border border-primary-100 dark:border-primary-800/50">Incluye 2 destacados</p>
                    
                    <div class="my-6">
                        <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-start justify-center">
                            <span class="text-xl mt-1.5 mr-1 font-bold">S/</span>49
                            <span class="text-sm text-slate-500 dark:text-slate-400 font-medium ml-1 mt-auto mb-1">*</span>
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Por mes / facturación mensual</p>
                    </div>
                </div>
                
                <div>
                    <a href="/registro-plan?plan=1" class="block w-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 font-bold py-3 px-4 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shadow-sm mb-2 text-sm">Elegir plan básico</a>
                </div>
            </div>

            <!-- Plan Pro (Destacado) -->
            <div class="bg-slate-900 dark:bg-slate-800 rounded-3xl p-7 shadow-2xl border border-slate-800 dark:border-slate-700 flex flex-col justify-between relative transform md:-translate-y-4 z-10 transition-all hover:-translate-y-6 ring-1 ring-white/10 dark:ring-white/5 h-full">
                <div class="absolute inset-0 bg-gradient-to-b from-primary-900/40 via-transparent to-transparent opacity-50 rounded-3xl pointer-events-none"></div>
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-max">
                    <span class="bg-gradient-to-r from-amber-400 to-orange-500 text-amber-950 text-[10px] sm:text-xs px-3 py-1 rounded-full font-black uppercase tracking-widest shadow-lg border border-amber-300">MÁS POPULAR</span>
                </div>
                
                <div class="relative z-10 mt-2">
                    <p class="text-primary-300 dark:text-primary-400 text-sm font-bold uppercase tracking-widest mb-4">Pack Pro</p>
                    <h3 class="font-black text-white text-3xl mb-2 flex items-baseline gap-2 justify-center">25 <span class="text-xl font-bold text-slate-400">propiedades</span></h3>
                    <p class="text-sm font-medium text-amber-400 mb-6 bg-amber-400/10 py-1.5 px-3 rounded-full inline-block border border-amber-400/20">Incluye 5 destacados</p>
                    
                    <div class="my-6">
                        <p class="text-5xl font-extrabold text-white tracking-tight flex items-start justify-center">
                            <span class="text-2xl mt-1 mr-1 font-bold">S/</span>99
                            <span class="text-lg text-slate-400 font-medium ml-1 mt-auto mb-1">*</span>
                        </p>
                        <p class="text-sm text-slate-400 mt-2 font-medium">Por mes / facturación mensual</p>
                    </div>
                </div>
                
                <div class="relative z-10">
                    <a href="/registro-plan?plan=2" class="block w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white font-bold py-3.5 px-4 rounded-xl hover:from-primary-400 hover:to-primary-500 transition-all shadow-lg hover:shadow-primary-500/25 mb-2 text-sm transform hover:scale-[1.02]">Comenzar ahora</a>
                </div>
            </div>

            <!-- Plan Elite -->
            <div class="bg-white dark:bg-darkcard rounded-3xl p-6 shadow-xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between transition-all hover:-translate-y-2 relative group overflow-hidden h-full">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-slate-300 dark:bg-slate-700 transition-colors group-hover:bg-primary-600"></div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-bold uppercase tracking-widest mb-4">Pack Elite</p>
                    <h3 class="font-black text-slate-900 dark:text-white text-2xl mb-2 flex items-baseline gap-2 justify-center">50 <span class="text-lg font-bold text-slate-500 dark:text-slate-400">prop.</span></h3>
                    <p class="text-sm font-medium text-primary-600 dark:text-primary-400 mb-6 bg-primary-50 dark:bg-primary-900/20 py-1.5 px-3 rounded-full inline-block border border-primary-100 dark:border-primary-800/50">Incluye 10 destacados</p>
                    
                    <div class="my-6">
                        <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight flex items-start justify-center">
                            <span class="text-xl mt-1.5 mr-1 font-bold">S/</span>199
                            <span class="text-sm text-slate-500 dark:text-slate-400 font-medium ml-1 mt-auto mb-1">*</span>
                        </p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 font-medium">Por mes / facturación mensual</p>
                    </div>
                </div>
                
                <div>
                    <a href="/registro-plan?plan=3" class="block w-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 font-bold py-3 px-4 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all shadow-sm mb-2 text-sm">Elegir plan elite</a>
                </div>
            </div>
        </div>
        <p class="text-primary-200 text-sm mt-8 font-medium bg-black/10 inline-block px-4 py-2 rounded-lg backdrop-blur-sm">* Los precios pagados no incluyen IGV y no aplican a proyectos directos.</p>
    </div>
</div>

<div class="bg-slate-50 dark:bg-slate-900/50 py-24 border-t border-slate-200 dark:border-slate-800 transition-colors duration-200">
    <div class="container mx-auto px-4 max-w-4xl text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-6 tracking-tight">Es el momento de hacer crecer tu negocio</h2>
        <p class="text-lg text-slate-600 dark:text-slate-400 mb-12">Bienvenido al nuevo entorno inmobiliario. Herramientas pensadas para profesionales de alto rendimiento:</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-left">
            <div class="bg-white dark:bg-darkcard p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 rounded-xl flex items-center justify-center shrink-0 border border-primary-100 dark:border-primary-800/50 shadow-inner">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Audiencia Selecta</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Accede a la audiencia inmobiliaria corporativa más grande de Perú.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-darkcard p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-800/50 shadow-inner">
                    <i class="fa-solid fa-bolt text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Integración API</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Ahorra horas publicando directamente desde tu CRM o sistema interno.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-darkcard p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-500 rounded-xl flex items-center justify-center shrink-0 border border-amber-100 dark:border-amber-800/50 shadow-inner">
                    <i class="fa-solid fa-address-book text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Centro de Contactos</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Gestiona todos tus leads y contratos desde un único panel inteligente.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-darkcard p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-800/50 shadow-inner">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-1">Analíticas Avanzadas</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Analiza tus resultados y visitas en tiempo real desde cualquier dispositivo.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
