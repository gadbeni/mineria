<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Certificado — C.O.M.</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #333; }

        .container { max-width: 750px; margin: 30px auto; background: #fff; border: 2px solid #1a7102; border-radius: 6px; overflow: hidden; }

        .header { background: #1a7102; color: #fff; padding: 18px 24px; display: flex; align-items: center; gap: 16px; }
        .header img { height: 70px; }
        .header-text h1 { font-size: 15px; text-transform: uppercase; margin-bottom: 4px; }
        .header-text h2 { font-size: 12px; font-weight: normal; }

        .badge { background: #e8f5e0; border: 2px solid #1a7102; border-radius: 6px; margin: 20px 24px 10px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; }
        .badge-icon { font-size: 32px; color: #1a7102; }
        .badge-text { font-size: 18px; font-weight: bold; color: #1a7102; }
        .badge-sub { font-size: 12px; color: #555; margin-top: 2px; }

        .com-code { text-align: center; margin: 10px 24px; padding: 12px; border: 2px dashed #1a7102; border-radius: 4px; }
        .com-code .label { font-size: 11px; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        .com-code .code { font-size: 28px; font-weight: bold; color: #1a7102; font-family: Georgia, serif; }

        .section { margin: 0 24px 16px; }
        .section-title { background: #1a7102; color: #fff; font-size: 11px; font-weight: bold; padding: 5px 10px; text-transform: uppercase; }

        table.data { width: 100%; border-collapse: collapse; font-size: 12px; }
        table.data td { padding: 6px 10px; border: 1px solid #ccc; vertical-align: top; }
        table.data td.label { background: #f0f0f0; font-weight: bold; width: 40%; }

        .status-active { color: #1a7102; font-weight: bold; }
        .status-expired { color: #c0392b; font-weight: bold; }

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

    <div class="badge">
        <div class="badge-icon">&#10003;</div>
        <div>
            <div class="badge-text">CERTIFICADO VERIFICADO</div>
            <div class="badge-sub">Este C.O.M. es auténtico y fue emitido por la D.D.M.E.H.</div>
        </div>
    </div>

    <div class="com-code">
        <div class="label">Código Operador Minero</div>
        <div class="code">{{ $certificate->code }}</div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Certificado</div>
        <table class="data">
            <tr>
                <td class="label">Fecha de Emisión</td>
                <td>{{ date('d/m/Y', strtotime($certificate->dateStart)) }}</td>
            </tr>
            <tr>
                <td class="label">Válido Hasta</td>
                <td>
                    {{ date('d/m/Y', strtotime($certificate->dateFinish)) }}
                    @if(\Carbon\Carbon::parse($certificate->dateFinish)->isFuture())
                        <span class="status-active">&nbsp;(VIGENTE)</span>
                    @else
                        <span class="status-expired">&nbsp;(VENCIDO)</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Responsable</td>
                <td>
                    {{ $certificate->signature->alias ?? '' }}
                    {{ $certificate->signature->first_name ?? '' }}
                    {{ $certificate->signature->last_name ?? '' }}<br>
                    <small>{{ $certificate->signature->job ?? '' }}</small>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Operador Minero</div>
        <table class="data">
            <tr>
                <td class="label">Razón Social</td>
                <td style="text-transform:uppercase">{{ $certificate->company->razon }}</td>
            </tr>
            <tr>
                <td class="label">Representante Legal</td>
                <td>{{ $certificate->company->representative }}</td>
            </tr>
            <tr>
                <td class="label">Cédula de Identidad</td>
                <td>{{ $certificate->company->ci }}</td>
            </tr>
            <tr>
                <td class="label">NIT</td>
                <td>{{ $certificate->company->nit }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td>{{ $certificate->company->nim }}</td>
            </tr>
            <tr>
                <td class="label">Actividad</td>
                <td>{{ $certificate->company->activity }}</td>
            </tr>
            <tr>
                <td class="label">Municipio</td>
                <td>{{ $certificate->company->municipe }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Verificación electrónica — Gobierno Autónomo Departamental del Beni &nbsp;|&nbsp; mineria.beni.gob.bo
    </div>

</div>
</body>
</html>
