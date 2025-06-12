<?php

namespace App\Http\Controllers;

use App\Services\EmailServices;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendRegisterEmail(Request $r)
    {
        $ls = new EmailServices;

        $data = $ls->sendRegisterEmail($r);

        return \response()->json($data, 200);
    }
}
