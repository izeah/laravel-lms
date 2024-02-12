<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenaltySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('penalty')) {
                if (DB::table('penalty')->count() > 0) {
                    DB::table('penalty')->truncate();
                }

                $datas = [
                    [
                        'price' => 500,
                        'date' => Carbon::today()->toDateString(),
                    ]
                ];
                DB::table('penalty')->insert($datas);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
