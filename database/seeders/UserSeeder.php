<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            if (Schema::hasTable('users')) {
                if (DB::table('users')->count() > 0) {
                    DB::table('users')->truncate();
                }

                $datas = [
                    [
                        'sn' => 'npic123librarian',
                        'name' => 'Librarian',
                        'phone_number' => '0123-4567-89',
                        'dob' => '1990-01-01',
                        'address' => 'Phnom Penh',
                        'username' => 'librarian',
                        'email' => 'librarian@npic.com',
                        'password' => Hash::make('123'),
                        'profile_url' => 'admin.jpg',
                        'role_id' => 1,
                        'disabled' => '0',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'sn' => 'npic123chief',
                        'name' => 'Chief of Library',
                        'phone_number' => '0987-6543-21',
                        'dob' => '1990-01-01',
                        'address' => 'Phnom Penh',
                        'username' => 'chief',
                        'email' => 'chief@npic.com',
                        'password' => Hash::make('123'),
                        'profile_url' => 'default.png',
                        'role_id' => 2,
                        'disabled' => '0',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'sn' => 'npic123lecturer',
                        'name' => 'Lecturer 1',
                        'phone_number' => '0987-6543-22',
                        'dob' => '1990-01-01',
                        'address' => 'Phnom Penh',
                        'username' => 'lecturer1',
                        'email' => 'lecturer1@npic.com',
                        'password' => Hash::make('123'),
                        'profile_url' => 'default.png',
                        'role_id' => 3,
                        'disabled' => '0',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'sn' => 'npic123student',
                        'name' => 'Student 1',
                        'phone_number' => '0987-6543-23',
                        'dob' => '1990-01-01',
                        'address' => 'Phnom Penh',
                        'username' => 'student1',
                        'email' => 'student1@npic.com',
                        'password' => Hash::make('123'),
                        'profile_url' => 'default.png',
                        'role_id' => 4,
                        'disabled' => '0',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                    [
                        'sn' => 'npic456student',
                        'name' => 'Student 2',
                        'phone_number' => '0987-6543-24',
                        'dob' => '1990-01-01',
                        'address' => 'Phnom Penh',
                        'username' => 'student2',
                        'email' => 'student2@npic.com',
                        'password' => Hash::make('123'),
                        'profile_url' => 'default.png',
                        'role_id' => 4,
                        'disabled' => '0',
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    ],
                ];
                DB::table('users')->insert($datas);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
