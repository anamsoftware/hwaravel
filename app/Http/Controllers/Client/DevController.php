<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function dev($key)
    {
        if ($key != '1998') abort(404);
        else {
            dd(hwa_settings());
        }
    }
}
