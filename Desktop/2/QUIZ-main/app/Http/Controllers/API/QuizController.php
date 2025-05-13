<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display a listing of all quizzes for authenticated users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quizzes = Quiz::with(['category', 'creator:id,name'])
            ->when($request->user()->role_id !== 1, function($query) use ($request) {
                // If not admin, only show own quizzes and published public quizzes
                return $query->where('created_by', $request->user()->id)
                    ->orWhere(function($q) {
                        return $q->where('is_public', true)
                            ->whereNotNull('published_at');
                    });
            })
            ->withCount('questions')
            ->latest()
            ->paginate(15);
        
        return response()->json($quizzes);
    }

    /**
     * Display a listing of public quizzes for guests.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicQuizzes()
    {
        try {
            $quizzes = Quiz::with(['category', 'creator:id,name'])
                ->where('is_public', true)
                ->whereNotNull('published_at')
                ->withCount('questions')
                ->latest()
                ->paginate(15);
            
            // Assurer une structure de réponse cohérente même sans quiz
            return response()->json([
                'success' => true,
                'data' => $quizzes->items(),
                'meta' => [
                    'current_page' => $quizzes->currentPage(),
                    'last_page' => $quizzes->lastPage(),
                    'per_page' => $quizzes->perPage(),
                    'total' => $quizzes->total()
                ]
            ]);
        } catch (\Exception $e) {
            // Journaliser l'erreur pour débogage
            \Log::error('Erreur dans publicQuizzes: ' . $e->getMessage());
            
            // Renvoyer une réponse structurée même en cas d'erreur
            return response()->json([
                'success' => false,
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'total' => 0
                ],
                'message' => 'Une erreur est survenue lors de la récupération des quiz publics.'
            ]);
        }
    }

    /**
     * Store a newly created quiz.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_public' => 'boolean',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'integer|min:0|max:100',
            'difficulty' => 'in:easy,medium,hard',
            'randomize_questions' => 'boolean',
            'show_answers' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_by' => $request->user()->id,
            'is_public' => $request->is_public ?? true,
            'time_limit' => $request->time_limit,
            'passing_score' => $request->passing_score ?? 60,
            'difficulty' => $request->difficulty ?? 'medium',
            'randomize_questions' => $request->randomize_questions ?? false,
            'show_answers' => $request->show_answers ?? true,
            'published_at' => $request->published_at,
        ]);

        return response()->json([
            'message' => 'Quiz created successfully',
            'quiz' => $quiz
        ], 201);
    }

    /**
     * Display the specified quiz for authenticated users.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $quiz = Quiz::with(['category', 'creator:id,name', 'questions.answers'])
            ->withCount('questions')
            ->findOrFail($id);
        
        // Check if user is authorized to view this quiz
        if ($quiz->created_by !== $request->user()->id && 
            !$request->user()->isAdmin() && 
            (!$quiz->is_public || !$quiz->isPublished())) {
            return response()->json([
                'message' => 'You are not authorized to view this quiz'
            ], 403);
        }
        
        return response()->json($quiz);
    }

    /**
     * Display the specified public quiz for guests.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPublicQuiz($id)
    {
        $quiz = Quiz::with(['category', 'creator:id,name'])
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->withCount('questions')
            ->findOrFail($id);
        
        // Only return limited information to guests
        $limitedQuiz = [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'description' => $quiz->description,
            'category' => $quiz->category,
            'creator' => $quiz->creator,
            'difficulty' => $quiz->difficulty,
            'time_limit' => $quiz->time_limit,
            'questions_count' => $quiz->questions_count,
        ];
        
        return response()->json($limitedQuiz);
    }

    /**
     * Update the specified quiz.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'is_public' => 'boolean',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'integer|min:0|max:100',
            'difficulty' => 'in:easy,medium,hard',
            'randomize_questions' => 'boolean',
            'show_answers' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::findOrFail($id);
        
        // Check if user is authorized to update this quiz
        if ($quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to update this quiz'
            ], 403);
        }
        
        // Update the quiz with the provided data
        $quiz->fill($request->only([
            'title',
            'description',
            'category_id',
            'is_public',
            'time_limit',
            'passing_score',
            'difficulty',
            'randomize_questions',
            'show_answers',
            'published_at',
        ]));
        
        $quiz->save();

        return response()->json([
            'message' => 'Quiz updated successfully',
            'quiz' => $quiz
        ]);
    }

    /**
     * Remove the specified quiz.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        
        // Check if user is authorized to delete this quiz
        if ($quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to delete this quiz'
            ], 403);
        }
        
        // Delete the quiz and all related data (questions, answers, attempts)
        $quiz->delete();

        return response()->json([
            'message' => 'Quiz deleted successfully'
        ]);
    }
}
