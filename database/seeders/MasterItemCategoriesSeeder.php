<?php

namespace Database\Seeders;

use App\Models\MasterItemCategory;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class MasterItemCategoriesSeeder extends Seeder
{
    private const CATEGORIES = [
        'Barge up to 330 ft',
        'Barge above 330 ft',
        'Cargo 50-70 m',
        'SPB 100-110 m',
        'SPOB 80-90 m',
        'Tanker 80-90 m',
        'Tug Boat up to 23 m',
        'Tug Boat 25-29 m',
        'Tug Boat above 30 m',
    ];

    public function run(): void
    {
        $tenant = Tenant::find('docking');

        if (! $tenant) {
            return;
        }

        $tenant->run(function (): void {
            foreach (self::CATEGORIES as $name) {
                MasterItemCategory::firstOrCreate(['name' => $name]);
            }
        });
    }
}
