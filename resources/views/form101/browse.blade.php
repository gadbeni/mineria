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
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> Registros</label>
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

    {{-- Modal: Confirmación de formulario --}}
    <div class="modal fade" tabindex="-1" id="modalConfirmar" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-confirmar" method="POST" action="">
                    @csrf
                    <div class="modal-header" style="background:#1a7102; color:#fff">
                        <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-check-circle"></i> Confirmar Formulario 101</h4>
                    </div>
                    <div class="modal-body">
                        <p>Formulario: <strong id="confirmar-codigo"></strong></p>
                        <div style="background:#f0faf0; border:1px solid #1a7102; border-radius:4px; padding:12px; margin-bottom:15px; font-size:13px">
                            <i class="fa fa-info-circle" style="color:#1a7102"></i>
                            Al confirmar, se registrará la firma del responsable de la D.D.M.E.H. y el documento quedará habilitado para impresión.
                        </div>
                        <div class="form-group">
                            <label for="signature_id"><b>Firma del Responsable D.D.M.E.H. <span style="color:red">*</span></b></label>
                            <select name="signature_id" id="signature_id" class="form-control" required>
                                <option value="">-- Seleccione la firma autorizada --</option>
                                @foreach($signatures as $sig)
                                    <option value="{{ $sig->id }}"
                                        data-name="{{ trim($sig->alias.' '.$sig->first_name.' '.$sig->last_name) }}"
                                        data-job="{{ $sig->job }}">
                                        {{ $sig->alias }} {{ $sig->first_name }} {{ $sig->last_name }} — {{ $sig->job }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Confirmar y habilitar impresión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Enviar formulario (Borrador -> Pendiente) --}}
    <div class="modal fade" tabindex="-1" id="modalEnviar" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-enviar" method="POST" action="">
                    @csrf
                    <div class="modal-header" style="background:#0b5394; color:#fff">
                        <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-paper-plane"></i> Enviar Formulario 101</h4>
                    </div>
                    <div class="modal-body">
                        <p>Formulario: <strong id="enviar-codigo"></strong></p>
                        <div style="background:#eaf2fb; border:1px solid #0b5394; border-radius:4px; padding:12px; font-size:13px">
                            <i class="fa fa-info-circle" style="color:#0b5394"></i>
                            Al enviar el formulario, ya no se podrá <b>editar</b> ni <b>eliminar</b>. El formulario se enviará para su <b>confirmación</b> e <b>impresión</b>.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Sí, enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Historial de rechazos --}}
    <div class="modal fade" tabindex="-1" id="modalHistorial" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#c0392b; color:#fff">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-history"></i> Historial de Rechazos — <span id="historial-codigo"></span></h4>
                </div>
                <div class="modal-body" style="background:#f7f8fa; max-height:65vh; overflow-y:auto">
                    <div id="historial-loader" class="text-center" style="padding:30px; color:#888">
                        <i class="fas fa-spinner fa-spin fa-2x"></i><br><small>Cargando historial...</small>
                    </div>
                    <div id="historial-body"></div>
                    <div id="historial-vacio" class="text-center" style="display:none; padding:30px; color:#999">
                        <i class="fa fa-inbox fa-2x"></i><br><small>Sin registros de rechazo.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Historial de ediciones --}}
    <div class="modal fade" tabindex="-1" id="modalEdiciones" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#2c6fbb; color:#fff">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-pencil"></i> Historial de Ediciones — <span id="ediciones-codigo"></span></h4>
                </div>
                <div class="modal-body" style="background:#f7f8fa; max-height:65vh; overflow-y:auto">
                    <div id="ediciones-loader" class="text-center" style="padding:30px; color:#888">
                        <i class="fas fa-spinner fa-spin fa-2x"></i><br><small>Cargando ediciones...</small>
                    </div>
                    <div id="ediciones-body"></div>
                    <div id="ediciones-vacio" class="text-center" style="display:none; padding:30px; color:#999">
                        <i class="fa fa-inbox fa-2x"></i><br><small>Sin ediciones registradas.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Vista previa del formulario --}}
    <div class="modal fade" tabindex="-1" id="modalPreview" role="dialog">
        <div class="modal-dialog modal-lg" role="document" style="width:90%; max-width:900px">
            <div class="modal-content">
                <div class="modal-header" style="background:#e6a817; color:#fff">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-eye"></i> Vista Previa — <span id="preview-codigo"></span></h4>
                </div>
                <div class="modal-body" style="padding:0">
                    <div id="preview-loader" class="text-center" style="padding:40px">
                        <i class="fas fa-spinner fa-spin fa-2x"></i><br><small>Cargando vista previa...</small>
                    </div>
                    <iframe id="preview-iframe" src="" frameborder="0"
                        style="width:100%; height:75vh; display:none"
                        onload="document.getElementById('preview-loader').style.display='none'; this.style.display='block';"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Rechazar formulario (Pendiente -> Borrador) --}}
    <div class="modal fade" tabindex="-1" id="modalRechazar" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-rechazar" method="POST" action="">
                    @csrf
                    <div class="modal-header" style="background:#c0392b; color:#fff">
                        <button type="button" class="close" data-dismiss="modal" style="color:#fff"><span>&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-times-circle"></i> Rechazar Formulario 101</h4>
                    </div>
                    <div class="modal-body">
                        <p>Formulario: <strong id="rechazar-codigo"></strong></p>
                        <div style="background:#fdecea; border:1px solid #c0392b; border-radius:4px; padding:12px; margin-bottom:15px; font-size:13px">
                            <i class="fa fa-info-circle" style="color:#c0392b"></i>
                            Al rechazar, el formulario volverá a estado <b>Borrador</b> para que pueda ser corregido y enviado nuevamente.
                        </div>
                        <div class="form-group">
                            <label for="reject_reason"><b>Motivo del rechazo <span style="color:red">*</span></b></label>
                            <textarea name="reject_reason" id="reject_reason" class="form-control" rows="3"
                                placeholder="Indique el motivo por el cual se rechaza este formulario..."
                                required></textarea>
                            <span id="reject_reason_error" class="text-danger" style="display:none">Este campo es obligatorio.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> Sí, rechazar
                        </button>
                    </div>
                </form>
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
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="text-center" style="text-transform:uppercase">
                        <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                        <br>
                        <p><b>¿Desea eliminar el siguiente registro?</b></p>
                    </div>

                    <div class="form-group">
                        <label for="delete_reason"><b>Motivo de eliminación <span style="color:red">*</span></b></label>
                        <textarea name="delete_reason" id="delete_reason" class="form-control" rows="3"
                            placeholder="Indique el motivo por el cual desea eliminar este registro..."
                            required></textarea>
                        <span id="delete_reason_error" class="text-danger" style="display:none">Este campo es obligatorio.</span>
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

        if ($.fn.select2) {
            $('#signature_id').select2({
                dropdownParent: $('#modalConfirmar'),
                width: '100%',
                templateResult: function(opt){
                    if(!opt.id) return opt.text;
                    var $o = $(opt.element);
                    return $('<span>'+($o.data('name')||'')+'<br>'+($o.data('job')||'')+'</span>');
                }
            });
        }

        function abrirConfirmar(id, codigo) {
            document.getElementById('confirmar-codigo').textContent = codigo;
            if ($.fn.select2) { $('#signature_id').val('').trigger('change'); }
            else { document.getElementById('signature_id').value = ''; }
            document.getElementById('form-confirmar').action = '/admin/form101s/' + id + '/confirmar';
            $('#modalConfirmar').modal('show');
        }

        function abrirEnviar(id, codigo) {
            document.getElementById('enviar-codigo').textContent = codigo;
            document.getElementById('form-enviar').action = '/admin/form101s/' + id + '/enviar';
            $('#modalEnviar').modal('show');
        }

        function abrirPreview(url, codigo) {
            document.getElementById('preview-codigo').textContent = codigo;
            var iframe = document.getElementById('preview-iframe');
            iframe.style.display = 'none';
            document.getElementById('preview-loader').style.display = 'block';
            iframe.src = url;
            $('#modalPreview').modal('show');
        }

        $('#modalPreview').on('hidden.bs.modal', function(){
            document.getElementById('preview-iframe').src = '';
        });

        function escapeHtml(s){
            return (s === null || s === undefined) ? '' : String(s)
                .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function abrirEdiciones(id, codigo) {
            document.getElementById('ediciones-codigo').textContent = codigo;
            $('#ediciones-loader').show();
            $('#ediciones-vacio').hide();
            $('#ediciones-body').empty();
            $('#modalEdiciones').modal('show');

            $.get('/admin/form101s/' + id + '/edits', function(data){
                $('#ediciones-loader').hide();
                if(!data || data.length === 0){
                    $('#ediciones-vacio').show();
                    return;
                }
                var total = data.length;
                var html = '';
                data.forEach(function(e, i){
                    var num = total - i;
                    var filas = '';
                    if(!e.cambios || e.cambios.length === 0){
                        filas = '<tr><td colspan="3" style="text-align:center; color:#999; font-style:italic">Sin cambios en los campos auditados</td></tr>';
                    } else {
                        e.cambios.forEach(function(c){
                            filas +=
                            '<tr>'+
                                '<td style="font-weight:600; color:#333; white-space:nowrap">'+ escapeHtml(c.campo) +'</td>'+
                                '<td style="color:#c0392b; text-decoration:line-through">'+ escapeHtml(c.antes) +'</td>'+
                                '<td style="color:#1a7102; font-weight:600">'+ escapeHtml(c.despues) +'</td>'+
                            '</tr>';
                        });
                    }
                    html +=
                    '<div style="background:#fff; border:1px solid #d8e4f0; border-left:5px solid #2c6fbb; border-radius:8px; padding:12px 14px; margin-bottom:14px; box-shadow:0 2px 6px rgba(0,0,0,.06)">'+
                        '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; flex-wrap:wrap; gap:6px">'+
                            '<span style="font-weight:700; color:#2c6fbb"><i class="fa fa-pencil"></i> Edición #'+num+'</span>'+
                            '<span style="font-size:12px; color:#777"><i class="fa fa-user"></i> <b>'+ escapeHtml(e.by) +'</b> &nbsp; <i class="fa fa-clock-o"></i> '+ escapeHtml(e.at) +'</span>'+
                        '</div>'+
                        '<table class="table table-bordered" style="margin-bottom:0; font-size:12px; background:#fff">'+
                            '<thead><tr style="background:#eef3f8">'+
                                '<th style="width:22%">Campo</th>'+
                                '<th style="width:39%">Antes</th>'+
                                '<th style="width:39%">Después</th>'+
                            '</tr></thead>'+
                            '<tbody>'+ filas +'</tbody>'+
                        '</table>'+
                    '</div>';
                });
                $('#ediciones-body').html(html);
            }).fail(function(){
                $('#ediciones-loader').hide();
                $('#ediciones-vacio').html('<i class="fa fa-exclamation-triangle fa-2x"></i><br><small>Error al cargar las ediciones.</small>').show();
            });
        }

        function abrirHistorial(id, codigo) {
            document.getElementById('historial-codigo').textContent = codigo;
            $('#historial-loader').show();
            $('#historial-tabla').hide();
            $('#historial-vacio').hide();
            $('#historial-body').empty();
            $('#modalHistorial').modal('show');

            $.get('/admin/form101s/' + id + '/rejections', function(data){
                $('#historial-loader').hide();
                if(!data || data.length === 0){
                    $('#historial-vacio').show();
                    return;
                }
                var total = data.length;
                var html = '';
                data.forEach(function(r, i){
                    var num = total - i; // más reciente arriba = número mayor
                    var esUltimo = (i === 0);
                    html +=
                    '<div style="display:flex; gap:12px; margin-bottom:16px">'+
                        '<div style="flex:0 0 40px">'+
                            '<div style="width:40px; height:40px; border-radius:50%; background:#c0392b; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; box-shadow:0 2px 5px rgba(192,57,43,.4)">'+num+'</div>'+
                        '</div>'+
                        '<div style="flex:1; background:#fff; border:1px solid #f1c6c6; border-left:5px solid #c0392b; border-radius:8px; padding:14px 16px; box-shadow:0 2px 6px rgba(0,0,0,.08)">'+
                            (esUltimo ? '<span style="display:inline-block; background:#c0392b; color:#fff; font-size:10px; font-weight:700; padding:2px 8px; border-radius:10px; margin-bottom:8px; text-transform:uppercase; letter-spacing:.5px">Último rechazo</span><br>' : '')+
                            '<div style="font-size:10px; font-weight:700; color:#c0392b; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px">'+
                                '<i class="fa fa-times-circle"></i> Motivo del rechazo'+
                            '</div>'+
                            '<div style="font-size:14px; font-weight:600; color:#222; line-height:1.4">'+ (r.reason || '<i style="color:#999; font-weight:400">Sin motivo</i>') +'</div>'+
                            '<div style="margin-top:10px; padding-top:8px; border-top:1px dashed #eee; font-size:12px; color:#777; display:flex; justify-content:space-between; flex-wrap:wrap; gap:6px">'+
                                '<span><i class="fa fa-user"></i> <b>'+ (r.by || 'N/D') +'</b></span>'+
                                '<span><i class="fa fa-clock-o"></i> '+ (r.at || '') +'</span>'+
                            '</div>'+
                        '</div>'+
                    '</div>';
                });
                $('#historial-body').html(html);
            }).fail(function(){
                $('#historial-loader').hide();
                $('#historial-vacio').html('<i class="fa fa-exclamation-triangle fa-2x"></i><br><small>Error al cargar el historial.</small>').show();
            });
        }

        function abrirRechazar(id, codigo) {
            document.getElementById('rechazar-codigo').textContent = codigo;
            document.getElementById('reject_reason').value = '';
            document.getElementById('reject_reason_error').style.display = 'none';
            document.getElementById('form-rechazar').action = '/admin/form101s/' + id + '/rechazar';
            $('#modalRechazar').modal('show');
        }

        $('#form-rechazar').on('submit', function(e){
            var reason = $.trim($('#reject_reason').val());
            if(reason === ''){
                e.preventDefault();
                $('#reject_reason_error').show();
                $('#reject_reason').focus();
            }
        });

        function deleteItem(url){
            $('#delete_form').attr('action', url);
            $('#delete_reason').val('');
            $('#delete_reason_error').hide();
        }

        $('#myModalEliminar').on('hidden.bs.modal', function(){
            $('#delete_reason').val('');
            $('#delete_reason_error').hide();
        });

        $('#delete_form').on('submit', function(e){
            var reason = $.trim($('#delete_reason').val());
            if(reason === ''){
                e.preventDefault();
                $('#delete_reason_error').show();
                $('#delete_reason').focus();
            }
        });


        
       
    </script>
@stop
@else
    @section('content')
        <h1>No tienes permiso</h1>
    @stop
@endif