<div class="col-md-12">
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>                    
                    <th style="text-align: center">Codigo</th>
                    <th style="text-align: center">Empresa/Compañia</th>
                    <th style="text-align: center">Tipo de Mineral</th>
                    <th style="text-align: center">Peso Bruto</th>
                    <th style="text-align: center">Peso Neto</th>
                    <th style="text-align: center">Municipio</th>                    
                    <th style="text-align: center">Localidad</th>   
                    <th style="text-align: center">Código Área Minera</th>   
                    <th style="text-align: center">Nombre Área Minera</th>   
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td><small>{{ $item->code }}</small></td>
                    <td>
                        <table> 
                            <tr>
                                <td>
                                    <small>{{ $item->certificate->code }}</small><br>
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

            
                
                    <td class="no-sort no-click bread-actions text-right">
                        <a href="{{route('form101s.prinf', ['form'=>$item->id])}}" target="_blank" title="Imprimir" class="btn btn-sm btn-success" data-toggle="modal">
                            <i class="fa-solid fa-print"></i>
                        </a>
                        <button title="Borrar" class="btn btn-sm btn-danger delete" onclick="deleteItem('{{ route('form101s.destroy', ['form101' => $item->id]) }}')" data-toggle="modal" data-target="#myModalEliminar">
                            <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">Borrar</span>
                        </button>
                    </td>
                    
                    
                </tr>
                @empty
                    <tr>
                        <td style="text-align: center" valign="top" colspan="10" class="dataTables_empty">No hay datos disponibles en la tabla</td>
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