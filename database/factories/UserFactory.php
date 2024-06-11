<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\ClubProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('1234'),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Configure the factory for a normal user.
     */
    public function asUser(): static
    {
        return $this->afterCreating(function (User $user) {
            UserProfile::factory()->create(['user_id' => $user->id]);
            $user->assignRole('user');
        });
    }

    /**
     * Configure the factory for a club user.
     */
    public function asClub(): static
    {
        return $this->afterCreating(function (User $user) {
            ClubProfile::factory()->create(['user_id' => $user->id]);
            $user->assignRole('club');
        });
    }
}
