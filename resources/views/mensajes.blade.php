@extends('layouts.dashboard')

@section('title', 'Mensajes')
@section('header_title', 'Mis Mensajes')

@section('content')
<div x-data="{ showDeleteModal: false, formToSubmit: null, confirmDelete(id) { this.formToSubmit = document.getElementById('delete-form-' + id); this.showDeleteModal = true; }, executeDelete() { if(this.formToSubmit) this.formToSubmit.submit(); this.showDeleteModal = false; } }">
@if(isset($mensajes) && $mensajes->count() > 0)
    <div class="grid grid-cols-1 gap-4 mb-8">
        @foreach($mensajes as $msj)
        <div class="bg-white dark:bg-darkcard border border-slate-200 dark:border-slate-700 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative">
            @if(!$msj->leido)
                <span class="absolute top-4 right-4 flex items-center justify-center text-yellow-500 animate-pulse" title="No leído">
                    <i class="fa-solid fa-bell text-lg drop-shadow-sm"></i>
                </span>
            @endif
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-900 dark:text-white">{{ $msj->nombre }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ $msj->email }} • {{ $msj->telefono }}</p>
                </div>
                <div class="text-xs text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded font-medium">
                    {{ $msj->created_at->diffForHumans() }}
                </div>
            </div>
            
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl text-sm text-slate-700 dark:text-slate-300 mb-4 border border-slate-100 dark:border-slate-800">
                "{{ $msj->mensaje }}"
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-slate-100 dark:border-slate-800 pt-3 gap-3">
                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-building text-primary-500"></i> Sobre: <a href="{{ route('detalle-local', $msj->local_id) }}" class="font-bold text-primary-600 hover:underline" target="_blank">{{ $msj->local->titulo ?? 'Inmueble eliminado' }}</a>
                </div>
                
                <div class="flex items-center gap-2">
                    <form action="{{ route('mensajes.toggle-leido', $msj) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors {{ $msj->leido ? 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' : 'bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-900/20 dark:text-primary-400 dark:hover:bg-primary-900/40' }}" title="{{ $msj->leido ? 'Marcar como no leído' : 'Marcar como leído' }}">
                            <i class="fa-solid {{ $msj->leido ? 'fa-envelope' : 'fa-envelope-open' }}"></i> {{ $msj->leido ? 'Marcar no leído' : 'Marcar leído' }}
                        </button>
                    </form>
                    <form action="{{ route('mensajes.destroy', $msj) }}" method="POST" class="inline" id="delete-form-{{ $msj->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="confirmDelete({{ $msj->id }})" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40">
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
        <div class="relative mb-8">
            <div class="w-24 h-24 bg-primary-50 dark:bg-primary-900/20 rounded-full flex items-center justify-center shadow-inner border border-primary-100 dark:border-primary-800/50">
                <i class="fa-solid fa-envelope-open-text text-4xl text-primary-500"></i>
            </div>
            <div class="absolute -bottom-1 -right-1 bg-white dark:bg-darkcard rounded-full p-2 shadow-sm border border-slate-200 dark:border-slate-700 flex items-center justify-center w-10 h-10">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">Aún no tienes mensajes</h2>
        <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-md text-sm leading-relaxed">Cuando las empresas interactúen con tus locales publicados, sus preguntas y solicitudes de contacto B2B aparecerán aquí.</p>
        
        <a href="/mis-inmuebles" class="bg-primary-600 dark:bg-primary-500 text-white font-bold py-2.5 px-6 rounded-xl hover:bg-primary-700 dark:hover:bg-primary-600 transition-all shadow-sm border border-primary-600 dark:border-primary-500 text-sm flex items-center justify-center gap-2 m-0 lg:mx-auto w-full lg:w-auto">
            <i class="fa-solid fa-building"></i> Ver mis inmuebles actuales
        </a>
    </div>
    </div>
@endif

    <!-- Delete Confirmation Modal (Alpine) -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-8 w-full max-w-md border border-slate-200 dark:border-slate-700 transform transition-all text-center" @click.away="showDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-100 dark:border-red-800/50 shadow-inner">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-red-500 dark:text-red-400 animate-pulse"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">¿Eliminar Mensaje?</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">Esta acción es permanente y no podrás recuperar la información de este contacto.</p>
            
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
