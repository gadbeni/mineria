<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter landscape;
            margin-top: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 1.5cm;
            margin-left: 1.5cm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 8.5px;
            color: #000;
            margin-top: 1.5cm;
            margin-right: 1.5cm;
            margin-bottom: 1.5cm;
            margin-left: 1.5cm;
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
            font-size: 7.5px;
            border: 1px solid #1a5e20;
        }
        table.data td {
            border: 1px solid #ccc;
            padding: 3px;
            font-size: 7.5px;
            vertical-align: middle;
        }
        table.data tbody tr:nth-child(even) { background-color: #f1f8e9; }
        table.data tbody tr.baja { background-color: #fff3f3; color: #888; }

        .activa { color: #1b5e20; font-weight: bold; }
        .baja   { color: #b71c1c; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 7px; color: #777; text-align: right; }
        .total  { font-size: 8.5px; margin: 4px 0; }
    </style>
</head>
<body>

    <table class="header-logos" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width:10%"><img src="{{ public_path('images/icon.png') }}" width="55px"></td>
            <td>
                <div class="inst1">GOBIERNO AUTÓNOMO DEPARTAMENTAL DEL BENI</div>
                <div class="inst2">DIRECCIÓN DEPARTAMENTAL DE MINERÍA, ENERGÍA E HIDROCARBUROS</div>
                <div class="rep-title">REPORTE DE EMPRESAS EMPADRONADAS</div>
                <div class="periodo">
                    @if(!empty($estado))
                        Estado: {{ $estado == 'activa' ? 'Solo activas' : 'Solo bajas' }} &nbsp;|&nbsp;
                    @else
                        Activas + bajas &nbsp;|&nbsp;
                    @endif
                    Generado: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </td>
            <td style="width:10%; text-align:right"><img src="{{ public_path('images/mineria.png') }}" width="55px"></td>
        </tr>
    </table>

    <p class="total">Total de empresas: <strong>{{ count($data) }}</strong></p>

    <table class="data">
        <thead>
            <tr>
                <th style="width:3%">#</th>
                <th style="width:11%">Cód. Operador Minero</th>
                <th style="width:22%">Razón Social</th>
                <th style="width:8%">NIT</th>
                <th style="width:8%">NIM</th>
                <th style="width:18%">Representante Legal</th>
                <th style="width:17%">Actividad</th>
                <th style="width:5%">Estado</th>
                <th style="width:8%">F. Registro</th>
                <th style="width:8%">F. Baja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            <tr class="{{ $item->deleted_at ? 'baja' : '' }}">
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td style="text-align:center; font-weight:bold">{{ $item->codeMiningOperator }}</td>
                <td style="text-transform:uppercase">{{ $item->razon }}</td>
                <td style="text-align:center">{{ $item->nit }}</td>
                <td style="text-align:center">{{ $item->nim }}</td>
                <td>{{ $item->representative }}</td>
                <td>{{ $item->activity }}</td>
                <td style="text-align:center">
                    @if($item->deleted_at)
                        <span class="baja">BAJA</span>
                    @else
                        <span class="activa">ACTIVA</span>
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
                <td colspan="10" style="text-align:center; padding:8px">Sin registros</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        GAD BENI — Dirección de Minería, Energía e Hidrocarburos &nbsp;|&nbsp; {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
