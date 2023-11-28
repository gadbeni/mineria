@extends('voyager::master')

@section('page_title', 'Viendo Certificado')

@if (1==1)

    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="col-md-4" style="padding: 0px">
                                <h1 id="titleHead" class="page-title">
                                    <i class="fa-regular fa-file-lines"></i> Certificados
                                </h1>                                
                            </div>
                            <div class="col-md-8 text-right" style="margin-top: 0px">                            
                                
                                    <a href="{{ route('certificates.create') }}" class="btn btn-success">
                                        <i class="voyager-plus"></i> <span>Crear</span>
                                    </a>
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('content')
        <div class="page-content browse container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row" id="div-results" style="min-height: 120px">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Nit</th>
                                                    <th>Operador Minero</th>                    
                                                    <th>Repesentante Legal</th>                    
                                                    {{-- <th>Codigo</th>       --}}
                                                    <th>Fecha Emision</th>                    
                                                    <th>Fecha Valides</th>                    
                                                    {{-- <th style="text-align: center">Estado</th> --}}
                                                    <th class="text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($certificate as $item)
                                                    <tr style="text-transform: capitalize;">
                                                        <td>{{$item->id}}</td>
                                                        <td>{{$item->company->nit}}</td>
                                                        <td>{{$item->code}}</td>
                                                        <td>{{$item->company->representative}}</td>
                                                        {{-- <td>{{$item->code}}</td> --}}
                                                        <td>{{ date("d-m-Y", strtotime($item->dateStart)) }}</td>
                                                        <td>{{ date("d-m-Y", strtotime($item->dateFinish)) }}</td>
                                                        <td class="no-sort no-click bread-actions text-right">
                                                            <a href="{{route('certificates.print', ['id'=>$item->id])}}" title="Imprimir certificado" target="_blank" class="btn btn-sm btn-success">
                                                                <i class="fa-solid fa-print"></i>
                                                            </a>
                                                            <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('certificates.destroy', ['certificate' => $item->id]) }}')" data-toggle="modal" data-target="#delete-modal">
                                                                <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                                            </button>
                                                        </td>

                                                    </tr>
                                        
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>







        <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="delete-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="#" id="delete_form" method="POST">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id">

                                <div class="text-center" style="text-transform:uppercase">
                                    <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                                    <br>
                                    
                                    <p><b>Desea eliminar el siguiente registro?</b></p>
                                </div>
                            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        </form>
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>


    
    @stop

    @section('css')
    @stop

    @section('javascript')
        <script>
            $(document).ready(() => {
                $('#dataTable').DataTable({
                    language: {
                        // "order": [[ 0, "desc" ]],
                        sProcessing: "Procesando...",
                        sLengthMenu: "Mostrar _MENU_ registros",
                        sZeroRecords: "No se encontraron resultados",
                        sEmptyTable: "Ningún dato disponible en esta tabla",
                        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                        sSearch: "Buscar:",
                        sInfoThousands: ",",
                        sLoadingRecords: "Cargando...",
                        oPaginate: {
                            sFirst: "Primero",
                            sLast: "Último",
                            sNext: "Siguiente",
                            sPrevious: "Anterior"
                        },
                        oAria: {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        },
                        buttons: {
                            copy: "Copiar",
                            colvis: "Visibilidad"
                        }
                    },
                    order: [[ 0, 'desc' ]],
                })
            });
            

            function deleteItem(url){
                $('#delete_form').attr('action', url);
            }
        </script>
    @stop
@endif