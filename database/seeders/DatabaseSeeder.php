<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::transaction(function () {
            $superAdmin = User::create([
                'name' => 'Administrator',
                'email' => 'super_admin@gmail.com',
                'password' => bcrypt(12345678),
                'email_verified_at' => now(),
            ]);

            $superAdminRole = Role::findOrCreate('super admin');

            $superAdmin->assignRole($superAdminRole);
        });
    }
}
