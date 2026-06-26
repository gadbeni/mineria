<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use TCG\Voyager\Models\Role;

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

    private function generateCodeMiningOperator(string $razon, string $nit): string
    {
        // Siglas: primera letra de cada palabra en mayúscula
        $words = preg_split('/\s+/', trim($razon));
        $initials = '';
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(mb_substr($word, 0, 1));
            }
        }

        // Primeros 3 dígitos del NIT
        $digits = preg_replace('/\D/', '', $nit);
        $nitPrefix = str_pad(substr($digits, 0, 3), 3, '0', STR_PAD_RIGHT);

        $prefix = $initials . '-' . $nitPrefix;

        // Correlativo: cuenta todas las empresas con el mismo prefijo (incluyendo borradas)
        $count = Company::withTrashed()
            ->where('codeMiningOperator', 'like', $prefix . '%')
            ->count();

        return $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
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
            $request->merge([
                'registerUser_id'     => Auth::user()->id,
                'codeMiningOperator'  => $this->generateCodeMiningOperator($request->razon, $request->nit),
            ]);

            $company = Company::create($request->all());
            // return $company;

            User::create([
                'company_id' => $company->id,
                'name'       => $request->name,
                'role_id'    => Role::where('name', 'formulario')->value('id'),
                'email'      => $request->email,
                'avatar'     => 'users/default.png',
                'password'   => bcrypt($request->password),
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

    public function list($search = null)
    {
        $paginate = request('paginate') ?? 25;

        $data = Company::where('deleted_at', null)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw("nit like '%$search%'")
                      ->orWhereRaw("razon like '%$search%'")
                      ->orWhereRaw("representative like '%$search%'")
                      ->orWhereRaw("codeMiningOperator like '%$search%'");
                });
            })
            ->orderBy('id', 'ASC')
            ->paginate($paginate);

        return view('company.list', compact('data'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->status = (int) $request->status;
        $company->save();

        $label = $company->status ? 'Activo' : 'Inactivo';
        return back()->with(['message' => "Estado actualizado a: $label", 'alert-type' => 'success']);
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
