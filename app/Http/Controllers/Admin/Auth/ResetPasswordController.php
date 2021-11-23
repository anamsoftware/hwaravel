<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\Admin\ResetPasswordRequest;
use App\Notifications\Admin\ResetPasswordSuccess;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Dashboard redirect
     */
    protected const DASHBOARD = 'admin.home';

    protected const LOGIN_DIR = 'admin.auth.login';

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

    /**
     * Forget admin password
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function forget(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route(self::DASHBOARD);
            } else {
                // redirect login form
                return view("{$path}.password.forget")->with([
                    'path' => $path
                ]);
            }
        } else {
            // POST Method

            // Validate data input
            $validator = Validator::make($request->only(['email']), [
                'email' => ['required', 'max:191', 'email', 'exists:users,email']
            ], [
                'email.exists' => 'Email is not existed.'
            ]);

            if ($validator->fails()) {
                // check validate and return error message.
                hwa_notify_error($validator->getMessageBag()->first(), ['top' => true, 'title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get email from request
                $email = strtolower(trim($request['email']));

                // Check existed and active user
                $user = $this->user->where('email', $email)->where('active', 1)->first();
                if (!$user) {
                    // User is blocked.
                    hwa_notify_error("User is blocked.", ['title' => 'Error!', 'top' => true]);
                    return redirect()->back()->withInput()->withErrors([
                        'email' => 'User is blocked.'
                    ]);
                } else {
                    if (!hwa_demo_env()) {
                        // Create or update token for recovery password
                        $passwordReset = PasswordReset::updateOrCreate([
                            'email' => $user->email
                        ], [
                            'email' => $user->email,
                            'token' => Str::random(60),
                        ]);

                        if ($passwordReset) {
                            // Send mail to reset password
                            try {
                                $dataSend = [
                                    'subject' => hwa_app_name() . " | Recovery your password",
                                    'first_name' => $user->first_name,
                                    'token' => $passwordReset->token,
                                ];
                                $user->notify(new ResetPasswordRequest($dataSend));
                            } catch (Exception $exception) {
                                Log::error($exception->getMessage());
                            }

                            // Notify success
                            hwa_notify_success("We have e-mailed your password reset link!", ['title' => 'Success!', 'top' => true]);
                            return redirect()->route(self::LOGIN_DIR);
                        } else {
                            // Notify error
                            hwa_notify_error("Error to recovery password.", ['title' => 'Error!', 'top' => true]);
                            return redirect()->back()->withInput();
                        }
                    } else {
                        // Notify success
                        hwa_notify_success("We have e-mailed your password reset link!", ['title' => 'Success!', 'top' => true]);
                        return redirect()->route(self::LOGIN_DIR);
                    }
                }
            }
        }
    }

    /**
     * Reset admin password
     *
     * @param $token
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function reset($token, Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            // GET Method
            if (auth()->guard('admin')->check()) {
                // redirect to dashboard if logged
                return redirect()->route(self::DASHBOARD);
            } else {
                if (!$passwordReset = PasswordReset::where('token', $token)->first()) {
                    // Invalid token
                    hwa_notify_error("Token is invalid or expired.", ['title' => 'Error!', 'top' => true]);
                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                } else {
                    // Token is invalid after hour
                    if (Carbon::parse($passwordReset->updated_at)->addMinutes(30)->isPast()) {
                        $passwordReset->delete();
                        hwa_notify_error("Tokens expired.", ['title' => 'Error!', 'top' => true]);
                        return redirect()->route(self::LOGIN_DIR);
                    } else {
                        // Call view reset password
                        return view("{$path}.password.reset")->with([
                            'path' => $path,
                            'passwordReset' => $passwordReset,
                        ]);
                    }
                }
            }
        } else {
            // Token not found
            if (!$passwordReset = PasswordReset::where('token', $token)->first()) {
                hwa_notify_error("Token is invalid or expired.", ['title' => 'Error!', 'top' => true]); // notify
                return redirect()->route(self::LOGIN_DIR); // redirect to login page
            } else {
                // Existed token
                if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
                    // Delete token if invalid
                    $passwordReset->delete();
                    hwa_notify_error("Tokens expired.", ['title' => 'Error!', 'top' => true]);
                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                } else {
                    if (!$user = $this->user->where('email', $passwordReset->email)->where('active', 1)->first()) {
                        // Delete token if user is blocked
                        $passwordReset->delete();
                        hwa_notify_error("User is blocked.", ['top' => true]); // notify
                        return redirect()->route(self::LOGIN_DIR); // redirect to login page
                    } else {
                        // Validate data input
                        $validator = Validator::make($request->all(), [
                            'password' => ['required', 'min:6', 'max:191'],
                            'password_confirmation' => ['required', 'min:6', 'max:191', 'same:password'],
                        ]);

                        if ($validator->fails()) {
                            // Invalid data
                            hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!', 'top' => true]); // notify
                            return redirect()->back()->withInput()->withErrors($validator); // redirect back
                        } else {
                            if (!hwa_demo_env()) {
                                // Get password
                                $password = $request['password'];
                                if ($user->fill(['password' => bcrypt($password)])->save()) {
                                    // Delete token if update success
                                    $passwordReset->delete();

                                    // Send mail to reset password successfully
                                    try {
                                        $dataSend = [
                                            'subject' => hwa_app_name() . " | Your password was changed successfully",
                                            'first_name' => $user->first_name,
                                            'email' => $user->email,
                                            'password' => $password,
                                            'updated_at' => $user->updated_at,
                                        ];
                                        $user->notify(new ResetPasswordSuccess($dataSend)); // send notify

                                        // using queue

                                    } catch (Exception $exception) {
                                        // Error to send email
                                        Log::error($exception->getMessage());
                                    }

                                    // Login with user
                                    auth()->guard('admin')->login($user);
                                    hwa_notify_success("Success to reset password.", ['title' => 'Success!']); // notify
                                    return redirect()->route(self::DASHBOARD); // redirect to home page
                                } else {
                                    // Error to update
                                    hwa_notify_error("Error to reset password.", ['title' => 'Error!', 'top' => true]); // notify
                                    return redirect()->route(self::LOGIN_DIR); // redirect to login page
                                }
                            } else {
                                // Notify success
                                hwa_notify_success("Success to reset password.", ['title' => 'Success!']); // notify
                                return redirect()->route(self::DASHBOARD); // redirect to home page
                            }
                        }
                    }
                }
            }
        }
    }
}
