<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\Candidate;

class TeamsAndCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamsData = [
            'لائحة صوت التغيير' => [
                'دلع ترو',
                'محمد الخطيب',
                'سليمة حوحو',
                'درويش الشمعة',
                'بشرى الجعيد'
            ],
            'لائحة صوت الطالب' => [
                'ريان سلام عليك',
                'ريتاج نبعة',
                'عائشة حمو',
                'خنساء سلات',
                'جمانة زهراء',
            ],
            'لائحة بكرا النا' => [
                'شذا شبو',
                'حياة الحاج',
                'نغم بو عرم',
                'فاروق دمج',
                'عبد الله شبو',
                'علي غنيم',
            ],
            'لائحة نحن الجيل الطالع' => [
                'ايناس الخطيب',
                'ربى الحاج',
                'فاطمة الشمعة',
                'هيام العك',
                'شادي برو',
                'سعدة الشمعة',
            ],
            'لائحة طلاب اليوم قادة الغد' => [
                'حلمي المعوش',
                'بهاء المعوش',
                'يارا حداد',
                'كارين عطوي',
                'نداء الجعيد',
            ],
        ];

        foreach ($teamsData as $teamName => $candidates) {
            $team = Team::firstOrCreate(['name' => $teamName]);

            foreach ($candidates as $candidateName) {
                $team->candidates()->firstOrCreate(['name' => $candidateName]);
            }
        }
    }
}

