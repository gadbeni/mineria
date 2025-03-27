<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Form101;
use App\Models\TypeMineral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use App\Http\Controllers\HTML2PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Form101Controller extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }


    public function index()
    {

        return view('form101.browse');
    }

    public function list($search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;

     
        $data = Form101::with(['certificate.company', 'typeMineral'])
                    ->whereHas('certificate', function($query) use($search){
                        $query->whereRaw(Auth::user()->role->name == 'funcionario' ? 'company_id = '.Auth::user()->company_id : 1);
                    })
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('certificate.company', function($query) use($search){
                                $query->whereRaw("(razon like '%$search%' or representative like '%$search%' or nit like '%$search%')");
                            })
                            ->OrwhereHas('certificate', function($query) use($search){
                                $query->whereRaw("(code like '%$search%')")
                                ->whereRaw(Auth::user()->role->name == 'funcionario' ? 'company_id = '.Auth::user()->company_id : 1);
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

        return view('form101.add', compact('company', 'type'));

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // return $request;

            // $form = Form101::create($request->all());
            $form = Form101::create([
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
            ]);

            // return $form;
            $form->update(['code'=>'SDMEH-'.str_pad($form->id, 6, "0", STR_PAD_LEFT), 'register_id'=>Auth::user()->id]);

        


            // return 1;
            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Registrado exitosamente exitosamente.', 'alert-type' => 'success']);            
        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('form101s.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function prinf($form)
    {
        // return 1;
        $forms = Form101::with(['certificate.company', 'typeMineral'])
                    ->where('id', $form)->where('deleted_at', NULL)->orderBy('id', 'DESC')->first();



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
    
    public function destroy($id)
    {
        // return $id;
        DB::beginTransaction();
        try {
            Form101::where('id', $id)->update(['deleted_at'=>Carbon::now(), 'deleted_id'=>Auth::user()->id]);
            DB::commit();
            return redirect()->route('form101s.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('form101s.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
