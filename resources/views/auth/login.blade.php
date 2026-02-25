@extends('layouts.public')

@section('title', 'Iniciar sesión')

@section('content')
<div class="container mx-auto px-4 py-16 flex justify-center min-h-[80vh] items-center">
    <div class="bg-white dark:bg-darkcard rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 sm:p-10 w-full max-w-md transition-colors duration-200" x-data="{ showForgotPasswordMessage: false }">
        
        <div class="text-center mb-10 text-slate-900 dark:text-white">
            <a href="/" class="text-3xl font-extrabold text-primary-600 dark:text-primary-500 flex items-center justify-center gap-3 mb-6 transition-colors">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/40 rounded-xl flex items-center justify-center border border-primary-200 dark:border-primary-800 shadow-sm">
                    <i class="fa-solid fa-city text-2xl"></i>
                </div>
                Tu Propiedad Cerca
            </a>
            <h1 class="text-2xl font-bold tracking-tight mb-2">Bienvenido de nuevo</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Ingresa a tu cuenta para gestionar tus inmuebles</p>
        </div>

        <div x-show="showForgotPasswordMessage" style="display: none;" x-transition class="mb-6 mx-auto p-4 bg-emerald-50 dark:bg-emerald-900/40 border border-emerald-300 dark:border-emerald-700 rounded-xl relative text-left shadow-sm">
            <button @click="showForgotPasswordMessage = false" type="button" class="absolute top-2 right-2 p-1 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors"><i class="fa-solid fa-xmark"></i></button>
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-shield-halved text-emerald-600 dark:text-emerald-400 text-xl mt-0.5"></i>
                <div class="pr-2">
                    <h3 class="font-bold text-emerald-800 dark:text-emerald-300 mb-1 leading-tight">Por temas de seguridad</h3>
                    <p class="text-sm text-emerald-700 dark:text-emerald-400 leading-relaxed mb-3">
                        Comunícate con soporte para poder restablecer tu contraseña:
                    </p>
                    <div class="bg-white/60 dark:bg-emerald-950/50 p-3 rounded-lg border border-emerald-200/60 dark:border-emerald-800/60 text-xs">
                        <span class="font-semibold block text-emerald-900 dark:text-emerald-300 mb-1"><i class="fa-regular fa-envelope mr-1"></i> Norvil: <a href="mailto:gbermeonorvilom@uss.edu.pe" class="font-bold hover:underline">gbermeonorvilom@uss.edu.pe</a></span>
                        <span class="font-semibold block text-emerald-900 dark:text-emerald-300"><i class="fa-regular fa-envelope mr-1"></i> Reyes: <a href="mailto:reyesgonza@uss.edu.pe" class="font-bold hover:underline">reyesgonza@uss.edu.pe</a></span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Correo Electrónico</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-slate-400"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl pl-10 pr-4 py-3 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="tu@empresa.com">
                </div>
                @error('email')
                    <span class="text-xs text-red-500 dark:text-red-400 mt-2 block font-medium flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-slate-400"></i>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl pl-10 pr-4 py-3 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="••••••••">
                </div>
                @error('password')
                    <span class="text-xs text-red-500 dark:text-red-400 mt-2 block font-medium flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-8 text-sm pt-2">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-primary-600 bg-slate-100 border-slate-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-slate-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                    <label for="remember" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">Recordarme</label>
                </div>
                <a href="#" @click.prevent="showForgotPasswordMessage = true" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold hover:underline transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" class="w-full bg-primary-600 dark:bg-primary-500 text-white font-bold py-3.5 px-6 rounded-xl hover:bg-primary-700 dark:hover:bg-primary-600 transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-primary-500/20 active:scale-[0.98] flex justify-center items-center gap-2">
                Iniciar sesión <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
            ¿No tienes una cuenta? <a href="/publicar" class="font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 hover:underline transition-colors">Compra plan ahora</a>
        </p>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs text-slate-500 dark:text-slate-500 font-medium">
            <a href="#" class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Términos</a>
            <a href="#" class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Privacidad</a>
            <a href="#" class="hover:text-slate-800 dark:hover:text-slate-300 transition-colors">Cookies</a>
        </div>
    </div>
</div>
@endsection
