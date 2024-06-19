<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClubClass;
use App\Models\ClubHour;
use App\Models\ClubPhoto;
use App\Models\ClubProfile;
use App\Models\Court;
use App\Models\Tournament;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClubProfile>
 */
class ClubProfileFactory extends Factory
{

    protected $model = ClubProfile::class;

    public function definition(): array
    {
        $provincias = [
            "Álava", "Albacete", "Alicante", "Almería", "Asturias", "Ávila",
            "Badajoz", "Baleares", "Barcelona", "Burgos", "Cáceres", "Cádiz",
            "Cantabria", "Castellón", "Ciudad Real", "Córdoba", "La Coruña",
            "Cuenca", "Girona", "Granada", "Guadalajara", "Guipúzcoa", "Huelva",
            "Huesca", "Jaén", "León", "Lleida", "Lugo", "Madrid", "Málaga",
            "Murcia", "Navarra", "Ourense", "Palencia", "Las Palmas", "Pontevedra",
            "La Rioja", "Salamanca", "Segovia", "Sevilla", "Soria", "Tarragona",
            "Santa Cruz de Tenerife", "Teruel", "Toledo", "Valencia", "Valladolid",
            "Vizcaya", "Zamora", "Zaragoza"
        ];

        return [
            'name' => fake()->company(),
            'telephone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'province' => fake()->randomElement($provincias),
            'description' => fake()->paragraph(),
        ];
    }

}
