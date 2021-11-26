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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    protected $viewPath = 'admin.settings';

    /**
     * General Settings
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $path = $this->viewPath;
        if ($request->getMethod() == 'GET') {
            return view("{$path}.index")->with([
                'path' => $path
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'admin_email' => ['nullable', 'email'],
                'time_zone' => ['nullable', Rule::in(array_keys(hwa_timezone_list()))],
                'enable_captcha' => ['nullable', Rule::in(['0', '1'])],
                'captcha_type' => ['nullable', Rule::in(['v2', 'v3'])],
            ]);

            if ($validator->fails()) {
                // Invalid data and notice error message
                hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (!hwa_demo_env()) {
                    $favicon = $this->uploadImage($request, 'favicon');
                    $smallLogo = $this->uploadImage($request, 'admin_logo_small');
                    $logo = $this->uploadImage($request, 'admin_logo');
                    $auth_bg = $this->uploadImage($request, 'auth_bg');

                    $generalSettings = [
                        "admin_title" => strtolower(trim($request['admin_title'])),
                        "admin_email" => strtolower(trim($request['admin_email'])),
                        "time_zone" => trim($request['time_zone']),
                        "favicon" => $favicon,
                        "admin_logo_small" => $smallLogo,
                        "admin_logo" => $logo,
                        "auth_bg" => $auth_bg,
                        "email_from_name" => trim($request['email_from_name']),
                        "enable_captcha" => trim($request['enable_captcha']),
                        "captcha_type" => trim($request['captcha_type']),
                        "captcha_site_key" => trim($request['captcha_site_key']),
                        "captcha_secret" => trim($request['captcha_secret']),
                    ];

                    $this->saveSettings($generalSettings);
                }
                // Notice success
                hwa_notify_success("Success to update settings.", ['title' => 'Success!']);
                return redirect()->back();
            }
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

    /**
     * Upload image
     *
     * @param $request
     * @param $key
     * @return string
     */
    private function uploadImage($request, $key)
    {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $name = strtolower("hwa_" . md5(Str::random(20) . time() . Str::random(20)) . '.' . $file->getClientOriginalExtension());
            Image::make($file->getRealPath())->save(hwa_image_path("system", $name));
        } else {
            $name = hwa_setting($key);
        }
        return $name;
    }
}
