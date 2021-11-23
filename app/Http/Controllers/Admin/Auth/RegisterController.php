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
 * Class RegisterController
 * @package App\Http\Controllers\Admin\Auth
 */
class RegisterController extends Controller
{
    protected $viewPath = 'admin.auth';

    /**
     * @var User
     */
    protected $user;

    /**
     * RegisterController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Admin register
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function register(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route('admin.home');
            } else {
                // redirect login form
                return view("{$path}.register")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['required', 'max:191'],
                'username' => ['required', 'min:6', 'max:191', 'unique:users,username'],
                'email' => ['required', 'min:6', 'max:191', 'email', 'unique:users,email'],
                'password' => ['required', 'min:6', 'max:32'],
            ]);

            // Validate
            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['top' => true, 'title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get user data from request
                $userData = [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'password' => bcrypt($request['password']), // Hash
                ];

                // Create new user
                if ($this->user->create($userData)) {
                    // Notify and redirect to login page
                    hwa_notify_success("Success to register new user.", ['title' => 'Success!', 'top' => true]);
                    return redirect()->route("{$path}.login");
                } else {
                    // Notify and redirect back
                    hwa_notify_error("Error to register new user.", ['title' => 'Error!', 'top' => true]);
                    return redirect()->back()->withInput();
                }
            }
        }
    }
}
