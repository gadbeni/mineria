@extends('layouts.templateultimo')

@section('page_title', 'Formulario 101')

@section('content')
@php $isPreview = $preview ?? false; @endphp
@if($forms->status !== 'Confirmado' && !$isPreview)
    <div style="text-align:center; margin-top:60px">
        <i class="fa fa-lock" style="font-size:60px; color:#d9534f"></i>
        <h3 style="color:#d9534f">Documento no confirmado</h3>
        <p>Este formulario aún no ha sido confirmado por el responsable de la D.D.M.E.H.</p>
        <a href="{{ url()->previous() }}" class="btn btn-default">Volver</a>
    </div>
@else
@if($isPreview)
    <div class="no-print" style="background:#fff3cd; border:2px dashed #f0ad4e; border-radius:6px; padding:12px 18px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between">
        <span>
            <i class="fa fa-eye" style="color:#f0ad4e"></i>
            <strong> VISTA PREVIA </strong> — Este formulario aún no ha sido confirmado. El documento se muestra tal como quedará al imprimir.
        </span>
        <button onclick="if(window.parent && window.parent !== window && window.parent.jQuery){ window.parent.jQuery('#modalPreview').modal('hide'); } else { window.close(); }" class="btn btn-xs btn-default" style="margin-left:16px">
            <i class="fa fa-times"></i> Cerrar
        </button>
    </div>
