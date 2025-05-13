<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DigitalCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Plutôt que de truncate (qui échoue à cause des contraintes FK),
        // on vérifie et crée chaque catégorie si elle n'existe pas déjà

        // Catégories de développement digital
        $categories = [
            [
                'name' => 'Développement Web Frontend',
                'icon' => '/icons/frontend.png',
                'description' => 'HTML, CSS, JavaScript et frameworks frontend modernes'
            ],
            [
                'name' => 'Développement Web Backend',
                'icon' => '/icons/backend.png',
                'description' => 'PHP, Node.js, Python, bases de données et architecture serveur'
            ],
            [
                'name' => 'DevOps & Cloud',
                'icon' => '/icons/devops.png',
                'description' => 'Docker, Kubernetes, AWS, Azure, CI/CD et Infrastructure as Code'
            ],
            [
                'name' => 'Frameworks PHP',
                'icon' => '/icons/php-frameworks.png',
                'description' => 'Laravel, Symfony, CodeIgniter et autres frameworks PHP'
            ],
            [
                'name' => 'JavaScript & Frameworks',
                'icon' => '/icons/javascript.png',
                'description' => 'React, Vue.js, Angular, Node.js et JavaScript avancé'
            ],
            [
                'name' => 'Mobile & Applications',
                'icon' => '/icons/mobile.png',
                'description' => 'Développement iOS, Android, React Native et Flutter'
            ],
            [
                'name' => 'Base de données',
                'icon' => '/icons/database.png',
                'description' => 'SQL, NoSQL, conception et optimisation de bases de données'
            ],
            [
                'name' => 'UI/UX Design',
                'icon' => '/icons/design.png',
                'description' => 'Principes de design, outils et meilleures pratiques'
            ],
            [
                'name' => 'Intelligence Artificielle',
                'icon' => '/icons/ai.png',
                'description' => 'Machine Learning, Deep Learning et applications de l\'IA'
            ],
            [
                'name' => 'Sécurité Informatique',
                'icon' => '/icons/security.png',
                'description' => 'Cybersécurité, tests d\'intrusion et bonnes pratiques'
            ],
            [
                'name' => 'Blockchain & Web3',
                'icon' => '/icons/blockchain.png',
                'description' => 'Développement blockchain, smart contracts et DApps'
            ],
            [
                'name' => 'Gestion de Projet IT',
                'icon' => '/icons/project.png',
                'description' => 'Méthodologies agiles, gestion d\'équipe et outils de collaboration'
            ]
        ];

        foreach ($categories as $category) {
            // Vérifier si la catégorie existe déjà par son nom
            $existingCategory = Category::where('name', $category['name'])->first();
            
            if ($existingCategory) {
                // Mise à jour de la catégorie existante
                $existingCategory->update([
                    'icon' => $category['icon'],
                    'description' => $category['description']
                ]);
            } else {
                // Création d'une nouvelle catégorie
                Category::create($category);
            }
        }
    }
}
