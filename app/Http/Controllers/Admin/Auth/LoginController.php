<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class LoginController
 * @package App\Http\Controllers\Admin\Auth
 */
class LoginController extends Controller
{
    protected const DASHBOARD = 'admin.home';

    /**
     * @var string View path
     */
    protected $viewPath = 'admin.auth';

    protected $user;

    /**
     * LoginController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Admin login
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function login(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route(self::DASHBOARD);
            } else {
                // redirect login form
                return view("{$path}.login")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method
            if (filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                // Validate email
                $validator_arr = ['email' => ['required', 'max:191', 'email', 'exists:users,email']];
                $msg_ar = ['email.exists' => 'The email is not existed.',];
                $credentials = ['email' => $request['email'], 'password' => $request['password']];

                // Check existed user using email
                $checkExisted = $this->user->findByEmail($credentials['email']);
            } else {
                // Validate username
                $validator_arr = ['email' => ['required', 'max:191', 'exists:users,username']];
                $msg_ar = ['email.exists' => 'Username is not existed.',];
                $credentials = ['username' => $request['email'], 'password' => $request['password']];

                // Check existed user using username
                $checkExisted = $this->user->findByUserName($credentials['username']);
            }

            // Validate
            $validator = Validator::make($request->all(), array_merge($validator_arr, [
                'password' => ['required', 'min:6', 'max:191'],
            ]), $msg_ar);

            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['top' => true, 'title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get status remember
                if ($request->has('remember_me')) {
                    $remember = true;
                } else {
                    $remember = false;
                }

                if ($checkExisted && $checkExisted->active == 1) {
                    // User active
                    if (auth()->guard('admin')->attempt($credentials, $remember)) {
                        // Login successfully.
                        hwa_notify_success("Login successfully.", ['title' => 'Success!']);
                        return redirect()->route(self::DASHBOARD);
                    } else {
                        // Wrong password
                        hwa_notify_error("Password is wrong.", ['top' => true, 'title' => 'Error!']);
                        return redirect()->back()->withInput()->withErrors([
                            'password' => 'Password is wrong.',
                        ]);
                    }
                } else {
                    // User blocked
                    hwa_notify_error("User is blocked.", ['top' => true, 'title' => 'Error!']);
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Admin logout
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        // Logout using auth()
        auth()->guard('admin')->logout();

        // Redirect to login page
        hwa_notify_success("Logout successfully.", ['title' => 'Success!', 'top' => true]);
        return redirect()->route("{$this->viewPath}.login");
    }
}
