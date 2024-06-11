<?php

namespace Database\Factories;

use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{

    protected $model = UserProfile::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'age' => fake()->numberBetween(18, 100),
            'telephone' => fake()->phoneNumber(),
            'profile_photo_path' => null,
        ];
    }
}
