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

        $rows = collect($bankings)->map(function ($b) use ($createdBy) {
            return [
                'alias' => strtolower($b['alias']),
                'name' => ucwords(strtolower($b['name'])),
                'created_by' => $createdBy,
                'updated_at' => now(),
                'created_at' => now(),
            ];
        })->all();

        // Upsert ensures idempotency on alias
        Banking::upsert($rows, ['alias'], ['name', 'created_by', 'updated_at']);
    }
}
