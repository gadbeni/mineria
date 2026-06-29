<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter portrait;
            margin-top: 1.5cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            margin-left: 2cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #000;
            margin-top: 1.5cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            margin-left: 2cm;
        }

        .header-logos { width: 100%; border-bottom: 2px solid #2e7d32; padding-bottom: 6px; margin-bottom: 8px; }
        .header-logos td { vertical-align: middle; }
        .inst1 { font-size: 13px; font-weight: bold; text-align: center; }
        .inst2 { font-size: 11px; font-weight: bold; text-align: center; }
        .rep-title { font-size: 12px; font-weight: bold; color: #2e7d32; text-align: center; margin-top: 3px; }
        .periodo { font-size: 8px; color: #555; text-align: center; margin-top: 2px; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.data th {
            background-color: #2e7d32;
            color: white;
            padding: 4px 3px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #1a5e20;
        }
        table.data td {
            border: 1px solid #ccc;
            padding: 3px 3px;
            font-size: 8px;
            vertical-align: middle;
        }
        table.data tbody tr:nth-child(even) { background-color: #f1f8e9; }
        table.data tbody tr.eliminado { background-color: #fff3f3; color: #888; }

        .activo        { color: #1b5e20; font-weight: bold; }
        .inactivo      { color: #b71c1c; font-weight: bold; }
        .eliminado-lbl { color: #b71c1c; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 7.5px; color: #777; text-align: right; }
        .total { font-size: 9px; margin: 4px 0; }
    </style>
</head>
<body>

    <table class="header-logos" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width:10%"><img src="{{ public_path('images/icon.png') }}" width="55px"></td>
            <td>
                <div class="inst1">GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI</div>
                <div class="inst2">DIRECCIÓN DEPARTAMENTAL DE MINERÍA, ENERGÍA E HIDROCARBUROS</div>
                <div class="rep-title">REPORTE DE CERTIFICADOS DE OPERADOR MINERO (C.O.M.)</div>
                <div class="periodo">
                    @if($desde || $hasta)
                        Período: {{ $desde ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '—' }}
                        al {{ $hasta ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '—' }}
                    @else
                        Todos los registros
                    @endif
                    @if(!empty($empresaNombre))
                        &nbsp;|&nbsp; Empresa: {{ $empresaNombre }}
                    @endif
                    @if(!empty($municipio))
                        &nbsp;|&nbsp; Municipio: {{ $municipio }}
                    @endif
                    @if(!empty($estado))
                        &nbsp;|&nbsp; Estado: {{ ucfirst($estado) }}
                    @endif
                    @if(!empty($incluyeEliminados))
                        &nbsp;|&nbsp; Incluye eliminados
                    @endif
                    &nbsp;|&nbsp; Generado: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </td>
            <td style="width:10%; text-align:right"><img src="{{ public_path('images/mineria.png') }}" width="55px"></td>
        </tr>
    </table>

    <p class="total">Total de registros: <strong>{{ count($data) }}</strong></p>

    <table class="data">
        <thead>
            <tr>
                <th style="width:4%">#</th>
                <th style="width:12%">Código C.O.M.</th>
                <th style="width:22%">Empresa / Razón Social</th>
                <th style="width:9%">NIT</th>
                <th style="width:9%">NIM</th>
                <th style="width:18%">Representante Legal</th>
                <th style="width:8%">Fecha Inicio</th>
                <th style="width:8%">Fecha Fin</th>
                <th style="width:5%">Estado</th>
                <th style="width:10%">Fecha Creación</th>
                @if(!empty($incluyeEliminados))
                <th style="width:7%">Eliminado</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr class="{{ $item->deleted_at ? 'eliminado' : '' }}">
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td style="text-align:center; font-weight:bold">{{ $item->company->codeMiningOperator }}</td>
                <td>{{ $item->company->razon }}</td>
                <td style="text-align:center">{{ $item->company->nit }}</td>
                <td style="text-align:center">{{ $item->company->nim }}</td>
                <td>{{ $item->company->representative }}</td>
                <td style="text-align:center">
                    {{ $item->dateStart ? \Carbon\Carbon::parse($item->dateStart)->format('d/m/Y') : '—' }}
                </td>
                <td style="text-align:center">
                    {{ $item->dateFinish ? \Carbon\Carbon::parse($item->dateFinish)->format('d/m/Y') : '—' }}
                </td>
                <td style="text-align:center">
                    @if($item->deleted_at)
                        —
                    @elseif(\Carbon\Carbon::parse($item->dateFinish)->gte(\Carbon\Carbon::today()))
                        <span class="activo">ACTIVO</span>
                    @else
                        <span class="inactivo">INACTIVO</span>
                    @endif
                </td>
                <td style="text-align:center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                @if(!empty($incluyeEliminados))
                <td style="text-align:center">
                    @if($item->deleted_at)
                        <span class="eliminado-lbl">{{ \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y') }}</span>
                    @else
                        —
                    @endif
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ !empty($incluyeEliminados) ? 11 : 10 }}" style="text-align:center; padding:8px">Sin registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        GAD BENI — Dirección de Minería, Energía e Hidrocarburos &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
