<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class CreateDefaultCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default categories for quizzes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categories = [
            [
                'name' => 'Culture Générale',
                'description' => 'Questions sur divers sujets de culture générale',
                'icon' => 'fa-solid fa-book'
            ],
            [
                'name' => 'Histoire',
                'description' => 'Questions sur l\'histoire mondiale',
                'icon' => 'fa-solid fa-landmark'
            ],
            [
                'name' => 'Géographie',
                'description' => 'Questions sur la géographie mondiale',
                'icon' => 'fa-solid fa-globe'
            ],
            [
                'name' => 'Sciences',
                'description' => 'Questions sur les sciences',
                'icon' => 'fa-solid fa-flask'
            ],
            [
                'name' => 'Technologie',
                'description' => 'Questions sur la technologie et l\'informatique',
                'icon' => 'fa-solid fa-laptop'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }

        $this->info('Catégories créées avec succès !');
        return 0;
    }
}
