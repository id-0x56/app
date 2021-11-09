<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment('production')) {
            $users = [
                [
                    'name' => 'admin',
                    'email' => 'admin@example.com',
                    'email_verified_at' => now(),
                    'password' => '$2y$10$rd/3QPCb9s.JPFwFURtDR.ejoj.wXed.P4wEq44fOnKIL1f2WTX5C', // Pa$$w0rd
                    'remember_token' => Str::random(10),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ];
            DB::table('users')->insert($users);
        } else {
            User::factory(rand(3, 5))->create();
        }
    }
}
