<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin', 'user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $users = [
            [
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => Hash::make('1234'),
                'role' => 'admin',
            ],
            [
                'name' => 'Exemplo',
                'email' => 'e@example.com',
                'password' => Hash::make('1234'),
                'role' => 'user'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
