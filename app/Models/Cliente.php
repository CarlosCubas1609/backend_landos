<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = 'clientes';
    protected $fillable = ['tipo_documento_id', 'documento', 'nombres', 'apellidos', 'celular', 'email', 'direccion', 'fecha_nacimiento', 'genero_id', 'estado'];
    public $timestamps = true;

    public function vehiculos() {
        return $this->hasMany(Vehiculo::class, 'cliente_id', 'id');
    }
}
