<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Caja Diaria</title>
    <link rel="icon" href="{{ base_path() . '/img/siscom.ico' }}" />

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: black;
        }

        .cabecera {
            width: 100%;
            position: relative;
            height: 100px;
            max-height: 150px;
        }

        .logo {
            width: 30%;
            position: absolute;
            left: 0%;
        }

        .logo .logo-img {
            position: relative;
            width: 95%;
            margin-right: 5%;
            height: 90px;
        }

        .img-fluid {
            width: 100%;
            height: 100%;
        }

        .empresa {
            width: 60%;
            position: absolute;
            left: 30%;
        }

        .empresa .empresa-info {
            position: relative;
            width: 100%;
        }

        .nombre-empresa {
            font-size: 16px;
        }

        .ruc-empresa {
            font-size: 15px;
        }

        .direccion-empresa {
            font-size: 12px;
        }

        .text-info-empresa {
            font-size: 12px;
        }

        .comprobante {
            width: 30%;
            position: absolute;
            left: 70%;
        }

        .comprobante .comprobante-info {
            position: relative;
            width: 100%;
            display: flex;
            align-content: center;
            align-items: center;
            text-align: center;
        }

        .numero-documento {
            margin: 1px;
            padding-top: 20px;
            padding-bottom: 20px;
            border: 2px solid #2471A3;
            font-size: 14px;
        }

        .nombre-documento {
            margin-top: 5px;
            margin-bottom: 5px;
            margin-left: 0px;
            margin-right: 0px;
            width: 100%;
            background-color: #7DCEA0;
        }

        .logos-empresas {
            width: 100%;
            height: 105px;
        }

        .img-logo {
            width: 95%;
            height: 100px;
        }

        .logo-empresa {
            width: 14.2%;
            float: left;
        }

        .informacion {
            width: 100%;
            position: relative;
            border: 2px solid #2471A3;
        }

        .tbl-informacion {
            width: 100%;
            font-size: 12px;
        }

        .cuerpo {
            width: 100%;
            position: relative;
            border: 1px solid #2471A3;
            margin-top: 10px;
        }

        .tbl-detalles {
            width: 100%;
            font-size: 12px;
        }

        .tbl-detalles thead {
            border-top: 2px solid #2471A3;
            border-left: 2px solid #2471A3;
            border-right: 2px solid #2471A3;
        }

        .tbl-detalles tbody {
            border-top: 2px solid #2471A3;
            border-bottom: 2px solid #2471A3;
            border-left: 2px solid #2471A3;
            border-right: 2px solid #2471A3;
        }

        .info-total-qr {
            position: relative;
            width: 100%;
        }

        .tbl-total {
            width: 100%;
            border: 2px solid #2471A3;
        }

        .qr-img {
            margin-top: 15px;
        }

        .text-cuerpo {
            font-size: 12px
        }

        .tbl-qr {
            width: 100%;
        }

        /*---------------------------------------------*/

        .m-0 {
            margin: 0;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .p-0 {
            padding: 0;
        }

        .cont-check{
            position: relative;
        }

        .checkmark {
            display:inline-block;
            width: 22px;
            height:22px;
            -ms-transform: rotate(45deg); /* IE 9 */
            -webkit-transform: rotate(45deg); /* Chrome, Safari, Opera */
            transform: rotate(45deg);
        }

        .checkmark_stem {
            position: absolute;
            width:3px;
            height:12px;
            background-color:#2471A3;
            left:11px;
            top:6px;
        }

        .checkmark_kick {
            position: absolute;
            width:3px;
            height:3px;
            background-color:#2471A3;
            left:8px;
            top:15px;
        }

        .cont-remove{
            position: relative;
        }

        .remove {
            display:inline-block;
            width: 22px;
            height:22px;
            -ms-transform: rotate(45deg); /* IE 9 */
            -webkit-transform: rotate(45deg); /* Chrome, Safari, Opera */
            transform: rotate(45deg);
        }

        .remove_stem {
            position: absolute;
            width:3px;
            height:12px;
            background-color: brown;
            left:11px;
            top:6px;
        }

        .remove_kick {
            position: absolute;
            width:12px;
            height:3px;
            background-color:brown;
            left:7px;
            top:10px;
        }

    </style>
</head>

<body>
    <div class="cabecera">
        <div class="logo">
            <div class="logo-img">
                @if (DB::table('empresas')->count() == 0)
                    <img src="{{ public_path() . '/img/default.png' }}" class="img-fluid">
                @else
                    <img src="{{ DB::table('empresas')->first()->ruta_logo ? base_path() . '/storage/app/' . DB::table('empresas')->first()->ruta_logo : public_path() . '/img/default.png' }}" class="img-fluid">
                @endif
            </div>
        </div>
        <div class="empresa">
            <div class="empresa-info">
                <p class="m-0 p-0 text-uppercase nombre-empresa">
                    {{ DB::table('empresas')->count() == 0 ? 'SYSTEM CR' : DB::table('empresas')->first()->razon_social }}
                </p>
                <p class="m-0 p-0 text-uppercase direccion-empresa">
                    {{ DB::table('empresas')->count() == 0 ? '- ' : DB::table('empresas')->first()->direccion_fiscal }}
                </p>

                <p class="m-0 p-0 text-info-empresa">Central telefónica:
                    {{ DB::table('empresas')->count() == 0 ? '-' : DB::table('empresas')->first()->telefono }}</p>
                <p class="m-0 p-0 text-info-empresa">Email:
                    {{ DB::table('empresas')->count() == 0 ? '-' : DB::table('empresas')->first()->correo }}</p>
            </div>
        </div>

    </div><br>
    <div class="informacion">
        <table class="tbl-informacion">
            <tbody style="padding-top: 5px; padding-bottom: 5px;">
                <tr>
                    <td style="padding-left: 5px;">CAJA</td>
                    <td>:</td>
                    <td>CAJA PRINCIPAL</td>
                    {{-- <td>{{ getFechaFormato( $documento->fecha_documento ,'d/m/Y')}}</td> --}}
                </tr>
                <tr>
                    <td style="padding-left: 5px;">Fecha</td>
                    <td>:</td>
                    <td>{{ $fecha }}</td>
                </tr>
            </tbody>
        </table>
    </div><br>
    <span style="text-transform: uppercase;font-size:15px">ORDENES</span>
    <br>
    <div class="cuerpo">
        <table class="tbl-detalles text-uppercase" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: center;border-right: 2px solid #2471A3">Cliente</th>
                    <th style="text-align: center; border-right: 2px solid #2471A3">Vehiculo</th>
                    <th style="text-align: center; border-right: 2px solid #2471A3">Total</th>
                    <th style="text-align: center; border-right: 2px solid #2471A3">Desc.</th>
                    <th style="text-align: center; border-right: 2px solid #2471A3">Total a pagar</th>
                    @php
                        $cont = 0;
                        while($cont < count(tipos_pago()))
                        {
                            if($cont == count(tipos_pago()) - 1)
                            {
                                echo '<th style="text-align: center;">'.tipos_pago()[$cont]->descripcion.'</th>';
                            }
                            else {
                                echo '<th style="text-align: center; border-right: 2px solid #2471A3">'.tipos_pago()[$cont]->descripcion.'</th>';
                            }
                            $cont++;
                        }
                    @endphp
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes as $orden)
                {{-- $orden->sunat != '2' &&  --}}
                    @if ($orden->estado_pago == 'PAGADA')
                        <tr>
                            <td style="text-align: center; border-right: 2px solid #2471A3">
                                {{ $orden->cliente }}</td>
                            <td style="text-align: center; border-right: 2px solid #2471A3">
                                {{ $orden->vehiculo }}</td>
                            <td style="text-align: center; border-right: 2px solid #2471A3;">
                                {{ $orden->total }}
                            </td>
                            <td style="text-align: center; border-right: 2px solid #2471A3;">
                                {{ $orden->descuento }}
                            </td>
                            <td style="text-align: center; border-right: 2px solid #2471A3">
                                {{ $orden->total - $orden->descuento }}
                            </td>
                            @foreach(tipos_pago() as $tipo)
                                @if($tipo->id == 1)
                                    
                                    <td style="text-align: center; border-right: 2px solid #2471A3">{{$orden->efectivo}}</td>';
                                @else
                                    @if($tipo->id == $orden->tipo_pago_id)
                                        <td style="text-align: center; border-right: 2px solid #2471A3">{{$orden->importe}}</td>';
                                    @else
                                        <td style="text-align: center; border-right: 2px solid #2471A3">0.00</td>';
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">TOTAL</td>
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">{{ count($ordenes) }}</td>
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">{{ $ordenes->sum('total') }}</td>
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">{{ $ordenes->sum('descuento') }}</td>
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">{{ $ordenes->sum('total') - $ordenes->sum('descuento') }}</td>
                    @foreach (tipos_pago() as $tipo)
                    <td style="text-align: center; border-right: 2px solid #2471A3; border-top: 2px solid #2471A3">{{ number_format(cuadreMovimientoCajaIngresosVentaResum($ordenes,$tipo->id), 2) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div><br> 
    <div class="info-total-qr">
        <table class="tbl-qr" cellpadding="2" cellspacing="0">
            <tr>
                <td style="width: 100%;">
                    <table class="tbl-total text-uppercase" cellpadding="2" cellspacing="0">
                        <thead style="background-color: #2471A3; color: white;">
                            <tr>
                                <th style="text-align:center; padding: 5px;" colspan="2">RESUMEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (tipos_pago() as $tipo)
                            <tr>
                                <td style="text-align:left; padding: 5px;">
                                    <p class="m-0 p-0">{{$tipo->descripcion}}</p>
                                </td>
                                <td style="text-align:right; padding: 5px;">
                                    <p class="p-0 m-0">{{ number_format(cuadreMovimientoCajaIngresosVentaResum($ordenes,$tipo->id), 2) }}</p>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                            <tr style="background-color: #A2D9CE;">
                                <td style="text-align:left; padding: 5px;">
                                    <p class="p-0 m-0">TOTAL VENTA ELECTRONICO</p>
                                </td>
                                <td style="text-align:right; padding: 5px;">
                                    <p class="p-0 m-0">
                                        {{ number_format(cuadreMovimientoCajaIngresosVentaElectronico($ordenes), 2) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr></td>
                            </tr>
                            <tr style="background-color: #A2D9CE;">
                                <td style="text-align:left; padding: 5px;">
                                    <p class="p-0 m-0">TOTAL VENTA DEL DIA</p>
                                </td>
                                <td style="text-align:right; padding: 5px;">
                                    <p class="p-0 m-0">
                                        {{ number_format(cuadreMovimientoCajaIngresosVenta($ordenes), 2) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br>
    </div>
</body>

</html>