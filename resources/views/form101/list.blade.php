<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>                    
                    <th style="text-align: center">C.O.M.</th>
                    <th style="text-align: center">Empresa/Compañia</th>
                    <th style="text-align: center">Tipo de Mineral</th>
                    <th style="text-align: center">Peso Bruto</th>
                    <th style="text-align: center">Peso Neto</th>
                    <th style="text-align: center">Municipio</th>                    
                    <th style="text-align: center">Localidad</th>   
                    <th style="text-align: center">Código Área Minera</th>   
                    <th style="text-align: center">Nombre Área Minera</th>
                    <th style="text-align: center">Fecha de Creación</th>
                    <th style="text-align: center">Estado</th>
                    <th style="text-align: center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td><small>{{ $item->certificate->company->codeMiningOperator }}</small><br></td>
                    <td>
                        <table> 
                            <tr>
                                <td>
                                    <small>Nit:</small> {{ $item->certificate->company->nit }} <br>
                                    <small>Razon Social:</small> {{ $item->certificate->company->razon }} <br>
                                    <small>Representante:</small> {{ $item->certificate->company->representative }} <br>
                                    <small>Actividad Social:</small> {{ $item->certificate->company->activity }} <br>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
                    <td style="text-align: center">{{$item->typeMineral->name}}</td>
                    
                    <td style="text-align: right"> {{$item->pesoBruto}}</td>
                    <td style="text-align: right"> {{$item->pesoNeto}}</td>
                    <td style="text-align: center">{{$item->municipio}}</td>
                    <td style="text-align: center">{{$item->localidad}}</td>
                    <td style="text-align: center">{{$item->codigoAreaMinero}}</td>
                    <td style="text-align: center">{{$item->nombreAreaMinero}}</td>
                    <td style="text-align: center">
                        <small>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</small><br>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</small>
                    </td>

                    <td style="text-align: center">
                        @if($item->status == 'Confirmado')
                            <span class="label label-success" style="font-size:12px; padding:5px 10px">
                                <i class="fa fa-check"></i> Confirmado
                            </span>
                            <br>
                            <small class="text-muted" style="font-size:10px">
                                {{ \Carbon\Carbon::parse($item->confirmed_at)->format('d/m/Y H:i') }}
                            </small>
                        @endif

                        @if($item->status == 'Pendiente')
                            <span class="label label-primary" style="font-size:12px; padding:5px 10px">
                                <i class="fa fa-clock-o"></i> Pendiente
                            </span>
                        @endif
                        @if($item->status == 'Borrador')
                            @if($item->reject_reason)
                                <div style="border:1px solid #f1b0b7; background:#fdf3f4; border-radius:6px; padding:8px 10px; text-align:left; max-width:240px; margin:0 auto">
                                    <div style="font-weight:600; color:#c0392b; font-size:12px; margin-bottom:4px">
                                        <i class="fa fa-times-circle"></i> Rechazado
                                    </div>
                                    <div style="font-size:11px; color:#555; line-height:1.35">
                                        <b>Motivo:</b> {{ \Illuminate\Support\Str::limit($item->reject_reason, 90) }}
                                    </div>
                                    <div style="font-size:10px; color:#999; margin-top:3px">
                                        <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($item->rejected_at)->format('d/m/Y H:i') }}
                                    </div>
                                    <a href="javascript:void(0)" onclick="abrirHistorial('{{ $item->id }}', '{{ $item->code }}')"
                                       style="display:inline-block; margin-top:5px; font-size:11px; color:#c0392b">
                                        <i class="fa fa-history"></i> Ver historial
                                    </a>
                                </div>
                            @else
                                <span class="label label-warning" style="font-size:12px; padding:5px 10px">
                                    <i class="fa fa-clock-o"></i> Borrador
                                </span>
                            @endif
                        @endif
                    </td>

                    <td class="no-sort no-click bread-actions text-right">
                        @if($item->status == 'Confirmado')
                            <a href="{{route('form101s.prinf', ['form'=>$item->id])}}"
                               onclick="window.open(this.href); return false;"
                               title="Imprimir" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-print"></i> Imprimir
                            </a>


                            
                        @endif

                        @if($item->status == 'Pendiente')
                            @if(auth()->user()->hasRole('admin'))
                                <button type="button"
                                   onclick="abrirPreview('{{ route('form101s.preview', ['form' => $item->id]) }}', '{{ $item->code }}')"
                                   title="Vista previa del formulario antes de confirmar"
                                   class="btn btn-sm btn-warning">
                                    <i class="fa fa-eye"></i> Vista Previa
                                </button>
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="abrirConfirmar('{{ $item->id }}', '{{ $item->code }}')"
                                    title="Confirmar para habilitar impresión">
                                    <i class="fa fa-check-circle"></i> Confirmar
                                </button>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="abrirRechazar('{{ $item->id }}', '{{ $item->code }}')"
                                    title="Rechazar formulario">
                                    <i class="fa fa-times-circle"></i> Rechazar
                                </button>
                            @else
                                <span class="label label-default" style="font-size:11px; padding:5px 8px">
                                    <i class="fa fa-clock-o"></i> En espera de confirmación
                                </span>
                            @endif
                        @endif

                        @if($item->status == 'Borrador')
                            <a href="{{ route('form101s.edit', ['form101' => $item->id]) }}"
                               title="Editar formulario" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i> Editar
                            </a>
                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="abrirEnviar('{{ $item->id }}', '{{ $item->code }}')"
                                title="Enviar formulario">
                                <i class="fa fa-paper-plane"></i> Enviar
                            </button>
                            @if(!$item->reject_reason)
                                <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('form101s.destroy', ['form101' => $item->id]) }}')" data-toggle="modal" data-target="#myModalEliminar">
                                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                                </button>
                            @endif
                        @endif
                    </td>
                    
                    
                </tr>
                @empty
                    <tr>
                        <td style="text-align: center" valign="top" colspan="12" class="dataTables_empty">No hay datos disponibles en la tabla</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-4" style="overflow-x:auto">
        @if(count($data)>0)
            <p class="text-muted">Mostrando del {{$data->firstItem()}} al {{$data->lastItem()}} de {{$data->total()}} registros.</p>
        @endif
    </div>
    <div class="col-md-8" style="overflow-x:auto">
        <nav class="text-right">
            {{ $data->links() }}
        </nav>
    </div>
</div>

<script>
   
   var page = "{{ request('page') }}";
    $(document).ready(function(){
        $('.page-link').click(function(e){
            e.preventDefault();
            let link = $(this).attr('href');
            if(link){
                page = link.split('=')[1];
                list(page);
            }
        });
    });
</script>