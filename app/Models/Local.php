<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;

class Local extends Model
{
    use HasFactory;

    protected $table = 'locales';

    protected $fillable = [
        'tenant_id', 'titulo', 'descripcion', 'direccion', 'precio_mensual', 'estado',
        'operacion', 'tipo_propiedad', 'area', 'distrito', 'imagenes',
        'alquilado_en', 'inquilino_nombre', 'banos'
    ];

    protected $casts = [
        'imagenes' => 'array',
        'alquilado_en' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
