@extends('layouts.template-print-alt')

@section('page_title', 'Formulario 101')

@section('content')
{{-- <div class="descarga" style="width: 90%; margin: auto;"> <a href="javascript:generateHTML2PDF()">DESCARGAR FORMULARIO</a></div> --}}
<div >
    {{-- <div class="watermark1" id="watermark1">
        <img src="{{ asset('images/icon.png') }}" /> 
    </div> --}}
    
    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/mining.jpeg') }}" alt="GADBENI" width="70px"></td>
            <td style="text-align: center;  width:60%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    SECRETARIA DEPARTAMENTAL DE MINERIA ENERGIA E HIDROCARBUROS
                    
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                    
                </small>
            </td>
            <td style="text-align: right; width:20%">
                <h3 style="margin-bottom: 0px; margin-top: 10px">
                    <div id="qr_code">
                        <img src="data:image/png;base64, {!! $qr !!}">
                        
                        {{-- {!! QrCode::size(80)->generate('Numero de Formulario: '.$forms->code.', Numero COM: '.$forms->certificate->code.
                        ', Numero NIM: '.$forms->certificate->company->nim.', Numero de NIT: '.$forms->certificate->company->nit.', Razon Social: '.$forms->certificate->company->razon.', Representante Legal: '.$forms->certificate->company->representative); !!} --}}
                       
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: left;  width:70%">
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
                <th style="text-align: center; height:25px">NUMERO DE FORMULARIO</th>
                <th style="text-align: center; height:25px">NUMERO DE COM</th>
                <th style="text-align: center; height:25px">NUMERO DE NIM</th>
                <th style="text-align: center; height:25px">NUMERO DE NIT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; height:25px">{{$forms->code}}</td>
                <td style="text-align: left; height:25px">{{$forms->certificate->code}}</td>
                <td style="text-align: left; height:25px">{{$forms->certificate->company->nim}}</td>
                <td style="text-align: left; height:25px">{{$forms->certificate->company->nit}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th colspan="2" style="text-align: left; height:25px">RAZON SOCIAL/NOMBRES Y APELLIDOS</th>
                <td colspan="4" style="text-align: left; height:25px">{{$forms->certificate->company->razon}} <br>{{$forms->certificate->company->representative}}
                </td>
            </tr>
            <tr>
                <th style="text-align: center; width: 20%; height:25px">TIPO DE MINERAL</th>
                <th style="text-align: center; width: 20%; height:25px">LEY DE MINERAL</th>
                <th style="text-align: center; width: 15%; height:25px">PESO BRUTO</th>
                <th style="text-align: center; width: 15%; height:25px">HUMEDAD %</th>
                <th style="text-align: center; width: 15%; height:25px">PESO NETO</th>
                <th style="text-align: center; width: 15%; height:25px">LOTE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; height:25px">{{$forms->typeMineral->name}}</td>
                <td style="text-align: center; height:25px">{{$forms->leyMineral}} %</td>
                <td style="text-align: center; height:25px">{{$forms->pesoBruto}} Kg</td>
                <td style="text-align: center; height:25px">{{$forms->humedad}} %</td>
                <td style="text-align: center; height:25px">{{$forms->pesoNeto}} Kg</td>
                <td style="text-align: center; height:25px">{{$forms->lote}}</td>
            </tr>
        </tbody>

    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center; height:25px">MUNICIPIO</th>
                <th style="text-align: center; height:25px">LOCALIDAD / COMUNIDAD</th>
                <th style="text-align: center; height:25px">CODIGO DE AREA MINERA</th>
                <th style="text-align: center; height:25px">NOMBRE DE AREA MINERA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; height:25px">{{$forms->municipio}}</td>
                <td style="text-align: left; height:25px">{{$forms->localidad}}</td>
                <td style="text-align: left; height:25px">{{$forms->codigoAreaMinero}}</td>
                <td style="text-align: left; height:25px">{{$forms->nombreAreaMinero}}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center; width: 25%; height:25px">MEDIO DE TRANSPORTE</th>
                <th style="text-align: center; width: 25%; height:25px">ORIGEN</th>
                <th style="text-align: center; width: 25%; height:25px">INTERMEDIO / FINAL</th>
                <th style="text-align: center; width: 25%; height:25px">PLACA / MATRICULA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left; height:25px">{{$forms->medioTransporte}}</td>
                <td style="text-align: left; height:25px">{{$forms->origen}}</td>
                <td style="text-align: left; height:25px">{{$forms->final}}</td>
                <td style="text-align: left; height:25px">{{$forms->matricula}}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th style="text-align: left; height:25px">NOMBRE DE CONDUCTOR</th>
                <td style="text-align: left; height:25px">{{$forms->nombreConductor}}</td>
                <th style="text-align: left; height:25px">LICENCIA DEL CONDUCTOR</th>
                <td style="text-align: left; height:25px">{{$forms->licenciaConducir}}</td>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="text-align: left; height:25px">NOMBRE DEL ENCARGADO DEL TRANSPORTE</th>
                <td style="text-align: left; height:25px">{{$forms->nombreEncargadoTrasporte}}</td>
                <th style="text-align: left; height:25px">CEDULA DEL ENCARGADO DEL TRANSPORTE</th>
                <td style="text-align: left; height:25px">{{$forms->ciEncargadoTrasporte}}</td>
            </tr>
        </thead>

    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: center;width: 25%; height:150px"></th>
                <th style="text-align: center;width: 25%; height:150px"></th>
                <th style="text-align: center;width: 25%; height:150px"></th>
                <th rowspan="2" style="text-align: center;width: 25%; height:150px"><b style="color: rgb(160, 148, 148)">SELLO DE LA <br>S.D.M.E.H.</b></th>
            </tr>
            <tr>
                <th style="text-align: center; height:25px">SELLO DE LA EMPRESA</th>
                <th style="text-align: center; height:25px">FIRMA DEL RPTE</th>
                <th style="text-align: center; height:25px">RESPONSABLE SDMEH</th>
            </tr>
        </thead>
    </table>
    <br>
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th style="text-align: left;width: 20%; height:50px">OBSERVACIONES</th>
                <td style="text-align: left;width: 80%; height:50px">{{$forms->observation}}</td>
            </tr>
        </thead>
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
