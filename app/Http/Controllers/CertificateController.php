<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Signature;
use App\Models\Company;
use App\Models\Code;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// use PDF

class CertificateController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        $certificate = Certificate::with(['company', 'signature', 'code'])
            ->where('deleted_at', null)->get();
        return view('certificates.browse', compact('certificate'));
    }

    public function create()
    {
        $signature = Signature::where('deleted_at', null)->where('status',1)->get();
        $company = Company::where('deleted_at', null)->where('status',1)->get();
        return view('certificates.add', compact('signature', 'company'));
    }

    public function store(Request $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $ok = Code::where('deleted_at', null)->where('code', $request->code)->first();
            if($ok)
            {
                return redirect()->route('certificates.create')->with(['message' => 'El Codigo debe ser diferente', 'alert-type' => 'error']);
            }
            // $code = Code::create(['code'=>$request->code, 'initials' => 'EMCC', 'registerUser_id'=>Auth::user()->id]);
            // return 1;
            $cert = Certificate::create([
                'company_id'=>$request->company_id,
                'signature_id'=>$request->signature_id,
    
                'dateStart'=>$request->dateStart,
                'dateFinish'=>$request->dateFinish,
                // 'miningOperator'=>$request->miningOperator,
                'registerUser_id'=>Auth::user()->id
            ]);

            $cert->update(['code'=>'COM-'.str_pad($cert->id, 6, "0", STR_PAD_LEFT)]);

            $certificate = Certificate::with(['company', 'signature', 'code'])
            ->where('deleted_at', null)->where('id', $cert->id)->first();

            // return $certificate;
            Http::get('https://whatsapp-api.beni.gob.bo/?number=591'.$certificate->company->phone.'&message=Hola *'.$certificate->company->representative.'*, este es el numero oficial del GAD_BENI cualquier consulta o pregunta hagalo por este medio Para poder descargar su certificado *CODIGO OPERADOR MINERO*%0A%0AHas clic en el enlace de abajo.%0A👇👇%0Ahttps://mineria.beni.gob.bo/certificates/'.$certificate->id.'/print');
            Http::get('https://whatsapp-api.beni.gob.bo/?number=591'.$certificate->company->phone.'&message=Puede enviar con un  "ok" o un "si" para confirmar el mensaje gracias');

            DB::commit();
            return redirect()->route('certificates.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;    
            return redirect()->route('certificates.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function print($id)
    {
        // return $id;
        $certificate = Certificate::with(['company', 'signature', 'code'])
            ->where('deleted_at', null)->where('id', $id)->first();

        // return $certificate;

        // $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('string'));

        $qr = base64_encode(QrCode::size(120)->generate('CODIGO: '.$certificate->code.', OPERADOR MINERO: '.$certificate->company->miningOperator.', NIT: '.$certificate->company->nit.', NIM: '.$certificate->company->nim.', ACTIVIDAD: '.$certificate->company->activity.', REPRESENTANTE LEGAL: '.$certificate->company->representative.
            ', CEDULA DE IDENTIDAD: '.$certificate->company->ci.', MUNICIPIO: '.$certificate->company->municipe.', VALIDO HASTA: '.date("d-m-Y", strtotime($certificate->dateFinish)).', FECHA DE EMISION: '.date("d-m-Y", strtotime($certificate->dateStart))));
            
            // return $certificate;
        // return view('certificates.print',compact('certificate'));

 
        // $people = Person::where('id', $id)->where('deleted_at', null)->first();

        return PDF::loadView('certificates.print',compact('certificate', 'qr') )
        ->setPaper('A4', 'landscape')
        ->stream('CERTIFICADO.pdf');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $ok = Certificate::where('id', $id)->first();
            $ok->update(['deleted_at'=>Carbon::now()]);
            Code::where('id', $ok->code_id)->update(['deleted_at'=>Carbon::now()]);
            DB::commit();
            return redirect()->route('certificates.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            return 0;
            return redirect()->route('certificates.create')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }
}
