<?php

namespace Database\Seeders;

use App\Models\Drs\SheetType;
use Illuminate\Database\Seeder;

class SheetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'code' => 'MD-3',
                'title' => 'MD-3',
                'description' => 'Match Day -3 (3 days before)',
                'available_to_customer' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'MD-2',
                'title' => 'MD-2',
                'description' => 'Match Day -2 (2 days before)',
                'available_to_customer' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'MD-1',
                'title' => 'MD-1',
                'description' => 'Match Day -1 (1 day before)',
                'available_to_customer' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'MD',
                'title' => 'MD',
                'description' => 'Match Day (day of match)',
                'available_to_customer' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($types as $type) {
            SheetType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
