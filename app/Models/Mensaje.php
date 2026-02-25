<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $fillable = [
        'local_id',
        'tenant_id',
        'nombre',
        'telefono',
        'email',
        'mensaje',
        'leido'
    ];

    public function local()
    {
        return $this->belongsTo(Local::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
