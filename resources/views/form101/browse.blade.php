@extends('voyager::master')

@section('page_title', 'Viendo Ingresos')

{{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css"> --}}
@if(auth()->user()->hasPermission('browse_form101s'))
    @section('page_header')
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <h1 id="subtitle" class="page-title">
                        <i class="fa-regular fa-file-lines"></i> Formulario 101
                    </h1>
                    @if(auth()->user()->hasPermission('add_form101s'))
                        <a href="{{ route('form101s.create') }}" class="btn btn-success btn-add-new">
                            <i class="voyager-plus"></i> <span>Crear</span>
                        </a>
                    @endif
                </div>
                <div class="col-md-4">

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
                        <div class="row">
                            <div class="col-sm-10">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            <div class="col-sm-2">

                                <input type="text" id="input-search" class="form-control" placeholder="&#xF002; Buscar..." style="font-family:Arial, FontAwesome" />
                            </div>

                            
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="myModalEliminar" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" id="delete_form" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente ingreso?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="text-center" style="text-transform:uppercase">
                        <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                        <br>
                        
                        <p><b>Desea eliminar el siguiente registro?</b></p>
                    </div>
                </div>                
                <div class="modal-footer">
                    
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
           
            </div>
        </div>
    </div>
  

@stop

@section('css')
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/> --}}
<style>
    
</style>

    
@stop

@section('javascript')
    <script src="{{ url('js/main.js') }}"></script>
        
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        var countPage = 10, order = 'id', typeOrder = 'desc';
        $(document).ready(() => {
            list();

            $('.radio-type').click(function(){
                list();
            });
            
            $('#input-search').on('keyup', function(e){
                // alert(2)
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
               
                list();
            });
        });

        function list(page = 1){
            // $('#div-results').loading({message: 'Cargando...'});
            var loader = '<div class="col-md-12 bg"><div class="loader" id="loader-3"></div></div>'
            $('#div-results').html(loader);

            let type = $(".radio-type:checked").val();

            let url = '{{ url("admin/form101s/ajax/list") }}';
            let search = $('#input-search').val() ? $('#input-search').val() : '';

            $.ajax({
                url: `${url}/${search}?paginate=${countPage}&page=${page}`,

                type: 'get',
                
                success: function(result){
                $("#div-results").html(result);
            }});

        }

        function deleteItem(url){
                $('#delete_form').attr('action', url);
        }


        
       
    </script>
@stop
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif