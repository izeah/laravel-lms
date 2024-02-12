<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('roles')) {
                if (DB::table('roles')->count() > 0) {
                    DB::table('roles')->truncate();
                }

                $datas = [
                    [
                        'name' => 'Librarian',
                    ],
                    [
                        'name' => 'Chief of Library',
                    ],
                    [
                        'name' => 'Lecturer',
                    ],
                    [
                        'name' => 'Student',
                    ],
                ];
                DB::table('roles')->insert($datas);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
