<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.auth';

    /**
     * @var User
     */
    protected $user;

    /**
     * ResetPasswordController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function forget(Request $request) {
        if ($request->getMethod() == 'GET') {
            if (auth()->guard('admin')->check()) {

            } else {

            }
        } else {

        }
    }

    public function reset(Request $request) {
        if ($request->getMethod() == 'GET') {
            if (auth()->guard('admin')->check()) {

            } else {

            }
        } else {

        }
    }
}
