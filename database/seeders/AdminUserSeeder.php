<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.lv'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        $user->forceFill([
            'is_admin' => true,
            'is_blocked' => false,
        ])->save();
    }
}
