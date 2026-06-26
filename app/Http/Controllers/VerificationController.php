<?php

namespace App\Http\Controllers;
use App\Models\Form101;
use App\Models\Certificate;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify($token)
    {
        $form = Form101::with(['certificate.company', 'typeMineral'])
            ->where('verification_token', $token)
            ->firstOrFail();

        return view('verification.document', compact('form'));
    }

    public function verifyCertificate($token)
    {
        $certificate = Certificate::with(['company', 'signature'])
            ->where('verification_token', $token)
            ->firstOrFail();

        return view('verification.certificate', compact('certificate'));
    }
}
