@extends('layouts.template-print-alt')

@section('page_title', 'Certificado')

@section('content')
{{-- <div class="descarga" style="width: 90%; margin: auto;"> <a href="javascript:generateHTML2PDF()">DESCARGAR CERTIFICADO</a></div> --}}
<div id="html2pdf" >
    <table width="100%">
        <tr>           
            <td style="text-align: center;  width:95%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 35px">
                    GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI
                </h1>
               
                <small style="margin-bottom: 0px; margin-top: 5px; font-size: 20px">
                    SECRETARIA DEPARTAMENTAL DE MINERIA, ENERGIA E HIDROCARBUROS
                </small>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 30px">
                    C.O.M
                </h1>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 30px">
                    CODIGO DE OPERADOR MINERO
                </h1>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 30px">
                    {{$certificate->company->codeMiningOperator}}
                </h1>
            </td>
        </tr>
    </table>
    <br>

    {{-- <table width="100%">
        <tr style="text-transform: uppercase;">
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 30px">
                    {{$certificate->miningOperator}}
                </h1>
            </td>
        </tr>
    </table> --}}

    <div class="text" style="text-transform: uppercase;">
        <p style="font-size: 16px;">
            <b>NIT: {{$certificate->company->nit}}</b><br>
            <b>NIM: {{$certificate->company->nim}}</b><br>
            <b>ACTIVIDAD: {{$certificate->company->activity}}</b><br>
            <b>REPRESENTANTE LEGAL: {{$certificate->company->representative}}</b><br>
            <b>CEDULA DE IDENTIDAD: {{$certificate->company->ci}}</b><br>
            <b>MUNICIPIO: {{$certificate->company->municipe}}</b> <br>
            <b>CODIGO MUNICIPIO MINERO: {{$certificate->company->municipeMiningOperator}}</b>
        </p>
    </div>
    <table width="100%">
        <tr>
            <td style="text-align: rigth; width:32%">
                
            </td>
            <td style="text-align: center; ont-size: 15px; width:36%">
                <b style="text-transform: capitalize;">{{$certificate->signature->alias}} {{$certificate->signature->first_name}} {{$certificate->signature->last_name}}</b>
                <br>
                <b style="text-transform: uppercase;">{{$certificate->signature->job}}</b>
            </td>
            <td style="text-align: right; width:32%">
                {{-- <img src="data:image/png:base64{!! base64_encode($qr)!!}" alt=""> --}}
                <img src="data:image/png;base64, {!! $qr !!}">
                {{-- <img src="data:image/svg+xml;base64,{{ base64_encode($qr) }}"> --}}
                {{-- <img src="data:image/svg+xml;utf8, {{ $qr }}" /> --}}
                {{-- {!! QrCode::size(120)->generate('CODIGO: '.$certificate->code.', OPERADOR MINERO: '.$certificate->company->miningOperator.', NIT: '.$certificate->company->nit.', NIM: '.$certificate->company->nim.', ACTIVIDAD: '.$certificate->company->activity.', REPRESENTANTE LEGAL: '.$certificate->company->representative.
            ', CEDULA DE IDENTIDAD: '.$certificate->company->ci.', MUNICIPIO: '.$certificate->company->municipe.', VALIDO HASTA: '.date("d-m-Y", strtotime($certificate->dateFinish)).', FECHA DE EMISION: '.date("d-m-Y", strtotime($certificate->dateStart))); !!}
             --}}
            </td>
        </tr>
    </table>
    @php
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $fecha = Carbon\Carbon::parse($certificate->dateStart);
        $mes = $meses[($fecha->format('n')) - 1];

        $fecha1 = Carbon\Carbon::parse($certificate->dateFinish);
        $mes1 = $meses[($fecha->format('n')) - 1];
        // $inputs['Fecha'] =  . ' de ' . $mes . ' de ' . $fecha->format('Y');
    @endphp
    <div class="text">
        <p style="font-size: 12px;"><b>VALIDO HASTA:</b>  {{$fecha1->format('d')}} de {{$mes1}} de {{$fecha1->format('Y')}}<br><b>FECHA DE EMISION:</b> {{$fecha->format('d')}} de {{$mes}} de {{$fecha->format('Y')}}</p>
    </div>
</div>

@endsection

@section('css')
    <style>
        #html2pdf {
            border-color: rgb(26, 113, 2);
            border-width: 5px;
            border-style: solid;
            margin: 20px;
            padding: 20px;
        }
    </style>
@stop


@section('script')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script>
        // $(document).ready(function(){

        //     generateHTML2PDF();
        //     // document.getElementById('watermark1' ).style.display = 'none';


        // })
        // function generateHTML2PDF() {       
        //     var element = document.getElementById('html2pdf');
            
        //     var opt = {
        //     margin:       0.5,
        //     filename:     'certificado.pdf',
        //     image:        { type: 'jpeg', quality: 0.98 },
        //     html2canvas:  { scale: 2 },
        //     jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
        //     };

        //     html2pdf().set(opt).from(element).save();
        //     document.getElementById('watermark1' ).style.display = 'block';

        //     // window.close()

        //     // html2pdf(element);
        //     // document.getElementById('watermark1' ).style.display = 'none';


        // }

    </script>
@stop