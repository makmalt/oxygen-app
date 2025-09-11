<?php

namespace Database\Seeders;

use App\Models\PenanggungJawab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenanggungJawabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PenanggungJawab::factory()->createMany([
            ['nama' => 'Wildan'],
            ['nama' => 'Oca'],
            ['nama' => 'Budi'],
            ['nama' => 'Gaguk'],
            ['nama' => 'Suroso'],
            ['nama' => 'Salam'],
            ['nama' => 'Erik'],
            ['nama' => 'Bu Kaji'],
        ]);
    }
}
