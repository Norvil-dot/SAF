<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'plan', 'estado'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function locales()
    {
        return $this->hasMany(Local::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
