<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.auth.profile';

    /**
     * @var string User path
     */
    protected $imagePath = 'users';

    /**
     * @var User
     */
    protected $user;

    /**
     * ProfileController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Profile admin
     *
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function profile(Request $request)
    {
        // Get auth path
        $path = $this->viewPath;

        // Get current user logged id
        $user_id = auth()->guard('admin')->id();

        // Get user info with meta data
        $userWithMeta = $this->user->findUserMetaByUserId($user_id);

        if ($request->getMethod() == 'GET') {
            // Show profile form
            return view("{$path}")->with([
                'path' => $path,
                'user' => $userWithMeta
            ]);
        } else {
            // Validate data
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['required', 'max:191'],
                'username' => ['required', 'min:6', 'max:191', 'unique:users,username,' . $user_id],
                'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $user_id],
                'phone' => ['nullable', 'max:20'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
            ]);

            if ($validator->fails()) {
                // Validate fail and notice error message
                hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                // Get user image
                $currentImage = $userWithMeta['avatar'] ?? '';

                // Upload user image
                if ($request->has('avatar')) {
                    $file = $request->file('avatar'); // Get file
                    // Rename image
                    $updateImage = strtolower("hwa_" . md5(Str::random(12) . time() . Str::random(25)) . '.' . $file->getClientOriginalExtension());
                    // Save image to /public/storage/users
                    Image::make($file->getRealPath())->resize(720, 720)->save(hwa_image_path($this->imagePath, $updateImage));
                } else {
                    $updateImage = $currentImage; // No file update
                }

                // Select user
                $user = $this->user->find($user_id);

                // Get user data
                $data = [
                    'first_name' => $request['fist_name'],
                    'last_name' => $request['last_name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                ];

                // Get user meta data
                $metaData = [
                    'phone' => $request['phone'],
                    'gender' => $request['gender'],
                    'avatar' => $updateImage,
                ];

                if ($user->fill($data)->save()) {
                    // Update profile success

                    // delete old image
                    if ($request->has('avatar')) {
                        if (file_exists($imagePath = hwa_image_path($this->imagePath, $currentImage))) {
                            File::delete($imagePath);
                        }
                    }

                    // Update user meta data
                    foreach ($metaData as $metaKey => $metaValue) {
                        UserMeta::_update($user_id, $metaKey, $metaValue);
                    }

                    // Notice and return users list
                    hwa_notify_success("Success to update profile.", ['title' => 'Success!']);
                    return redirect()->route("{$path}.index");
                } else {
                    // Delete new image just upload
                    if ($request->has('avatar')) {
                        if (file_exists($imagePath = hwa_image_path($this->imagePath, $updateImage))) {
                            File::delete($imagePath);
                        }
                    }

                    // Notice error and return back
                    hwa_notify_error("Error to update profile.", ['title' => 'Error!']);
                    return redirect()->back()->withInput();
                }
            }

        }
    }

    /**
     * Change admin password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function changePassword(Request $request)
    {
        // Get current user
        $user = $this->user->find(auth()->guard('admin')->id());

        // Validate data
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'min:6', 'max:32'],
            'password' => ['required', 'min:6', 'max:32'],
            'password_confirmation' => ['required', 'min:6', 'max:32', 'same:password'],
        ]);

        if ($validator->fails()) {
            // Validate fail and notice error message
            hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if (!password_verify($request['old_password'], $user['password'])) {
                // Old password wrong
                hwa_notify_error('Old password is wrong.', ['title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors([
                    'old_password' => 'Old password is wrong.'
                ]);
            } else {
                if (password_verify($request['password'], $user['password'])) {
                    // New password must be different old password
                    hwa_notify_error('New password must be different old password.', ['title' => 'Error!']);
                    return redirect()->back()->withInput()->withErrors([
                        'password' => 'New password must be different old password.'
                    ]);
                } else {
                    // Update new password
                    $user['password'] = bcrypt($request['password']);
                    $user->save();

                    // Notice and return users list
                    hwa_notify_success("Success to change new password.", ['title' => 'Success!']);
                    return redirect()->back();
                }
            }
        }
    }
}
