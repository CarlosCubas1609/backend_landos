<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;
    protected $table = 'ordens';
    protected $fillable = ['cliente', 'cliente_id', 'telefono', 'vehiculo', 'vehiculo_id', 'placa', 'tipo_pago_id', 'efectivo', 'importe', 'servicio', 'servicio_id', 'total', 'descuento', 'estado'];
    public $timestamps = true;

    public function model_vehiculo()
    {
        return $this->belongsTo(Vehiculo::class,'vehiculo_id', 'id');
    }

    public function servicio_model() {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'id');
    }

    public function cliente_model() {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
}
