@extends('voyager::master')

@section('page_title', 'Crear certificado')

{{-- @if (auth()->user()->hasPermission('add_loans')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-regular fa-file-lines"></i> Crear Certificado
        </h1>
        <a href="{{ route('certificates.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('certificates.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Detalle del Prestamos</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Fecha Emision</small>
                                        <input type="date" name="dateStart" class="form-control text" required>
                                    </div>   
                                    <div class="form-group col-md-3">
                                        <small>Valido Hasta</small>
                                        <input type="date" name="dateFinish" class="form-control text" required>
                                    </div>   
                                    {{-- <div class="form-group col-md-3">
                                        <small>Codigo de Certificado</small>
                                        <input type="number" name="code" id="code" style="text-align: right" onkeypress='return inputNumeric(event)' onchange="valCode()" onkeyup="valCode()" class="form-control text" required>
                                        <b class="text-danger" id="label-amount" style="display:none">El codigo ingresado ya existe..</b>

                                    </div>    --}}
                                    {{-- <div class="form-group col-md-3">
                                        <small>Código Operador Minero</small>
                                        <input type="number" name="miningOperator" id="miningOperator" style="text-align: right" class="form-control text" required>
                                    </div>    --}}
                                                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Empresa / Compañia</small>
                                        <select name="company_id"  class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($company as $item)
                                                <option value="{{$item->id}}">NIT:{{$item->nit}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$item->miningOperator}} - {{$item->representative}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small>Firma del Certificado</small>
                                        <select name="signature_id" id="guarantor_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Seleccionar un garante --</option>
                                            @foreach ($signature as $item)
                                                <option value="{{$item->id}}">{{$item->alias}} {{$item->first_name}} {{$item->last_name}} - {{$item->job}}</option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                </div>
                                {{-- <div class="row">
                                    <div class="form-group col-md-12">
                                        <small>Observación</small>
                                        <textarea name="observation" id="observation" class="form-control text" cols="30" rows="5"></textarea>
                                    </div>                                  
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="btn-sumit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                
            </form>              
        </div>
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script>
            function inputNumeric(event) {
                if(event.charCode >= 48 && event.charCode <= 57){
                    return true;
                }
                return false;        
            }

            // function valCode()
            // {
            //     let cod = $(`#code`).val() ? parseFloat($(`#code`).val()) : 0;
            //     $.get('{{route('ajax-certificate.code')}}/'+cod, function(data){
            //         // alert(data);

            //         if(data)
            //         {
            //             $('#btn-sumit').attr('disabled', 'disabled');
            //             $('#label-amount').css('display', 'block');
            //         }
            //         else
            //         {
            //             $('#btn-sumit').removeAttr('disabled');
            //             $('#label-amount').css('display', 'none');

            //         }                   
                            
            //     });

                
            // }
        </script>
    @stop

{{-- @endif --}}