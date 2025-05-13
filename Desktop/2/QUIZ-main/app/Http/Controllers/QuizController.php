<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Affiche la liste des quiz de l'utilisateur connecté
     */
    public function index()
    {
        $quizzes = Quiz::where('created_by', Auth::id())
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('quizzes.index', compact('quizzes'));
    }
    
    /**
     * Affiche la page pour parcourir les quiz publics
     */
    public function browse()
    {
        $categories = Category::withCount('quizzes')->get();
        
        // Pour les quiz populaires, utilisons created_at pour le moment
        // car attempts_count n'existe pas dans la table
        $popularQuizzes = Quiz::where('is_public', true)
            ->withCount('questions')
            ->orderBy('created_at', 'desc')  // Temporairement ordonné par date de création
            ->limit(10)
            ->get();
            
        $recentQuizzes = Quiz::where('is_public', true)
            ->withCount('questions')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('quizzes.browse', compact('categories', 'popularQuizzes', 'recentQuizzes'));
    }
    
    /**
     * Affiche le formulaire pour créer un nouveau quiz
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent créer des quiz.');
        }
        $categories = Category::all();
        return view('quizzes.create', compact('categories'));
    }
    
    /**
     * Enregistre un nouveau quiz
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Seuls les administrateurs peuvent créer des quiz.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit' => 'nullable|integer|min:0',
            'is_public' => 'boolean',
        ]);
        
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_by' => Auth::id(),
            'difficulty' => $request->difficulty,
            'time_limit' => $request->time_limit,
            'is_public' => $request->has('is_public'),
            'passing_score' => $request->passing_score ?? 60,
            'randomize_questions' => $request->has('randomize_questions'),
            'show_answers' => $request->has('show_answers'),
            'published_at' => $request->has('is_public') ? now() : null,
        ]);
        
        return redirect()->route('quizzes.questions.index', $quiz->id)
            ->with('success', 'Quiz créé avec succès. Ajoutez maintenant des questions à votre quiz.');
    }
    
    /**
     * Affiche les détails d'un quiz
     */
    public function show($id)
    {
        $quiz = Quiz::with('category', 'user')
            ->withCount('questions')
            ->findOrFail($id);
            
        // Vérifier que l'utilisateur est autorisé à voir ce quiz
        if (!$quiz->is_public && $quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce quiz.');
        }
        
        return view('quizzes.show', compact('quiz'));
    }
    
    /**
     * Affiche le formulaire pour éditer un quiz
     */
    public function edit($id)
    {
        $quiz = Quiz::findOrFail($id);
        
        if ($quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce quiz.');
        }
        
        $categories = Category::all();
        return view('quizzes.edit', compact('quiz', 'categories'));
    }
    
    /**
     * Met à jour un quiz
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        
        if ($quiz->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce quiz.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit' => 'nullable|integer|min:0',
            'is_public' => 'boolean',
        ]);
        
        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'difficulty' => $request->difficulty,
            'time_limit' => $request->time_limit,
            'is_public' => $request->has('is_public'),
        ]);
        
        return redirect()->route('quizzes.show', $quiz->id)
            ->with('success', 'Quiz mis à jour avec succès.');
    }
    
    /**
     * Supprime un quiz
     */
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        
        if ($quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce quiz.');
        }
        
        $quiz->delete();
        
        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz supprimé avec succès.');
    }
    
    /**
     * Affiche la liste des questions d'un quiz
     */
    public function showQuestions($quizId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        
        if ($quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir les questions de ce quiz.');
        }
        
        return view('quizzes.questions.index', compact('quiz'));
    }
    
    /**
     * Affiche le formulaire pour créer une nouvelle question
     */
    public function createQuestion($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        if ($quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à ajouter des questions à ce quiz.');
        }
        
        return view('quizzes.questions.create', compact('quiz'));
    }
    
    /**
     * Enregistre une nouvelle question
     */
    public function storeQuestion(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        if ($quiz->created_by !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à ajouter des questions à ce quiz.');
        }
        
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:single,multiple',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
            'explanation' => 'nullable|string',
            'points' => 'required|integer|min:1',
        ]);
        
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'text' => $request->text,
            'type' => $request->type,
            'explanation' => $request->explanation,
            'points' => $request->points,
        ]);
        
        foreach ($request->answers as $answerData) {
            Answer::create([
                'question_id' => $question->id,
                'text' => $answerData['text'],
                'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
            ]);
        }
        
        return redirect()->route('quizzes.questions.index', $quiz->id)
            ->with('success', 'Question ajoutée avec succès.');
    }
    
    /**
     * Affiche le formulaire pour éditer une question
     */
    public function editQuestion($id)
    {
        $question = Question::with(['quiz', 'answers'])->findOrFail($id);
        
        if ($question->quiz->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette question.');
        }
        
        return view('quizzes.questions.edit', compact('question'));
    }
    
    /**
     * Met à jour une question
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::with('quiz')->findOrFail($id);
        
        if ($question->quiz->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette question.');
        }
        
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:single,multiple',
            'answers.*.id' => 'nullable|exists:answers,id',
            'answers.*.text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
            'explanation' => 'nullable|string',
            'points' => 'required|integer|min:1',
        ]);
        
        $question->update([
            'text' => $request->text,
            'type' => $request->type,
            'explanation' => $request->explanation,
            'points' => $request->points,
        ]);
        
        // Supprimer les réponses existantes qui ne sont pas dans la requête
        $existingAnswerIds = collect($request->answers)
            ->pluck('id')
            ->filter()
            ->toArray();
            
        $question->answers()
            ->whereNotIn('id', $existingAnswerIds)
            ->delete();
        
        // Mettre à jour ou créer des réponses
        foreach ($request->answers as $answerData) {
            if (isset($answerData['id']) && $answerData['id']) {
                // Mettre à jour une réponse existante
                Answer::where('id', $answerData['id'])
                    ->update([
                        'text' => $answerData['text'],
                        'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
                    ]);
            } else {
                // Créer une nouvelle réponse
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $answerData['text'],
                    'is_correct' => isset($answerData['is_correct']) && $answerData['is_correct'],
                ]);
            }
        }
        
        return redirect()->route('quizzes.questions.index', $question->quiz->id)
            ->with('success', 'Question mise à jour avec succès.');
    }
    
    /**
     * Supprime une question
     */
    public function destroyQuestion($id)
    {
        $question = Question::with('quiz')->findOrFail($id);
        
        if ($question->quiz->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette question.');
        }
        
        $quizId = $question->quiz->id;
        $question->delete();
        
        return redirect()->route('quizzes.questions.index', $quizId)
            ->with('success', 'Question supprimée avec succès.');
    }
    
    /**
     * Importe des questions depuis un fichier
     */
    public function importQuestions(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'file' => 'required|file|mimes:json,csv,xlsx',
        ]);
        
        $quiz = Quiz::findOrFail($request->quiz_id);
        
        if ($quiz->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à ajouter des questions à ce quiz.');
        }
        
        // Logique d'importation selon le type de fichier
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        // Traitement simple pour l'exemple
        // Dans une application réelle, vous devriez implémenter une logique d'importation plus robuste
        if ($extension === 'json') {
            $questions = json_decode(file_get_contents($file->path()), true);
            // Traiter les questions...
        } elseif ($extension === 'csv') {
            // Traiter le CSV...
        } elseif ($extension === 'xlsx') {
            // Traiter le Excel...
        }
        
        return redirect()->route('quizzes.questions.index', $quiz->id)
            ->with('success', 'Questions importées avec succès.');
    }
}
