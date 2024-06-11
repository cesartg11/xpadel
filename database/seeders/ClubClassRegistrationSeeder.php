<?php

namespace Database\Seeders;

use App\Models\ClubClass;
use App\Models\ClubClassRegistration;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class ClubClassRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = UserProfile::pluck('id'); // Obtén todos los IDs de usuarios
        $classes = ClubClass::all(); // Obtén todas las clases

        foreach ($classes as $class) {
            // Asigna aleatoriamente entre 1 a 5 usuarios por clase
            $selectedUsers = $userIds->random(rand(1, 5));

            foreach ($selectedUsers as $userId) {
                // Asegúrate de no duplicar registros para la misma clase y usuario
                if (!ClubClassRegistration::where('class_id', $class->id)->where('user_profile_id', $userId)->exists()) {
                    ClubClassRegistration::create([
                        'class_id' => $class->id,
                        'user_profile_id' => $userId,
                    ]);
                }
            }
        }
    }
}
