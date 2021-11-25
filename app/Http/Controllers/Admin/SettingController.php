<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $viewPath = 'admin.settings';

    public function index(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.index")->with([
                'path' => $path
            ]);
        } else {

        }
    }

    public function email(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.email")->with([
                'path' => $path
            ]);
        } else {

        }
    }

    public function socialLogin(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.social")->with([
                'path' => $path
            ]);
        } else {

        }
    }
}
