<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Documento — Formulario 101</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; }

        .container { max-width: 750px; margin: 30px auto; background: #fff; border: 2px solid #2db308; border-radius: 6px; overflow: hidden; }

        .header { background: #093101; color: #fff; padding: 18px 24px; display: flex; align-items: center; gap: 16px; }
        .header img { height: 70px; }
        .header-text h1 { font-size: 15px; text-transform: uppercase; margin-bottom: 4px; }
        .header-text h2 { font-size: 12px; font-weight: normal; }

        .badge { border-radius: 6px; margin: 20px 24px 10px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; }
        .badge.vigente { background: #e8f5e0; border: 2px solid #1a7102; }
        .badge.caducado { background: #fde8e8; border: 2px solid #b30000; }
        .badge-icon { font-size: 32px; }
        .badge.vigente .badge-icon { color: #155906; }
        .badge.caducado .badge-icon { color: #b30000; }
        .badge-text { font-size: 18px; font-weight: bold; }
        .badge.vigente .badge-text { color: #1a7102; }
        .badge.caducado .badge-text { color: #b30000; }
        .badge-status { display: inline-block; margin-left: 10px; font-size: 14px; font-weight: bold; padding: 2px 10px; border-radius: 4px; vertical-align: middle; }
        .badge-status.vigente { background: #1a7102; color: #fff; }
        .badge-status.caducado { background: #b30000; color: #fff; }
        .badge-sub { font-size: 12px; color: #555; margin-top: 2px; }

        .section { margin: 0 24px 16px; }
        .section-title { background: #0ab11b; color: #fff; font-size: 11px; font-weight: bold; padding: 5px 10px; text-transform: uppercase; }

        table.data { width: 100%; border-collapse: collapse; font-size: 12px; }
        table.data td { padding: 6px 10px; border: 1px solid #ccc; vertical-align: top; }
        table.data td.label { background: #f0f0f0; font-weight: bold; width: 40%; }

        .footer { background: #f0f0f0; border-top: 1px solid #ccc; padding: 10px 24px; font-size: 10px; color: #777; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <img src="{{ asset('images/icon2.png') }}" alt="GAD Beni">
        <div class="header-text">
            <h1>Gobierno Autónomo Departamental del Beni</h1>
            <h2>Dirección Departamental de Minería, Energía e Hidrocarburos</h2>
        </div>
    </div>

    @php
        $expiry = \Carbon\Carbon::parse($form->created_at)->addDays(8);
        $isExpired = now()->gt($expiry);
    @endphp
    <div class="badge {{ $isExpired ? 'caducado' : 'vigente' }}">
        <div class="badge-icon">{{ $isExpired ? '✕' : '✓' }}</div>
        <div>
            <div class="badge-text">
                DOCUMENTO VERIFICADO
                <span class="badge-status {{ $isExpired ? 'caducado' : 'vigente' }}">
                    {{ $isExpired ? 'CADUCADO' : 'VIGENTE' }}
                </span>
            </div>
            <div class="badge-sub">Este Formulario 101 de Transporte de Minerales y Metales, es auténtico y fue emitido por la Dirección Departamental de Minería, Energía e Hidrocarburos (D.D.M.E.H.)</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Formulario</div>
        <table class="data">
            <tr>
                <td class="label">Número de Formulario</td>
                <td>{{ $form->code }}</td>
            </tr>
            <tr>
                <td class="label">C.O.M.</td>
                <td>{{ $form->certificate->code }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Emisión</td>
                <td>{{ date('d/m/Y H:i', strtotime($form->created_at)) }}</td>
            </tr>
            <tr>
                <td class="label">Válido Hasta</td>
                <td>{{ date('d/m/Y H:i', strtotime($form->created_at . ' +8 days')) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Operador Minero</div>
        <table class="data">
            <tr>
                <td class="label">Razón Social</td>
                <td>{{ $form->certificate->company->razon }}</td>
            </tr>
            <tr>
                <td class="label">Representante Legal</td>
                <td>{{ $form->certificate->company->representative }}</td>
            </tr>
            <tr>
                <td class="label">NIT</td>
                <td>{{ $form->certificate->company->nit }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td>{{ $form->certificate->company->nim }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Mineral Transportado</div>
        <table class="data">
            <tr>
                <td class="label">Tipo de Mineral</td>
                <td>{{ $form->typeMineral->name }}</td>
            </tr>
            <tr>
                <td class="label">Ley de Mineral</td>
                <td>{{ $form->leyMineral }} %</td>
            </tr>
            <tr>
                <td class="label">Peso Bruto / Neto</td>
                <td>{{ $form->pesoBruto }} / {{ $form->pesoNeto }} ({{ $form->unidaddemedida1 }})</td>
            </tr>
            <tr>
                <td class="label">Humedad</td>
                <td>{{ $form->humedad }} %</td>
            </tr>
            <tr>
                <td class="label">Lote</td>
                <td>{{ $form->lote }}</td>
            </tr>
            <tr>
                <td class="label">Origen → Destino Final</td>
                <td>{{ $form->origen }} → {{ $form->final }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Datos del Medio de Transporte</div>
        <table class="data">
            <tr>
                <td class="label">Medio de Transporte</td>
                <td>{{ $form->medioTransporte}}</td>
            </tr>
            <tr>
                <td class="label">Placa / Matricula</td>
                <td>{{ $form->matricula }}</td>
            </tr>
            <tr>
                <td class="label">Nombre del Conductor</td>
                <td>{{ $form->nombreConductor}} </td>
            </tr>
        </table>
    </div>
    <div class="footer">
        Verificación electrónica — Gobierno Autónomo Departamental del Beni &nbsp;|&nbsp; mineria.beni.gob.bo
    </div>

</div>
</body>
</html>
