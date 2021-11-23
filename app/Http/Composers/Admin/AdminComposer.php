<?php

namespace App\Http\Composers\Admin;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\View\View;

class AdminComposer
{
    /**
     * @var Authenticatable|null
     */
    public $admin;

    /**
     * AdminComposer constructor.
     */
    public function __construct()
    {
        $this->admin = auth()->guard('admin')->id();
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $user_id = $this->admin; // Get current user logged id
        $user = User::findUserMetaByUserId($user_id); // Get user logged with meta data
        $view->with('adminLogin', $user); // Share data to view
    }

}
