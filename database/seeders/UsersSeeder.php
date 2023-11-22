<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function () {
            $users = User::factory()->count(100)->create();

            $users->each(function ($user) {
                Profile::factory()->create(['user_id' => $user->id]);
            });
        });
        //  ./vendor/bin/sail artisan db:seed --class=UsersSeeder
    }
}
