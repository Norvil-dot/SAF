<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Propiedad Cerca / PropTech - @yield('title', 'Inicio')</title>
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
                            500: '#6366f1', // Indigo 500
                            600: '#4f46e5', // Indigo 600
                            700: '#4338ca',
                        },
                        secondary: '#475569', // Slate 600
                        accent: '#10b981', // Emerald 500
                        darkbg: '#0f172a', // Slate 900
                        darkcard: '#1e293b', // Slate 800
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
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
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-darkbg dark:text-slate-200 transition-colors duration-200 font-sans" 
      x-data="{ 
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          showLogoutModal: false,
          showModal: false,
          modalTitle: '',
          modalContent: '',
          toggleDarkMode() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
          },
          openInfoModal(title, content) {
              this.modalTitle = title;
              this.modalContent = content;
              this.showModal = true;
          }
      }"
      x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/80 dark:bg-darkcard/80 border-b border-slate-200 dark:border-slate-700 transition-colors duration-200">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold flex items-center gap-2 text-primary-600 dark:text-primary-400">
                <i class="fa-solid fa-city"></i> Tu Propiedad Cerca
            </a>
            <nav class="hidden md:flex space-x-6 items-center font-medium text-sm">
                <a href="/venta" class="text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition">Venta</a>
                <a href="/alquiler" class="text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition">Alquiler</a>
                <a href="/publicar" class="border border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400 px-4 py-1.5 rounded-md hover:bg-primary-600 hover:text-white dark:hover:bg-primary-400 dark:hover:text-slate-900 transition">PUBLICAR</a>
                
                <!-- Dark Mode Toggle -->
                <button @click="toggleDarkMode()" class="text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 transition p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none">
                    <i class="fa-solid fa-moon" x-show="!darkMode"></i>
                    <i class="fa-solid fa-sun" x-show="darkMode" style="display: none;"></i>
                </button>

                @auth
                    <div class="relative flex items-center space-x-4 border-l border-slate-300 dark:border-slate-700 pl-4 ml-2">
                        <a href="/dashboard" class="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center border border-primary-200 dark:border-primary-800">
                                <span class="font-bold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:block">{{ Auth::user()->name }}</span>
                        </a>
                        <button @click="showLogoutModal = true" class="text-slate-500 hover:text-red-600 dark:hover:text-red-400 transition" title="Salir">
                            <i class="fa-solid fa-right-from-bracket text-lg"></i>
                        </button>
                    </div>
                @else
                    <a href="/login" class="text-slate-600 dark:text-slate-300 hover:text-primary-600 dark:hover:text-primary-400 transition flex items-center gap-2">
                        <i class="fa-solid fa-user"></i> Iniciar sesión
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-darkcard border-t border-slate-200 dark:border-slate-800 mt-16 transition-colors duration-200">
        <div class="container mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <a href="/" class="text-2xl font-bold flex items-center gap-2 text-primary-600 dark:text-primary-400 mb-4">
                    <i class="fa-solid fa-city"></i> Tu Propiedad Cerca
                </a>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    Somos una empresa altamente capacitada para la publicidad y la comodidad de las personas que quieran adquirir propiedades. Tu próximo espacio ideal está aquí.
                </p>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 dark:text-white mb-4">Explorar</h4>
                <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Propiedades en Venta</a></li>
                    <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Propiedades en Alquiler</a></li>
                    <li><a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Terrenos y Lotes</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 dark:text-white mb-4">Empresa</h4>
                <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <li><a href="#" @click.prevent="openInfoModal('Acerca de nosotros', 'Somos un equipo de profesionales apasionados y altamente capacitados en el sector del PropTech y publicidad inmobiliaria. Nuestra misión principal es brindarte la máxima comodidad y seguridad a la hora de buscar, comprar o alquilar la propiedad de tus sueños. Nos esmeramos en ofrecer una plataforma intuitiva y en destacar las mejores oportunidades del mercado mediante estrategias publicitarias avanzadas. Ya sea que busques un hogar confortable o el local comercial ideal para impulsar tu negocio, estamos aquí para guiarte en todo el proceso. ¡El hogar de tus sueños o el éxito de tu empresa comienza aquí, con Tu Propiedad Cerca!')" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Acerca de nosotros</a></li>
                    <li><a href="#" @click.prevent="openInfoModal('Términos y condiciones', '¡Bienvenido a Tu Propiedad Cerca! Al acceder y utilizar nuestra plataforma, aceptas estos términos. Nuestra principal responsabilidad es proporcionar un espacio publicitario de alta calidad. Los precios y disponibilidad de los inmuebles mostrados están sujetos a cambios por parte de los propietarios sin previo aviso. Recomendamos siempre tomar precauciones estándar durante las visitas presenciales y acuerdos finales. Nos reservamos el derecho de eliminar cualquier solicitud, anuncio o usuario que incurra en prácticas dudosas o que viole nuestro compromiso de seguridad e integridad.')" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Términos y condiciones</a></li>
                    <li><a href="#" @click.prevent="openInfoModal('Política de privacidad', 'Tu tranquilidad es nuestra prioridad. En Tu Propiedad Cerca, todos los datos proporcionados por los usuarios son tratados con estricta confidencialidad. Implementamos medidas de seguridad de primer nivel para proteger tu información. Los datos recabados solo se usarán con el propósito publicitario y comunicacional dentro de la plataforma para conectar propiedades ideales con personas idóneas. Jamás venderemos ni expondremos tus datos personales a terceros sin tu consentimiento.')" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Política de privacidad</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 dark:text-white mb-4">Contacto</h4>
                <ul class="space-y-3 text-sm text-slate-500 dark:text-slate-400">
                    <li class="flex items-center gap-2"><i class="fa-solid fa-envelope w-4"></i> soporte@tupropiedadcerca.pe</li>
                    <li class="flex items-center gap-2"><i class="fa-solid fa-phone w-4"></i> +51 987 654 321</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-darkbg py-6 transition-colors duration-200">
            <div class="container mx-auto px-4 text-center text-sm text-slate-500 dark:text-slate-400">
                &copy; {{ date('Y') }} Tu Propiedad Cerca. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <!-- Logout Confirmation Modal (Alpine.js) -->
    <div x-show="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-xl shadow-2xl p-6 w-full max-w-sm border border-slate-200 dark:border-slate-700 transform transition-all" @click.away="showLogoutModal = false">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fa-solid fa-right-from-bracket text-primary-500"></i> Cerrar Sesión
            </h3>
            <p class="text-slate-600 dark:text-slate-400 mb-6 text-sm">¿Estás seguro que quieres salir de tu cuenta?</p>
            <div class="flex justify-end space-x-3">
                <button @click="showLogoutModal = false" class="px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition font-medium">Cancelar</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium shadow-sm">Sí, salir</button>
                </form>
            </div>
        </div>
    </div>

    <!-- General Info Modal (Alpine.js) -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
        <div class="bg-white dark:bg-darkcard rounded-xl shadow-2xl p-8 w-full max-w-2xl mx-4 border border-slate-200 dark:border-slate-700 transform transition-all max-h-[90vh] overflow-y-auto" @click.away="showModal = false">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 flex items-center justify-center">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white" x-text="modalTitle"></h3>
            </div>
            <p class="text-slate-600 dark:text-slate-300 mb-8 text-base leading-relaxed" x-text="modalContent"></p>
            <div class="flex justify-end">
                <button @click="showModal = false" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition font-bold shadow-sm">Entendido</button>
            </div>
        </div>
    </div>

</body>
</html>
