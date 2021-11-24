<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = hwaCore()->getSettings();
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate([
                'key' => $key
            ], [
                'key' => $key,
                'value' => $value
            ]);
        }
    }
}
