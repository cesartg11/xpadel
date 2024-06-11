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
        return [
            'name' => fake()->company(),
            'telephone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'province' => fake()->state(),
            'description' => fake()->paragraph(),
        ];
    }

}
