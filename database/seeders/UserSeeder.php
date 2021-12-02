<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => 'admin@' . hwa_app_domain(),
        ], [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'full_name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@' . hwa_app_domain(),
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
