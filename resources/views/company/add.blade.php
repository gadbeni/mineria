@extends('voyager::master')

@section('page_title', 'Añadir Empresa')

@if (auth()->user()->hasPermission('add_companies'))

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-building"></i> Crear Empresa / Compañia
        </h1>
        <a href="{{ route('voyager.companies.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            <form id="agent" action="{{route('companies.store')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-heading"><h6 class="panel-title">Detalle de la Empresa</h6></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <small>Nit</small>
                                        <input type="text" name="nit" id="nit" style="text-align: right" class="form-control text" required>

                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Nim</small>
                                        <input type="text" name="nim" id="nim" style="text-align: right" class="form-control text" required>

                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Actividad</small>
                                        <input type="text" name="activity" id="activity" class="form-control text" required>

                                    </div> 

                                    <div class="form-group col-md-3">
                                        <small>Código Operador Minero</small>
                                        <input type="text" name="codeMiningOperator" id="codeMiningOperator" class="form-control text" required>

                                    </div>                      
                                </div>

                                <div class="row">

                                    <div class="form-group col-md-12">
                                        <small>Razon Social</small>
                                        <input type="text" name="razon" id="razon" class="form-control text" required>
                                    </div>  

                                    <div class="form-group col-md-3">
                                        <small>Celular</small>
                                        <input type="text" name="phone" id="phone" class="form-control text" required>

                                    </div> 
                                    <div class="form-group col-md-3">
                                        <small>Ci</small>
                                        <input type="text" name="ci" id="ci" class="form-control text" required>

                                    </div> 

                                    <div class="form-group col-md-6">
                                        <small>Representante Legal</small>
                                        <input type="text" name="representative" id="representative" class="form-control text" required>
                                    </div>                    
                                </div>

                                <div class="row">

                                    <div class="form-group col-md-3">
                                        <small>Municipio Productor</small>
                                        <input type="text" name="municipe" id="municipe" class="form-control text" required>
                                    </div>     
                                    <div class="form-group col-md-3">
                                        <small>Código Municipio Minero</small>
                                        <input type="text" name="municipeMiningOperator" id="municipeMiningOperator" class="form-control text" required>

                                    </div>                
                                </div>
                                <hr>
                                <h6 class="panel-title">Detalle del Usuario</h6>
                                <div class="row">
                                    
                                    <div class="form-group col-md-5">
                                        <small>Nombre</small>
                                        <input type="text" name="name" value="" placeholder="Nombre del usuario" class="form-control text" minlength="10" maxlength="50" required>
                                    </div> 
                                    <div class="form-group col-md-4">
                                        <small>Email</small>
                                        <input type="email" name="email" value="" placeholder="Email del usuario" class="form-control text" required>
                                    </div>   

                                    <div class="form-group col-md-3">
                                        <small>Contraseña</small>
                                        <div class="input-group">
                                            <input type="password" id="input-password" name="password" minlength="7" maxlength="15" class="form-control text" required>
                                            <span class="input-group-addon" id="btn-verpassword"><span class="fa fa-eye"></span></span>
                                        </div>
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
            .form-control, .select2-selection, .mce-tinymce {
                border: 1px solid #7a7a7a !important;
                /* color: #f40202; */
                font-weight: bold
            }
        </style>
    @endsection

    @section('javascript')
        <script>
            $(document).ready(function(){
                let ver_pass = false;
                $('#btn-verpassword').click(function(){
                    if(ver_pass){
                        ver_pass = false;
                        $(this).html('<span class="fa fa-eye"></span>');
                        $('#input-password').prop('type', 'password');
                    }else{
                        ver_pass = true;
                        $(this).html('<span class="fa fa-eye-slash"></span>');
                        $('#input-password').prop('type', 'text');
                    }
                });
            });
        </script>
    @stop

@endif