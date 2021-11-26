<?php

use App\Libraries\HwaCore;
use App\Models\Setting;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists('hwaCore')) {

    /**
     * @return HwaCore
     */
    function hwaCore()
    {
        return HwaCore::instance();
    }
}

if (!function_exists('hwa_app_name')) {

    /**
     * App name
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_app_name()
    {
        return config('app.name') ?? 'Hwaravel';
    }
}

if (!function_exists('hwa_app_version')) {

    /**
     * App name
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_app_version()
    {
        return config('app.version') ?? '1.0.0';
    }
}

if (!function_exists('hwa_app_author')) {

    /**
     * App author
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_app_author()
    {
        return config('app.author') ?? 'Anam Software';
    }
}

if (!function_exists('hwa_app_domain')) {

    /**
     * App domain
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_app_domain()
    {
        return config('app.domain') ?? 'anamsoft.tech';
    }
}

if (!function_exists('hwa_app_contact')) {

    /**
     * App domain
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_app_contact()
    {
        return config('app.contact') ?? 'contact@anamsoft.tech';
    }
}

if (!function_exists('hwa_admin_dir')) {

    /**
     * App admin dir
     *
     * @return Repository|Application|mixed|string
     */
    function hwa_admin_dir()
    {
        return config('app.admin_dir') ?? 'admin';
    }
}

