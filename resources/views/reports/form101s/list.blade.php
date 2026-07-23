<div class="table-responsive">
    <table class="table table-bordered table-hover table-sm" style="font-size:12px">
        <thead style="background:#2e7d32; color:white">
            <tr>
                <th>#</th>
                <th>Código de Formulario</th>
                <th>C.O.M.</th>
                <th>Empresa / Razón Social</th>
                <th>NIT</th>
                <th>Tipo Mineral</th>
                <th style="text-align:center">U.M.</th>
                <th style="text-align:right">Peso Bruto</th>
                <th style="text-align:right">Peso Neto</th>
                <th>Municipio</th>
                <th>Localidad</th>
                <th>Origen</th>
                <th>Destino Final</th>
                <th style="text-align:center">Est. Formulario</th>
                <th style="text-align:center">Est. C.O.M.</th>
                <th style="text-align:center">Fecha Creación</th>
                <th>Registrado por</th>
                @if($incluyeEliminados)
                <th style="text-align:center">Eliminado</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr @if($item->deleted_at) style="background:#fff3f3; color:#888" @endif>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $item->code }}</strong></td>
                <td>{{ $item->certificate?->company?->codeMiningOperator ?? '—' }}</td>
                <td>{{ $item->certificate?->company?->razon ?? '—' }}</td>
                <td>{{ $item->certificate?->company?->nit ?? '—' }}</td>
                <td>{{ $item->typeMineral?->name ?? '—' }}</td>
                <td style="text-align:center">{{ $item->unidaddemedida1 ?? '—' }}</td>
                <td style="text-align:right">{{ $item->pesoBruto }}</td>
                <td style="text-align:right">{{ $item->pesoNeto }}</td>
                <td>{{ $item->municipio }}</td>
                <td>{{ $item->localidad }}</td>
                <td>{{ $item->origen }}</td>
                <td>{{ $item->final }}</td>
                <td style="text-align:center">
                    @if($item->status == 'Confirmado')
                        <span class="label label-success">Confirmado</span>
                    @elseif($item->status == 'Pendiente')
                        <span class="label label-warning">Pendiente</span>
                    @elseif($item->status == 'Borrador')
                        <span class="label label-default">Borrador</span>
                    @else
                        <span class="label label-default">{{ $item->status ?? '—' }}</span>
                    @endif
                </td>
                <td style="text-align:center">
                    @if($item->certificate?->dateFinish && \Carbon\Carbon::parse($item->certificate->dateFinish)->gte(\Carbon\Carbon::today()))
                        <span class="label label-success">Activo</span>
                    @elseif($item->certificate?->dateFinish)
                        <span class="label label-danger">Inactivo</span>
                    @else
                        <span class="label label-default">—</span>
                    @endif
                </td>
                <td style="text-align:center">
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                </td>
                <td>{{ optional($item->registeredBy)->name ?? '—' }}</td>
                @if($incluyeEliminados)
                <td style="text-align:center">
                    @if($item->deleted_at)
                        <span class="label label-danger">
                            {{ \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y') }}
                        </span>
                    @else
                        —
                    @endif
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $incluyeEliminados ? 18 : 17 }}" style="text-align:center" class="text-muted">No hay registros para el período seleccionado.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot style="background:#eef5ee; font-weight:bold">
            @if(($subtotalUm ?? '') == '' || ($subtotalUm ?? '') == 'kg')
            <tr>
                <td colspan="8" style="text-align:right">Subtotal Peso Neto (Kg):</td>
                <td style="text-align:right">{{ number_format($totalPesoNetoKg, 2) }}</td>
                <td colspan="{{ $incluyeEliminados ? 9 : 8 }}" style="text-align:left">Kg</td>
            </tr>
            @endif
            @if(($subtotalUm ?? '') == '' || ($subtotalUm ?? '') == 'gr')
            <tr>
                <td colspan="8" style="text-align:right">Subtotal Peso Neto (Gr):</td>
                <td style="text-align:right">{{ number_format($totalPesoNetoGr, 2) }}</td>
                <td colspan="{{ $incluyeEliminados ? 9 : 8 }}" style="text-align:left">Gr</td>
            </tr>
            @endif
            @if(($subtotalUm ?? '') == 'total')
            <tr style="background:#e3eefc">
                <td colspan="8" style="text-align:right">Total general (Gr convertidos a Kg + Kg):</td>
                <td style="text-align:right">{{ number_format($totalGeneralKg, 2) }}</td>
                <td colspan="{{ $incluyeEliminados ? 9 : 8 }}" style="text-align:left">Kg</td>
            </tr>
            @endif
        </tfoot>
    </table>
</div>

{{-- Paginación (20 por página) --}}
@if($data->hasPages())
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-top:10px">
        <span class="text-muted" style="font-size:12px">
            Mostrando {{ $data->firstItem() }}–{{ $data->lastItem() }} de {{ $data->total() }} registro(s)
        </span>
        <nav>
            {{ $data->onEachSide(1)->links() }}
        </nav>
    </div>
@endif
