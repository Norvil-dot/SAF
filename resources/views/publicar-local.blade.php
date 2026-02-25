@extends('layouts.dashboard')

@section('title', isset($local) ? 'Editar Local' : 'Publicar Mi Local')
@section('header_title', isset($local) ? 'Editar Inmueble' : 'Publicar Inmueble')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-darkcard rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 sm:p-10 transition-colors duration-200">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 border-b border-slate-200 dark:border-slate-800 pb-4 tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-900/40 text-primary-600 dark:text-primary-500 flex items-center justify-center border border-primary-100 dark:border-primary-800/50 shadow-sm">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            {{ isset($local) ? 'Editar Propiedad' : 'Detalles de tu Propiedad' }}
        </h1>
        
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 px-5 py-4 rounded-xl mb-8 flex gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl shrink-0 mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-sm mb-1">Por favor corrige los siguientes errores:</h4>
                    <ul class="list-disc pl-5 text-sm font-medium space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        <form action="{{ isset($local) ? route('dashboard.locales.update', $local) : route('dashboard.locales.store') }}" method="POST" enctype="multipart/form-data" x-data="imageUploader()" @submit.prevent="submitForm" class="space-y-8">
            @csrf
            @if(isset($local))
                @method('PUT')
            @endif
            
            <div class="bg-slate-50 dark:bg-slate-800/30 p-6 rounded-xl border border-slate-100 dark:border-slate-700/50 space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2"><i class="fa-solid fa-circle-info text-primary-500"></i> Información General</h3>
                
                <!-- Título y Tipo -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Título del anuncio</label>
                        <input type="text" name="titulo" value="{{ $local->titulo ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="Ej: Local comercial en esquina estratégica" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Tipo de Propiedad</label>
                        <select name="tipo_propiedad" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm cursor-pointer" required>
                            <option value="">Selecciona tipo</option>
                            <option value="Departamentos" {{ (isset($local) && $local->tipo_propiedad == 'Departamentos') ? 'selected' : '' }}>Departamentos</option>
                            <option value="Casas / Casa completa" {{ (isset($local) && $local->tipo_propiedad == 'Casas / Casa completa') ? 'selected' : '' }}>Casas / Casa completa</option>
                            <option value="Habitaciones" {{ (isset($local) && $local->tipo_propiedad == 'Habitaciones') ? 'selected' : '' }}>Habitaciones</option>
                            <option value="Locales" {{ (isset($local) && $local->tipo_propiedad == 'Locales') ? 'selected' : '' }}>Locales</option>
                            <option value="Oficinas y Consultorios" {{ (isset($local) && $local->tipo_propiedad == 'Oficinas y Consultorios') ? 'selected' : '' }}>Oficinas y Consultorios</option>
                            <option value="Terrenos" {{ (isset($local) && $local->tipo_propiedad == 'Terrenos') ? 'selected' : '' }}>Terrenos</option>
                            <option value="Depósitos" {{ (isset($local) && $local->tipo_propiedad == 'Depósitos') ? 'selected' : '' }}>Depósitos</option>
                            <option value="Fincas" {{ (isset($local) && $local->tipo_propiedad == 'Fincas') ? 'selected' : '' }}>Fincas</option>
                            <option value="Haciendas" {{ (isset($local) && $local->tipo_propiedad == 'Haciendas') ? 'selected' : '' }}>Haciendas</option>
                            <option value="Parqueaderos" {{ (isset($local) && $local->tipo_propiedad == 'Parqueaderos') ? 'selected' : '' }}>Parqueaderos</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Tipo de Operación</label>
                        <select name="operacion" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm cursor-pointer" required>
                            <option value="alquiler" {{ (isset($local) && $local->operacion == 'alquiler') ? 'selected' : '' }}>Alquiler</option>
                            <option value="venta" {{ (isset($local) && $local->operacion == 'venta') ? 'selected' : '' }}>Venta</option>
                        </select>
                    </div>
                </div>

                <!-- Precio y Área -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Precio</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500 dark:text-slate-400 font-bold">S/</span>
                            <input type="number" name="precio" value="{{ $local->precio_mensual ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl pl-10 pr-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="2500" required>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Área construida</label>
                        <div class="relative">
                            <input type="number" name="area" value="{{ $local->area ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl pr-10 pl-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="120" required>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 dark:text-slate-400 font-bold pointer-events-none">m²</span>
                        </div>
                    </div>
                </div>
                
                <!-- Baños -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Cantidad de baños</label>
                    <input type="number" name="banos" value="{{ $local->banos ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="Ej: 2" min="0" step="1">
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Descripción detallada</label>
                    <textarea name="descripcion" rows="5" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm resize-y" placeholder="Describe las características principales del local, entorno, vías de acceso, etc." required>{{ $local->descripcion ?? '' }}</textarea>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/30 p-6 rounded-xl border border-slate-100 dark:border-slate-700/50 space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2 flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-amber-500"></i> Ubicación</h3>
                
                <!-- Ubicación -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Distrito</label>
                        <input type="text" name="distrito" value="{{ $local->distrito ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="Ej: Miraflores" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">Dirección exacta</label>
                        <input type="text" name="direccion" value="{{ $local->direccion ?? '' }}" class="w-full bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl px-4 py-2.5 outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all shadow-sm" placeholder="Ej: Av. Larco 1234" required>
                    </div>
                </div>
            </div>

            <!-- Fotografías con Previsualización (Alpine JS) -->
            <div class="bg-slate-50 dark:bg-slate-800/30 p-6 rounded-xl border border-slate-100 dark:border-slate-700/50">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2"><i class="fa-regular fa-images text-emerald-500"></i> Fotografías de tu local</h3>
                
                <!-- Zona de carga (visible si no hay imágenes) -->
                <div x-show="images.length === 0" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-10 text-center bg-white dark:bg-slate-900/50 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/80 hover:border-primary-400 group cursor-pointer relative">
                    <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/30 text-primary-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-sm">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-2 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Arrastra tus fotos aquí o haz clic</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Soporta PNG, JPG, max 5MB por imagen</p>
                    <input type="file" multiple @change="handleFiles" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg">
                    <span class="inline-flex bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm group-hover:border-primary-500 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors pointer-events-none relative z-0">Seleccionar archivos</span>
                </div>

                <!-- Carrusel de Previsualización (visible si hay imágenes) -->
                <div x-show="images.length > 0" x-cloak class="relative rounded-xl overflow-hidden shadow-md bg-slate-900 aspect-video flex items-center justify-center group ring-1 ring-slate-200 dark:ring-slate-700">
                    <!-- Badge Superior -->
                    <div class="absolute top-4 left-4 z-10 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm text-slate-900 dark:text-white text-[10px] font-bold px-3 py-1.5 rounded-md shadow-sm border border-white/20 uppercase tracking-wider">
                        MODO VISTA PREVIA
                    </div>

                    <!-- Imágenes -->
                    <template x-for="(img, index) in images" :key="index">
                        <img x-show="currentIndex === index" :src="img" class="w-full h-full object-contain absolute inset-0 transition-opacity duration-300 bg-slate-100 dark:bg-slate-900">
                    </template>
                    
                    <!-- Controles Flechas -->
                    <button type="button" @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/60 dark:bg-slate-900/60 hover:bg-white dark:hover:bg-slate-800 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-800 dark:text-white transition-all z-10 shadow-md opacity-0 group-hover:opacity-100" x-show="images.length > 1">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/60 dark:bg-slate-900/60 hover:bg-white dark:hover:bg-slate-800 backdrop-blur-sm rounded-full flex items-center justify-center text-slate-800 dark:text-white transition-all z-10 shadow-md opacity-0 group-hover:opacity-100" x-show="images.length > 1">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                    
                    <!-- Barra Inferior -->
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black pb-4 pt-12 px-6 flex justify-between items-end text-white z-10">
                        <div class="flex items-center gap-6 text-sm font-bold bg-black/40 px-3 py-1.5 rounded-full backdrop-blur-sm">
                            <span class="flex items-center gap-2"><i class="fa-regular fa-image text-lg"></i> <span x-text="(currentIndex + 1) + ' / ' + images.length"></span></span>
                        </div>
                        
                        <!-- Botón para añadir más y eliminar la actual -->
                        <div class="flex gap-2">
                            <button type="button" @click="removeCurrent()" class="bg-red-500/90 hover:bg-red-500 text-white px-3 py-2 rounded-lg text-sm transition-colors font-bold shadow-sm backdrop-blur-sm border border-red-400/30" title="Eliminar foto actual">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <div class="relative inline-block">
                                <input type="file" multiple @change="handleFiles" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/png, image/jpeg, image/jpg">
                                <button type="button" class="bg-white hover:bg-slate-100 text-slate-900 px-4 py-2 rounded-lg text-sm transition-colors font-bold shadow-sm border border-slate-200">Añadir más fotos</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 border-t border-slate-200 dark:border-slate-800 pt-8 mt-8">
                <a href="/dashboard" class="px-6 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-900 dark:hover:text-white font-bold transition-colors text-center shadow-sm">Cancelar</a>
                <button type="button" @click="showConfirmModal = true" class="bg-primary-600 dark:bg-primary-500 text-white font-bold py-3 px-8 rounded-xl hover:bg-primary-700 dark:hover:bg-primary-600 transition-all flex items-center justify-center gap-2 shadow-sm focus:ring-4 focus:ring-primary-500/20 active:scale-[0.98]">
                    <i class="fa-solid fa-paper-plane"></i> {{ isset($local) ? 'Guardar Cambios' : 'Publicar Local' }}
                </button>
            </div>
            
            <!-- Confimration Submit Modal -->
            <div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity" style="display: none;" x-cloak>
                <div class="bg-white dark:bg-darkcard rounded-2xl shadow-2xl p-8 w-full max-w-md border border-slate-200 dark:border-slate-700 transform transition-all text-center" @click.away="showConfirmModal = false">
                    <div class="w-20 h-20 bg-primary-50 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-6 border border-primary-100 dark:border-primary-800/50 shadow-inner">
                        <i class="fa-solid fa-cloud-arrow-up text-4xl text-primary-500 dark:text-primary-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Confirmar Publicación</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">¿Estás seguro de que deseas confirmar la publicación de este local con los datos ingresados?</p>
                    
                    <div class="flex items-center justify-center gap-4">
                        <button type="button" @click="showConfirmModal = false" class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl transition-colors w-full">
                            Seguir editando
                        </button>
                        <button type="button" @click="executeSubmit($event)" class="px-6 py-2.5 text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-sm hover:shadow transition-all border border-primary-600 focus:ring-4 focus:ring-primary-500/20 w-full flex justify-center items-center gap-2">
                            <i class="fa-solid fa-check"></i> Sí, publicar
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageUploader', () => ({
        images: {!! isset($local) ? json_encode($local->imagenes ?? []) : '[]' !!},
        files: [],
        currentIndex: 0,
        showConfirmModal: false,
        handleFiles(event) {
            const newFiles = event.target.files;
            if (!newFiles) return;
            for(let i = 0; i < newFiles.length; i++) {
                this.files.push(newFiles[i]);
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.images.push(e.target.result);
                };
                reader.readAsDataURL(newFiles[i]);
            }
            // Reset input so the same files can be selected again if needed
            event.target.value = '';
        },
        next() {
            if(this.images.length === 0) return;
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        prev() {
            if(this.images.length === 0) return;
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        },
        removeCurrent() {
            this.images.splice(this.currentIndex, 1);
            this.files.splice(this.currentIndex, 1);
            if (this.currentIndex >= this.images.length && this.currentIndex > 0) {
                this.currentIndex--;
            } else if (this.images.length === 0) {
                this.currentIndex = 0;
            }
        },
        submitForm(event) {
            // Previene el envío del formulario usando enter en los campos (lo maneja Alpine)
        },
        executeSubmit(event) {
            const form = document.querySelector('form');
            
            // Check form validity before proceeding
            if (!form.checkValidity()) {
                this.showConfirmModal = false;
                form.reportValidity();
                return;
            }

            // Append existing images that were not deleted
            const existingImages = this.images.filter(img => !img.startsWith('data:'));
            existingImages.forEach(img => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'existing_images[]';
                hiddenInput.value = img;
                form.appendChild(hiddenInput);
            });

            // Append actual binary files to the form submission using DataTransfer
            if (this.files.length > 0) {
                const dataTransfer = new DataTransfer();
                this.files.forEach(file => dataTransfer.items.add(file));
                
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.multiple = true;
                hiddenInput.name = 'fotos[]';
                hiddenInput.files = dataTransfer.files;
                hiddenInput.style.display = 'none';
                
                form.appendChild(hiddenInput);
            }
            
            form.submit();
        }
    }));
});
</script>
@endsection
