<?php

namespace Database\Seeders;

use App\Models\Banking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bankings = [
            ['name' => 'PT. BANK CENTRAL ASIA TBK.', 'alias' => 'bca'],
            ['name' => 'PT. BANK NEGARA INDONESIA (PERSERO)', 'alias' => 'bni'],
            ['name' => 'PT. BANK RAKYAT INDONESIA (PERSERO)', 'alias' => 'bri'],
            ['name' => 'PT. BANK MANDIRI (PERSERO) TBK.', 'alias' => 'mandiri'],
        ];

        $createdBy = optional(getUserWithRole('employee'))->id;

        foreach ($bankings as $banking) {
            Banking::firstOrCreate(
                ['alias' => strtolower($banking['alias'])],
                ['name' => ucwords(strtolower($banking['name'])), 'created_by' => $createdBy]
            );
        }
    }
}
