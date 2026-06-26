<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\Signature;


class AjaxController extends Controller
{
    public function toggleSignatureStatus(Request $request, $id)
    {
        $signature = Signature::findOrFail($id);

        if ($signature->status == 1) {
            // Dar de baja: requiere motivo
            $request->validate(['baja_reason' => 'required|string|max:500']);
            $signature->update(['status' => 0, 'baja_reason' => $request->baja_reason]);
            return redirect()->back()->with(['message' => 'Firma dada de baja correctamente.', 'alert-type' => 'success']);
        } else {
            // Reactivar: limpiar motivo
            $signature->update(['status' => 1, 'baja_reason' => null]);
            return redirect()->back()->with(['message' => 'Firma activada correctamente.', 'alert-type' => 'success']);
        }
    }

    public function code($code)
    {
        $ok=true;
        $code = Code::where('deleted_at', null)->where('code', $code)->first();
        if(!$code)
        {
            $ok=false;
        }
        return $ok;

    }
}
