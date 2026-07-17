<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Form101;
use App\Models\TypeMineral;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use App\Http\Controllers\HTML2PDF;
use App\Models\Form101Rejection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Form101Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $signatures = Signature::where('deleted_at', null)->where('status', 1)->get();
        return view('form101.browse', compact('signatures'));
    }

    public function list($search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;

     
        $companyId = Auth::user()->company_id;

        $data = Form101::with(['certificate.company', 'typeMineral'])
                    ->withCount(['edits', 'rejections'])
                    ->whereHas('certificate', function($query) use($search, $companyId){
                        $query->whereRaw($companyId ? 'company_id = '.$companyId : 1);
                    })
                    ->where(function($query) use ($search, $companyId){
                        if($search){
                            $query->OrwhereHas('certificate.company', function($query) use($search){
                                $query->whereRaw("(razon like '%$search%' or representative like '%$search%' or nit like '%$search%')");
                            })
                            ->OrwhereHas('certificate', function($query) use($search, $companyId){
                                $query->whereRaw("(code like '%$search%')")
                                ->whereRaw($companyId ? 'company_id = '.$companyId : 1);
                            })
                            ->OrwhereHas('typeMineral', function($query) use($search){
                                $query->whereRaw("(name like '%$search%')");
                            })
                            // ->OrWhereRaw($search ? "typeLoan like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "code like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
                   
                return view('form101.list', compact('data'));

    }

    public function create()
    {
        // return 1;
        $company = Company::where('deleted_at', null)->get();
        $type = TypeMineral::where('deleted_at', null)->get();
       // $type = MedioTransporte::where('deleted_at', null)->get();
        //$type = UnidadMedida::where('deleted_at', null)->get();

        return view('form101.edit-add', compact('company', 'type'));

    }

    public function edit($id)
    {
        $form101 = Form101::with(['certificate.company', 'typeMineral'])
                    ->where('id', $id)->where('deleted_at', null)->firstOrFail();

        if ($form101->status !== 'Borrador') {
            return redirect()->route('form101s.index')->with(['message' => 'Solo se pueden editar formularios en estado Borrador.', 'alert-type' => 'error']);
        }

        $company = Company::where('deleted_at', null)->get();
        $type = TypeMineral::where('deleted_at', null)->get();

        return view('form101.edit-add', compact('company', 'type', 'form101'));
    }

    public function update(Request $request, $id)
    {
        $form101 = Form101::where('id', $id)->where('deleted_at', null)->firstOrFail();

        if ($form101->status !== 'Borrador') {
            return redirect()->route('form101s.index')->with(['message' => 'Solo se pueden editar formularios en estado Borrador.', 'alert-type' => 'error']);
        }

        $request->validate([
            'certificate_id'            => 'required|exists:certificates,id',
            'typeMineral_id'            => 'required|exists:type_minerals,id',
            'leyMineral'                => 'required|string|max:50',
            'unidadmedida_id'           => 'required|string|max:20',
            'pesoBruto'                 => 'required|numeric|min:0.01',
            'humedad'                   => 'required|string|max:50',
            'pesoNeto'                  => 'required|numeric|min:0.01',
            'lote'                      => 'required|numeric|min:0.01',
            'municipio'                 => 'required|string|max:100',
            'localidad'                 => 'required|string|max:100',
            'codigoAreaMinero'          => 'required|string|max:100',
            'nombreAreaMinero'          => 'required|string|max:100',
            'medioTransporte'           => 'required|string|max:50',
            'origen'                    => 'required|string|max:150',
            'intermedio'                => 'required|string|max:150',
            'final'                     => 'required|string|max:150',
            'matricula'                 => 'required|string|max:50',
            'nombreConductor'           => 'required|string|max:150',
            'licenciaConducir'          => 'required|string|max:50',
            'nombreEncargadoTrasporte'  => 'required|string|max:150',
            'ciEncargadoTrasporte'      => 'required|string|max:50',
            'observation'               => 'nullable|string|max:500',
        ], [
            'certificate_id.required'           => 'Seleccione una empresa / C.O.M.',
            'typeMineral_id.required'           => 'Seleccione el tipo de mineral.',
            'leyMineral.required'               => 'La ley de mineral es obligatoria.',
            'unidadmedida_id.required'          => 'Seleccione la unidad de medida.',
            'pesoBruto.required'                => 'El peso bruto es obligatorio.',
            'pesoBruto.min'                     => 'El peso bruto debe ser mayor a 0.',
            'humedad.required'                  => 'La humedad es obligatoria.',
            'pesoNeto.required'                 => 'El peso neto es obligatorio.',
            'pesoNeto.min'                      => 'El peso neto debe ser mayor a 0.',
            'lote.required'                     => 'El lote es obligatorio.',
            'municipio.required'                => 'El código de municipio es obligatorio.',
            'localidad.required'                => 'La localidad / comunidad es obligatoria.',
            'codigoAreaMinero.required'         => 'El código de área minera es obligatorio.',
            'nombreAreaMinero.required'         => 'El nombre de área minera es obligatorio.',
            'medioTransporte.required'          => 'Seleccione el medio de transporte.',
            'origen.required'                   => 'El origen es obligatorio.',
            'intermedio.required'               => 'El destino intermedio es obligatorio.',
            'final.required'                    => 'El destino final es obligatorio.',
            'matricula.required'                => 'La placa/matrícula es obligatoria.',
            'nombreConductor.required'          => 'El nombre del conductor es obligatorio.',
            'licenciaConducir.required'         => 'La licencia de conducir es obligatoria.',
            'nombreEncargadoTrasporte.required' => 'El encargado del transporte es obligatorio.',
            'ciEncargadoTrasporte.required'     => 'El C.I. del encargado del transporte es obligatorio.',
        ]);

        // Campos que se auditan (label => valor nuevo)
        $nuevos = [
            'certificate_id'           => $request->certificate_id,
            'typeMineral_id'           => $request->typeMineral_id,
            'leyMineral'               => $request->leyMineral,
            'pesoBruto'                => $request->pesoBruto,
            'humedad'                  => $request->humedad,
            'pesoNeto'                 => $request->pesoNeto,
            'lote'                     => $request->lote,
            'municipio'                => $request->municipio,
            'localidad'                => $request->localidad,
            'codigoAreaMinero'         => $request->codigoAreaMinero,
            'nombreAreaMinero'         => $request->nombreAreaMinero,
            'medioTransporte'          => $request->medioTransporte,
            'origen'                   => $request->origen,
            'intermedio'               => $request->intermedio,
            'final'                    => $request->final,
            'matricula'                => $request->matricula,
            'nombreConductor'          => $request->nombreConductor,
            'licenciaConducir'         => $request->licenciaConducir,
            'nombreEncargadoTrasporte' => $request->nombreEncargadoTrasporte,
            'ciEncargadoTrasporte'     => $request->ciEncargadoTrasporte,
            'observation'              => $request->observation,
            'unidaddemedida1'          => $request->unidadmedida_id,
        ];

        // Snapshot ANTES (solo campos auditados, para el diff)
        $antes = [];
        foreach (array_keys($nuevos) as $campo) {
            $antes[$campo] = $form101->{$campo};
        }

        // Snapshot COMPLETO del registro ANTES (todas las columnas)
        $registroAntes = $form101->toArray();

        // Diff: campos que cambiaron (antes/despues) y los que NO cambiaron
        $changed = [];
        $unchanged = [];
        foreach ($nuevos as $campo => $valorNuevo) {
            if ((string) $antes[$campo] !== (string) $valorNuevo) {
                $changed[$campo] = ['antes' => $antes[$campo], 'despues' => $valorNuevo];
            } else {
                $unchanged[$campo] = $valorNuevo;
            }
        }

        DB::beginTransaction();
        try {
            $form101->update($nuevos);

            // Snapshot COMPLETO del registro DESPUES (todas las columnas)
            $registroDespues = $form101->fresh()->toArray();

            // Guarda historial de edicion en JSON: registro completo antes/despues + diff
            \App\Models\Form101Edit::create([
                'form101_id' => $form101->id,
                'before'     => $registroAntes,
                'after'      => $registroDespues,
                'changed'    => $changed,
                'unchanged'  => $unchanged,
                'edited_by'  => Auth::user()->id,
                'edited_at'  => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['message' => 'Ocurrió un error: ' . $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'certificate_id'            => 'required|exists:certificates,id',
            'typeMineral_id'            => 'required|exists:type_minerals,id',
            'leyMineral'                => 'required|string|max:50',
            'unidadmedida_id'           => 'required|string|max:20',
            'pesoBruto'                 => 'required|numeric|min:0.01',
            'humedad'                   => 'required|string|max:50',
            'pesoNeto'                  => 'required|numeric|min:0.01',
            'lote'                      => 'required|numeric|min:0.01',
            'municipio'                 => 'required|string|max:100',
            'localidad'                 => 'required|string|max:100',
            'codigoAreaMinero'          => 'required|string|max:100',
            'nombreAreaMinero'          => 'required|string|max:100',
            'medioTransporte'           => 'required|string|max:50',
            'origen'                    => 'required|string|max:150',
            'intermedio'                => 'required|string|max:150',
            'final'                     => 'required|string|max:150',
            'matricula'                 => 'required|string|max:50',
            'nombreConductor'           => 'required|string|max:150',
            'licenciaConducir'          => 'required|string|max:50',
            'nombreEncargadoTrasporte'  => 'required|string|max:150',
            'ciEncargadoTrasporte'      => 'required|string|max:50',
            'observation'               => 'nullable|string|max:500',
        ], [
            'certificate_id.required'           => 'Seleccione una empresa / C.O.M.',
            'typeMineral_id.required'           => 'Seleccione el tipo de mineral.',
            'leyMineral.required'               => 'La ley de mineral es obligatoria.',
            'unidadmedida_id.required'          => 'Seleccione la unidad de medida.',
            'pesoBruto.required'                => 'El peso bruto es obligatorio.',
            'pesoBruto.min'                     => 'El peso bruto debe ser mayor a 0.',
            'humedad.required'                  => 'La humedad es obligatoria.',
            'pesoNeto.required'                 => 'El peso neto es obligatorio.',
            'pesoNeto.min'                      => 'El peso neto debe ser mayor a 0.',
            'lote.required'                     => 'El lote es obligatorio.',
            'municipio.required'                => 'El código de municipio es obligatorio.',
            'localidad.required'                => 'La localidad / comunidad es obligatoria.',
            'codigoAreaMinero.required'         => 'El código de área minera es obligatorio.',
            'nombreAreaMinero.required'         => 'El nombre de área minera es obligatorio.',
            'medioTransporte.required'          => 'Seleccione el medio de transporte.',
            'origen.required'                   => 'El origen es obligatorio.',
            'intermedio.required'               => 'El destino intermedio es obligatorio.',
            'final.required'                    => 'El destino final es obligatorio.',
            'matricula.required'                => 'La placa/matrícula es obligatoria.',
            'nombreConductor.required'          => 'El nombre del conductor es obligatorio.',
            'licenciaConducir.required'         => 'La licencia de conducir es obligatoria.',
            'nombreEncargadoTrasporte.required' => 'El encargado del transporte es obligatorio.',
            'ciEncargadoTrasporte.required'     => 'El C.I. del encargado del transporte es obligatorio.',
            'observation.required'              => 'Las observaciones son obligatorias.',
        ]);

        DB::beginTransaction();
        try {
            $form = Form101::create([
                'verification_token'=>Str::uuid(),
                'certificate_id'=>$request->certificate_id,
                'typeMineral_id'=>$request->typeMineral_id,
                'leyMineral'=>$request->leyMineral,
                'pesoBruto'=>$request->pesoBruto,
                'humedad'=>$request->humedad,
                'pesoNeto'=>$request->pesoNeto,
                'lote'=>$request->lote,
                'municipio'=>$request->municipio,
                'localidad'=>$request->localidad,
                'codigoAreaMinero'=>$request->codigoAreaMinero,
                'nombreAreaMinero'=>$request->nombreAreaMinero,
                'medioTransporte'=>$request->medioTransporte,
                'origen'=>$request->origen,
                'intermedio'=>$request->intermedio,
                'final'=>$request->final,
                'matricula'=>$request->matricula,
                'nombreConductor'=>$request->nombreConductor,
                'licenciaConducir'=>$request->licenciaConducir, 
                'nombreEncargadoTrasporte'=>$request->nombreEncargadoTrasporte,
                'ciEncargadoTrasporte'=>$request->ciEncargadoTrasporte,
                'observation'=>$request->observation,
                'unidaddemedida1'=>$request->unidadmedida_id,
                'status'=>'Borrador',
            ]);

            // return $form;
            $form->update(['code'=>'DDMEH-'.str_pad($form->id, 6, "0", STR_PAD_LEFT), 'register_id'=>Auth::user()->id]);

        


            // return 1;
            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Registrado exitosamente exitosamente.', 'alert-type' => 'success']);            
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['message' => 'Ocurrió un error: ' . $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function enviar($id)
    {
        $form101 = Form101::where('id', $id)->where('deleted_at', null)->firstOrFail();

        if ($form101->status !== 'Borrador') {
            return redirect()->route('form101s.index')->with(['message' => 'Solo se pueden enviar formularios en estado Borrador.', 'alert-type' => 'error']);
        }

        $form101->update(['status' => 'Pendiente']);

        return redirect()->route('form101s.index')->with(['message' => 'Formulario enviado correctamente. Estado: Pendiente.', 'alert-type' => 'success']);
    }

    public function prinf($form)
    {
        // return 1;
        $forms = Form101::with(['certificate.company', 'typeMineral'])
                    ->where('id', $form)->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();

        if (!$forms->verification_token) {
            $forms->update(['verification_token' => Str::uuid()]);
        }

                    //$html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 3);
                   // $html2pdf->pdf->SetDisplayMode('fullpage');
                    //$html2pdf->writeHTML($form, isset($_GET['vuehtml']));
                    //$html2pdf->Output('PDF-CF.pdf');

        $qr = base64_encode(QrCode::size(80)->generate('Numero de Formulario: '.$forms->code.', Numero COM: '.$forms->certificate->code.', Numero NIM: '.$forms->certificate->company->nim.', Numero de NIT: '.$forms->certificate->company->nit.', Razon Social: '.$forms->certificate->company->razon.', Representante Legal: '.$forms->certificate->company->representative));
                    







 

         return view('form101.prinf', compact('forms', 'qr'));

        view()->share('forms', $forms);
         $pdf = PDF::loadView('form101.prinf',compact('forms'));

         return $pdf->download('Formulario 101.pdf');





        return PDF::loadView('form101.prinf',compact('forms', 'qr') )
        ->setPaper('A4', 'portrait')
        ->stream('Formulario 101.pdf');

    }
    
    public function preview($form)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo el administrador puede previsualizar formularios.');
        }

        $forms = Form101::with(['certificate.company', 'typeMineral', 'signature'])
                    ->where('id', $form)->where('deleted_at', NULL)->first();

        if (!$forms) {
            abort(404);
        }

        $qr = base64_encode(QrCode::size(80)->generate('Numero de Formulario: '.$forms->code.', Numero COM: '.$forms->certificate->code.', Numero NIM: '.$forms->certificate->company->nim.', Numero de NIT: '.$forms->certificate->company->nit.', Razon Social: '.$forms->certificate->company->razon.', Representante Legal: '.$forms->certificate->company->representative));

        $preview = true;
        return view('form101.prinf', compact('forms', 'qr', 'preview'));
    }

    public function confirmar(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo el administrador puede confirmar formularios.');
        }

        $request->validate(['signature_id' => 'required|exists:signatures,id']);

        $form101 = Form101::where('id', $id)->where('deleted_at', null)->firstOrFail();

        if ($form101->status !== 'Pendiente') {
            return redirect()->route('form101s.index')->with(['message' => 'Solo se pueden confirmar formularios en estado Pendiente.', 'alert-type' => 'error']);
        }

        $form101->update([
            'status'       => 'Confirmado',
            'confirmed_at' => Carbon::now(),
            'confirmed_by' => Auth::user()->id,
            'signature_id' => $request->signature_id,
        ]);

        return redirect()->route('form101s.index')->with(['message' => 'Formulario confirmado correctamente.', 'alert-type' => 'success']);
    }

    public function rechazar(Request $request, $id)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Solo el administrador puede rechazar formularios.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ], [
            'reject_reason.required' => 'Indique el motivo del rechazo.',
        ]);

        $form101 = Form101::where('id', $id)->where('deleted_at', null)->firstOrFail();

        if ($form101->status !== 'Pendiente') {
            return redirect()->route('form101s.index')->with(['message' => 'Solo se pueden rechazar formularios en estado Pendiente.', 'alert-type' => 'error']);
        }

        $now = Carbon::now();

        DB::beginTransaction();
        try {
            $form101->update([
                'status'        => 'Borrador',
                'reject_reason' => $request->reject_reason,
                'rejected_by'   => Auth::user()->id,
                'rejected_at'   => $now,
            ]);

            Form101Rejection::create([
                'form101_id'    => $form101->id,
                'reject_reason' => $request->reject_reason,
                'rejected_by'   => Auth::user()->id,
                'rejected_at'   => $now,
            ]);

            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Formulario rechazado. Volvió a estado Borrador.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with(['message' => 'Ocurrió un error: ' . $th->getMessage(), 'alert-type' => 'error']);
        }
    }

    public function rejections($id)
    {
        $rejections = Form101Rejection::with('rejectedBy')
                        ->where('form101_id', $id)
                        ->orderBy('rejected_at', 'desc')
                        ->get()
                        ->map(function($r){
                            return [
                                'reason' => $r->reject_reason,
                                'by'     => optional($r->rejectedBy)->name ?? 'N/D',
                                'at'     => optional($r->rejected_at)->format('d/m/Y H:i'),
                            ];
                        });

        return response()->json($rejections);
    }

    public function edits($id)
    {
        $labels = [
            'certificate_id'           => 'Empresa / C.O.M.',
            'typeMineral_id'           => 'Tipo de Mineral',
            'leyMineral'               => 'Ley de Mineral',
            'pesoBruto'                => 'Peso Bruto',
            'humedad'                  => 'Humedad',
            'pesoNeto'                 => 'Peso Neto',
            'lote'                     => 'Lote',
            'municipio'                => 'Cód. Municipio',
            'localidad'                => 'Localidad',
            'codigoAreaMinero'         => 'Cód. Área Minera',
            'nombreAreaMinero'         => 'Nombre Área Minera',
            'medioTransporte'          => 'Medio de Transporte',
            'origen'                   => 'Origen',
            'intermedio'               => 'Intermedio',
            'final'                    => 'Final',
            'matricula'                => 'Placa/Matrícula',
            'nombreConductor'          => 'Conductor',
            'licenciaConducir'         => 'Licencia',
            'nombreEncargadoTrasporte' => 'Encargado Transporte',
            'ciEncargadoTrasporte'     => 'C.I. Encargado',
            'observation'              => 'Observaciones',
            'unidaddemedida1'          => 'Unidad de Medida',
        ];

        $edits = \App\Models\Form101Edit::with('editedBy')
                    ->where('form101_id', $id)
                    ->orderBy('edited_at', 'desc')
                    ->get()
                    ->map(function($e) use ($labels){
                        $cambios = [];
                        foreach (($e->changed ?? []) as $campo => $vals){
                            $cambios[] = [
                                'campo'   => $labels[$campo] ?? $campo,
                                'antes'   => $vals['antes'] ?? '',
                                'despues' => $vals['despues'] ?? '',
                            ];
                        }
                        return [
                            'by'      => optional($e->editedBy)->name ?? 'N/D',
                            'at'      => optional($e->edited_at)->format('d/m/Y H:i'),
                            'cambios' => $cambios,
                        ];
                    });

        return response()->json($edits);
    }

    public function destroy(Request $request, $id)
    {
        // return $id;
        DB::beginTransaction();
        try {
            Form101::where('id', $id)->update([
                'deleted_at'    => Carbon::now(),
                'deleted_id'    => Auth::user()->id,
                'delete_reason' => $request->delete_reason,
            ]);
            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('form101s.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
