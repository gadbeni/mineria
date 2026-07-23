@extends('voyager::master')

@section('page_title', 'Firmas Autorizadas')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="voyager-lock"></i> Firmas Autorizadas
                            </h1>
                        </div>
                        <div class="col-md-8 text-right" style="margin-top: 15px">
                            <a href="{{ route('voyager.signatures.create') }}" class="btn btn-success">
                                <i class="voyager-plus"></i> <span>Nueva Firma</span>
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

                        @if(session('message'))
                            <div class="alert alert-{{ session('alert-type', 'success') }}">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Alias</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Cargo</th>
                                        <th style="text-align:center">Firma</th>
                                        <th style="text-align:center">Estado</th>
                                        <th>Motivo de Baja</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataTypeContent as $signature)
                                        <tr>
                                            <td>{{ $signature->id }}</td>
                                            <td>{{ $signature->alias }}</td>
                                            <td>{{ $signature->first_name }}</td>
                                            <td>{{ $signature->last_name }}</td>
                                            <td>{{ $signature->job }}</td>
                                            <td style="text-align:center">
                                                @if($signature->image)
                                                    <img src="{{ Voyager::image($signature->image) }}"
                                                        alt="Firma {{ $signature->alias }} {{ $signature->first_name }}"
                                                        style="max-height:45px; max-width:120px; cursor:pointer; background:#fff; border:1px solid #eee; border-radius:4px; padding:2px"
                                                        onclick="verFirma('{{ Voyager::image($signature->image) }}', '{{ addslashes($signature->alias . ' ' . $signature->first_name . ' ' . $signature->last_name) }}')">
                                                @else
                                                    <span style="color:#bbb">— sin firma —</span>
                                                @endif
                                            </td>
                                            <td style="text-align:center">
                                                @if($signature->status == 1)
                                                    <span class="label label-success" style="font-size:13px; padding:6px 12px">
                                                        <i class="fa fa-check"></i> Activo
                                                    </span>
                                                @else
                                                    <span class="label label-danger" style="font-size:13px; padding:6px 12px">
                                                        <i class="fa fa-times"></i> Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="font-size:12px; color:#888">
                                                {{ $signature->baja_reason ?? '—' }}
                                            </td>
                                            <td class="text-right" style="white-space:nowrap">
                                                <a href="{{ route('voyager.signatures.show', $signature->id) }}"
                                                    class="btn btn-sm btn-info" title="Ver registro completo">
                                                    <i class="voyager-eye"></i> Ver
                                                </a>

                                                @if($signature->status == 1)
                                                    {{-- Botón que abre modal de baja --}}
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        onclick="abrirModalBaja({{ $signature->id }}, '{{ addslashes($signature->alias . ' ' . $signature->first_name . ' ' . $signature->last_name) }}')"
                                                        title="Dar de baja">
                                                        <i class="fa fa-ban"></i> Dar de baja
                                                    </button>
                                                @else
                                                    {{-- Reactivar directamente (sin motivo) --}}
                                                    <form action="{{ route('signatures.toggle-status', $signature->id) }}" method="POST" style="display:inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('¿Activar esta firma?')"
                                                            title="Activar">
                                                            <i class="fa fa-check"></i> Activar
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Eliminar --}}
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="abrirModalEliminar({{ $signature->id }}, '{{ addslashes($signature->alias . ' ' . $signature->first_name . ' ' . $signature->last_name) }}')"
                                                    title="Eliminar">
                                                    <i class="voyager-trash"></i>
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

    {{-- Modal: Ver Firma --}}
    <div class="modal fade" id="modal-firma" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#31708f; color:#fff">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-eye"></i> Firma de <span id="firma-nombre"></span></h4>
                </div>
                <div class="modal-body" style="text-align:center; background:#f8f8f8">
                    <img id="firma-img" src="" alt="Firma"
                        style="max-width:100%; max-height:60vh; background:#fff; border:1px solid #ddd; border-radius:4px; padding:10px">
                </div>
                <div class="modal-footer">
                    <a id="firma-download" href="" target="_blank" class="btn btn-info">
                        <i class="voyager-download"></i> Abrir en pestaña nueva
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Dar de Baja --}}
    <div class="modal fade" id="modal-baja" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-baja" method="POST" action="">
                    @csrf
                    <div class="modal-header" style="background:#f0ad4e; color:#fff">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-ban"></i> Dar de Baja Firma</h4>
                    </div>
                    <div class="modal-body">
                        <p>Firma: <strong id="baja-nombre"></strong></p>
                        <div class="form-group">
                            <label for="baja_reason">Motivo de baja <span style="color:red">*</span></label>
                            <textarea name="baja_reason" id="baja_reason" class="form-control" rows="3"
                                placeholder="Ej: Cambio de autoridad, renuncia, designación de nuevo director..." required></textarea>
                            <span class="help-block">Este motivo quedará registrado en la base de datos.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fa fa-ban"></i> Confirmar Baja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Eliminar --}}
    <div class="modal fade" id="modal-eliminar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-eliminar" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header" style="background:#d9534f; color:#fff">
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Eliminar Firma</h4>
                    </div>
                    <div class="modal-body">
                        <p>Firma: <strong id="eliminar-nombre"></strong></p>
                        <div style="background:#fdf2f2; border:1px solid #d9534f; border-radius:4px; padding:12px; margin-bottom:10px">
                            <i class="fa fa-exclamation-triangle" style="color:#d9534f"></i>
                            <strong>Esta acción no se puede deshacer.</strong> Los certificados emitidos con esta firma conservarán el registro histórico.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="voyager-trash"></i> Sí, eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        function verFirma(url, nombre) {
            document.getElementById('firma-nombre').textContent = nombre;
            document.getElementById('firma-img').src = url;
            document.getElementById('firma-download').href = url;
            $('#modal-firma').modal('show');
        }

        function abrirModalBaja(id, nombre) {
            document.getElementById('baja-nombre').textContent = nombre;
            document.getElementById('baja_reason').value = '';
            document.getElementById('form-baja').action = '/admin/signatures/' + id + '/toggle-status';
            $('#modal-baja').modal('show');
        }

        function abrirModalEliminar(id, nombre) {
            document.getElementById('eliminar-nombre').textContent = nombre;
            document.getElementById('form-eliminar').action = '/admin/signatures/' + id;
            $('#modal-eliminar').modal('show');
        }
    </script>
@stop
