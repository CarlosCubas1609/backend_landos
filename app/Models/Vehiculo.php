<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;
    protected $table = 'vehiculos';
    protected $fillable = ['placa', 'color', 'modelo', 'marca', 'url_foto_placa', 'cliente_id', 'estado'];
    public $timestamps = true;

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
}
