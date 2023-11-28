<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('company.browse');
    }


    public function create()
    {
        return view('company.add');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $ok = Company::where('nit', $request->nit)->where('deleted_at', null)->first();
            if($ok)
            {
                return redirect()->route('voyager.companies.index')->with(['message' => 'Ya existe una empresa con el Nit registrada', 'alert-type' => 'error']);
            }
            $ok = User::where('email', $request->email)->first();
            if($ok)
            {
                return redirect()->route('voyager.companies.index')->with(['message' => 'El Correo ingresado ya se encuentra en uso', 'alert-type' => 'error']);
            }
            $request->merge(['registerUser_id'=>Auth::user()->id]);

            // return $request;
            $company = Company::create($request->all());
            // return $company;

            User::create([
                'company_id'=>$company->id,
                'name' =>  $request->name,
                'role_id' => 2,
                'email' => $request->email,
                'avatar' => 'users/default.png',
                'password' => bcrypt($request->password),
            ]);
            
            // Http::get('https://whatsapp-api.beni.gob.bo/?number=591'.$request->phone.'&message=Hola%0A*'.$request->name.'*%0A%0A*DETALLE DE LA EMPRESA*%0A*NIT:* '.$request->nit.'%0A*NIM:* '.$request->nim.
            // '%0A*RAZON SOCIAL:* '.$request->razon.'%0A*ACTIVIDAD:* '.$request->activity.
            // '%0A%0AUSUARIO: '.$request->email.'%0ACONTRASEÑA: '.$request->password.'%0A%0APara ingresar al sistema de mineria%0Ahaz click👇👇%0Ahttps://mineria.beni.gob.bo');
            
            
            // Http::get('https://whatsapp-api.beni.gob.bo/?number=591'.$certificate->company->phone.'&message=Puede enviar con un  "ok" o un "si" para confirmar el mensaje gracias');
            // return 1;
            DB::commit();
            return redirect()->route('voyager.companies.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);

        } catch (\Throwable $th) {
            DB::rollBack();
            // return 0;    
            return redirect()->route('voyager.companies.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
        }
    }

    public function ajaxCompany()
    {
        $q = request('q');
        $data = Certificate::with(['company'])
            ->where(function($query) use ($q){
                if($q){
                    $query->OrwhereHas('company', function($query) use($q){
                        $query->whereRaw("(razon like '%$q%' or representative like '%$q%' or nit like '%$q%' or activity like '%$q%')");
                    })
                    ->OrWhereRaw($q ? "code like '%$q%'" : 1);
                }
            })
            ->where('deleted_at', NULL)->get();



        // $data = Company::whereRaw($q ? '(nit like "%'.$q.'%" or activity like "%'.$q.'%" or representative like "%'.$q.'%" or razon like "%'.$q.'%" )' : 1)
        // ->where('deleted_at', null)->get();

        return response()->json($data);
    }
}
