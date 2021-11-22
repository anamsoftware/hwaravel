<?php

namespace App\Libraries;

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
                'route' => ''
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

}
