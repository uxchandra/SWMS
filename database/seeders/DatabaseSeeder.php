<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'id'        => 1,
            'role'      => 'admin divisi',
            'deskripsi' => 'admin divisi bertugas melakukan permintaan '
        ]);

        User::create([
            'id'        => 1,
            'name'      => 'Kepala Gudang',
            'username'     => 'adminhrd',
            'password'  =>  bcrypt('hrd'),
            'role_id'   =>  1
        ]);
    }
}
