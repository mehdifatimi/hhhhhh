<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Sciences',
                'description' => 'Quiz sur différents domaines scientifiques : physique, chimie, biologie...',
                'icon' => 'fa-solid fa-flask'
            ],
            [
                'name' => 'Histoire',
                'description' => 'Testez vos connaissances sur l\'histoire mondiale, des événements anciens aux temps modernes.',
                'icon' => 'fa-solid fa-landmark'
            ],
            [
                'name' => 'Géographie',
                'description' => 'Des quiz sur les pays, capitales, fleuves et montagnes du monde entier.',
                'icon' => 'fa-solid fa-globe'
            ],
            [
                'name' => 'Littérature',
                'description' => 'Quiz sur les grands auteurs, œuvres littéraires et mouvements littéraires.',
                'icon' => 'fa-solid fa-book'
            ],
            [
                'name' => 'Cinéma',
                'description' => 'Testez vos connaissances sur les films, réalisateurs et acteurs célèbres.',
                'icon' => 'fa-solid fa-film'
            ],
            [
                'name' => 'Musique',
                'description' => 'Quiz sur les artistes, chansons et genres musicaux à travers les époques.',
                'icon' => 'fa-solid fa-music'
            ],
            [
                'name' => 'Sport',
                'description' => 'Des questions sur le football, tennis, basketball et autres disciplines sportives.',
                'icon' => 'fa-solid fa-futbol'
            ],
            [
                'name' => 'Informatique',
                'description' => 'Quiz sur la programmation, les technologies et l\'histoire de l\'informatique.',
                'icon' => 'fa-solid fa-laptop-code'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
