<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMeta;
use App\Notifications\Admin\RegisterUserRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{

    /**
     * @var string View path
     */
    protected $viewPath = 'admin.users';

    /**
     * @var string Image path
     */
    protected $imagePath = 'users';

    /**
     * @var User
     */
    protected $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        // Get user path
        $path = $this->viewPath;

        // Get list users
        $results = $this->user->select([
            'id', 'full_name', 'username', 'email', 'active'
        ])->orderBy('id', 'desc')->get();

        // Show list users
        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        // Get user path
        $path = $this->viewPath;

        // Show form create new user
        return view("{$path}.form")->with([
            'path' => $path,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function store(Request $request)
    {
        // Get user path
        $path = $this->viewPath;

        // Validate rule
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'max:191'],
            'last_name' => ['required', 'max:191'],
            'username' => ['required', 'min:6', 'max:191', 'unique:users,username'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'min:6', 'max:32'],
            'phone' => ['nullable', 'max:20'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
            'active' => ['required', Rule::in(['0', '1'])],
        ]);

        if ($validator->fails()) {
            // Validate fail and notice error message
            hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            if (!hwa_demo_env()) {
                // Get user password
                $password = trim($request['password']);

                // Upload user image
                $avatar = '';
                if ($request->has('avatar')) {
                    $file = $request->file('avatar'); // Get file
                    // Rename image
                    $avatar = strtolower("hwa_" . md5(Str::random(12) . time() . Str::random(25)) . '.' . $file->getClientOriginalExtension());
                    // Save image to /public/storage/users
                    Image::make($file->getRealPath())->resize(720, 720)->save(hwa_image_path($this->imagePath, $avatar));
                }

                // Get user data
                $data = [
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'password' => bcrypt($password),
                    'active' => $request['active'],
                ];

                // Get user meta data
                $metaData = [
                    'phone' => $request['phone'],
                    'gender' => $request['gender'],
                    'avatar' => $avatar,
                ];

                if ($result = $this->user->create($data)) {
                    // Get new user id
                    $id = $result->id;

                    // Send notify to email
                    try {
                        $dataSend = [
                            'subject' => hwa_app_name() . " | Success to add new account",
                            'first_name' => $result->first_name,
                            'email' => $result->email,
                            'password' => $password,
                        ];
                        $result->notify(new RegisterUserRequest($dataSend));
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                    }

                    // Add user meta data
                    foreach ($metaData as $metaKey => $metaValue) {
                        UserMeta::_add($id, $metaKey, $metaValue);
                    }

                    // Notice and return users list
                    hwa_notify_success("Success to add new user.", ['title' => 'Success!']);
                    return redirect()->route("{$path}.index");
                } else {
                    // Delete new image just upload
                    if (file_exists($imagePath = hwa_image_path($this->imagePath, $avatar))) {
                        File::delete($imagePath);
                    }

                    // Notice error and return back
                    hwa_notify_error("Error to add new user.", ['title' => 'Error!']);
                    return redirect()->back()->withInput();
                }
            } else {
                // Notice and return users list
                hwa_notify_success("Success to add new user.", ['title' => 'Success!']);
                return redirect()->route("{$path}.index");
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        // Get user path
        $path = $this->viewPath;

        if (!$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            // show user edit form
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Get users path
        $path = $this->viewPath;

        if (!$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            // Validate rule
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['required', 'max:191'],
                'username' => ['required', 'max:191', 'unique:users,username,' . $id],
                'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $id],
                'password' => ['nullable', 'min:6', 'max:32'],
                'phone' => ['nullable', 'max:20'],
                'gender' => ['nullable', Rule::in(['male', 'female'])],
                'active' => ['required', Rule::in(['0', '1'])],
            ]);

            if ($validator->fails()) {
                // Validate fail and notice error message
                hwa_notify_error($validator->getMessageBag()->first(), ['title' => 'Error!']);
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                if (auth()->guard('admin')->id() == $id) {
                    // notify error
                    hwa_notify_error("Can't deactivate this user. This user is logged on!", ['title' => 'Error!']);
                    return redirect()->back()->withInput();
                } else {
                    if (!hwa_demo_env()) {
                        // Get user image
                        $currentImage = $result['avatar'] ?? '';

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
                        $selectResult = $this->user->find($id);

                        // Get user data
                        $data = [
                            'first_name' => $request['first_name'],
                            'last_name' => $request['last_name'],
                            'username' => $request['username'],
                            'email' => $request['email'],
                            'password' => !empty($request['password']) ? bcrypt($request['password']) : $selectResult['password'],
                            'active' => $request['active'],
                        ];

                        // Get user meta data
                        $metaData = [
                            'phone' => $request['phone'],
                            'gender' => $request['gender'],
                            'avatar' => $updateImage,
                        ];

                        if ($selectResult->fill($data)->save()) {
                            // delete old image
                            if ($request->has('avatar')) {
                                if (file_exists($imagePath = hwa_image_path($this->imagePath, $currentImage))) {
                                    File::delete($imagePath);
                                }
                            }

                            // Update user meta data
                            foreach ($metaData as $metaKey => $metaValue) {
                                UserMeta::_update($id, $metaKey, $metaValue);
                            }

                            // Notice and return users list
                            hwa_notify_success("Success to update user.", ['title' => 'Success!']);
                            return redirect()->route("{$path}.index");
                        } else {
                            // Delete new image just upload
                            if ($request->has('avatar')) {
                                if (file_exists($imagePath = hwa_image_path($this->imagePath, $updateImage))) {
                                    File::delete($imagePath);
                                }
                            }

                            // Notice error and return back
                            hwa_notify_error("Error to update user.", ['title' => 'Error!']);
                            return redirect()->back()->withInput();
                        }
                    } else {
                        // Notice and return users list
                        hwa_notify_success("Success to update user.", ['title' => 'Success!']);
                        return redirect()->route("{$path}.index");
                    }
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!$result = $this->user->findUserMetaByUserId($id)) {
            // User not found
            abort(404);
        } else {
            if (auth()->guard('admin')->id() == $id) {
                // notify error
                hwa_notify_error("Can't delete this user. This user is logged on!", ['title' => 'Error!']);
            } else {
                if (!hwa_demo_env()) {
                    // Get user image
                    $avatar = $result['avatar'] ?? '';

                    // Select user
                    $selectResult = $this->user->find($id);

                    if ($selectResult->delete()) {
                        // Delete success
                        if (file_exists($path = hwa_image_path("users", $avatar))) {
                            File::delete($path); // Delete user image
                        }
                        // notify success
                        hwa_notify_success("Success to delete user.", ['title' => 'Success!']);
                    } else {
                        // notify error
                        hwa_notify_error("Error to delete user.", ['title' => 'Error!']);
                    }
                } else {
                    // notify success
                    hwa_notify_success("Success to delete user.", ['title' => 'Success!']);
                }
            }
            return redirect()->back();
        }
    }
}
