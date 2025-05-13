<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Vérifions d'abord s'il y a au moins un utilisateur admin
        $admin = User::where('role_id', 1)->first();
        
        if (!$admin) {
            // Créer un utilisateur admin si aucun n'existe
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@quiz.com',
                'password' => bcrypt('password'),
                'role_id' => 1,
                'email_verified_at' => now()
            ]);
        }
        
        // Récupérer les catégories
        $scienceCategory = Category::where('name', 'Sciences')->first();
        $historyCategory = Category::where('name', 'Histoire')->first();
        $geographyCategory = Category::where('name', 'Géographie')->first();
        $techCategory = Category::where('name', 'Informatique')->first();
        
        // Créer les quiz
        $quizzes = [
            [
                'title' => 'Découverte Scientifique',
                'description' => 'Un quiz fascinant sur les grandes découvertes scientifiques qui ont changé le monde.',
                'category_id' => $scienceCategory->id,
                'created_by' => $admin->id,
                'difficulty' => 'medium',
                'time_limit' => 600, // 10 minutes
                'passing_score' => 70,
                'is_public' => true,
                'published_at' => Carbon::now(),
                'questions' => [
                    [
                        'question_text' => 'Qui a formulé la théorie de la relativité ?',
                        'question_type' => 'multiple_choice',
                        'points' => 10,
                        'answers' => [
                            ['answer_text' => 'Albert Einstein', 'is_correct' => true],
                            ['answer_text' => 'Isaac Newton', 'is_correct' => false],
                            ['answer_text' => 'Niels Bohr', 'is_correct' => false],
                            ['answer_text' => 'Marie Curie', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quel élément chimique a pour symbole "O" ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Or', 'is_correct' => false],
                            ['answer_text' => 'Oxygène', 'is_correct' => true],
                            ['answer_text' => 'Osmium', 'is_correct' => false],
                            ['answer_text' => 'Oganesson', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quelle est la planète la plus proche du Soleil ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Vénus', 'is_correct' => false],
                            ['answer_text' => 'Mars', 'is_correct' => false],
                            ['answer_text' => 'Mercure', 'is_correct' => true],
                            ['answer_text' => 'Neptune', 'is_correct' => false]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Histoire Mondiale',
                'description' => 'Testez vos connaissances sur les grands événements qui ont façonné notre monde.',
                'category_id' => $historyCategory->id,
                'created_by' => $admin->id,
                'difficulty' => 'hard',
                'time_limit' => 900, // 15 minutes
                'passing_score' => 60,
                'is_public' => true,
                'published_at' => Carbon::now(),
                'questions' => [
                    [
                        'question_text' => 'En quelle année a commencé la Première Guerre mondiale ?',
                        'question_type' => 'multiple_choice',
                        'points' => 10,
                        'answers' => [
                            ['answer_text' => '1914', 'is_correct' => true],
                            ['answer_text' => '1916', 'is_correct' => false],
                            ['answer_text' => '1918', 'is_correct' => false],
                            ['answer_text' => '1939', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Qui était le premier empereur de Rome ?',
                        'question_type' => 'multiple_choice',
                        'points' => 10,
                        'answers' => [
                            ['answer_text' => 'Jules César', 'is_correct' => false],
                            ['answer_text' => 'Auguste', 'is_correct' => true],
                            ['answer_text' => 'Néron', 'is_correct' => false],
                            ['answer_text' => 'Caligula', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => "Quel roi de France était connu sous le nom du 'Roi Soleil' ?",
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Louis XIV', 'is_correct' => true],
                            ['answer_text' => 'Louis XVI', 'is_correct' => false],
                            ['answer_text' => 'Napoléon Bonaparte', 'is_correct' => false],
                            ['answer_text' => 'François Ier', 'is_correct' => false]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Capitales du Monde',
                'description' => 'Connaissez-vous les capitales des pays à travers le monde ? Découvrez-le avec ce quiz !',
                'category_id' => $geographyCategory->id,
                'created_by' => $admin->id,
                'difficulty' => 'easy',
                'time_limit' => 300, // 5 minutes
                'passing_score' => 80,
                'is_public' => true,
                'published_at' => Carbon::now(),
                'questions' => [
                    [
                        'question_text' => 'Quelle est la capitale de la France ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Lyon', 'is_correct' => false],
                            ['answer_text' => 'Marseille', 'is_correct' => false],
                            ['answer_text' => 'Paris', 'is_correct' => true],
                            ['answer_text' => 'Nice', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quelle est la capitale du Japon ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Séoul', 'is_correct' => false],
                            ['answer_text' => 'Pékin', 'is_correct' => false],
                            ['answer_text' => 'Tokyo', 'is_correct' => true],
                            ['answer_text' => 'Bangkok', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quelle est la capitale de l\'Australie ?',
                        'question_type' => 'multiple_choice',
                        'points' => 10,
                        'answers' => [
                            ['answer_text' => 'Sydney', 'is_correct' => false],
                            ['answer_text' => 'Melbourne', 'is_correct' => false],
                            ['answer_text' => 'Canberra', 'is_correct' => true],
                            ['answer_text' => 'Perth', 'is_correct' => false]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Programmation Web',
                'description' => 'Testez vos connaissances en développement web, du HTML au JavaScript en passant par les frameworks modernes.',
                'category_id' => $techCategory->id,
                'created_by' => $admin->id,
                'difficulty' => 'medium',
                'time_limit' => 600, // 10 minutes
                'passing_score' => 70,
                'is_public' => true,
                'published_at' => Carbon::now(),
                'questions' => [
                    [
                        'question_text' => 'Que signifie HTML ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Hyper Text Markup Language', 'is_correct' => true],
                            ['answer_text' => 'High Tech Modern Language', 'is_correct' => false],
                            ['answer_text' => 'Hyper Transfer Module Language', 'is_correct' => false],
                            ['answer_text' => 'Home Tool Markup Language', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quel langage est principalement utilisé pour styler les pages web ?',
                        'question_type' => 'multiple_choice',
                        'points' => 5,
                        'answers' => [
                            ['answer_text' => 'Java', 'is_correct' => false],
                            ['answer_text' => 'CSS', 'is_correct' => true],
                            ['answer_text' => 'Python', 'is_correct' => false],
                            ['answer_text' => 'Ruby', 'is_correct' => false]
                        ]
                    ],
                    [
                        'question_text' => 'Quel est le framework JavaScript créé par Facebook ?',
                        'question_type' => 'multiple_choice',
                        'points' => 10,
                        'answers' => [
                            ['answer_text' => 'Angular', 'is_correct' => false],
                            ['answer_text' => 'Vue.js', 'is_correct' => false],
                            ['answer_text' => 'React', 'is_correct' => true],
                            ['answer_text' => 'Ember', 'is_correct' => false]
                        ]
                    ]
                ]
            ]
        ];
        
        foreach ($quizzes as $quizData) {
            $questions = $quizData['questions'];
            unset($quizData['questions']);
            
            $quiz = Quiz::create($quizData);
            
            foreach ($questions as $questionData) {
                $answers = $questionData['answers'];
                unset($questionData['answers']);
                
                $questionData['quiz_id'] = $quiz->id;
                $question = Question::create($questionData);
                
                foreach ($answers as $answerData) {
                    $answerData['question_id'] = $question->id;
                    Answer::create($answerData);
                }
            }
        }
    }
}
