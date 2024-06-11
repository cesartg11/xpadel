<?php

namespace Database\Seeders;

use App\Models\CourtRental;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->command->info('Inicializando con datos tabla Roles...');
        $this->call(RoleSeeder::class);

        $this->command->info('Inicializando con datos tabla Users, User_profile, Club_profile..., esto puede tardar un rato.');
        User::factory(200)->asUser()->create();
        User::factory(20)->asClub()->create();

        $this->command->info('Inicializando con datos tabla Club_hours...');
        $this->call(ClubHourSeeder::class);

        $this->command->info('Inicializando con datos tabla Courts...');
        $this->call(CourtSeeder::class);

        $this->command->info('Inicializando con datos tabla Classes...');
        $this->call(ClubClassSeeder::class);

        $this->command->info('Inicializando con datos tabla Tournaments, tournament_registrations...');
        $this->call(TournamentSeeder::class);

        $this->command->info('Inicializando con datos tabla class_registrations...');
        $this->call(ClubClassRegistrationSeeder::class);

        $this->command->info('Inicializando con datos tabla court_rentals...');
        CourtRental::factory(150)->create();

        $this->command->info('Inicializando con datos tabla tournament_registrations...');
        $this->call(TournamentRegistrationSeeder::class);

        $this->command->info('Inicializando con datos tabla tournament_matches...');
        $this->call(TournamentMatchSeeder::class);

        $this->command->info('Añadiendo administrador a users');
        $this->call(AdminSeeder::class);
    }
}
