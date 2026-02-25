<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel de Control') - Tu Propiedad Cerca</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1', // Indigo
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        secondary: '#475569', // Slate
                        accent: '#10b981', // Emerald
                        darkbg: '#0f172a', // Slate 900
                        darkcard: '#1e293b', // Slate 800
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-darkbg dark:text-slate-200 font-sans antialiased flex h-screen overflow-hidden transition-colors duration-200" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          showLogoutModal: false,
          showUpgradeModal: false,
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
          }
      }"
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">

    @php
        $tenantInfo = auth()->user()->tenant;
        $isFreePlanFull = ($tenantInfo && $tenantInfo->plan === 'Gratuito') && \App\Models\Local::where('tenant_id', $tenantInfo->id)->count() >= 1;
    @endphp

    <!-- Sidebar -->
    <div class="w-64 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-300 flex flex-col h-full shadow-[4px_0_24px_rgba(0,0,0,0.02)] border-r border-slate-200 dark:border-slate-800 z-20 transition-colors duration-200">
        <!-- User Profile Area -->
        <div class="p-6 text-center border-b border-slate-200 dark:border-slate-800/50">
            <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/50 rounded-full mx-auto flex items-center justify-center mb-3 shadow-inner border border-primary-200 dark:border-primary-500/30">
                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
            </div>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white tracking-wide truncate">{{ Auth::user()->name ?? 'Usuario' }}</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email ?? 'correo@ejemplo.com' }}</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-3">
            <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 px-3">Menú Principal</div>
            <ul class="space-y-1">
                <li>
                    <a href="/dashboard" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->is('dashboard') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->is('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-blue-500 dark:text-blue-400/80' }}"></i>
                        <span class="ml-3 text-sm">Dashboard</span>
                    </a>
                </li>
                <li>
                    @if($isFreePlanFull)
                        <button type="button" @click="showUpgradeModal = true" class="w-full flex items-center px-3 py-2.5 rounded-lg text-slate-400 dark:text-slate-500 opacity-70 cursor-not-allowed transition-colors text-left" title="Límite del plan gratuito alcanzado">
                    @else
                        <a href="/publicar-local" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->is('publicar-local') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                    @endif
                        <i class="fa-solid fa-plus-square w-5 text-center {{ request()->is('publicar-local') && !$isFreePlanFull ? 'text-emerald-600 dark:text-emerald-400' : 'text-emerald-500 dark:text-emerald-400/80' }}"></i>
                        <span class="ml-3 text-sm">Publicar Propiedad</span>
                    @if($isFreePlanFull)
                        </button>
                    @else
                        </a>
                    @endif
                </li>
                <li>
                    <a href="/mis-inmuebles" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->is('mis-inmuebles') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                        <i class="fa-solid fa-building w-5 text-center {{ request()->is('mis-inmuebles') ? 'text-purple-600 dark:text-purple-400' : 'text-purple-500 dark:text-purple-400/80' }}"></i>
                        <span class="ml-3 text-sm">Mis Propiedades</span>
                    </a>
                </li>
            </ul>
            
            <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-8 mb-3 px-3">Gestión</div>
            <ul class="space-y-1">
                <li>
                    <a href="/ingresos" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->is('ingresos') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                        <i class="fa-solid fa-wallet w-5 text-center {{ request()->is('ingresos') ? 'text-amber-600 dark:text-amber-400' : 'text-amber-500 dark:text-amber-400/80' }}"></i>
                        <span class="ml-3 text-sm">Facturación</span>
                    </a>
                </li>
                <li>
                    <a href="/mensajes" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->is('mensajes') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                        <div class="flex items-center">
                            <i class="fa-regular fa-comments w-5 text-center {{ request()->is('mensajes') ? 'text-cyan-600 dark:text-cyan-400' : 'text-cyan-500 dark:text-cyan-400/80' }}"></i>
                            <span class="ml-3 text-sm">Mensajes</span>
                        </div>
                        @php
                            $unreadMensajes = \App\Models\Mensaje::where('tenant_id', auth()->user()->tenant_id)->where('leido', false)->count();
                        @endphp
                        @if($unreadMensajes > 0)
                            <span class="bg-yellow-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-pulse shadow-sm">{{ $unreadMensajes }}</span>
                        @else
                            <span class="bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="/notificaciones" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->is('notificaciones') ? 'bg-primary-50 dark:bg-primary-500/10 text-primary-600 dark:text-primary-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-colors' }}">
                        <div class="flex items-center">
                            <i class="fa-regular fa-bell w-5 text-center {{ request()->is('notificaciones') ? 'text-rose-600 dark:text-rose-400' : 'text-rose-500 dark:text-rose-400/80' }}"></i>
                            <span class="ml-3 text-sm">Notificaciones</span>
                        </div>
                        @php
                            $unreadNotificaciones = \App\Models\Notificacion::where('tenant_id', auth()->user()->tenant_id)->where('leido', false)->count();
                        @endphp
                        @if($unreadNotificaciones > 0)
                            <span class="bg-yellow-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-pulse shadow-sm">{{ $unreadNotificaciones }}</span>
                        @else
                            <span class="bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                        @endif
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom Actions -->
        <div class="p-4 border-t border-slate-200 dark:border-slate-800/50">
            <button @click="showLogoutModal = true" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors font-medium">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar sesión
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50 dark:bg-darkbg transition-colors duration-200">
        
        <!-- Top bar menu -->
        <header class="bg-white dark:bg-darkcard border-b border-slate-200 dark:border-slate-800 z-10 flex justify-between items-center px-6 py-3 h-16 transition-colors duration-200 shadow-sm">
            <h1 class="text-xl font-bold text-slate-800 dark:text-white">@yield('header_title', 'Panel de Control')</h1>
            
            <div class="flex items-center space-x-4">
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()" class="text-slate-500 dark:text-slate-400 hover:text-primary-600 dark:hover:text-primary-400 transition p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="fa-solid fa-moon" x-show="!darkMode"></i>
                    <i class="fa-solid fa-sun" x-show="darkMode" style="display: none;"></i>
                </button>
                
                <span class="w-px h-6 bg-slate-200 dark:bg-slate-700"></span>
                
                <a href="/" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 flex items-center gap-2 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i> <span>Ver Portal</span>
                </a>
            </div>
        </header>

        <!-- Main Content scrollable area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <div><span class="block text-sm">{{ session('success') }}</span></div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        <div><span class="block text-sm">{{ session('error') }}</span></div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Logout Confirmation Modal (Alpine.js) -->
    <div x-show="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-xl shadow-2xl p-6 w-full max-w-sm border border-slate-200 dark:border-slate-700 transform transition-all" @click.away="showLogoutModal = false">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket text-red-500"></i> Cerrar Sesión
            </h3>
            <p class="text-slate-600 dark:text-slate-400 mb-6 text-sm">¿Estás seguro de que deseas salir del panel de administración?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showLogoutModal = false" class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition font-medium">Cancelar</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-sm">Sí, salir</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Upgrade Plan Modal (Alpine.js) -->
    <div x-show="showUpgradeModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-8 w-full max-w-md border border-slate-200 dark:border-slate-700 transform transition-all text-center" @click.away="showUpgradeModal = false">
            <div class="w-20 h-20 bg-amber-50 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border border-amber-100 dark:border-amber-800/50 shadow-inner">
                <i class="fa-solid fa-crown text-4xl text-amber-500 dark:text-amber-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">¡Límite de Plan Gratuito!</h3>
            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm leading-relaxed">Estás en el plan gratuito donde <strong class="text-slate-800 dark:text-slate-200">solo se permite subir 1 propiedad</strong>. Actualiza tu plan para que puedas disfrutar subiendo más propiedades.</p>
            <div class="flex items-center justify-center gap-4">
                <button type="button" @click="showUpgradeModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full">
                    Más tarde
                </button>
                <a href="/publicar" class="px-5 py-2.5 text-sm font-bold bg-amber-500 hover:bg-amber-600 text-white rounded-xl shadow-sm hover:shadow transition-all border border-amber-600 focus:ring-4 focus:ring-amber-500/20 w-full flex justify-center items-center gap-2">
                    <i class="fa-solid fa-arrow-up"></i> Actualizar Plan
                </a>
            </div>
        </div>
    </div>

</body>
</html>
