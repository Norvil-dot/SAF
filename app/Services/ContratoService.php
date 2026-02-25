<?php

namespace App\Services;

use App\Models\Contrato;
use App\Models\Local;
use Illuminate\Support\Facades\DB;
use Exception;

class ContratoService
{
    /**
     * Crear un nuevo contrato y actualizar el estado del local.
     */
    public function crearContrato(array $data)
    {
        DB::beginTransaction();

        try {
            $local = Local::findOrFail($data['local_id']);
            
            if ($local->estado !== 'disponible') {
                throw new Exception("El local no está disponible.");
            }

            $contrato = Contrato::create([
                'tenant_id' => auth()->user()->tenant_id,
                'local_id' => $data['local_id'],
                'cliente_id' => $data['cliente_id'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'] ?? null,
                'estado' => 'activo'
            ]);

            $local->update(['estado' => 'alquilado']);

            DB::commit();

            return $contrato;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
