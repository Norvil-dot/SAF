<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function index()
    {
        return response()->json(Local::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'descripcion' => 'nullable|string',
            'direccion' => 'required|string',
            'precio_mensual' => 'required|numeric',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $local = Local::create($validated);
        return response()->json($local, 201);
    }

    public function show(Local $local)
    {
        return response()->json($local);
    }

    public function update(Request $request, Local $local)
    {
        $validated = $request->validate([
            'titulo' => 'sometimes|string',
            'descripcion' => 'nullable|string',
            'direccion' => 'sometimes|string',
            'precio_mensual' => 'sometimes|numeric',
            'estado' => 'sometimes|string|in:disponible,alquilado',
        ]);

        $local->update($validated);
        return response()->json($local);
    }

    public function destroy(Local $local)
    {
        $local->delete();
        return response()->json(null, 204);
    }
}
