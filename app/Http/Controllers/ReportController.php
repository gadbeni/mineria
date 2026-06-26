<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Form101;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ? Form101::withTrashed()->with(['certificate.company', 'typeMineral'])
            : Form101::with(['certificate.company', 'typeMineral'])->whereNull('deleted_at');

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'confirmado') {
                $query->where('confirmado', 1);
            } elseif ($request->estado === 'pendiente') {
                $query->where('confirmado', 0);
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
                        compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'empresaNombre', 'origen', 'destinoFinal'))
                ->setPaper('letter', 'landscape');

            return $request->has('preview')
                ? $pdf->stream('Reporte-Formularios101.pdf')
                : $pdf->download('Reporte-Formularios101.pdf');
        }

        return view('reports.form101s',
            compact('data', 'desde', 'hasta', 'estado', 'incluyeEliminados', 'companies', 'empresaId', 'origen', 'destinoFinal', 'origenes', 'destinos'));
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
