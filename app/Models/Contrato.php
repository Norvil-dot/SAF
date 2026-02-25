<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'local_id', 'cliente_id', 'fecha_inicio', 'fecha_fin', 'estado'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function local()
    {
        return $this->belongsTo(Local::class);
    }

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
