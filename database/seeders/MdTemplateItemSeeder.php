<?php

namespace Database\Seeders;

use App\Models\Drs\MdTemplateItem;
use Illuminate\Database\Seeder;

class MdTemplateItemSeeder extends Seeder
{
    public function run(): void
    {
        // All items available for MD templates
        $allItems = [
            ['title' => 'Workforce & Metro PSA Operational',                                                                                  'row_color' => 'red',    'sort_order' => 10,  'countdown_to_ko' => 'KO-19h', 'location' => '', 'fa_codes' => ['SEC', 'VUM', 'CMP']],
            ['title' => 'Venue Team Meeting',                                                                                                 'row_color' => 'yellow', 'sort_order' => 20,  'countdown_to_ko' => 'KO-08h', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'Temporary Traffic management & control measures on site as per agreed plans - Close Roads near stadium as per plans', 'row_color' => 'yellow', 'sort_order' => 30,  'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'VOC OPEN: KO-5',                                                                                                     'row_color' => 'yellow', 'sort_order' => 40,  'countdown_to_ko' => 'KO-5h', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'TETRA CHECK-INS',                                                                                                    'row_color' => 'yellow', 'sort_order' => 50,  'countdown_to_ko' => 'KO-6h30m', 'location' => '', 'fa_codes' => ['VUM', 'SEC', 'CMP']],
            ['title' => 'Bomb Sweep by Amiri Guard',                                                                                         'row_color' => 'red',    'sort_order' => 55,  'countdown_to_ko' => 'KO-7h', 'location' => '', 'fa_codes' => ['SEC']],
            ['title' => '1Hr to GO - Ensure operational readiness in all areas and report any issues to VOC : KO-4',                          'row_color' => 'yellow', 'sort_order' => 60,  'countdown_to_ko' => 'KO-4h', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'All Zones Activated',                                                                                                'row_color' => 'red',    'sort_order' => 70,  'countdown_to_ko' => 'KO-5h', 'location' => '', 'fa_codes' => ['SEC', 'CMP']],
            ['title' => 'Accreditation Zoning Activation: KO-4',                                                                              'row_color' => 'yellow', 'sort_order' => 75,  'countdown_to_ko' => 'KO-4h', 'location' => 'All Areas', 'fa_codes' => ['VUM', 'SEC', 'CMP']],
            ['title' => 'Media PSA Operational',                                                                                              'row_color' => 'red',    'sort_order' => 80,  'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['SEC']],
            ['title' => '30M to GO - Ensure operational readiness: KO -3h30',                                                                 'row_color' => 'yellow', 'sort_order' => 90,  'countdown_to_ko' => 'KO-3h30m', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'FULL Floodlights ON',                                                                                                'row_color' => 'yellow', 'sort_order' => 100, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'GATES OPEN',                                                                                                         'row_color' => 'yellow', 'sort_order' => 110, 'countdown_to_ko' => 'KO-3h', 'location' => '', 'fa_codes' => ['VUM', 'SEC', 'CMP']],
            ['title' => 'Fan Zone is Operational',                                                                                            'row_color' => 'yellow', 'sort_order' => 120, 'countdown_to_ko' => 'KO-3h', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'TEAM A KIT VAN ARRIVAL',                                                                                             'row_color' => 'green',  'sort_order' => 130, 'countdown_to_ko' => 'KO-5h', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'TEAM B KIT VAN ARRIVAL',                                                                                             'row_color' => 'green',  'sort_order' => 140, 'countdown_to_ko' => 'KO-4h50m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'TEAM A ARRIVAL',                                                                                                     'row_color' => 'green',  'sort_order' => 150, 'countdown_to_ko' => 'KO-1h40m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'TEAM B ARRIVAL',                                                                                                     'row_color' => 'green',  'sort_order' => 160, 'countdown_to_ko' => 'KO-1h30m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'Fan Zone is closed',                                                                                                 'row_color' => 'yellow', 'sort_order' => 170, 'countdown_to_ko' => 'KO-1h30m', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'Warm Up starts',                                                                                                     'row_color' => 'green',  'sort_order' => 180, 'countdown_to_ko' => 'KO-50m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'Warm Up Finishes',                                                                                                   'row_color' => 'green',  'sort_order' => 190, 'countdown_to_ko' => 'KO-20m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'Pre-match ceremony starts',                                                                                          'row_color' => 'yellow', 'sort_order' => 200, 'countdown_to_ko' => 'KO-12m', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'KICK-OFF :KO',                                                                                                       'row_color' => 'green',  'sort_order' => 210, 'countdown_to_ko' => 'HT', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'END OF FIRST HALF',                                                                                                  'row_color' => 'green',  'sort_order' => 220, 'countdown_to_ko' => 'KO+45m', 'location' => '', 'fa_codes' => ['CMP', 'VUM']],
            ['title' => 'START OF SECOND HALF: FE- 45',                                                                                      'row_color' => 'green',  'sort_order' => 230, 'countdown_to_ko' => 'FW-45m', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'All Parkings ready for Egress Operation',                                                                            'row_color' => 'yellow', 'sort_order' => 240, 'countdown_to_ko' => 'FW-15m', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'STC & TCP closes',                                                                                                   'row_color' => 'yellow', 'sort_order' => 245, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['VUM', 'SEC']],
            ['title' => 'Official Match Attendance announcement',                                                                             'row_color' => 'yellow', 'sort_order' => 250, 'countdown_to_ko' => 'FW-15m', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
            ['title' => 'Redeployment + Egress postmatch',                                                                                    'row_color' => 'yellow', 'sort_order' => 260, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['VUM', 'SEC', 'CMP']],
            ['title' => 'Egress gates are pre-open : FW-30',                                                                                  'row_color' => 'yellow', 'sort_order' => 265, 'countdown_to_ko' => 'FW-30m', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'Egress Gates (Stadium Gate)',                                                                                        'row_color' => 'yellow', 'sort_order' => 270, 'countdown_to_ko' => 'FW-15m', 'location' => 'Inner perimeter', 'fa_codes' => ['VUM', 'SEC']],
            ['title' => 'Egress Gates open : FW - 15',                                                                                        'row_color' => 'yellow', 'sort_order' => 275, 'countdown_to_ko' => 'FW-15m', 'location' => 'Outer perimeter', 'fa_codes' => ['VUM']],
            ['title' => 'Final Whistle - FW',                                                                                                 'row_color' => 'green',  'sort_order' => 280, 'countdown_to_ko' => 'FW', 'location' => '', 'fa_codes' => ['CMP', 'VUM']],
            ['title' => 'Fan Zone is Operational',                                                                                            'row_color' => 'yellow', 'sort_order' => 285, 'countdown_to_ko' => 'FW', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'Fan Zone is closed',                                                                                                 'row_color' => 'yellow', 'sort_order' => 290, 'countdown_to_ko' => 'FW+1h', 'location' => '', 'fa_codes' => ['VUM']],
            ['title' => 'Post match Press Conference',                                                                                        'row_color' => 'yellow', 'sort_order' => 300, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['SEC', 'CMP']],
            ['title' => 'TEAM A has left the stadium',                                                                                        'row_color' => 'green',  'sort_order' => 310, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['CMP', 'VUM']],
            ['title' => 'Team B has left the stadium',                                                                                        'row_color' => 'green',  'sort_order' => 320, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['CMP', 'VUM']],
            ['title' => 'Referees have left the stadium',                                                                                     'row_color' => 'green',  'sort_order' => 330, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['CMP']],
            ['title' => 'Accreditation zoning deactivation',                                                                                  'row_color' => 'yellow', 'sort_order' => 340, 'countdown_to_ko' => 'FW+2h', 'location' => '', 'fa_codes' => ['VUM', 'SEC']],
            ['title' => 'VOC close/End of Operations',                                                                                        'row_color' => 'yellow', 'sort_order' => 350, 'countdown_to_ko' => '', 'location' => '', 'fa_codes' => ['VUM', 'CMP']],
        ];

        // Expand items by FA code
        $rows = [];
        foreach ($allItems as $item) {
            $faCodes = $item['fa_codes'];
            unset($item['fa_codes']); // Remove the temporary fa_codes key

            foreach ($faCodes as $faCode) {
                $rows[] = array_merge($item, [
                    'fa_code' => $faCode,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Insert all rows
        foreach ($rows as $row) {
            MdTemplateItem::updateOrCreate(
                ['title' => $row['title'], 'fa_code' => $row['fa_code']],
                $row
            );
        }
    }
}
