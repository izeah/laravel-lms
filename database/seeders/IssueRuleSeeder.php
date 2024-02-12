<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IssueRuleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('issue_rules')) {
                if (DB::table('issue_rules')->count() > 0) {
                    DB::table('issue_rules')->truncate();
                }

                $datas = [
                    [
                        'role_id' => 3,
                        'max_borrow_item' => 10,
                        'max_borrow_day' => 30,
                    ],
                    [
                        'role_id' => 4,
                        'max_borrow_item' => 2,
                        'max_borrow_day' => 14,
                    ],
                ];
                DB::table('issue_rules')->insert($datas);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
