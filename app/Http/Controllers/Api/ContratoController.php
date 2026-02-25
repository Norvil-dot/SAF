<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Pago;
use App\Services\ContratoService;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    protected $contratoService;

    public function __construct(ContratoService $contratoService)
    {
        $this->contratoService = $contratoService;
    }

    public function index()
    {
        return response()->json(Contrato::with(['local', 'cliente'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'local_id' => 'required|exists:locales,id',
            'cliente_id' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date',
        ]);

        try {
            $contrato = $this->contratoService->crearContrato($validated);
            return response()->json($contrato, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show(Contrato $contrato)
    {
        $contrato->load(['local', 'cliente', 'pagos']);
        return response()->json($contrato);
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'fecha_fin' => 'nullable|date',
            'estado' => 'sometimes|string|in:activo,finalizado',
        ]);

        $contrato->update($validated);

        if (isset($validated['estado']) && $validated['estado'] === 'finalizado') {
            $contrato->local->update(['estado' => 'disponible']);
        }

        return response()->json($contrato);
    }
}
