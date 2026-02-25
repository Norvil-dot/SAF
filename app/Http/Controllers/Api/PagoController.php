<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        return response()->json(Pago::with('contrato')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contrato_id' => 'required|exists:contratos,id',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $pago = Pago::create($validated);
        return response()->json($pago, 201);
    }
}
