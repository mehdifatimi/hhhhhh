<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Category;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;

class DigitalQuizzesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Trouver un admin utilisateur pour créer les quiz
        $admin = User::where('role_id', 1)->first();
        
        if (!$admin) {
            // Créer un admin si aucun n'existe
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@digitalquiz.com',
                'password' => bcrypt('password'),
                'role_id' => 1,
            ]);
        }

        // Récupérer toutes les catégories
        $categories = Category::all();

        // Contenu du quiz pour chaque catégorie
        $quizData = [
            'Développement Web Frontend' => [
                'title' => 'Les fondamentaux du HTML5 et CSS3',
                'description' => 'Testez vos connaissances sur les bases du développement frontend avec HTML5 et CSS3.',
                'difficulty' => 'easy',
                'time_limit' => 600, // 10 minutes
                'questions' => [
                    [
                        'content' => 'Quelle balise HTML5 est utilisée pour créer un titre principal ?',
                        'answers' => [
                            ['content' => '<h1>', 'is_correct' => true],
                            ['content' => '<header>', 'is_correct' => false],
                            ['content' => '<title>', 'is_correct' => false],
                            ['content' => '<main>', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle propriété CSS permet de centrer horizontalement un élément block ?',
                        'answers' => [
                            ['content' => 'text-align: center;', 'is_correct' => false],
                            ['content' => 'align: center;', 'is_correct' => false],
                            ['content' => 'margin: 0 auto;', 'is_correct' => true],
                            ['content' => 'position: center;', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle unité CSS est relative à la taille de la police de l\'élément parent ?',
                        'answers' => [
                            ['content' => 'px', 'is_correct' => false],
                            ['content' => 'em', 'is_correct' => true],
                            ['content' => 'rem', 'is_correct' => false],
                            ['content' => 'vh', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Développement Web Backend' => [
                'title' => 'Bases des API REST',
                'description' => 'Découvrez les concepts fondamentaux des API REST et leur implémentation.',
                'difficulty' => 'medium',
                'time_limit' => 720, // 12 minutes
                'questions' => [
                    [
                        'content' => 'Quel est le code de statut HTTP pour une ressource créée avec succès ?',
                        'answers' => [
                            ['content' => '200 OK', 'is_correct' => false],
                            ['content' => '201 Created', 'is_correct' => true],
                            ['content' => '204 No Content', 'is_correct' => false],
                            ['content' => '302 Found', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle méthode HTTP est généralement utilisée pour mettre à jour une ressource existante ?',
                        'answers' => [
                            ['content' => 'GET', 'is_correct' => false],
                            ['content' => 'POST', 'is_correct' => false],
                            ['content' => 'PUT', 'is_correct' => true],
                            ['content' => 'DELETE', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce que CORS en développement web ?',
                        'answers' => [
                            ['content' => 'Custom Object Request System', 'is_correct' => false],
                            ['content' => 'Cross-Origin Resource Sharing', 'is_correct' => true],
                            ['content' => 'Create, Open, Read, Send', 'is_correct' => false],
                            ['content' => 'Client Origin Response Server', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'DevOps & Cloud' => [
                'title' => 'Intro à Docker',
                'description' => 'Testez vos connaissances sur les conteneurs Docker et leur utilisation en production.',
                'difficulty' => 'medium',
                'time_limit' => 900, // 15 minutes
                'questions' => [
                    [
                        'content' => 'Quel fichier est utilisé pour définir une image Docker ?',
                        'answers' => [
                            ['content' => 'Docker.json', 'is_correct' => false],
                            ['content' => 'docker-compose.yml', 'is_correct' => false],
                            ['content' => 'Dockerfile', 'is_correct' => true],
                            ['content' => 'container.config', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle commande permet de lister tous les conteneurs Docker en cours d\'exécution ?',
                        'answers' => [
                            ['content' => 'docker ps', 'is_correct' => true],
                            ['content' => 'docker list', 'is_correct' => false],
                            ['content' => 'docker containers', 'is_correct' => false],
                            ['content' => 'docker images', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce qu\'un volume Docker ?',
                        'answers' => [
                            ['content' => 'Un système de mesure de ressources CPU', 'is_correct' => false],
                            ['content' => 'Une méthode de stockage persistant pour les données des conteneurs', 'is_correct' => true],
                            ['content' => 'Un système de contrôle de version pour les images', 'is_correct' => false],
                            ['content' => 'Un outil de monitoring pour conteneurs', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Frameworks PHP' => [
                'title' => 'Maîtriser Laravel',
                'description' => 'Évaluez vos compétences sur le framework PHP le plus populaire, Laravel.',
                'difficulty' => 'medium',
                'time_limit' => 780, // 13 minutes
                'questions' => [
                    [
                        'content' => 'Quelle commande Artisan permet de créer un nouveau modèle avec sa migration ?',
                        'answers' => [
                            ['content' => 'php artisan create:model --migration', 'is_correct' => false],
                            ['content' => 'php artisan model:generate', 'is_correct' => false],
                            ['content' => 'php artisan make:model -m', 'is_correct' => true],
                            ['content' => 'php artisan new:model', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quel est le moteur de template par défaut de Laravel ?',
                        'answers' => [
                            ['content' => 'Twig', 'is_correct' => false],
                            ['content' => 'Smarty', 'is_correct' => false],
                            ['content' => 'Blade', 'is_correct' => true],
                            ['content' => 'Volt', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle classe Laravel permet de manipuler les fichiers uploadés ?',
                        'answers' => [
                            ['content' => 'Illuminate\\Http\\File', 'is_correct' => false],
                            ['content' => 'Illuminate\\Http\\UploadedFile', 'is_correct' => true],
                            ['content' => 'Illuminate\\Support\\FileManager', 'is_correct' => false],
                            ['content' => 'Illuminate\\Http\\FileUpload', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'JavaScript & Frameworks' => [
                'title' => 'React.js pour débutants',
                'description' => 'Découvrez les fondamentaux de React.js, la bibliothèque JavaScript de Facebook.',
                'difficulty' => 'medium',
                'time_limit' => 840, // 14 minutes
                'questions' => [
                    [
                        'content' => 'Quelle méthode de cycle de vie est appelée après qu\'un composant React a été monté dans le DOM ?',
                        'answers' => [
                            ['content' => 'componentWillMount()', 'is_correct' => false],
                            ['content' => 'componentDidMount()', 'is_correct' => true],
                            ['content' => 'componentDidUpdate()', 'is_correct' => false],
                            ['content' => 'componentMounted()', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Comment créer un "hook d\'état" dans un composant fonctionnel React ?',
                        'answers' => [
                            ['content' => 'const state = useStateHook(initialValue);', 'is_correct' => false],
                            ['content' => 'const state = createState(initialValue);', 'is_correct' => false],
                            ['content' => 'const [state, setState] = useState(initialValue);', 'is_correct' => true],
                            ['content' => 'this.state = initialValue;', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Lequel des outils suivants n\'est PAS couramment utilisé avec React ?',
                        'answers' => [
                            ['content' => 'Redux', 'is_correct' => false],
                            ['content' => 'Angular CLI', 'is_correct' => true],
                            ['content' => 'React Router', 'is_correct' => false],
                            ['content' => 'Create React App', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Mobile & Applications' => [
                'title' => 'Développement Flutter',
                'description' => 'Testez vos connaissances sur Flutter, le framework de développement mobile cross-platform de Google.',
                'difficulty' => 'hard',
                'time_limit' => 900, // 15 minutes
                'questions' => [
                    [
                        'content' => 'Quel langage de programmation est utilisé avec Flutter ?',
                        'answers' => [
                            ['content' => 'JavaScript', 'is_correct' => false],
                            ['content' => 'Kotlin', 'is_correct' => false],
                            ['content' => 'Swift', 'is_correct' => false],
                            ['content' => 'Dart', 'is_correct' => true],
                        ]
                    ],
                    [
                        'content' => 'Quel widget Flutter est utilisé pour créer une liste déroulante ?',
                        'answers' => [
                            ['content' => 'ScrollView', 'is_correct' => false],
                            ['content' => 'ListView', 'is_correct' => true],
                            ['content' => 'Column', 'is_correct' => false],
                            ['content' => 'GridView', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle fonction est appelée chaque fois qu\'un widget StatefulWidget est créé ?',
                        'answers' => [
                            ['content' => 'build()', 'is_correct' => false],
                            ['content' => 'initState()', 'is_correct' => true],
                            ['content' => 'createState()', 'is_correct' => false],
                            ['content' => 'constructor()', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Base de données' => [
                'title' => 'SQL Avancé',
                'description' => 'Perfectionnez vos connaissances en SQL avec ce quiz sur les requêtes avancées et l\'optimisation.',
                'difficulty' => 'hard',
                'time_limit' => 960, // 16 minutes
                'questions' => [
                    [
                        'content' => 'Quel type de jointure SQL renvoie des enregistrements présents dans les deux tables ?',
                        'answers' => [
                            ['content' => 'LEFT JOIN', 'is_correct' => false],
                            ['content' => 'RIGHT JOIN', 'is_correct' => false],
                            ['content' => 'INNER JOIN', 'is_correct' => true],
                            ['content' => 'OUTER JOIN', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle clause SQL est utilisée pour filtrer des groupes de résultats ?',
                        'answers' => [
                            ['content' => 'WHERE', 'is_correct' => false],
                            ['content' => 'HAVING', 'is_correct' => true],
                            ['content' => 'GROUP FILTER', 'is_correct' => false],
                            ['content' => 'FILTER BY', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce qu\'un index dans une base de données ?',
                        'answers' => [
                            ['content' => 'Une copie complète de la table', 'is_correct' => false],
                            ['content' => 'Une structure de données qui améliore la vitesse de récupération des données', 'is_correct' => true],
                            ['content' => 'Un type de contrainte d\'intégrité', 'is_correct' => false],
                            ['content' => 'Une clé étrangère', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'UI/UX Design' => [
                'title' => 'Principes de UI/UX Design',
                'description' => 'Découvrez les fondamentaux du design d\'interface utilisateur et de l\'expérience utilisateur.',
                'difficulty' => 'easy',
                'time_limit' => 720, // 12 minutes
                'questions' => [
                    [
                        'content' => 'Qu\'est-ce que le "whitespace" en design UI ?',
                        'answers' => [
                            ['content' => 'L\'espace où le texte est affiché', 'is_correct' => false],
                            ['content' => 'Une zone qui doit toujours être de couleur blanche', 'is_correct' => false],
                            ['content' => 'L\'espace vide entre les éléments de design', 'is_correct' => true],
                            ['content' => 'Un type de police sans serif', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle est la différence entre UX et UI ?',
                        'answers' => [
                            ['content' => 'UI concerne l\'apparence, UX concerne la fonctionnalité', 'is_correct' => true],
                            ['content' => 'UI est pour le web, UX pour les applications mobiles', 'is_correct' => false],
                            ['content' => 'UX est l\'ancienne terminologie, UI est la nouvelle', 'is_correct' => false],
                            ['content' => 'Il n\'y a pas de différence, ce sont des synonymes', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce qu\'un "wireframe" ?',
                        'answers' => [
                            ['content' => 'Une maquette détaillée avec tous les éléments visuels', 'is_correct' => false],
                            ['content' => 'Un squelette simplifié de l\'interface', 'is_correct' => true],
                            ['content' => 'Un ensemble de règles de design', 'is_correct' => false],
                            ['content' => 'La structure technique d\'une application', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Intelligence Artificielle' => [
                'title' => 'Introduction au Machine Learning',
                'description' => 'Apprenez les concepts de base du machine learning et ses applications.',
                'difficulty' => 'hard',
                'time_limit' => 1080, // 18 minutes
                'questions' => [
                    [
                        'content' => 'Quelle est la différence entre l\'apprentissage supervisé et non supervisé ?',
                        'answers' => [
                            ['content' => 'L\'apprentissage supervisé utilise des données étiquetées, l\'apprentissage non supervisé non', 'is_correct' => true],
                            ['content' => 'L\'apprentissage supervisé requiert un GPU, l\'apprentissage non supervisé non', 'is_correct' => false],
                            ['content' => 'L\'apprentissage supervisé est utilisé pour la régression, l\'apprentissage non supervisé pour la classification', 'is_correct' => false],
                            ['content' => 'L\'apprentissage supervisé est plus ancien que l\'apprentissage non supervisé', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce que l\'overfitting en machine learning ?',
                        'answers' => [
                            ['content' => 'Quand un modèle fonctionne trop rapidement', 'is_correct' => false],
                            ['content' => 'Quand un modèle est trop général', 'is_correct' => false],
                            ['content' => 'Quand un modèle performé trop bien sur les données d\'entraînement mais mal sur les données nouvelles', 'is_correct' => true],
                            ['content' => 'Quand un modèle utilise trop de ressources CPU', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle librairie Python est le plus souvent utilisée pour le deep learning ?',
                        'answers' => [
                            ['content' => 'NumPy', 'is_correct' => false],
                            ['content' => 'Pandas', 'is_correct' => false],
                            ['content' => 'Scikit-learn', 'is_correct' => false],
                            ['content' => 'TensorFlow', 'is_correct' => true],
                        ]
                    ]
                ]
            ],
            'Sécurité Informatique' => [
                'title' => 'Cybersécurité pour développeurs',
                'description' => 'Apprenez à sécuriser vos applications contre les attaques les plus courantes.',
                'difficulty' => 'medium',
                'time_limit' => 840, // 14 minutes
                'questions' => [
                    [
                        'content' => 'Qu\'est-ce qu\'une attaque XSS ?',
                        'answers' => [
                            ['content' => 'Une tentative d\'accès aux fichiers du serveur', 'is_correct' => false],
                            ['content' => 'Une injection de code malveillant dans une page web', 'is_correct' => true],
                            ['content' => 'Une surcharge des ressources serveur', 'is_correct' => false],
                            ['content' => 'Une tentative de deviner les mots de passe', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quelle est la méthode recommandée pour stocker les mots de passe en base de données ?',
                        'answers' => [
                            ['content' => 'En texte brut pour faciliter la récupération', 'is_correct' => false],
                            ['content' => 'Avec une simple encryption réversible', 'is_correct' => false],
                            ['content' => 'Avec un hachage et un sel', 'is_correct' => true],
                            ['content' => 'Dans un fichier séparé de la base de données', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce que HTTPS ?',
                        'answers' => [
                            ['content' => 'Une méthode d\'authentification', 'is_correct' => false],
                            ['content' => 'Un protocole de transfert sécurisé utilisant SSL/TLS', 'is_correct' => true],
                            ['content' => 'Un type de serveur web', 'is_correct' => false],
                            ['content' => 'Un langage de programmation sécurisé', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Blockchain & Web3' => [
                'title' => 'Introduction à la Blockchain',
                'description' => 'Découvrez les concepts fondamentaux de la blockchain et des applications décentralisées.',
                'difficulty' => 'medium',
                'time_limit' => 900, // 15 minutes
                'questions' => [
                    [
                        'content' => 'Qu\'est-ce qu\'un smart contract ?',
                        'answers' => [
                            ['content' => 'Un contrat juridique numérisé', 'is_correct' => false],
                            ['content' => 'Un programme auto-exécutable stocké sur une blockchain', 'is_correct' => true],
                            ['content' => 'Un algorithme de consensus blockchain', 'is_correct' => false],
                            ['content' => 'Un type de cryptomonnaie', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Quel langage est utilisé pour développer des smart contracts sur Ethereum ?',
                        'answers' => [
                            ['content' => 'JavaScript', 'is_correct' => false],
                            ['content' => 'Python', 'is_correct' => false],
                            ['content' => 'Solidity', 'is_correct' => true],
                            ['content' => 'C++', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce qu\'une dApp ?',
                        'answers' => [
                            ['content' => 'Une application de développement', 'is_correct' => false],
                            ['content' => 'Une application décentralisée fonctionnant sur une blockchain', 'is_correct' => true],
                            ['content' => 'Un type de base de données', 'is_correct' => false],
                            ['content' => 'Une extension de navigateur', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
            'Gestion de Projet IT' => [
                'title' => 'Méthodologies Agiles',
                'description' => 'Évaluez vos connaissances sur les méthodologies agiles et leur application dans les projets IT.',
                'difficulty' => 'easy',
                'time_limit' => 660, // 11 minutes
                'questions' => [
                    [
                        'content' => 'Quelle cérémonie n\'est PAS une pratique Scrum ?',
                        'answers' => [
                            ['content' => 'Daily Standup', 'is_correct' => false],
                            ['content' => 'Sprint Review', 'is_correct' => false],
                            ['content' => 'Gantt Chart Meeting', 'is_correct' => true],
                            ['content' => 'Sprint Planning', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce qu\'un "Product Backlog" ?',
                        'answers' => [
                            ['content' => 'Une liste priorisée de fonctionnalités à développer', 'is_correct' => true],
                            ['content' => 'Un journal des bugs trouvés dans le produit', 'is_correct' => false],
                            ['content' => 'Un document de spécifications techniques', 'is_correct' => false],
                            ['content' => 'Un rapport sur l\'avancement du projet', 'is_correct' => false],
                        ]
                    ],
                    [
                        'content' => 'Qu\'est-ce que le "Pair Programming" ?',
                        'answers' => [
                            ['content' => 'Une technique où deux programmeurs travaillent ensemble sur un même code', 'is_correct' => true],
                            ['content' => 'La programmation de deux applications en parallèle', 'is_correct' => false],
                            ['content' => 'Un système d\'attribution des tâches par paires', 'is_correct' => false],
                            ['content' => 'Une méthode de test en binôme', 'is_correct' => false],
                        ]
                    ]
                ]
            ],
        ];

        // Créer ou mettre à jour un quiz pour chaque catégorie
        foreach ($categories as $category) {
            if (isset($quizData[$category->name])) {
                $data = $quizData[$category->name];
                
                // Créer ou mettre à jour le quiz
                $quiz = Quiz::updateOrCreate(
                    ['title' => $data['title'], 'category_id' => $category->id],
                    [
                        'description' => $data['description'],
                        'difficulty' => $data['difficulty'],
                        'time_limit' => $data['time_limit'],
                        'created_by' => $admin->id,
                        'is_public' => true,
                        'published_at' => now(),
                    ]
                );
                
                // Ajouter les questions et réponses si elles n'existent pas déjà
                if (count($quiz->questions) === 0 && isset($data['questions'])) {
                    foreach ($data['questions'] as $questionData) {
                        $question = Question::create([
                            'quiz_id' => $quiz->id,
                            'question_text' => $questionData['content'],
                            'question_type' => 'multiple_choice',
                        ]);
                        
                        // Ajouter les réponses
                        foreach ($questionData['answers'] as $answerData) {
                            Answer::create([
                                'question_id' => $question->id,
                                'answer_text' => $answerData['content'],
                                'is_correct' => $answerData['is_correct'],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
