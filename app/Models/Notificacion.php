<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $fillable = [
        'tenant_id',
        'local_id',
        'titulo',
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
