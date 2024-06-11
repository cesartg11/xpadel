<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->email = 'admin@xpadel.com';
        $user->password = Hash::make('1234');
        $user->save();

        // Asegúrate de que el rol 'administrador' exista antes de ejecutar este seeder.
        $user->assignRole('administrador');
    }
}
