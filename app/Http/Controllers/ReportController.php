<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Form101;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class ReportController extends Controller
{
    /**
     * Reporte de Formularios 101 (DDMEH) con filtro por rango de fechas.
     * Soporta exportación PDF (descarga o preview en el navegador).
     */
    public function form101s(Request $request)
    {
        if (!auth()->user()->hasPermission('browse_reportsform101s')) {
            abort(403, 'No tienes permiso para acceder a los reportes.');
        }

        $incluyeEliminados = $request->filled('incluir_eliminados');

        $query = $incluyeEliminados
            ? Form101::withTrashed()->with(['certificate.company', 'typeMineral', 'registeredBy'])
            : Form101::with(['certificate.company', 'typeMineral', 'registeredBy'])->whereNull('deleted_at');

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'confirmado') {
                $query->where('status', 'Confirmado');
            } elseif ($request->estado === 'pendiente') {
                $query->where('status', 'Pendiente');
            } elseif ($request->estado === 'borrador') {
                $query->where('status', 'Borrador');
            }
        }

        if ($request->filled('empresa_id')) {
            $query->whereHas('certificate', function ($q) use ($request) {
                $q->where('company_id', $request->empresa_id);
            });
        } elseif (Auth::user()->company_id) {
            $query->whereHas('certificate', function ($q) {
                $q->where('company_id', Auth::user()->company_id);
            });
        }

        if ($request->filled('origen')) {
            $query->where('origen', $request->origen);
        }

        if ($request->filled('destino_final')) {
            $query->where('final', $request->destino_final);
        }

        $data              = $query->orderBy('id', 'DESC')->get();
        // Subtotales de Peso Neto por unidad de medida
        $totalPesoNetoKg   = $data->where('unidaddemedida1', 'Kg')->sum('pesoNeto');
        $totalPesoNetoGr   = $data->where('unidaddemedida1', 'Gr')->sum('pesoNeto');
        // Total general en Kg (gramos convertidos a Kg + kilogramos)
        $totalGeneralKg    = $totalPesoNetoKg + ($totalPesoNetoGr / 1000);
        $desde             = $request->desde;
        $hasta             = $request->hasta;
        $estado            = $request->estado;
        $empresaId         = $request->empresa_id;
        $origen            = $request->origen;
        $destinoFinal      = $request->destino_final;
        $companies         = Company::withTrashed()->orderBy('razon')->get();
        $origenes          = Form101::whereNotNull('origen')->where('origen', '!=', '')->distinct()->orderBy('origen')->pluck('origen');
        $destinos          = Form101::whereNotNull('final')->where('final', '!=', '')->distinct()->orderBy('final')->pluck('final');

        if ($request->has('pdf') || $request->has('preview')) {
            $empresaNombre = $empresaId ? Company::find($empresaId)?->razon : null;
            $pdf = Pdf::loadView('reports.pdf.form101s',
                        compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'empresaNombre', 'origen', 'destinoFinal', 'totalPesoNetoKg', 'totalPesoNetoGr', 'totalGeneralKg'))
                ->setPaper('letter', 'landscape');

            return $request->has('preview')
                ? $pdf->stream('Reporte-Formularios101.pdf')
                : $pdf->download('Reporte-Formularios101.pdf');
        }

        if ($request->has('excel')) {
            $empresaNombre = $empresaId ? Company::find($empresaId)?->razon : null;
            return $this->form101sExcel($data, $incluyeEliminados, $totalPesoNetoKg, $totalPesoNetoGr, $totalGeneralKg);
        }

        $subtotalUm = $request->subtotal_um; // '', 'kg', 'gr', 'total'

        return view('reports.form101s',
            compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'companies', 'empresaId', 'origen', 'destinoFinal', 'origenes', 'destinos', 'totalPesoNetoKg', 'totalPesoNetoGr', 'subtotalUm', 'totalGeneralKg'));
    }

    /**
     * Genera y descarga el reporte de Formularios 101 en formato XLSX real (PhpSpreadsheet).
     */
    private function form101sExcel($data, $incluyeEliminados, $totalPesoNetoKg = 0, $totalPesoNetoGr = 0, $totalGeneralKg = 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Formularios 101');

        $headers = [
            '#', 'Código de Formulario', 'C.O.M.', 'Empresa / Razón Social', 'NIT',
            'Tipo Mineral', 'U.M.', 'Peso Bruto', 'Peso Neto', 'Municipio', 'Localidad',
            'Origen', 'Destino Final', 'Est. Formulario', 'Est. C.O.M.', 'Fecha Creación', 'Registrado por',
        ];
        if ($incluyeEliminados) {
            $headers[] = 'Eliminado';
        }

        // Encabezados
        $col = 1;
        foreach ($headers as $h) {
            $sheet->setCellValue([$col, 1], $h);
            $col++;
        }
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headers));
        $headerStyle = $sheet->getStyle('A1:' . $lastColLetter . '1');
        $headerStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2E7D32');
        $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Filas
        $row = 2;
        foreach ($data as $i => $item) {
            $estadoForm = $item->status ?? '—';

            $comActivo = '—';
            if ($item->certificate && $item->certificate->dateFinish) {
                $comActivo = \Carbon\Carbon::parse($item->certificate->dateFinish)->gte(\Carbon\Carbon::today()) ? 'Activo' : 'Inactivo';
            }

            $c = 1;
            $sheet->setCellValue([$c++, $row], $i + 1);
            $sheet->setCellValue([$c++, $row], $item->code);
            $sheet->setCellValue([$c++, $row], optional(optional($item->certificate)->company)->codeMiningOperator ?? '—');
            $sheet->setCellValue([$c++, $row], optional(optional($item->certificate)->company)->razon ?? '—');
            // NIT como texto (evita notación científica)
            $sheet->setCellValueExplicit([$c++, $row], (string) (optional(optional($item->certificate)->company)->nit ?? '—'), DataType::TYPE_STRING);
            $sheet->setCellValue([$c++, $row], optional($item->typeMineral)->name ?? '—');
            $sheet->setCellValue([$c++, $row], $item->unidaddemedida1 ?? '—');
            $sheet->setCellValue([$c++, $row], (float) $item->pesoBruto);
            $sheet->setCellValue([$c++, $row], (float) $item->pesoNeto);
            $sheet->setCellValue([$c++, $row], $item->municipio);
            $sheet->setCellValue([$c++, $row], $item->localidad);
            $sheet->setCellValue([$c++, $row], $item->origen);
            $sheet->setCellValue([$c++, $row], $item->final);
            $sheet->setCellValue([$c++, $row], $estadoForm);
            $sheet->setCellValue([$c++, $row], $comActivo);
            $sheet->setCellValue([$c++, $row], \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i'));
            $sheet->setCellValue([$c++, $row], optional($item->registeredBy)->name ?? '—');
            if ($incluyeEliminados) {
                $sheet->setCellValue([$c++, $row], $item->deleted_at ? \Carbon\Carbon::parse($item->deleted_at)->format('d/m/Y') : '');
            }
            $row++;
        }

        // Filas de subtotales y total general al final
        $lastCol = count($headers);
        $row++; // fila en blanco

        $resumen = [
            ['Subtotal Peso Neto (Kg):', number_format($totalPesoNetoKg, 2) . ' Kg'],
            ['Subtotal Peso Neto (Gr):', number_format($totalPesoNetoGr, 2) . ' Gr'],
            ['Total general (Gr→Kg + Kg):', number_format($totalGeneralKg, 2) . ' Kg'],
        ];
        foreach ($resumen as $r) {
            $sheet->setCellValue([1, $row], $r[0]);
            $sheet->mergeCells('A' . $row . ':F' . $row);
            $sheet->setCellValue([7, $row], $r[1]);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('G' . $row)->getFont()->setBold(true);
            $row++;
        }

        // Autoajuste de ancho
        for ($col = 1; $col <= count($headers); $col++) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }
        $sheet->freezePane('A2');

        $filename = 'Reporte-Formularios101-' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Reporte de Certificados de Operador Minero (C.O.M.) con filtro por fechas.
     * Soporta exportación PDF (descarga o preview en el navegador).
     */
    public function certificates(Request $request)
    {
        if (!auth()->user()->hasPermission('browse_reportscertificates')) {
            abort(403, 'No tienes permiso para acceder a los reportes.');
        }

        $incluyeEliminados = $request->filled('incluir_eliminados');

        $query = $incluyeEliminados
            ? Certificate::withTrashed()->with(['company', 'signature'])
            : Certificate::with(['company', 'signature'])->whereNull('deleted_at');

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        if ($request->filled('estado')) {
            $hoy = \Carbon\Carbon::today();
            if ($request->estado === 'activo') {
                $query->where('dateFinish', '>=', $hoy);
            } elseif ($request->estado === 'inactivo') {
                $query->where('dateFinish', '<', $hoy);
            }
        }

        if ($request->filled('empresa_id')) {
            $query->where('company_id', $request->empresa_id);
        }

        if ($request->filled('municipio')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('municipe', $request->municipio);
            });
        }

        $data              = $query->orderBy('id', 'DESC')->get();
        $desde             = $request->desde;
        $hasta             = $request->hasta;
        $estado            = $request->estado;
        $empresaId         = $request->empresa_id;
        $municipio         = $request->municipio;
        $companies         = Company::where('deleted_at', null)->orderBy('razon')->get();
        $municipios        = Company::whereNotNull('municipe')->where('municipe', '!=', '')->distinct()->orderBy('municipe')->pluck('municipe');

        if ($request->has('pdf') || $request->has('preview')) {
            $empresaNombre = $empresaId ? Company::find($empresaId)?->razon : null;
            $pdf = Pdf::loadView('reports.pdf.certificates',
                        compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'empresaNombre', 'municipio'))
                ->setPaper('letter', 'portrait');

            return $request->has('preview')
                ? $pdf->stream('Reporte-Certificados.pdf')
                : $pdf->download('Reporte-Certificados.pdf');
        }

        return view('reports.certificates',
            compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'companies', 'empresaId', 'municipio', 'municipios'));
    }

    /**
     * Reporte de Empresas Empadronadas — incluye empresas con baja (soft delete).
     */
    public function companies(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a los reportes.');
        }

        // Siempre con withTrashed para que el reporte muestre activas + bajas
        $query = Company::withTrashed();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("nit like '%$search%'")
                  ->orWhereRaw("razon like '%$search%'")
                  ->orWhereRaw("codeMiningOperator like '%$search%'")
                  ->orWhereRaw("representative like '%$search%'");
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activa') {
                $query->whereNull('deleted_at');
            } elseif ($request->estado === 'baja') {
                $query->whereNotNull('deleted_at');
            }
        }

        $data   = $query->orderBy('id', 'ASC')->get();
        $estado = $request->estado;
        $q      = $request->q;

        if ($request->has('pdf') || $request->has('preview')) {
            $pdf = Pdf::loadView('reports.pdf.companies', compact('data', 'estado', 'q'))
                ->setPaper('letter', 'landscape');

            return $request->has('preview')
                ? $pdf->stream('Reporte-Empresas.pdf')
                : $pdf->download('Reporte-Empresas.pdf');
        }

        return view('reports.companies', compact('data', 'estado', 'q'));
    }
}
