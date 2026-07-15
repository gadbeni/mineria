@extends('voyager::master')

@php
    $isEdit = isset($form101) && $form101;
@endphp

@section('page_title', $isEdit ? 'Editar Formulario 101' : 'Crear Formulario 101')

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-regular fa-file-lines"></i> {{ $isEdit ? 'Editar Formulario 101' : 'Crear Formulario 101' }}
        </h1>
        <a href="{{ route('form101s.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="agent" action="{{ $isEdit ? route('form101s.update', $form101->id) : route('form101s.store') }}" method="POST">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><small class="panel-title">Detalle de la Empresa</small></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small>Empresa / Compañia <span style="color:red">*</span></small>
                                        <select name="certificate_id" class="form-control" id="select_company" required>
                                            @if ($isEdit && $form101->certificate)
                                                <option value="{{ $form101->certificate_id }}" selected>
                                                    {{ $form101->certificate->code }} - Nit: {{ optional($form101->certificate->company)->nit }} - {{ optional($form101->certificate->company)->representative }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Tipo de Mineral <span style="color:red">*</span></small>
                                        <select name="typeMineral_id"  class="form-control select2" required>
                                            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>--Selecciona una opción--</option>
                                            @foreach ($type as $item)
                                                <option value="{{$item->id}}" {{ (string) old('typeMineral_id', $isEdit ? $form101->typeMineral_id : '') === (string) $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Ley de Mineral <span style="color:red">*</span></small>
                                        <input type="text" name="leyMineral" id="leyMineral" style="text-align: right" class="form-control text" value="{{ old('leyMineral', $isEdit ? $form101->leyMineral : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Unidad de Medida <span style="color:red">*</span></small>
                                        <select name="unidadmedida_id"  class="form-control select2" required>
                                            <option disabled {{ !$isEdit ? 'selected' : '' }}>--Selecciona una opción--</option>
                                                <option value="Kg" {{ old('unidadmedida_id', $isEdit ? $form101->unidaddemedida1 : '') === 'Kg' ? 'selected' : '' }}> Kg </option>
                                                <option value="Gr" {{ old('unidadmedida_id', $isEdit ? $form101->unidaddemedida1 : '') === 'Gr' ? 'selected' : '' }}> Gr </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Peso Bruto <span style="color:red">*</span></small>
                                        <input type="number" name="pesoBruto" id="pesoBruto" min="0.01" step="0.01" style="text-align: right" class="form-control text" value="{{ old('pesoBruto', $isEdit ? $form101->pesoBruto : '') }}" required>

                                    </div>


                                    <div class="form-group col-md-3">
                                        <small>Humedad <span style="color:red">*</span></small>
                                        <input type="text" name="humedad" id="humedad" style="text-align: right" class="form-control text" value="{{ old('humedad', $isEdit ? $form101->humedad : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Peso Neto <span style="color:red">*</span></small>
                                        <input type="number" name="pesoNeto" id="pesoNeto" min="0.01" step="0.01" style="text-align: right" class="form-control text" value="{{ old('pesoNeto', $isEdit ? $form101->pesoNeto : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Lote <span style="color:red">*</span></small>
                                        <input type="number" name="lote" id="lote" min="0.01" step="0.01" style="text-align: right" class="form-control text" value="{{ old('lote', $isEdit ? $form101->lote : '') }}" required>

                                    </div>
                                </div>
                                <hr>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <small>Código Municipio Productor <span style="color:red">*</span></small>
                                        <input type="number" name="municipio" id="municipio" class="form-control text" value="{{ old('municipio', $isEdit ? $form101->municipio : '') }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Localidad / Comunidad <span style="color:red">*</span></small>
                                        <input type="text" name="localidad" id="localidad" class="form-control text" value="{{ old('localidad', $isEdit ? $form101->localidad : '') }}" required>

                                    </div>

                                   <div class="form-group col-md-3">
                                        <small>Código Área Minera <span style="color:red">*</span></small>
                                        <input type="text" name="codigoAreaMinero" id="codigoAreaMinero" class="form-control text" value="{{ old('codigoAreaMinero', $isEdit ? $form101->codigoAreaMinero : '') }}" required>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Nombre Área Minera <span style="color:red">*</span></small>
                                        <input type="text" name="nombreAreaMinero" id="nombreAreaMinero" class="form-control text" value="{{ old('nombreAreaMinero', $isEdit ? $form101->nombreAreaMinero : '') }}" required>

                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="panel-heading"><small class="panel-title">Detalle del Transporte</small></div>
                                    <div class="form-group col-md-3">
                                        <small>Medio de Transporte <span style="color:red">*</span></small>
                                        <select name="medioTransporte"  class="form-control select2" required>
                                            <option value="" disabled {{ !$isEdit ? 'selected' : '' }}>--Selecciona una opción--</option>
                                                <option value="Terrestre" {{ old('medioTransporte', $isEdit ? $form101->medioTransporte : '') === 'Terrestre' ? 'selected' : '' }}> Terrestre </option>
                                                <option value="Aereo" {{ old('medioTransporte', $isEdit ? $form101->medioTransporte : '') === 'Aereo' ? 'selected' : '' }}> Aereo </option>
                                                <option value="Fluvial" {{ old('medioTransporte', $isEdit ? $form101->medioTransporte : '') === 'Fluvial' ? 'selected' : '' }}> Fluvial </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Origen <span style="color:red">*</span></small>
                                        <input type="text" name="origen" id="origen" class="form-control text" value="{{ old('origen', $isEdit ? $form101->origen : '') }}" required>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Intermedio <span style="color:red">*</span></small>
                                        <input type="text" name="intermedio" id="intermedio" class="form-control text" value="{{ old('intermedio', $isEdit ? $form101->intermedio : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Final <span style="color:red">*</span></small>
                                        <input type="text" name="final" id="final" class="form-control text" value="{{ old('final', $isEdit ? $form101->final : '') }}" required>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <small>Placa/Matrícula <span style="color:red">*</span></small>
                                        <input type="text" name="matricula" id="matricula" class="form-control text" value="{{ old('matricula', $isEdit ? $form101->matricula : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Nombre del Conductor <span style="color:red">*</span></small>
                                        <input type="text" name="nombreConductor" id="nombreConductor" class="form-control text" value="{{ old('nombreConductor', $isEdit ? $form101->nombreConductor : '') }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Licencia de Conducir <span style="color:red">*</span></small>
                                        <input type="text" name="licenciaConducir" id="licenciaConducir" class="form-control text" value="{{ old('licenciaConducir', $isEdit ? $form101->licenciaConducir : '') }}" required>

                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Encargado del Transporte <span style="color:red">*</span></small>
                                        <input type="text" name="nombreEncargadoTrasporte" id="nombreEncargadoTrasporte" class="form-control text" value="{{ old('nombreEncargadoTrasporte', $isEdit ? $form101->nombreEncargadoTrasporte : '') }}" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>C.I. Encargado del Transporte <span style="color:red">*</span></small>
                                        <input type="text" name="ciEncargadoTrasporte" id="ciEncargadoTrasporte" class="form-control text" value="{{ old('ciEncargadoTrasporte', $isEdit ? $form101->ciEncargadoTrasporte : '') }}" required>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small class="panel-title">Observaciones</small>
                                        <textarea name="observation" id="observation" class="form-control" cols="30" rows="3">{{ old('observation', $isEdit ? $form101->observation : '') }}</textarea>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="btn_submit" class="btn btn-primary">{{ $isEdit ? 'Actualizar' : 'Guardar' }}</button>
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
            $(document).ready(function(){

                var isEdit = {{ $isEdit ? 'true' : 'false' }};

                $('#agent').submit(function(e){
                    $('#btn_submit').text('Guardando...');
                    $('#btn_submit').attr('disabled', true);

                });
                var productSelected;

                $('#select_company').select2({
                // tags: true,
                    placeholder: '<i class="fa fa-search"></i> Buscar...',
                    escapeMarkup : function(markup) {
                        return markup;
                    },
                    language: {
                        inputTooShort: function (data) {
                            return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                        },
                        noResults: function () {
                            return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                        }
                    },
                    quietMillis: 250,
                    minimumInputLength: 0,
                    ajax: {
                        url: "{{ url('admin/companies/certificate/list') }}",
                        data: function(params) {
                            return { term: params.term || '' };
                        },
                        processResults: function (data) {
                            let results = [];
                            data.map(function(item){
                                if (item && item.company) {
                                    results.push({ ...item, disabled: false });
                                }
                            });
                            return { results };
                        },
                        cache: false
                    },
                    templateResult: formatResultCustomers_people,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        if (!opt.id) return '<i class="fa fa-search"></i> Buscar...';
                        if (!opt.company) return opt.text || '';
                        return '<small style="font-size: 15px">'+opt.code+'</small>, <small>Nit: </small>'+opt.company.nit+'<small>, Razon Social: </small>'+opt.company.razon+'<small>, Representante: </small>'+opt.company.representative+'<small>, Actividad Social: </small>'+opt.company.activity;
                    }
                }).change(function(){

                });
            })

            function formatResultCustomers_people(option){
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
                if (!option.company) return option.code || '';

                return $(`<div style="display: flex">
                                <div>
                                    <small style="font-size: 15px">${option.code}</small><br>
                                    <small>Nit: </small><b style="font-size: 15px; color: black">${option.company.nit}</b><br>
                                    <small>Razon Social: </small><b style="font-size: 15px; color: black">${option.company.razon}</b><br>
                                    <small>Representante: </small><b style="font-size: 15px; color: black">${option.company.representative}</b><br>
                                    <small>Actividad Social: </small><b style="font-size: 15px; color: black">${option.company.activity}</b><br>
                                </div>
                            </div>`);
            }

        </script>
    @stop
