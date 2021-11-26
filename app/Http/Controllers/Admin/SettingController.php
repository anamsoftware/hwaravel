<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    /**
     * Email settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function email(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.email")->with([
                'path' => $path
            ]);
        } else {
            // Validate data input
            $validator = Validator::make($request->all(), [
                'email_driver' => ['required', Rule::in(hwaCore()->getEmailDriver())],
                'email_from_address' => ['nullable', 'email']
            ]);

            if ($validator->fails()) {
                // Invalid data and notice error message
                hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get data setting from request
                $emailSettings = [
                    "email_driver" => strtolower(trim($request['email_driver'])),
                    "email_port" => trim($request['email_port']),
                    "email_host" => strtolower(trim($request['email_host'])),
                    "email_username" => strtolower(trim($request['email_username'])),
                    "email_password" => trim($request['email_password']),
                    "email_encryption" => $request['email_encryption'],
                    "email_mail_gun_domain" => strtolower(trim($request['email_mail_gun_domain'])),
                    "email_mail_gun_secret" => $request['email_mail_gun_secret'],
                    "email_mail_gun_endpoint" => strtolower(trim($request['email_mail_gun_endpoint'])),
                    "email_ses_key" => $request['email_ses_key'],
                    "email_ses_secret" => trim($request['email_ses_secret']),
                    "email_ses_region" => $request['email_ses_region'],
                    "email_from_name" => trim($request['email_from_name']),
                    "email_from_address" => strtolower(trim($request['email_from_address'])),
                ];

                if (!hwa_demo_env()) {
                    // Save data if not demo env
                    $this->saveSettings($emailSettings);
                }

                // Notice success
                hwa_notify_success("Success to update settings.", ['title' => 'Success!']);
                return redirect()->back();
            }
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

    /**
     * Save setting
     *
     * @param array $data
     */
    private function saveSettings(array $data)
    {
        foreach ($data as $settingKey => $settingValue) {
            Setting::updateOrCreate([
                'key' => $settingKey,
            ], [
                'key' => $settingKey,
                'value' => $settingValue
            ]);
        }
    }
}