if (!function_exists('hwa_local_env')) {

    /**
     * Get app environment
     *
     * @return bool
     */
    function hwa_local_env()
    {
        if (App::environment() == 'local') {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('hwa_demo_env')) {

    /**
     * Get demo environment
     *
     * @return bool
     */
    function hwa_demo_env()
    {
        if (App::environment() == 'demo') {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('hwa_page_title')) {

    /**
     * Get page title
     *
     * @param string $title
     * @return Repository|Application|mixed|string
     */
    function hwa_page_title($title = '')
    {
        if (!empty($title)) {
            return $title . ' | ' . hwa_app_name();
        }
        return hwa_app_name();
    }
}

if (!function_exists('hwa_unique_username')) {

    /**
     * Check unique username
     *
     * @param $username
     * @return false|mixed|string
     */
    function hwa_unique_username($username)
    {
        $index = 0;

        do {
            $username = ($index > 0) ? "$username$index" : $username;
            $index++;
        } while (DB::table('users')->where('username', $username)->first());

        return $username;
    }
}

if (!function_exists('hwa_generate_username')) {

    /**
     * Generate user from email
     *
     * @param $email
     * @return false|mixed|string
     */
    function hwa_generate_username($email = '')
    {
        if (strpos($email, '@gmail.com') !== false || strpos($email, '@hotmail.com') !== false) {
            $email_extract = explode('@', $email);
            $username = Str::replace(".", "", $email_extract[0]);
            try {
                $username = hwa_unique_username($username);
            } catch (Exception $exception) {

            }
        } else {
            $username = $email;
        }

        return $username;
    }
}

if (!function_exists('hwa_notify_success')) {

    /**
     * Notification success
     *
     * @param $message
     * @param array $option
     */
    function hwa_notify_success($message = '', array $option = [])
    {
        $title = $option['title'] ?? "Success!";
        $top = $option['top'] ?? false;
        if ($top) {
            toastr()->success($message, $title, ["positionClass" => "toast-top-right"]);
        }
        toastr()->success($message, $title);
    }
}

if (!function_exists('hwa_notify_error')) {

    /**
     * Notification success
     *
     * @param $message
     * @param array $option
     */
    function hwa_notify_error($message = '', array $option = [])
    {
        $title = $option['title'] ?? "Error!";
        $top = $option['top'] ?? false;
        if ($top) {
            toastr()->error($message, $title, ["positionClass" => "toast-top-right"]);
        }
        toastr()->error($message, $title);
    }
}

if (!function_exists('hwa_notify_warning')) {

    /**
     * Notification success
     *
     * @param $message
     * @param array $option
     */
    function hwa_notify_warning($message = '', array $option = [])
    {
        $title = $option['title'] ?? "Warning!";
        $top = $option['top'] ?? false;
        if ($top) {
            toastr()->warning($message, $title, ["positionClass" => "toast-top-right"]);
        }
        toastr()->warning($message, $title);
    }
}

if (!function_exists('hwa_notify_info')) {

    /**
     * Notification success
     *
     * @param $message
     * @param array $option
     */
    function hwa_notify_info($message = '', array $option = [])
    {
        $title = $option['title'] ?? "Info!";
        $top = $option['top'] ?? false;
        if ($top) {
            toastr()->info($message, $title, ["positionClass" => "toast-top-right"]);
        }
        toastr()->info($message, $title);
    }
}

if (!function_exists('hwa_format_timezone_name')) {

    /**
     * @param $name
     * @return string|string[]
     */
    function hwa_format_timezone_name($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }
}

if (!function_exists('hwa_format_GMT_offset')) {

    /**
     * @param $offset
     * @return string
     */
    function hwa_format_GMT_offset($offset)
    {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }
}

if (!function_exists('hwa_timezone_list')) {

    /**
     * List timezone
     *
     * @return array
     * @throws Exception
     */
    function hwa_timezone_list()
    {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime('now', new DateTimeZone('UTC'));

            foreach (DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . hwa_format_GMT_offset($offset) . ') ' . hwa_format_timezone_name($timezone);
            }

            array_multisort($offsets, $timezones);
        }

        return $timezones;
    }
}

if (!function_exists('hwa_abort')) {

    /**
     * Error page
     *
     * @param $code
     * @return string
     */
    function hwa_abort($code)
    {
        $path = 'admin.pages.errors';
        switch ($code) {
            case 404:
                $view = "{$path}.error_404";
                break;
            case 500:
                $view = "{$path}.error_500";
                break;
            default:
                $view = "{$path}.error_404";
        }
        return view($view);
    }
}

/*
|--------------------------------------------------------------------------
| Storage Image
|--------------------------------------------------------------------------
*/

if (!function_exists('hwa_folder_path')) {

    /**
     * Get storage folder path
     *
     * @param $folder
     * @return string
     */
    function hwa_folder_path($folder)
    {
        if (!is_dir(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $path = storage_path('app/public/' . $folder . '/');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        return $path;
    }
}

if (!function_exists('hwa_image_path')) {

    /**
     * Get storage image path
     *
     * @param $folder
     * @param $imageName
     * @return string
     */
    function hwa_image_path($folder, $imageName)
    {
        if (!is_dir(public_path('storage'))) {
            Artisan::call('storage:link');
        }

        $path = storage_path('app/public/' . $folder . '/');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        return storage_path('app/public/' . $folder . '/' . $imageName);
    }
}

if (!function_exists('hwa_image_url')) {

    /**
     * Get storage image url
     *
     * @param $folder
     * @param $imageName
     * @return string
     */
    function hwa_image_url($folder, $imageName)
    {
        return asset('storage/' . $folder . '/' . $imageName);
    }
}

if (!function_exists('hwa_get_file_data')) {

    /**
     * @param $file
     * @param bool $toArray
     * @return bool|mixed
     */
    function hwa_get_file_data($file, $toArray = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($toArray) {
                return json_decode($file, true);
            }
            return $file;
        }
        if (!$toArray) {
            return null;
        }
        return [];
    }
}

if (!function_exists('hwa_human_file_size')) {

    /**
     * @param $bytes
     * @param int $precision
     * @return string
     */
    function hwa_human_file_size($bytes, $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.') . ' ' . $units[$pow];
    }
}

if (!function_exists('hwa_settings')) {

    /**
     * Settings
     *
     * @return array
     */
    function hwa_settings()
    {
        try {
            $settings = DB::table('settings')->get(['key', 'value'])->toArray();
            return array_combine(array_column($settings, 'key'), array_column($settings, 'value'));
        } catch (Exception $exception) {
            return [];
        }
    }
}

if (!function_exists('hwa_setting')) {

    /**
     * @param null $key
     * @param null $default
     * @return false|mixed|null
     */
    function hwa_setting($key = null, $default = null)
    {
        if (!empty($key)) {
            try {
                $setting = Setting::where('key', $key)->first();
                return $setting['value'];
            } catch (Exception $exception) {
                return $default;
            }
        }
        return false;
    }
}
