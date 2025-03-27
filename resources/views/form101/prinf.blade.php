@extends('layouts.templateultimo')

@section('page_title', 'Formulario 101')

@section('content')
{{-- <div class="descarga" style="width: 90%; margin: auto;"> <a href="javascript:generateHTML2PDF()">DESCARGAR FORMULARIO</a></div> --}}
<div >
    {{-- <div class="watermark1" id="watermark1">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div> --}}
    
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
                        <small style="font-size: 8px; font-weight: 100">Impreso por:{{ Auth::user()->name }}</small>
                    </td>
                    {{--{{ date('d/m/Y H:i:s') }}--}}
                </h3>
            </td>
        </tr>
    </table>
    <br>
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
                <td style="text-align: center; height:15px">{{$forms->certificate->code}}</td>
                <td style="text-align: center; height:15px">{{$forms->certificate->company->nim}}</td>
                <td style="text-align: center; height:15px">{{$forms->certificate->company->nit}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <tr>
        <small style="text-align: center; text-light; height:25px; width: 100%">DATOS GENERALES DEL MINERAL TRANSPORTADO</small>
    </tr>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th colspan="2" style="text-align: left; height:15px; width: 30%">RAZON SOCIAL</th>
                <td colspan="4" style="text-align: left; height:15px; width: 70%">{{$forms->certificate->company->razon}} / {{$forms->certificate->company->representative}}
                </td>
            </tr>
            <tr>
                <th style="text-align: center; height:15px">TIPO DE MINERAL</th>
                <th style="text-align: center; height:15px">LEY DE MINERAL</th>
                <th style="text-align: center; height:15px">UNIDAD DE MEDIDA</th>
                <th style="text-align: center; height:15px">PESO BRUTO</th>
                <th style="text-align: center; height:15px">PESO NETO</th>
                <th style="text-align: center; height:15px">HUMEDAD</th>
                <th style="text-align: center; height:15px">LOTE</th>
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
                <th style="background: green; text-align: center; color:white; height:15px">MUNICIPIO PRODUCTOR</th>
               {{-- <th style="background: green; text-align: center; color:white; height:15px">CODIGO DE AREA MINERA</th> --}}
                <th style="background: green; text-align: center; color:white; height:15px">NOMBRE DE AREA MINERA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height:15px">{{$forms->municipio}}</td>
                {{--<td style="text-align: center; height:15px">{{$forms->codigoAreaMinero}}</td>--}}
                <td style="text-align: center; height:15px">{{$forms->nombreAreaMinero}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <tr>
        <small style="text-align: center; text-light; height:25px; width: 100%">DATOS DEL MEDIO DE TRANSPORTE</small>
    </tr>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left; height:15px">MEDIO DE TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->medioTransporte}}</td>
                <th style="text-align: left; height:15px">ORIGEN</th>
                <td style="text-align: center; height:15px">{{$forms->origen}}</td>
                <th style="text-align: left; height:15px">DESTINO</th>
                <td style="text-align: center; height:15px">{{$forms->final}}</td>
                <th style="text-align: left; height:15px">PLACA / MATRICULA</th>
                <td style="text-align: center; height:15px">{{$forms->matricula}}</td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="text-align: left; height:15px">NOMBRE DE CONDUCTOR</th>
                <td style="text-align: center; height:15px">{{$forms->nombreConductor}}</td>
                <th style="text-align: left; height:15px">LICENCIA DEL CONDUCTOR</th>
                <td style="text-align: center; height:15px">{{$forms->licenciaConducir}}</td>
                <th style="text-align: left; height:15px">NOMBRE DEL ENCARGADO DEL TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->nombreEncargadoTrasporte}}</td>
                <th style="text-align: left; height:15px">C.I. DEL ENCARGADO DEL TRANSPORTE</th>
                <td style="text-align: center; height:15px">{{$forms->ciEncargadoTrasporte}}</td>
            </tr>
        </thead>

    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center;width: 25%; height:150px"></th>
                <th style="text-align: center;width: 25%; height:150px"></th>
                <th rowspan="2" style="text-align: center;width: 25%; height:150px">
                    <b style="width: 30%"><img src="{{ asset('images/firma.png') }}" alt="" width="130px"></b>
                    <br><br> <br>
                    RESPONSABLE DDMEH</th>
                <th rowspan="2" style="text-align: center;width: 25%; height:150px">
                    <b style="width: 30%"><img src="{{ asset('images/sello.png') }}" alt="" width="130px"></b>
                    <br>
                    <b style="color: rgb(160, 148, 148)">SELLO DE LA <br>D.D.M.E.H.</b>
                </th>
            </tr>
            <tr>
                <th style="text-align: center; height:25px">SELLO DE LA EMPRESA</th>
                <th style="text-align: center; height:25px">FIRMA DEL RPTE. LEGAL</th>
            </tr>
        </thead>
    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left;width: 15%; height:40px">OBSERVACIONES</th>
                <td style="text-align: left;width: 85%; height:40px">{{$forms->observation}}</td>
            </tr>
        </thead>   
    </table>
    <small style="text-align: left;width: 100%; height:10px">El formulario 101 es el único documento que habilita el transporte de minerales y metales al interior y exterior del país, tiene carácter de
        DECLARACIÓN JURADA y es de uso obligatorio para operadores mineros.</small>
    <br>
   <table style="width: 100%; font-size: 10px">
    <td>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        Emitido {{date("d-m-Y H:m:s", strtotime($forms->created_at))}}
        <br>
        
        @php
            $validate = date("Y-m-d H:m:s", strtotime($forms->created_at."+ 3 days"))
        @endphp
        Valido Hasta {{date("d-m-Y H:m:s", strtotime($validate))}}
    </td>
    <td style="text-align: right; width:20%">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <h3 style="margin-bottom: 0px; margin-top: 10px">
            <div id="qr_code">
                 {!! QrCode::size(80)->generate('Numero de Formulario: '.$forms->code.', Numero COM: '.$forms->certificate->code.
                ', Numero NIM: '.$forms->certificate->company->nim.', Numero de NIT: '.$forms->certificate->company->nit.', Razon Social: '.$forms->certificate->company->razon.', Representante Legal: '.$forms->certificate->company->representative); !!}   
            </div>
            <small style="font-size: 8px; font-weight: 100">Escanee el código QR <br> para verificar la autenticidad <br> del formulario </small>
        </h3>
       
    </td>
   </table>

</div>
@endsection

@section('css')
    <style>
        /* table, th, td {
            border-collapse: collapse;
        }
          
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        } */

        /* #watermark1 {
            width: 38%;
            position: fixed;
            top: 250px;
            opacity: 0.1;
            z-index:  -1;
            text-align: center
        }
        #watermark1 img{
            position: relative;
            width: 400px;
        } */

    </style>
@stop

@section('script')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function generateHTML2PDF() {       
            var element = document.getElementById('html2pdf');
            // document.getElementById('watermark1' ).style.display = 'block';
            
            var opt = {
            margin:       0,
            filename:     'formulario101.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
            // html2pdf(element);


        }
        // document.getElementById('watermark1' ).style.display = 'none';



    </script> --}}
@stop
