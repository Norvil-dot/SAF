@extends('layouts.public')

@section('title', 'Registro de Plan')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    /* Fix intl-tel-input width inside full width container */
    .iti { width: 100%; }
</style>

<!-- intlTelInput CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>

<div class="container mx-auto px-4 py-16 flex justify-center" 
     x-data="checkoutForm()" x-init="initPhones()">
    <div class="bg-white dark:bg-slate-900 rounded border dark:border-slate-800 shadow-lg p-8 w-full" :class="step === 1 ? 'max-w-md' : 'max-w-5xl'">

        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Contenido Izquierdo: Pasos -->
            <div class="w-full transition-all" :class="step === 1 ? '' : 'md:w-2/3'">
                
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 relative" role="alert" 
                         x-init="step = {{ $errors->has('email') || $errors->has('password') ? 1 : ($errors->has('stripe_error') ? 3 : 2) }}">
                        <strong class="font-bold">Error en el proceso:</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="mb-8" :class="step === 1 ? 'text-center' : ''">
                    <a href="/" class="text-2xl font-bold text-[#00cfa5] flex items-center gap-2 mb-4" :class="step === 1 ? 'justify-center' : ''">
                        <i class="fa-solid fa-city"></i> Tu Propiedad Cerca
                    </a>
                    
                    <!-- Navegación por pasos visual (Oculto en paso 1) -->
                    <div class="flex items-center space-x-4 mb-6 text-sm" x-show="step > 1" x-cloak>
                        <div class="flex items-center gap-2" :class="step >= 1 ? 'text-blue-600 font-bold' : 'text-gray-400'" @if(auth()->check()) x-show="false" @endif>
                            <span class="w-6 h-6 rounded-full border flex items-center justify-center bg-blue-100 border-blue-500 text-blue-500">
                                <i class="fa-solid fa-check text-xs"></i>
                            </span>
                            Facturación
                        </div>
                        <div class="h-px bg-gray-300 w-8" @if(auth()->check()) x-show="false" @endif></div>
                        <div class="flex items-center gap-2" :class="step >= 2 ? 'text-blue-600 font-bold' : 'text-gray-400'" @if(auth()->check()) x-show="false" @endif>
                            <span class="w-6 h-6 rounded-full border flex items-center justify-center" :class="step > 2 ? 'bg-blue-100 border-blue-500 text-blue-500' : (step === 2 ? 'bg-transparent border-blue-600' : 'border-gray-400')">
                                <span x-show="step <= 2">2</span>
                                <i class="fa-solid fa-check text-xs" x-show="step > 2" x-cloak></i>
                            </span>
                            Facturación
                        </div>
                        @if(request('plan', 1) != 0 && !auth()->check())
                        <div class="h-px bg-gray-300 w-8"></div>
                        @endif
                        @if(request('plan', 1) != 0)
                        <div class="flex items-center gap-2" :class="step >= 3 ? 'text-blue-600 font-bold' : 'text-gray-400'">
                            <span class="w-6 h-6 rounded-full border flex items-center justify-center border-gray-400" @if(auth()->check()) x-show="false" @endif>3</span>
                            Pago y Confirmación
                        </div>
                        @endif
                    </div>

                    <h1 class="text-xl font-bold text-[#122b40] dark:text-white" x-show="step === 1">Completa tus datos para continuar</h1>
                    <h1 class="text-xl font-bold text-[#122b40] dark:text-white" x-show="step === 2" x-cloak>Datos de {{ request('plan', 1) == 0 ? 'la Empresa' : 'Facturación' }}</h1>
                    @if(request('plan', 1) != 0)
                    <h1 class="text-xl font-bold text-[#122b40] dark:text-white" x-show="step === 3" x-cloak>Pago y Confirmación</h1>
                    @endif
                </div>

                <form action="/checkout/process" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="plan" value="{{ request('plan', 1) }}">

                    <!-- PASO 1: Datos Básicos -->
                    <div x-show="step === 1" class="space-y-4">
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Nombre y Apellido / Nombre de la empresa</label>
                            <input type="text" x-model="form1.nombre" name="nombre" placeholder="Ej: Juan Pérez" value="{{ old('nombre') }}" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted1 && !form1.nombre ? 'border-orange-400' : ''">
                            <p x-show="attempted1 && !form1.nombre" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                        </div>
                        
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Email</label>
                            <input type="email" x-model="form1.email" name="email" placeholder="Ej: correo@empresa.com" value="{{ old('email') }}" autocomplete="username" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted1 && !form1.email ? 'border-orange-400' : ''">
                            <p x-show="attempted1 && !form1.email" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                        </div>
                        
                        @if(!auth()->check())
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Contraseña para tu panel de control</label>
                            <input type="password" x-model="form1.password" name="password" placeholder="Mínimo 6 caracteres" autocomplete="new-password" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted1 && !form1.password ? 'border-orange-400' : ''">
                            <p x-show="attempted1 && !form1.password" class="text-xs text-orange-500 mt-1" x-cloak>Debes ingresar una contraseña para tu cuenta</p>
                            <p class="text-xs text-gray-500 mt-1">Con este correo y contraseña podrás iniciar sesión luego del pago.</p>
                        </div>
                        @endif
                        
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Teléfono</label>
                            <div class="w-full">
                                <input type="tel" id="phone1" x-model="form1.telefono" name="telefono" placeholder="987 654 321" value="{{ old('telefono') }}" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted1 && !form1.telefono ? 'border-orange-400' : ''">
                                <p x-show="attempted1 && !form1.telefono" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex gap-3 text-sm text-gray-600 text-left">
                            <input type="checkbox" id="terms1" x-model="form1.terms" class="mt-1 accent-[#00cfa5]">
                            <label for="terms1">Estás de acuerdo con nuestra <a href="#" class="text-blue-500 hover:underline">política de privacidad</a>, y con nuestros <a href="#" class="text-blue-500 hover:underline">términos y condiciones</a>.</label>
                        </div>
                        <p x-show="attempted1 && !form1.terms" class="text-xs text-orange-500 mt-1" x-cloak>Debes aceptar los términos y condiciones</p>

                        <button type="button" @click="goToStep(2)" class="w-full bg-[#1e6df5] text-white font-bold py-3 rounded-md hover:bg-blue-600 transition mt-4">
                            Continuar
                        </button>
                    </div>

                    <!-- PASO 2: Facturación -->
                    <div x-show="step === 2" x-cloak class="space-y-4">
                        <div class="mb-2">
                            <button type="button" @click="step = 1" class="text-sm text-[#122b40] dark:text-white font-semibold hover:underline flex items-center gap-2">
                                <i class="fa-solid fa-chevron-left"></i> Volver a Datos básicos
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Tipo de persona</label>
                                <select name="tipo_persona" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900 bg-white">
                                    <option value="fisica">Física</option>
                                    <option value="juridica">Jurídica</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">RUC/DNI</label>
                                <input type="text" x-model="form2.ruc" name="dni_ruc" placeholder="Ej: 20123456789" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.ruc ? 'border-orange-400' : ''">
                                <p x-show="attempted2 && !form2.ruc" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Nombre o razón social</label>
                            <input type="text" x-model="form2.razon_social" name="razon_social" placeholder="Ej: Inversiones SAC" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.razon_social ? 'border-orange-400' : ''">
                            <p x-show="attempted2 && !form2.razon_social" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Email</label>
                            <input type="email" name="email_facturacion" placeholder="factura@empresa.com" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Dirección fiscal</label>
                            <input type="text" x-model="form2.direccion" name="direccion_fiscal" placeholder="Av. Principal 123" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.direccion ? 'border-orange-400' : ''">
                            <p x-show="attempted2 && !form2.direccion" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Ciudad</label>
                                <input type="text" x-model="form2.ciudad" name="ciudad" placeholder="Ej: Lima" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.ciudad ? 'border-orange-400' : ''">
                                <p x-show="attempted2 && !form2.ciudad" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Provincia</label>
                                <input type="text" x-model="form2.provincia" name="provincia" placeholder="Ej: Lima" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.provincia ? 'border-orange-400' : ''">
                                <p x-show="attempted2 && !form2.provincia" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Código postal</label>
                                <input type="text" x-model="form2.postal" name="codigo_postal" placeholder="Ej: 15001" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.postal ? 'border-orange-400' : ''">
                                <p x-show="attempted2 && !form2.postal" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Teléfono</label>
                                <div class="w-full">
                                    <input type="tel" id="phone2" x-model="form2.telefono" name="telefono_facturacion" placeholder="987 654 321" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" :class="attempted2 && !form2.telefono ? 'border-orange-400' : ''">
                                    <p x-show="attempted2 && !form2.telefono" class="text-xs text-orange-500 mt-1" x-cloak>Este campo no puede estar vacío</p>
                                </div>
                            </div>
                        </div>

                        @if(request('plan', 1) == 0)
                        <button type="button" id="submit-button-free" @click="goToStep(3)" class="w-auto px-8 bg-[#1e6df5] text-white font-bold py-3 rounded-md hover:bg-blue-600 transition mt-4">
                            Crear cuenta gratis
                        </button>
                        @else
                        <button type="button" @click="goToStep(3)" class="w-auto px-8 bg-[#1e6df5] text-white font-bold py-3 rounded-md hover:bg-blue-600 transition mt-4">
                            Continuar
                        </button>
                        @endif
                    </div>

                    <!-- PASO 3: Pago -->
                    @if(request('plan', 1) != 0)
                    <div x-show="step === 3" x-cloak>
                        @if(!auth()->check())
                        <div class="mb-6">
                            <button type="button" @click="step = 2" class="text-sm text-[#122b40] dark:text-white font-semibold hover:underline flex items-center gap-2">
                                <i class="fa-solid fa-chevron-left"></i> Volver a Datos de facturación
                            </button>
                        </div>
                        @endif
                        
                        <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-transparent dark:border-blue-800 p-4 rounded mb-6 text-sm">
                            Al añadir tu tarjeta aceptas que se te cobre de forma recurrente por la suscripción.
                        </div>

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg text-[#122b40] dark:text-white">Tarjeta de crédito o débito</h3>
                            <div class="flex gap-2">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="Amex" class="h-6">
                            </div>
                        </div>
                        
                        <div class="mb-6 border rounded p-4 shadow-sm bg-white dark:bg-slate-900 dark:border-slate-700">
                            <!-- Stripe Element -->
                            <div id="card-element"></div>
                            <!-- Display errors -->
                            <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Nombre del titular como aparece en la tarjeta</label>
                            <input type="text" id="card-holder-name" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" required>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-2 text-[#122b40] dark:text-white">Documento del titular</label>
                            <div class="flex gap-2">
                                <select class="border rounded px-2 py-2 w-1/4 outline-none bg-gray-50 dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:bg-slate-800">
                                    <option>DNI</option>
                                    <option>CE</option>
                                </select>
                                <input type="text" class="w-full border rounded px-3 py-2 outline-none focus:border-[#00cfa5] transition bg-white dark:bg-slate-900 dark:border-slate-700 dark:text-white dark:placeholder-slate-400 text-slate-900" required>
                            </div>
                        </div>

                        <div class="mb-6 flex gap-3 text-sm text-gray-600">
                            <input type="checkbox" id="terms-payment" class="mt-1 accent-[#00cfa5]" required>
                            <label for="terms-payment">Estás de acuerdo con nuestra <a href="#" class="text-blue-500 hover:underline">política de privacidad</a>, y con nuestros <a href="#" class="text-blue-500 hover:underline">términos y condiciones</a>.</label>
                        </div>

                        <button type="submit" id="submit-button" class="w-full bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-gray-500 font-bold py-3 rounded-md transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock"></i> Aceptar y confirmar
                        </button>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Contenido Derecho: Resumen -->
            <div class="w-full md:w-1/3" x-show="step > 1" x-cloak>
                <div class="bg-gray-50 dark:bg-slate-800 rounded p-6 shadow-sm border dark:border-slate-700">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-4 uppercase">Resumen</h3>
                    @if(request('plan', 1) == 0)
                    <div class="flex justify-between mb-2 text-sm text-[#122b40] dark:text-white">
                        <span>Plan Gratuito</span>
                        <span>S/ 0</span>
                    </div>
                    <div class="flex justify-between mb-4 text-sm text-[#122b40] dark:text-white border-b pb-4">
                        <span>Duración</span>
                        <span>Ilimitado</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-[#122b40] dark:text-white">
                        <span>Total</span>
                        <span>S/ 0</span>
                    </div>
                    @else
                    <div class="flex justify-between mb-2 text-sm text-[#122b40] dark:text-white">
                        <span>Plan {{ request('plan', 1) == '2' ? '25 propiedades' : (request('plan', 1) == '3' ? '50 propiedades' : '10 propiedades') }}</span>
                        <span>S/ {{ request('plan', 1) == '2' ? '99' : (request('plan', 1) == '3' ? '199' : '49') }}</span>
                    </div>
                    <div class="flex justify-between mb-4 text-sm text-[#122b40] dark:text-white border-b pb-4">
                        <span>Duración del plan</span>
                        <span>1 mes</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-[#122b40] dark:text-white">
                        <span>Total (S/ IGV)</span>
                        <span>S/ {{ request('plan', 1) == '2' ? '99' : (request('plan', 1) == '3' ? '199' : '49') }}</span>
                    </div>
                    @endif
                </div>
                @if(request('plan', 1) != 0)
                <div class="mt-4" x-show="step === 2" x-cloak>
                    <a href="#" class="text-blue-500 text-sm hover:underline">Descargar proforma</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- intlTelInput Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<!-- Alpine Logic -->
<script>
    function checkoutForm() {
        return {
            step: {{ auth()->check() ? 3 : 1 }},
            attempted1: false,
            attempted2: false,
            form1: {
                nombre: '{{ auth()->user()->name ?? "" }}',
                email: '{{ auth()->user()->email ?? "" }}',
                @if(!auth()->check()) password: '', @endif
                telefono: '',
                terms: {{ auth()->check() ? 'true' : 'false' }}
            },
            form2: {
                ruc: '',
                razon_social: '',
                direccion: '',
                ciudad: '',
                provincia: '',
                postal: '',
                telefono: ''
            },
            initPhones() {
                const phone1 = document.querySelector("#phone1");
                const phone2 = document.querySelector("#phone2");
                
                const itiConfig = {
                    initialCountry: "pe",
                    preferredCountries: ["pe", "co", "cl", "ar", "mx"],
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                };

                const iti1 = window.intlTelInput(phone1, itiConfig);
                const iti2 = window.intlTelInput(phone2, itiConfig);

                // Update alpine model when input changes via flags
                phone1.addEventListener('countrychange', () => { this.form1.telefono = phone1.value; });
                phone1.addEventListener('keyup', () => { this.form1.telefono = phone1.value; });
                
                phone2.addEventListener('countrychange', () => { this.form2.telefono = phone2.value; });
                phone2.addEventListener('keyup', () => { this.form2.telefono = phone2.value; });
            },
            goToStep(targetStep) {
                if (targetStep === 2) {
                    this.attempted1 = true;
                    // Validate Step 1
                    let isValid = this.form1.nombre && this.form1.email && this.form1.telefono && this.form1.terms;
                    @if(!auth()->check()) 
                        isValid = isValid && this.form1.password; 
                    @endif
                    
                    if (isValid) {
                        this.step = 2;
                    }
                } else if (targetStep === 3) {
                    this.attempted2 = true;
                    // Validate Step 2
                    if (this.form2.ruc && this.form2.razon_social && this.form2.direccion && 
                        this.form2.ciudad && this.form2.provincia && this.form2.postal && this.form2.telefono) {
                        
                        @if(request('plan', 1) == 0)
                            let btn = document.getElementById('submit-button-free');
                            if(btn) {
                                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Creando cuenta...';
                                btn.disabled = true;
                            }
                            document.getElementById('checkout-form').submit();
                        @else
                            this.step = 3;
                        @endif
                    }
                }
            }
        }
    }
</script>

<!-- Stripe Script -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Configura la clave pública de prueba
    var stripe = Stripe('pk_test_TYooMQauvdEDq54NiTphI7jx'); 
    var elements = stripe.elements();

    function getStripeStyle() {
        var isDark = document.documentElement.classList.contains('dark');
        return {
          base: {
            color: isDark ? '#ffffff' : '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: isDark ? '#94a3b8' : '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };
    }

    var card = elements.create('card', {style: getStripeStyle(), hidePostalCode: true});
    
    // Solo montar el elemento cuando estemos en la página para evitar errores
    document.addEventListener('alpine:init', () => {
        card.mount('#card-element');
        
        // Observer para cambios en dark mode (captura la carga inicial y los cambios manuales)
        const observer = new MutationObserver(() => {
            card.update({style: getStripeStyle()});
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    });

    card.on('change', function(event) {
      var displayError = document.getElementById('card-errors');
      var submitButton = document.getElementById('submit-button');
      
      if (event.error) {
        displayError.textContent = event.error.message;
        submitButton.classList.remove('bg-[#1e6df5]', 'text-white', 'hover:bg-blue-600', 'dark:bg-[#1e6df5]', 'dark:text-white', 'dark:hover:bg-blue-600');
        submitButton.classList.add('bg-gray-100', 'text-gray-400', 'dark:bg-slate-800', 'dark:text-gray-500');
      } else {
        displayError.textContent = '';
        if(event.complete) {
            submitButton.classList.remove('bg-gray-100', 'text-gray-400', 'dark:bg-slate-800', 'dark:text-gray-500');
            submitButton.classList.add('bg-[#1e6df5]', 'text-white', 'hover:bg-blue-600', 'dark:bg-[#1e6df5]', 'dark:text-white', 'dark:hover:bg-blue-600');
        } else {
            submitButton.classList.remove('bg-[#1e6df5]', 'text-white', 'hover:bg-blue-600', 'dark:bg-[#1e6df5]', 'dark:text-white', 'dark:hover:bg-blue-600');
            submitButton.classList.add('bg-gray-100', 'text-gray-400', 'dark:bg-slate-800', 'dark:text-gray-500');
        }
      }
    });

    var form = document.getElementById('checkout-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      stripe.createToken(card).then(function(result) {
        if (result.error) {
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          stripeTokenHandler(result.token);
        }
      });
    });

    function stripeTokenHandler(token) {
      var form = document.getElementById('checkout-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);
      
      // Mostrar al usuario que está procesando
      var submitButton = document.getElementById('submit-button');
      submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Procesando...';
      submitButton.disabled = true;

      // Submit form
      form.submit();
    }
</script>
@endsection
