<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'buy_margin',
                'value' => '2.00',
                'description' => 'Alış kuru için uygulanacak marj yüzdesi',
                'type' => 'float'
            ],
            [
                'key' => 'sell_margin',
                'value' => '-3.00',
                'description' => 'Satış kuru için uygulanacak marj yüzdesi',
                'type' => 'float'
            ]
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 