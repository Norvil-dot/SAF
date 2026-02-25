@extends('layouts.dashboard')

@section('title', 'Mi Billetera')
@section('header_title', 'Mi Billetera e Ingresos')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Tarjeta Principal de Saldo -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 dark:from-primary-900/60 dark:to-slate-900 rounded-2xl p-8 sm:p-10 text-white shadow-xl relative overflow-hidden ring-1 ring-slate-800/50 dark:ring-primary-800/30" title="Módulo en proceso de habilitación en los siguientes días ya que por ahora el contrato de propiedades es de manera directa por temas de papeleos y firmas">
        <div class="absolute right-0 top-0 opacity-[0.03] dark:opacity-10 pointer-events-none">
            <i class="fa-solid fa-wallet text-9xl -mt-4 -mr-4 transform rotate-12 scale-150"></i>
        </div>
        
        <div class="relative z-10">
            <h2 class="text-sm font-semibold text-slate-300 dark:text-primary-200/80 uppercase tracking-widest mb-3 flex items-center gap-2">
                <i class="fa-solid fa-building-columns opacity-70"></i> Saldo Disponible (PEN)
            </h2>
            <div class="flex items-baseline gap-2 mb-8">
                <span class="text-5xl sm:text-6xl font-extrabold tracking-tight">S/ 0</span>
                <span class="text-xl sm:text-2xl text-slate-400 font-medium">.00</span>
            </div>
            
            <div class="flex flex-wrap gap-4">
                <button class="bg-emerald-500 hover:bg-emerald-400 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-lg hover:shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-2 opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-money-bill-transfer"></i> Solicitar Retiro
                </button>
                <button class="bg-white/10 hover:bg-white/20 text-white font-medium py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2 backdrop-blur-sm border border-white/10 hover:border-white/20 opacity-50 cursor-not-allowed">
                    <i class="fa-solid fa-clock-rotate-left"></i> Historial Completo
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" title="Módulo en proceso de habilitación en los siguientes días ya que por ahora el contrato de propiedades es de manera directa por temas de papeleos y firmas">
        <div class="bg-white dark:bg-darkcard p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors group">
            <div class="w-14 h-14 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-2xl shadow-inner border border-emerald-100 dark:border-emerald-800/50 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider mb-1">Ingresos este mes</p>
                <p class="text-2xl font-black text-slate-800 dark:text-white">S/ 0<span class="text-sm font-medium text-slate-400">.00</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-darkcard p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-amber-200 dark:hover:border-amber-800/50 transition-colors group">
            <div class="w-14 h-14 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center text-2xl shadow-inner border border-amber-100 dark:border-amber-800/50 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider mb-1">Pagos Pendientes</p>
                <p class="text-2xl font-black text-slate-800 dark:text-white">S/ 0<span class="text-sm font-medium text-slate-400">.00</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-darkcard p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex items-center gap-5 hover:border-primary-200 dark:hover:border-primary-800/50 transition-colors group">
            <div class="w-14 h-14 rounded-xl bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 flex items-center justify-center text-2xl shadow-inner border border-primary-100 dark:border-primary-800/50 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wider mb-1">Total Acumulado</p>
                <p class="text-2xl font-black text-slate-800 dark:text-white">S/ 0<span class="text-sm font-medium text-slate-400">.00</span></p>
            </div>
        </div>
    </div>

    <!-- Últimos Movimientos -->
    <div class="bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 sm:p-8" title="Módulo en proceso de habilitación en los siguientes días ya que por ahora el contrato de propiedades es de manera directa por temas de papeleos y firmas">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-list text-slate-400"></i> Últimos Movimientos
            </h3>
        </div>
        
        <div class="overflow-x-auto text-center py-10 opacity-70">
            <i class="fa-solid fa-hammer text-4xl text-slate-300 dark:text-slate-600 mb-4"></i>
            <p class="text-slate-500 dark:text-slate-400">Módulo en proceso de habilitación en los siguientes días ya que por ahora el contrato de propiedades es de manera directa por temas de papeleos y firmas.</p>
        </div>
    </div>

</div>
@endsection
