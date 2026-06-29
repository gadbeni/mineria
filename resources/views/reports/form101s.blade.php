@extends('voyager::master')

@section('page_title', 'Reporte — Formularios 101')

@section('page_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-title">
                <i class="fa-regular fa-file-lines"></i> Reporte de Formularios 101
            </h1>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">

                    {{-- Filtros --}}
                    <form method="GET" action="{{ route('reports.form101s') }}" style="margin-bottom:16px">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Empresa</label>
                                <select name="empresa_id" class="form-control input-sm">
                                    <option value="">— Todas las empresas —</option>
                                    @foreach($companies as $c)
                                        <option value="{{ $c->id }}" {{ $empresaId == $c->id ? 'selected' : '' }}>
                                            {{ $c->razon }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Origen</label>
                                <select name="origen" class="form-control input-sm">
                                    <option value="">— Todos —</option>
                                    @foreach($origenes as $o)
                                        <option value="{{ $o }}" {{ ($origen ?? '') == $o ? 'selected' : '' }}>{{ $o }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Destino Final</label>
                                <select name="destino_final" class="form-control input-sm">
                                    <option value="">— Todos —</option>
                                    @foreach($destinos as $d)
                                        <option value="{{ $d }}" {{ ($destinoFinal ?? '') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Desde</label>
                                <input type="date" name="desde" value="{{ $desde }}" class="form-control input-sm">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Hasta</label>
                                <input type="date" name="hasta" value="{{ $hasta }}" class="form-control input-sm">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Estado Formulario</label>
                                <select name="estado" class="form-control input-sm">
                                    <option value="">Todos</option>
                                    <option value="confirmado" {{ ($estado ?? '') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                    <option value="pendiente"  {{ ($estado ?? '') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                </select>
                            </div>
                            <div class="col-md-1 form-group" style="padding-top:25px">
                                <label style="font-weight:normal; display:block; font-size:11px">
                                    <input type="checkbox" name="incluir_eliminados" value="1"
                                           {{ $incluyeEliminados ? 'checked' : '' }}>
                                    Eliminados
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="display:flex; gap:6px; align-items:center">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="voyager-search"></i> Filtrar
                                </button>
                                <a href="{{ route('reports.form101s') }}" class="btn btn-default btn-sm">
                                    <i class="voyager-x"></i> Limpiar
                                </a>
                                <div style="margin-left:auto; display:flex; gap:6px;">
                                    <a href="{{ route('reports.form101s', array_merge(request()->query(), ['preview' => 1])) }}"
                                       class="btn btn-warning btn-sm" target="_blank">
                                        <i class="fa-solid fa-eye"></i> Previsualizar
                                    </a>
                                    <a href="{{ route('reports.form101s', array_merge(request()->query(), ['pdf' => 1])) }}"
                                       class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-file-pdf"></i> Exportar PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <p class="text-muted">
                        Total: <strong>{{ count($data) }}</strong> registro(s)
                        @if($desde || $hasta)
                            &mdash; Período:
                            {{ $desde ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '—' }}
                            al
                            {{ $hasta ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '—' }}
                        @endif
                    </p>

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
                                    <th style="text-align:right">Peso Bruto</th>
                                    <th style="text-align:right">Peso Neto</th>
                                    <th>Municipio</th>
                                    <th>Localidad</th>
                                    <th>Origen</th>
                                    <th>Destino Final</th>
                                    <th style="text-align:center">Est. Formulario</th>
                                    <th style="text-align:center">Est. C.O.M.</th>
                                    <th style="text-align:center">Fecha Creación</th>
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
                                    <td style="text-align:right">{{ $item->pesoBruto }}</td>
                                    <td style="text-align:right">{{ $item->pesoNeto }}</td>
                                    <td>{{ $item->municipio }}</td>
                                    <td>{{ $item->localidad }}</td>
                                    <td>{{ $item->origen }}</td>
                                    <td>{{ $item->final }}</td>
                                    <td style="text-align:center">
                                        @if($item->confirmado)
                                            <span class="label label-success">Confirmado</span>
                                        @else
                                            <span class="label label-warning">Pendiente</span>
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
                                    <td colspan="{{ $incluyeEliminados ? 16 : 15 }}" style="text-align:center" class="text-muted">No hay registros para el período seleccionado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
