@extends('layouts.public')

@section('title', 'Pago Exitoso')

@section('content')
<div class="container mx-auto px-4 py-20 flex justify-center min-h-[60vh] items-center">
    <div class="bg-white border-t-4 border-[#00cfa5] rounded-xl p-10 max-w-lg w-full text-center shadow-2xl">
        <i class="fa-solid fa-circle-check text-6xl text-[#00cfa5] mb-6 animate-bounce"></i>
        
        <h2 class="text-3xl font-bold text-[#122b40] mb-3">¡Excelente! Pago procesado con éxito.</h2>
        <p class="text-lg text-gray-600 mb-8 font-medium">Bienvenido a tu panel administrativo, {{ Auth::user()->name }}.</p>
        
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <p class="text-sm text-blue-800 font-semibold mb-2">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Preparando tu entorno de trabajo...
            </p>
            <p class="text-xs text-gray-500">Serás redirigido en <span id="countdown" class="font-bold text-gray-800">3</span> segundos.</p>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
          <div class="bg-[#1e6df5] h-2 rounded-full transition-all duration-100 ease-linear" style="width: 0%" id="progress"></div>
        </div>
        
        <div class="mt-8">
            <a href="/dashboard" class="text-xs text-blue-500 hover:underline">Ir ahora mismo si no redirige automáticamente</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let timeLeft = 30; // 3.0 seconds in deciseconds
        let progress = 0;
        const countdownEl = document.getElementById('countdown');
        const progressEl = document.getElementById('progress');
        
        const interval = setInterval(() => {
            timeLeft -= 1;
            progress += (100 / 30);
            
            if (progressEl) {
                progressEl.style.width = progress + '%';
            }
            
            if (countdownEl && timeLeft % 10 === 0) {
                countdownEl.innerText = Math.ceil(timeLeft / 10);
            }

            if(timeLeft <= 0) {
                clearInterval(interval);
                window.location.href = '/dashboard';
            }
        }, 100);
    });
</script>
@endsection
