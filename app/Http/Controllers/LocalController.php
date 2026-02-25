<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Local;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LocalController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'operacion' => 'required|in:alquiler,venta',
            'tipo_propiedad' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'distrito' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'banos' => 'nullable|integer|min:0',
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg|max:5120', // Max 5MB per image
        ]);

        $user = Auth::user();
        if (!$user->tenant_id) {
            $tenant = Tenant::create([
                'nombre' => 'Inmobiliaria de ' . $user->name,
                'plan' => 'Gratuito',
            ]);
            $user->tenant_id = $tenant->id;
            $user->save();
        } else {
            $tenant = $user->tenant;
        }

        // Validate property limits based on the tenant's plan
        $propertyCount = Local::where('tenant_id', $tenant->id)->count();
        $limit = 1; // Default
        switch ($tenant->plan) {
            case 'Gratuito': $limit = 1; break;
            case 'Básico': $limit = 10; break;
            case 'Pro': $limit = 25; break;
            case 'Elite': $limit = 50; break;
        }

        if ($propertyCount >= $limit) {
            $msgLimit = $limit == 1 ? "1 propiedad" : "{$limit} propiedades";
            return redirect()->back()
                ->withInput()
                ->with('error', "Estás en el plan {$tenant->plan} y has alcanzado el límite de {$msgLimit}. Si deseas subir más propiedades, por favor mejora tu plan en la sección correspondiente.");
        }

        $imagePaths = [];
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                // Generar nombre único
                $filename = uniqid('local_') . '.' . $file->getClientOriginalExtension();
                // Subir a storage/app/public/locales
                $path = $file->storeAs('locales', $filename, 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        Local::create([
            'tenant_id' => $tenant->id,
            'titulo' => $request->titulo,
            'operacion' => $request->operacion,
            'tipo_propiedad' => $request->tipo_propiedad,
            'precio_mensual' => $request->precio,
            'area' => $request->area,
            'descripcion' => $request->descripcion,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'banos' => $request->banos,
            'estado' => 'disponible',
            'imagenes' => $imagePaths,
        ]);

        return redirect()->route('mis-inmuebles')->with('success', '¡Inmueble publicado exitosamente!');
    }

    public function edit(Local $local)
    {
        if ($local->tenant_id != Auth::user()->tenant_id) abort(403);
        return view('publicar-local', compact('local'));
    }

    public function update(Request $request, Local $local)
    {
        if ($local->tenant_id != Auth::user()->tenant_id) abort(403);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'operacion' => 'required|in:alquiler,venta',
            'tipo_propiedad' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'area' => 'required|numeric|min:0',
            'descripcion' => 'required|string',
            'distrito' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'banos' => 'nullable|numeric|min:0',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'existing_images' => 'nullable|array'
        ]);

        $imagePaths = $request->input('existing_images', []);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                // Generar nombre único
                $filename = uniqid('local_') . '.' . $file->getClientOriginalExtension();
                // Subir a storage/app/public/locales
                $path = $file->storeAs('locales', $filename, 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        // Eliminar las imágenes que el usuario quitó en la vista
        if ($local->imagenes) {
            $removedImages = array_diff($local->imagenes, $imagePaths);
            foreach ($removedImages as $removedImage) {
                $path = str_replace('/storage/', '', $removedImage);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        
        $local->update([
            'titulo' => $request->titulo,
            'operacion' => $request->operacion,
            'tipo_propiedad' => $request->tipo_propiedad,
            'precio_mensual' => $request->precio,
            'area' => $request->area,
            'descripcion' => $request->descripcion,
            'distrito' => $request->distrito,
            'direccion' => $request->direccion,
            'banos' => $request->banos,
            'imagenes' => $imagePaths
        ]);
        
        return redirect()->route('mis-inmuebles')->with('success', '¡Inmueble actualizado exitosamente!');
    }

    public function destroy(Local $local)
    {
        if ($local->tenant_id != Auth::user()->tenant_id) abort(403);
        
        if ($local->imagenes) {
            foreach ($local->imagenes as $img) {
                $path = str_replace('/storage/', '', $img);
                Storage::disk('public')->delete($path);
            }
        }
        
        $local->delete();
        
        return redirect()->route('mis-inmuebles')->with('success', 'Inmueble eliminado correctamente.');
    }

    public function marcarAlquilado(Request $request, Local $local)
    {
        if ($local->tenant_id != Auth::user()->tenant_id) abort(403);

        $request->validate([
            'inquilino_nombre' => 'nullable|string|max:255'
        ]);

        $local->update([
            'estado' => 'alquilado',
            'alquilado_en' => now(),
            'inquilino_nombre' => $request->inquilino_nombre ?? 'No registrado'
        ]);

        \App\Models\Notificacion::create([
            'tenant_id' => $local->tenant_id,
            'local_id' => $local->id,
            'titulo' => 'Propiedad Alquilada',
            'mensaje' => 'La propiedad "' . $local->titulo . '" en ' . $local->distrito . ' fue marcada como ALQUILADA exitosamente. Inquilino: ' . ($request->inquilino_nombre ?? 'No registrado'),
            'leido' => false
        ]);

        return redirect()->route('mis-inmuebles')->with('success', '¡La propiedad "' . $local->titulo . '" en ' . $local->distrito . ' fue marcada como ALQUILADA exitosamente!');
    }

    public function marcarDisponible(Local $local)
    {
        if ($local->tenant_id != Auth::user()->tenant_id) abort(403);

        $local->update([
            'estado' => 'disponible',
            'alquilado_en' => null,
            'inquilino_nombre' => null
        ]);

        \App\Models\Notificacion::create([
            'tenant_id' => $local->tenant_id,
            'local_id' => $local->id,
            'titulo' => 'Propiedad Disponible',
            'mensaje' => 'La propiedad "' . $local->titulo . '" en ' . $local->distrito . ' fue liberada y ahora está DISPONIBLE para alquiler/venta.',
            'leido' => false
        ]);

        return redirect()->route('mis-inmuebles')->with('success', '¡La propiedad "' . $local->titulo . '" en ' . $local->distrito . ' fue liberada y ahora está DISPONIBLE!');
    }
}
