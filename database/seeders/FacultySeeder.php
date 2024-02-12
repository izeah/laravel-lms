<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FacultySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('faculties')) {
                if (DB::table('faculties')->count() > 0) {
                    DB::table('faculties')->truncate();
                }

                $datas = [
                    [
                        'name' => 'Civil Engineering',
                    ],
                    [
                        'name' => 'Electrical Engineering',
                    ],
                    [
                        'name' => 'General Mechanical Engineering',
                    ],
                    [
                        'name' => 'Automobile Engineering',
                    ],
                    [
                        'name' => 'Electronic Engineering',
                    ],
                    [
                        'name' => 'Computer Science',
                    ],
                    [
                        'name' => 'Optical Science',
                    ],
                    [
                        'name' => 'Culinary Art (Bakery & Cookery)',
                    ],
                    [
                        'name' => 'Tourism and Hospitality',
                    ],
                ];
                DB::table('faculties')->insert($datas);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