@endif
<div id="form-wrapper">
    <table width="100%">
        <tr>
            <td style="width: 15%"><img src="{{ asset('images/icon.png') }}" alt="" width="70px"></td>
            <td style="text-align: center;  width:75%">
                <h3 style="margin-bottom: 0px; margin-top: 5px; font-size: 19px">
                    GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI<br>
                </h3>
                <h4  style="margin-bottom: 0px; margin-top: 5px; font-size: 16px">
                    DIRECCIÓN DEPARTAMENTAL DE MINERÍA, ENERGÍA E HIDROCARBUROS
                </h4>
            </td>
            <td style="text-align: right; width:10%">
                <h3 style="margin-bottom: 0px; margin-top:10px">
                    <td style="width: 20%; font-weight:100">
                        <img src="{{ asset('images/mineria.png') }}" alt="GADBENI" width="70px">
                        <small style="font-size: 8px; font-weight: 100">Impreso:{{ Auth::user()->name }}</small>
                    </td>
                </h3>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 2px">
                    FORMULARIO 101 AUTORIZACION SALIDA DE MINERALES (INTERNO)
                </h3>
            </td>
        </tr>
    </table>
    <br>
        <tr>
            <small style="text-align: center; height:25px; width: 100%"><strong>DATOS GENERALES DEL OPERADOR MINERO</strong></small>
        </tr>
        <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="background: green; text-align: center; color:white; height:15px">NUMERO DE FORMULARIO</th>
                <th style="background: green; text-align: center; color:white; height:15px">NUMERO DE COM</th>
                <th style="background: green; text-align: center; color:white; height:15px">NUMERO DE NIM</th>
                <th style="background: green; text-align: center; color:white; height:15px">NUMERO DE NIT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height:15px">{{$forms->code}}</td>
                <td style="text-align: center; height:15px">{{$forms->certificate->company->codeMiningOperator}}</td>
                <td style="text-align: center; height:15px">{{$forms->certificate->company->nim}}</td>
                <td style="text-align: center; height:15px">{{$forms->certificate->company->nit}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <tr>
        <small style="text-align: center; height:25px; width: 100%"><strong>DATOS GENERALES DEL MINERAL TRANSPORTADO</strong></small>
    </tr>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="background: green; color:white"; colspan="2" style="text-align: left; height:15px; width: 30%">RAZON SOCIAL</th>
                <td colspan="4" style="text-align: left; height:15px; width: 70%">{{$forms->certificate->company->razon}} / {{$forms->certificate->company->representative}}
                </td>
            </tr>
            <tr>
                <th style="text-align: center; height:15px; background: green;  color:white">TIPO DE MINERAL</th>
                <th style="text-align: center; height:15px; background: green;  color:white">LEY DE MINERAL</th>
                <th style="text-align: center; height:15px; background: green;  color:white">UNIDAD DE MEDIDA</th>
                <th style="text-align: center; height:15px;background: green;  color:white">PESO BRUTO</th>
                <th style="text-align: center; height:15px; background: green; color:white">PESO NETO</th>
                <th style="text-align: center; height:15px;background: green; color:white">HUMEDAD</th>
                <th style="text-align: center; height:15px; background: green; color:white">LOTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height:15px">{{$forms->typeMineral->name}}</td>
                <td style="text-align: center; height:15px">{{$forms->leyMineral}} %</td>
                <td style="text-align: center; height:15px">{{$forms->unidaddemedida1}} </td>
                <td style="text-align: center; height:15px">{{$forms->pesoBruto}} </td>
                <td style="text-align: center; height:15px">{{$forms->pesoNeto}} </td>
                <td style="text-align: center; height:15px">{{$forms->humedad}} %</td>
                <td style="text-align: center; height:15px">{{$forms->lote}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="background: green; text-align: center; color:white; height:15px">CODIGO MUNICIPIO PRODUCTOR</th>
                <th style="background: green; text-align: center; color: white; height:15px"> LOCALIDAD/COMUNIDAD</th>
                <th style="background: green; text-align: center; color:white; height:15px">CODIGO DE AREA MINERA</th>
                <th style="background: green; text-align: center; color:white; height:15px">NOMBRE DE AREA MINERA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height:15px">{{$forms->municipio}}</td>
                <td style="text-align: center; height: 15px">{{$forms->localidad}}</td>
                <td style="text-align: center; height:15px">{{$forms->codigoAreaMinero}}</td>
                <td style="text-align: center; height:15px">{{$forms->nombreAreaMinero}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <tr>
        <small style="text-align: center; height:25px; width: 100%"><strong>DATOS DEL MEDIO DE TRANSPORTE</strong></small>
    </tr>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left; width: 20%; height:15px; background: green;  color:white">MEDIO DE TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->medioTransporte}}</td>
                <th style="text-align: left; width: 22%; height:15px; background: green; color:white">PLACA / MATRICULA</th>
                <td style="text-align: center; height:15px">{{$forms->matricula}}</td>
                <th style="text-align: left; width: 22%; height:15px; background: green; color:white">NOMBRE DE CONDUCTOR</th>
                <td style="text-align: center; height:15px">{{$forms->nombreConductor}}</td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="text-align: left;  height:15px;background: green; color:white">LICENCIA DEL CONDUCTOR</th>
                <td style="text-align: center; height:15px">{{$forms->licenciaConducir}}</td>
                <th style="text-align: left; height:15px; background: green; color:white">ENCARGADO DEL TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->nombreEncargadoTrasporte}}</td>
                <th style="text-align: left; height:15px; background: green; color:white">C.I. ENCARGADO-TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->ciEncargadoTrasporte}}</td>
            </tr>
        </thead>
    </table>

    <br>
    <tr>
        <small style="text-align: center; height:25px; width: 100%"><strong> DATOS DE CIRCULACION</strong></small>
    </tr>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left; height:15px;background: green; color:white">ORIGEN</th>
                <td style="text-align: center; height:15px">{{$forms->origen}}</td>

                <th style="text-align: left; height:15px; background: green; color:white">DESTINO INTERMEDIO</th>
                <td style="text-align: center; height:15px">{{$forms->intermedio}}</td>

                <th style="text-align: left; height:15px;background: green; color:white">DESTINO FINAL</th>
                <td style="text-align: center; height:15px">{{$forms->final}}</td>
            </tr>
        </thead>
    </table>

    <br>
    <tr>
        <small style="text-align: center; height:25px; width: 100%"><strong> PUNTOS DE CONTROL </strong></small>
    </tr>
    <div id="puntos-control">
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center;width: 25%; height:30px"></th>
                <th style="text-align: center;width: 25%; height:30px"></th>
                <th style="text-align: center;width: 25%; height:30px"></th>
                <th style="text-align: center;width: 25%; height:30px"></th>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center;width: 25%; height:30px"></th>
                <th style="text-align: center;width: 25%; height:30px"></th>
                <th rowspan="2" style="text-align: center; width: 25%; vertical-align: middle;">
                    @if(!empty($forms->signature->image))
                        <img src="{{ Voyager::image($forms->signature->image) }}" alt="firma" style="display:block; margin:0 auto; width:130px; max-width:100%; height:auto;">
                    @else
                       {{-- <img src="{{ asset('images/firma.png') }}" alt="firma" style="display:block; margin:0 auto; width:130px; max-width:100%; height:auto;">--}}
                    @endif
                    <span style="display:block; font-size:9px; text-transform:uppercase; margin-top:2px;">
                        {{ $forms->signature->alias ?? '' }}
                        {{ $forms->signature->first_name ?? '' }}
                        {{ $forms->signature->last_name ?? '' }}
                    </span>
                    <span style="display:block; font-size:8px; font-weight:normal;">{{ $forms->signature->job ?? 'RESPONSABLE DDMEH' }}</span>
                </th>
                <th rowspan="2" style="text-align: center;width: 25%; height:30px; vertical-align: middle;">
                    <img src="{{ asset('images/sello.png') }}" alt="" style="display:block; margin:0 auto; width:70px; height:auto;">
                    <b style="color: rgb(160, 148, 148)">SELLO DE LA <br>D.D.M.E.H.</b>
                </th>
            </tr>
            <tr>
                <th style="text-align: center; height:15px">SELLO DE LA EMPRESA</th>
                <th style="text-align: center; height:15px">FIRMA DEL RPTE. LEGAL</th>
            </tr>
        </thead>
    </table>
    </div>

    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left;width: 15%; height:35px">  OBSERVACIONES</th>
                <td style="text-align: left;width: 85%; height:35px">{{$forms->observation}}</td>
            </tr>
        </thead>
    </table>
    <small style="text-align: left;width: 100%; height:10px">El formulario 101 es el único documento que habilita el transporte de minerales y metales al interior y exterior del país, tiene carácter de
        DECLARACIÓN JURADA y es de uso obligatorio para operadores mineros.</small>

   <table style="width: 100%; font-size: 10px; margin-top:6px">
    <tr>
        <td style="vertical-align:bottom">
            @php
                $validate = date("d-m-Y H:i:s", strtotime($forms->created_at . " +8 days"))
            @endphp
            Emitido: {{date("d-m-Y H:i:s", strtotime($forms->created_at))}}
            <br>
            Valido Hasta: {{ $validate }}
        </td>


        <td style="text-align: right; width:110px; vertical-align:middle">
            {!! QrCode::size(100)->errorCorrection('M')->generate(url('/verify/'.$forms->verification_token)) !!}
            <div style="font-size:7px; text-align:center">Escanee el código QR<br>para verificar autenticidad</div>
        </td>
    </tr>
   </table>

</div>
@endif
@endsection

@section('css')
    <style>
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }
        @media print {
            html, body { height: 100%; margin: 0; }
            body { zoom: 1 !important; }
            .container { display: block !important; width: 100% !important; background: none !important; height: 100%; }
            .sheet {
                padding: 0 !important; width: 100% !important; max-width: 100% !important;
                box-shadow: none !important; background: white; height: 100%;
                display: flex; flex-direction: column;
            }
            .no-print { display: none !important; }
            br { display: none !important; }
            table { margin: 0 !important; }
            small[style*="height:25px"] { line-height: 1.2 !important; }
            #form-wrapper { flex: 1; display: flex; flex-direction: column; }
            #puntos-control { flex: 1; display: flex; flex-direction: column; }
            #puntos-control > table:first-child { flex: 1; }
        }
        @if($isPreview)
        .options { display: none !important; }
        @endif
    </style>
@stop
