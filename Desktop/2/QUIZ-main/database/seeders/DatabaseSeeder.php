<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Exécuter les seeders dans l'ordre approprié
        $this->call([
            // Créer les catégories de quiz
            CategorySeeder::class,
            
            // Ensuite, créer les quizzes avec leurs questions et réponses
            QuizSeeder::class,
        ]);
    }
}
