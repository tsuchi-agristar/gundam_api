<?php

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //平日
        Settings::create(
            [
                'id' => 1,
                'difficulty' => 1.0,
                'superior_attack_rate' => 0.8,
                'judge_seconds' => 5,
                'fixed_attacks_percent' => 25,
                'fixed_attacks_down_percent' => 10,
                'attack_start_time' => 60,
                'attack_end_time' => 360,
                'result_time' => 420
            ]
        );
        //休日
        Settings::create(
            [
                'id' => 2,
                'difficulty' => 2.0,
                'superior_attack_rate' => 0.8,
                'judge_seconds' => 5,
                'fixed_attacks_percent' => 25,
                'fixed_attacks_down_percent' => 10,
                'attack_start_time' => 60,
                'attack_end_time' => 360,
                'result_time' => 420
            ]
        );
    }
}
