<?php

use App\Models\TipoPago;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('mes')) {
    function mes()
    {
        date_default_timezone_set("America/Lima");
        $mes = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"][date("n") - 1];
        return $mes;
    }
}

if (!function_exists('FullAccess')) {
    function FullAccess()
    {
        $user = session()->get('user');
        $fullaccess = false;
        if (count($user->roles) > 0) {
            $cont = 0;
            while ($cont < count($user->roles)) {
                if ($user->roles[$cont]['full-access'] == 'SI') {
                    $fullaccess = true;
                    $cont = count($user->roles);
                }

                $cont = $cont + 1;
            }
        }
        return $fullaccess;
    }
}


if (!function_exists('cuadreMovimientoCajaIngresosVentaResum')) {
    function cuadreMovimientoCajaIngresosVentaResum($ordenes, $id)
    {
        if ($id == 1) {
            $totalIngresos = 0;
            foreach ($ordenes as $item) {
                if ($item->estado_pago == 'PAGADA') { // && $item->sunat != '2'
                    // if ($item->tipo_pago_id == $id) {
                    //     $totalIngresos = $totalIngresos + $item->importe;
                    // } else {
                    //     $totalIngresos = $totalIngresos + $item->efectivo;
                    // }
                    $totalIngresos = $totalIngresos + $item->efectivo;
                }
            }
            return $totalIngresos;
        } else {
            $totalIngresos = 0;
            foreach ($ordenes as $item) {
                if ($item->estado_pago == 'PAGADA') { // && $item->sunat != '2'
                    if ($item->tipo_pago_id == $id) {
                        $totalIngresos = $totalIngresos + $item->importe;
                    }
                }
            }
            return $totalIngresos;
        }
    }
}

if (!function_exists('tipos_pago')) {
    function tipos_pago()
    {
        $tipos = TipoPago::where('estado', 'ACTIVO')->get();
        return $tipos;
    }
}

if (!function_exists('cuadreMovimientoCajaIngresosVentaElectronico')) {
    function cuadreMovimientoCajaIngresosVentaElectronico($ordenes)
    {
        $totalIngresos = 0;
        foreach (tipos_pago() as $tipo) {
            if ($tipo->id > 1) {
                $totalIngresos = $totalIngresos + (cuadreMovimientoCajaIngresosVentaResum($ordenes, $tipo->id));
            }
        }
        return $totalIngresos;
    }
}

if (!function_exists('cuadreMovimientoCajaIngresosVenta')) {
    function cuadreMovimientoCajaIngresosVenta($ordenes)
    {
        $totalIngresos = 0;
        foreach ($ordenes as $item) {
            if ($item->estado_pago == 'PAGADA') { // && $item->sunat != '2'
                $totalIngresos = $totalIngresos + ($item->importe + $item->efectivo);
            }
        }
        return $totalIngresos;
    }
}
