<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" style="font-size:12px; margin-top:10px">
            <thead style="background:#2e7d32; color:white">
                <tr>
                    <th style="text-align:center">#</th>
                    <th>Cód. Operador Minero</th>
                    <th>Razón Social</th>
                    <th>NIT</th>
                    <th>NIM</th>
                    <th>Representante Legal</th>
                    <th>Actividad</th>
                    <th style="text-align:center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $i => $item)
                <tr>
                    <td style="text-align:center">{{ $data->firstItem() + $i }}</td>
                    <td><strong>{{ $item->codeMiningOperator }}</strong></td>
                    <td style="text-transform:uppercase">{{ $item->razon }}</td>
                    <td>{{ $item->nit }}</td>
                    <td>{{ $item->nim }}</td>
                    <td>{{ $item->representative }}</td>
                    <td>{{ $item->activity }}</td>
                    <td style="text-align:center; white-space:nowrap">
                        @if(auth()->user()->hasPermission('edit_companies'))
                        <a href="{{ route('voyager.companies.edit', $item->id) }}"
                           class="btn btn-sm btn-primary" title="Editar">
                            <i class="voyager-edit"></i>
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('delete_companies'))
                        <button class="btn btn-sm btn-danger" title="Eliminar"
                            onclick="deleteItem('{{ route('voyager.companies.destroy', $item->id) }}')">
                            <i class="voyager-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center" class="text-muted">No hay empresas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div style="display:flex; justify-content:space-between; align-items:center; padding: 0 4px">
        <small class="text-muted">
            Mostrando {{ $data->firstItem() }}–{{ $data->lastItem() }} de {{ $data->total() }} empresa(s)
        </small>
        <div>
            @if ($data->onFirstPage())
                <button class="btn btn-sm btn-default" disabled><i class="fa fa-chevron-left"></i></button>
            @else
                <button class="btn btn-sm btn-default" onclick="list({{ $data->currentPage() - 1 }})">
                    <i class="fa fa-chevron-left"></i>
                </button>
            @endif

            <span style="margin: 0 8px; font-size:12px">Página {{ $data->currentPage() }} / {{ $data->lastPage() }}</span>

            @if ($data->hasMorePages())
                <button class="btn btn-sm btn-default" onclick="list({{ $data->currentPage() + 1 }})">
                    <i class="fa fa-chevron-right"></i>
                </button>
            @else
                <button class="btn btn-sm btn-default" disabled><i class="fa fa-chevron-right"></i></button>
            @endif
        </div>
    </div>
</div>
