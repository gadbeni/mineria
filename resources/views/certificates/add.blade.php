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
                            <div class="panel-heading"><h6 class="panel-title">Detalle del Certificado</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Empresa / Compañia</small>
                                        <select name="company_id" id="company_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona una empresa --</option>
                                            @foreach ($company as $item)
                                                <option value="{{ $item->id }}"
                                                        data-code="{{ $item->codeMiningOperator }}">
                                                    NIT: {{ $item->nit }} — {{ $item->razon }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <small>Código Operador Minero <em class="text-muted">(desde empresa)</em></small>
                                        <input type="text" name="miningOperator" id="miningOperator"
                                               class="form-control text bg-light" readonly
                                               placeholder="Selecciona una empresa..." required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Fecha Emision</small>
                                        <input type="date" name="dateStart" class="form-control text" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Valido Hasta</small>
                                        <input type="date" name="dateFinish" class="form-control text" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <small>Firma del Certificado</small>
                                        <select name="signature_id" id="guarantor_id" class="form-control select2" required>
                                            <option value="" disabled selected>-- Seleccionar Representante --</option>
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
            $(document).ready(function () {
                // Al cambiar empresa, jalar su codeMiningOperator
                $('#company_id').on('change', function () {
                    var code = $(this).find('option:selected').data('code') || '';
                    $('#miningOperator').val(code);
                });

                // Si Select2 reemplaza el select nativo, escuchar también su evento
                $(document).on('select2:select', '#company_id', function () {
                    var code = $(this).find('option:selected').data('code') || '';
                    $('#miningOperator').val(code);
                });
            });
        </script>
    @stop

{{-- @endif --}}