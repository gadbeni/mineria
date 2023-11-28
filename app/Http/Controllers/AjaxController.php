<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;


class AjaxController extends Controller
{
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
