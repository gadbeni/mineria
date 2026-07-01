@extends('layouts.templatehorizontal')

@section('page_title', 'Certificado')

@section('content')
{{-- <div class="descarga" style="width: 90%; margin: auto;"> <a href="javascript:generateHTML2PDF()">DESCARGAR CERTIFICADO</a></div> --}}
<div id="html2pdf" >
    <table width="100%">
        <tr>
            <td style="width: 10%"><img src="{{ asset('images/icon.png')}}" alt="" width="120px"></td>           
            <td style="text-align:start; text-align:center;  width: 160%">
                <h6 style="margin-bottom: 10px; margin-top: 10px; font-size: 25px">
                    GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI
                </h6>
               
                <small style="margin-bottom: 0px; margin-top: 0px; font-size: 18px">
                    DIRECCIÓN DEPARTAMENTAL DE MINERÍA, ENERGÍA E HIDROCARBUROS
                </small>
            </td>
            <td style="text-align: right; width:20%">
                <h3 style="margin-bottom: 0px; margin-top: 10px">
                    <td style="width: 10%; font-weight:100">
                        <img src=" {{ asset('images/mineria.png')}}" alt="" width="120px">
                    </td>
                </h3>
            </td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 90px">
                    <div style=" font-family: Georgia, 'Times New Roman', serif;"> 
                    C.O.M.
                </div>
                </h1>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 20px">
                    CÓDIGO OPERADOR MINERO
                </h1>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr style="text-transform: uppercase;">
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 60px">
                    {{$certificate->company->razon}}
                </h1>
            </td>
        </tr>
    </table> 

    <table width="100%">
        <tr>
            <td style="text-align: center;  width:100%">
                <h1 style="margin-bottom: 0px; margin-top: 5px; font-size: 40px">
                    {{$certificate->company->codeMiningOperator}}
                </h1>
            </td>
        </tr>
    </table>
    <br>

<br>
    <table width="100%">
        <tr>
            <td style="text-align:left; width: 25%">
            @php
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $fecha = Carbon\Carbon::parse($certificate->dateStart);
            $mes = $meses[($fecha->format('n')) - 1];
            $fecha1 = Carbon\Carbon::parse($certificate->dateFinish);
            $mes1 = $meses[($fecha1->format('n')) - 1];
            @endphp
            <div class="text" style="text-transform: uppercase;">
                <p style="font-size: 10px;">
                    <b>NIT: {{$certificate->company->nit}}</b><br>
                    <b>NIM: {{$certificate->company->nim}}</b><br>
                    <b>ACTIVIDAD: {{$certificate->company->activity}}</b><br>
                    <b>REPRESENTANTE LEGAL: {{$certificate->company->representative}}</b><br>
                    <b>CEDULA DE IDENTIDAD: {{$certificate->company->ci}}</b><br>
                    <b>MUNICIPIO: {{$certificate->company->municipe}}</b> <br>
                    <b>CODIGO ÁREA MINERO: {{$certificate->company->municipeMiningOperator}}</b>
                </p>
            </div>
            <div class="text">
                <p style="font-size: 10px;"><b>FECHA DE EMISION:</b> {{$fecha->format('d')}} de {{$mes}} de {{$fecha->format('Y')}} <br><b>VALIDO HASTA:</b> {{$fecha1->format('d')}} de {{$mes1}} de {{$fecha1->format('Y')}}</p>
            </div>
        </td>

            <td style="text-align: center; -size: 15px; width:36%">
                <b style="text-transform: capitalize;">{{$certificate->signature->alias}} {{$certificate->signature->first_name}} {{$certificate->signature->last_name}}</b>
                <br>
                <b style="text-transform: uppercase;">{{$certificate->signature->job}}</b>
            </td>
            <td style="text-align: right; width:32%">
                {!! QrCode::size(120)->errorCorrection('M')->generate(url('/verify/certificate/'.$certificate->verification_token)) !!}
                <div style="font-size:7px; text-align:ligth; margin-top:3px">Escanee el código QR<br>para verificar autenticidad</div>
        </td>
        </tr>
    </table>
    
    
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
        @media print {
            html, body { height: 100%; margin: 0; }
            .background { background: none !important; height: 100%; }
            .sheet { height: 100%; padding: 0 !important; margin: 0 !important; }
            .content { height: 100%; padding: 0 !important; }

            #html2pdf {
                margin: 0 !important;
                padding: 12px 20px !important;
                border-width: 5px !important;
                height: calc(8.5in - 20mm - 10px);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                box-sizing: border-box;
            }
            #html2pdf > table { flex: 0 0 auto; width: 100%; }
            br { display: none !important; }

            /* Fuentes ajustadas para llenar carta horizontal */
            #html2pdf table:nth-of-type(2) h1 { font-size: 80px !important; margin: 0 !important; }
            #html2pdf table:nth-of-type(3) h1 { font-size: 22px !important; margin: 0 !important; }
            #html2pdf table:nth-of-type(4) h1 { font-size: 55px !important; margin: 0 !important; }
            #html2pdf table:nth-of-type(5) h1 { font-size: 36px !important; margin: 0 !important; }
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