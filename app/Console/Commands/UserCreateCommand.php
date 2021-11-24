<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\Admin\RegisterUserRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hwa:user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Info: Creating a super user...');

        try {
            $firstname = $this->askWithValidate("Enter first name", 'required|max:191');
            $lastname = $this->askWithValidate("Enter last name", 'required|max:191');
            $username = $this->askWithValidate("Enter username", 'required|max:191|unique:users,username');
            $email = $this->askWithValidate("Enter email", 'required|email|unique:users,email');
            $password = $this->askWithValidate("Enter password", 'required|min:6|max:32');

            $data = [
                'first_name' => $firstname,
                'last_name' => $lastname,
                'full_name' => "{$firstname} {$lastname}",
                'username' => strtolower(trim($username)),
                'email' => strtolower(trim($email)),
                'email_verified_at' => now(),
                'password' => bcrypt($password),
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($user = User::create($data)) {
                // Send notify to email
                try {
                    $dataSend = [
                        'subject' => hwa_app_name() . " | Success to add new account",
                        'first_name' => $user->first_name,
                        'email' => $user->email,
                        'password' => $password,
                    ];
                    $user->notify(new RegisterUserRequest($dataSend));
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                }

                $this->comment('User is created successfully.');
                $this->comment("---------------------------");
                $this->comment("| Username: {$username}");
                $this->comment("| Password: {$password}");
                $this->comment("---------------------------");
            }
            return 0;

        } catch (\Exception $exception) {
            $this->error('Error: User could not be created.');
            $this->error($exception->getMessage());
            return 1;
        }
    }

    /**
     * Ask with validate
     *
     * @param string $message
     * @param string $rules
     * @return string
     */
    protected function askWithValidate(string $message, string $rules): string
    {
        do {
            $input = $this->ask($message);
            $validate = $this->hwa_validate(compact('input'), ['input' => $rules]);
            if ($validate['error']) {
                $this->error($validate['message']);
            }
        } while ($validate['error']);

        return $input;
    }

    /**
     * Validate input
     *
     * @param array $data
     * @param array $rules
     * @return array
     */
    protected function hwa_validate(array $data, array $rules): array
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [
                'error' => true,
                'message' => $validator->getMessageBag()->first(),
            ];
        }

        return [
            'error' => false,
        ];
    }
}
