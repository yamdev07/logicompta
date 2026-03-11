<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de l'utilisateur de test
        User::factory()->create([
            'name' => 'Logicompta Admin',
            'email' => 'admin@logicompta.com',
        ]);

        // Appel des seeders comptables
        $this->call([
            AccountSeeder::class,
            JournalSeeder::class,
        ]);
    }
}
