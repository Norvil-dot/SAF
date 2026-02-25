<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'contrato_id', 'monto', 'fecha_pago', 'estado'
    ];

    protected $casts = [
        'fecha_pago' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
