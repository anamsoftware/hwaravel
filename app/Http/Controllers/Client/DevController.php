<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DevController extends Controller
{
    public function dev($key)
    {
        if ($key != '1998') abort(404);
        else {
            dd(config('mail.mailers.smtp.port'));
        }
    }
}
