<?php

namespace App\Libraries;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class HwaCore
{
    /**
     * Recall itself
     *
     * @var $_instance
     */
    public static $_instance;

    /**
     * Check instance and reset it
     *
     * @return HwaCore $_instance
     */
    public static function instance(): HwaCore
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Admin menu
     *
     * @return array
     */
    public function getAdminMenu()
    {
        return [
            [
                'label' => 'Dashboard',
                'icon' => 'bx-home-circle',
                'route' => 'home'
            ],
            [
                'label' => 'Administration',
                'icon' => 'bx-rocket',
                'items' => [
                    [
                        'label' => 'Users',
                        'route' => 'users.index',
                    ],
                    [
                        'label' => 'System information',
                        'route' => 'system.info',
                    ],
                ]
            ],
            [
                'label' => 'Settings',
                'icon' => 'bx-cog',
                'items' => [
                    [
                        'label' => 'General',
                        'route' => 'settings.index',
                    ],
                    [
                        'label' => 'Email',
                        'route' => 'settings.email',
                    ],
                    [
                        'label' => 'Social login',
                        'route' => 'settings.social_login',
                    ],
                ]
            ],
        ];
    }

    /**
     * Setting default
     *
     * @return array
     */
    public function getSettings()
    {
        return [
            'locale' => 'en',
            'time_zone' => 'Asia/Ho_Chi_Minh',
            'favicon' => null,
            'logo' => null,
            'logo_small' => null,
            'social_login_enable' => 0,
            'social_login_facebook_enable' => 0,
            'social_login_google_enable' => 0,
            'social_login_twitter_enable' => 0,
            'social_login_linkedin_enable' => 0,
            'social_login_facebook_app_id' => null,
            'social_login_facebook_app_secret' => null,
            'social_login_google_app_id' => null,
            'social_login_google_app_secret' => null,
            'social_login_twitter_app_id' => null,
            'social_login_twitter_app_secret' => null,
            'social_login_linkedin_app_id' => null,
            'social_login_linkedin_app_secret' => null,
        ];
    }

    /**
     * Get the Composer file contents as an array
     * @return array
     * @throws FileNotFoundException
     */
    public function getComposerArray()
    {
        return hwa_get_file_data(base_path('composer.json'));
    }

    /**
     * Get Installed packages & their Dependencies
     *
     * @param array $packagesArray
     * @return array
     */
    public function getPackagesAndDependencies(array $packagesArray): array
    {
        $packages = [];
        foreach ($packagesArray as $key => $value) {
            $packageFile = base_path('vendor/' . $key . '/composer.json');

            if ($key !== 'php' && File::exists($packageFile)) {
                $json2 = file_get_contents($packageFile);
                $dependenciesArray = json_decode($json2, true);
                $dependencies = array_key_exists('require', $dependenciesArray) ? $dependenciesArray['require'] : 'No dependencies';
                $devDependencies = array_key_exists('require-dev', $dependenciesArray) ? $dependenciesArray['require-dev'] : 'No dependencies';

                $packages[] = [
                    'name' => $key,
                    'version' => $value,
                    'dependencies' => $dependencies,
                    'dev-dependencies' => $devDependencies,
                ];
            }
        }

        return $packages;
    }


    /**
     * Get System environment details
     *
     * @return array
     */
    public function getSystemEnv(): array
    {
        return [
            'version' => App::version(),
            'timezone' => config('app.timezone'),
            'debug_mode' => config('app.debug'),
            'storage_dir_writable' => File::isWritable(base_path('storage')),
            'cache_dir_writable' => File::isReadable(base_path('bootstrap/cache')),
            'app_size' => hwa_human_file_size(hwaCore()->folderSize(base_path())),
        ];
    }

    /**
     * Get the system app's size
     *
     * @param $directory
     * @return int
     */
    protected function folderSize($directory): int
    {
        $size = 0;
        foreach (File::glob(rtrim($directory, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += File::isFile($each) ? File::size($each) : self::folderSize($each);
        }

        return $size;
    }

    /**
     * Get PHP/Server environment details
     * @return array
     */
    public function getServerEnv(): array
    {
        return [
            'version' => phpversion(),
            'server_software' => Request::server('SERVER_SOFTWARE'),
            'server_os' => function_exists('php_uname') ? php_uname() : 'N/A',
            'database_connection_name' => config('database.default'),
            'ssl_installed' => hwaCore()->checkSslIsInstalled(),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_connection' => config('queue.default'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'curl' => extension_loaded('curl'),
            'exif' => extension_loaded('exif'),
            'pdo' => extension_loaded('pdo'),
            'fileinfo' => extension_loaded('fileinfo'),
            'tokenizer' => extension_loaded('tokenizer'),
            'imagick_or_gd' => extension_loaded('imagick') || extension_loaded('gd'),
        ];
    }

    /**
     * Check if SSL is installed or not
     * @return boolean
     */
    protected function checkSslIsInstalled(): bool
    {
        return !empty(Request::server('HTTPS')) && Request::server('HTTPS') != 'off';
    }

}
