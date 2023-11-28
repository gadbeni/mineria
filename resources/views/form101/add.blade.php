@extends('voyager::master')

@section('page_title', 'Crear certificado')

{{-- @if (auth()->user()->hasPermission('add_loans')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-regular fa-file-lines"></i> Crear Formulario 101
        </h1>
        <a href="{{ route('form101s.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('form101s.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Detalle de la Empresa</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small>Empresa / Compañia</small>
                                        <select name="certificate_id" class="form-control" id="select_company" required></select>

                                        {{-- <select name="company_id"  class="form-control select2" required>
                                            <option value="" disabled selected>-- Selecciona un tipo --</option>
                                            @foreach ($company as $item)
                                                <option value="{{$item->id}}">NIT:{{$item->nit}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$item->representative}}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>                        
                                </div>


                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Tipo de Mineral</small>
                                        <select name="typeMineral_id"  class="form-control select2" required>
                                            <option value="" disabled selected>--Selecciona una opción--</option>
                                            @foreach ($type as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <small>Ley de Mineral</small>
                                        <input type="text" name="leyMineral" id="leyMineral" style="text-align: right" class="form-control text" required>

                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Peso Bruto</small>
                                        <input type="number" name="pesoBruto" id="pesoBruto" min="0.01" step="0.01" style="text-align: right" class="form-control text" required>

                                    </div> 


                                    <div class="form-group col-md-3">
                                        <small>Humedad</small>
                                        <input type="text" name="humedad" id="humedad" style="text-align: right" class="form-control text" required>

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Peso Neto</small>
                                        <input type="number" name="pesoNeto" id="pesoNeto" min="0.01" step="0.01" style="text-align: right" class="form-control text" required>

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Lote</small>
                                        <input type="number" name="lote" id="lote" min="0.01" step="0.01" style="text-align: right" class="form-control text" required>

                                    </div>                        
                                </div>
                                <hr>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <small>Municipio</small>
                                        <input type="text" name="municipio" id="municipio" class="form-control text" required>
                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Localidad</small>
                                        <input type="text" name="localidad" id="localidad" class="form-control text" required>

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Código Área Minera</small>
                                        <input type="text" name="codigoAreaMinero" id="codigoAreaMinero" class="form-control text" required>

                                    </div> 
                                    <div class="form-group col-md-3">
                                        <small>Nombre Área Minera</small>
                                        <input type="text" name="nombreAreaMinero" id="nombreAreaMinero" class="form-control text" required>

                                    </div>                       
                                </div>
                        
                                <hr>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Medio de Transporte</small>
                                        <input type="text" name="medioTransporte" id="medioTransporte" class="form-control text" required>
                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Origen</small>
                                        <input type="text" name="origen" id="origen" class="form-control text">

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Final</small>
                                        <input type="text" name="final" id="final" class="form-control text">

                                    </div> 
                                    <div class="form-group col-md-3">
                                        <small>Placa/Matrícula</small>
                                        <input type="text" name="matricula" id="matricula" class="form-control text">

                                    </div>        
                                    
                                    <div class="form-group col-md-3">
                                        <small>Nombre del Conductor</small>
                                        <input type="text" name="nombreConductor" id="nombreConductor" class="form-control text">
                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Licencia de Conducir</small>
                                        <input type="text" name="licenciaConducir" id="licenciaConducir" class="form-control text">

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Encargado del Transporte</small>
                                        <input type="text" name="nombreEncargadoTrasporte" id="nombreEncargadoTrasporte" class="form-control text">
                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Cedula De Identidad del Encargado del Transporte</small>
                                        <input type="text" name="ciEncargadoTrasporte" id="ciEncargadoTrasporte" class="form-control text">

                                    </div> 
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <small>Observaciones</small>
                                        <textarea name="observation" id="observation" class="form-control" cols="30" rows="3"></textarea>

                                    </div> 
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" id="btn_submit" class="btn btn-primary">Guardar</button>
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
                    minimumInputLength: 2,
                    ajax: {
                        url: "{{ url('admin/companies/certificate/list') }}",        
                        processResults: function (data) {
                            let results = [];
                            data.map(data =>{
                                results.push({
                                    ...data,
                                    disabled: false
                                });
                            });
                            return {
                                results
                            };
                        },
                        cache: true
                    },
                    templateResult: formatResultCustomers_people,
                    templateSelection: (opt) => {
                        productSelected = opt;
                        // alert(opt)
                        
                        return opt.id?'<small style="font-size: 15px">'+opt.code+'</small>, <small>Nit: </small>'+opt.company.nit+'<small>, Razon Social: </small>'+opt.company.razon+'<small>, Representante: </small>'+opt.company.representative+'<small>, Actividad Social: </small>'+opt.company.activity:'<i class="fa fa-search"></i> Buscar... ';
                    }
                }).change(function(){
                
                });
            })

            function formatResultCustomers_people(option){
                if (option.loading) {
                    return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
                }
       
                
                // Mostrar las opciones encontradas
                return $(`  <div style="display: flex">
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

{{-- @endif --}}