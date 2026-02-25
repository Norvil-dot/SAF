@extends('layouts.dashboard')

@section('title', 'Notificaciones')
@section('header_title', 'Notificaciones')

@section('content')
<div x-data="{ showDeleteModal: false, formToSubmit: null, confirmDelete(id) { this.formToSubmit = document.getElementById('delete-noti-' + id); this.showDeleteModal = true; }, executeDelete() { if(this.formToSubmit) this.formToSubmit.submit(); this.showDeleteModal = false; } }">
@if(isset($notificaciones) && $notificaciones->count() > 0)
    <div class="grid grid-cols-1 gap-4 mb-8">
        @foreach($notificaciones as $noti)
        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative">
            @if(!$noti->leido)
                <span class="absolute top-4 right-4 flex items-center justify-center text-yellow-500 animate-pulse" title="No leído">
                    <i class="fa-solid fa-bell text-lg drop-shadow-sm"></i>
                </span>
            @endif
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $noti->titulo }}</h3>
                </div>
                <div class="text-xs text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded font-medium">
                    {{ $noti->created_at->diffForHumans() }}
                </div>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl text-sm text-slate-700 dark:text-slate-300 mb-4 border border-slate-100 dark:border-slate-800">
                {{ $noti->mensaje }}
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-slate-100 dark:border-slate-800 pt-3 gap-3">
                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    @if($noti->local_id && $noti->local)
                    <i class="fa-solid fa-building text-primary-500"></i> Sobre: <a href="{{ route('detalle-local', $noti->local_id) }}" class="font-bold text-primary-600 hover:underline" target="_blank">{{ $noti->local->titulo ?? 'Inmueble' }}</a>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    <form action="{{ route('notificaciones.toggle-leido', $noti) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors {{ $noti->leido ? 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' : 'bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-900/20 dark:text-primary-400 dark:hover:bg-primary-900/40' }}" title="{{ $noti->leido ? 'Marcar como no leído' : 'Marcar como leído' }}">
                            <i class="fa-solid {{ $noti->leido ? 'fa-envelope-open' : 'fa-check' }}"></i> {{ $noti->leido ? 'Marcar no leído' : 'Marcar leído' }}
                        </button>
                    </form>
                    <form action="{{ route('notificaciones.destroy', $noti) }}" method="POST" class="inline" id="delete-noti-{{ $noti->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="confirmDelete({{ $noti->id }})" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
<div class="h-[75vh] flex flex-col items-center justify-center bg-white dark:bg-darkcard rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 text-center p-8 shadow-sm m-4 lg:m-0">
    <div class="mb-8 relative">
        <div class="w-24 h-24 bg-amber-50 dark:bg-amber-900/20 rounded-full flex items-center justify-center border border-amber-100 dark:border-amber-800/50 shadow-inner">
            <i class="fa-regular fa-bell text-4xl text-amber-500"></i>
        </div>
        <div class="absolute top-1 right-2 w-5 h-5 bg-slate-200 dark:bg-slate-700 rounded-full border-2 border-white dark:border-darkcard flex items-center justify-center">
            <div class="w-1.5 h-1.5 bg-slate-400 dark:bg-slate-500 rounded-full"></div>
        </div>
    </div>
    
    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Aún no tienes notificaciones</h2>
    <p class="text-slate-500 dark:text-slate-400 max-w-md text-sm leading-relaxed">Aquí te avisaremos sobre recordatorios de pagos, vencimientos de planes y novedades importantes de la plataforma B2B.</p>
</div>
@endif

    <!-- Delete Confirmation Modal (Alpine) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-8 w-full max-w-md border border-slate-200 dark:border-slate-700 transform transition-all text-center" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 dark:border-red-800/50 shadow-inner">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-red-500 dark:text-red-400 animate-pulse"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">¿Eliminar Notificación?</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">Esta acción es permanente y no podrás recuperarla.</p>
            
            <div class="flex items-center justify-center gap-4">
                <button type="button" @click="showDeleteModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full">
                    Cancelar
                </button>
                <button type="button" @click="executeDelete()" class="px-6 py-2.5 text-sm font-bold bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-sm hover:shadow transition-all border border-red-600 focus:ring-4 focus:ring-red-500/20 w-full flex justify-center items-center gap-2">
                    <i class="fa-solid fa-trash"></i> Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
