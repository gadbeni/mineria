@extends('voyager::master')

@section('page_title', 'Reporte — Empresas Empadronadas')

@section('page_header')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-title">
                <i class="fa-solid fa-building"></i> Reporte de Empresas Empadronadas
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
                    <form method="GET" action="{{ route('reports.companies') }}"
                          class="form-inline" style="margin-bottom:16px; display:flex; flex-wrap:wrap; gap:8px; align-items:flex-end">

                        <div class="form-group" style="margin-right:8px">
                            <label style="margin-right:4px">Buscar:</label>
                            <input type="text" name="q" value="{{ $q ?? '' }}"
                                   class="form-control input-sm" placeholder="NIT, razón social, código...">
                        </div>

                        <div class="form-group" style="margin-right:8px">
                            <label style="margin-right:4px">Estado:</label>
                            <select name="estado" class="form-control input-sm">
                                <option value="">Todas (activas + bajas)</option>
                                <option value="activa"  {{ ($estado ?? '') == 'activa'  ? 'selected' : '' }}>Solo activas</option>
                                <option value="baja"    {{ ($estado ?? '') == 'baja'    ? 'selected' : '' }}>Solo bajas</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="voyager-search"></i> Filtrar
                        </button>
                        <a href="{{ route('reports.companies') }}" class="btn btn-default btn-sm">
                            <i class="voyager-x"></i> Limpiar
                        </a>

                        <div style="margin-left:auto; display:flex; gap:6px;">
                            <a href="{{ route('reports.companies', array_merge(request()->query(), ['preview' => 1])) }}"
                               class="btn btn-warning btn-sm" target="_blank">
                                <i class="fa-solid fa-eye"></i> Previsualizar PDF
                            </a>
                            <a href="{{ route('reports.companies', array_merge(request()->query(), ['pdf' => 1])) }}"
                               class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-file-pdf"></i> Exportar PDF
                            </a>
                        </div>
                    </form>

                    <p class="text-muted">
                        Total: <strong>{{ count($data) }}</strong> empresa(s)
                        @if(!empty($estado))
                            &mdash; Filtro: <strong>{{ $estado == 'activa' ? 'Solo activas' : 'Solo bajas' }}</strong>
                        @else
                            &mdash; <em>Activas + bajas incluidas</em>
                        @endif
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" style="font-size:12px">
                            <thead style="background:#2e7d32; color:white">
                                <tr>
                                    <th style="text-align:center">#</th>
                                    <th>Cód. Operador Minero</th>
                                    <th>Razón Social</th>
                                    <th>NIT</th>
                                    <th>NIM</th>
                                    <th>Representante Legal</th>
                                    <th>Actividad</th>
                                    <th style="text-align:center">Estado</th>
                                    <th style="text-align:center">Fecha Registro</th>
                                    <th style="text-align:center">Fecha Baja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $i => $item)
                                <tr @if($item->deleted_at) style="background:#fff3f3; color:#888" @endif>
                                    <td style="text-align:center">{{ $i + 1 }}</td>
                                    <td><strong>{{ $item->codeMiningOperator }}</strong></td>
                                    <td style="text-transform:uppercase">{{ $item->razon }}</td>
                                    <td>{{ $item->nit }}</td>
                                    <td>{{ $item->nim }}</td>
                                    <td>{{ $item->representative }}</td>
                                    <td>{{ $item->activity }}</td>
                                    <td style="text-align:center">
                                        @if($item->deleted_at)
                                            <span class="label label-danger">BAJA</span>
                                        @else
                                            <span class="label label-success">ACTIVA</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td style="text-align:center">
                                        {{ $item->deleted_at ? \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y') : '—' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" style="text-align:center" class="text-muted">No hay registros.</td>
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
