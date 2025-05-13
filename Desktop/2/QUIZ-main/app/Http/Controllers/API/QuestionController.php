<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions for a specific quiz.
     *
     * @param  int  $quizId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($quizId, Request $request)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        // Check if user is authorized to view quiz questions
        if ($quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to view these questions'
            ], 403);
        }
        
        $questions = Question::with('answers')
            ->where('quiz_id', $quizId)
            ->get();
        
        return response()->json([
            'questions' => $questions
        ]);
    }

    /**
     * Store a newly created question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $quizId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        
        // Check if user is authorized to add questions to this quiz
        if ($quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to add questions to this quiz'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => ['required', Rule::in(['multiple_choice', 'true_false', 'text'])],
            'points' => 'integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'media_url' => 'nullable|string',
            'media_type' => 'nullable|string|in:image,video,audio',
            'hint' => 'nullable|string',
            'answers' => 'required|array',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
            'answers.*.explanation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Check if multiple_choice has at least 2 answers
        if ($request->question_type == 'multiple_choice' && count($request->answers) < 2) {
            return response()->json([
                'message' => 'Multiple choice questions must have at least 2 answer options'
            ], 422);
        }
        
        // Verify at least one correct answer for multiple_choice and true_false
        if (in_array($request->question_type, ['multiple_choice', 'true_false'])) {
            $hasCorrectAnswer = collect($request->answers)->contains('is_correct', true);
            if (!$hasCorrectAnswer) {
                return response()->json([
                    'message' => 'There must be at least one correct answer'
                ], 422);
            }
        }
        
        // If it's true/false, ensure there are exactly 2 answers (true and false)
        if ($request->question_type == 'true_false' && count($request->answers) != 2) {
            return response()->json([
                'message' => 'True/False questions must have exactly 2 answers'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create the question
            $question = Question::create([
                'quiz_id' => $quizId,
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'points' => $request->points ?? 1,
                'time_limit' => $request->time_limit,
                'media_url' => $request->media_url,
                'media_type' => $request->media_type,
                'hint' => $request->hint,
            ]);
            
            // Create the answers
            foreach ($request->answers as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct'],
                    'explanation' => $answerData['explanation'] ?? null,
                ]);
            }
            
            DB::commit();
            
            // Load the answers relationship for the response
            $question->load('answers');
            
            return response()->json([
                'message' => 'Question created successfully',
                'question' => $question
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $question = Question::with('quiz')->findOrFail($id);
        
        // Check if user is authorized to update this question
        if ($question->quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to update this question'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'sometimes|required|string',
            'question_type' => ['sometimes', 'required', Rule::in(['multiple_choice', 'true_false', 'text'])],
            'points' => 'sometimes|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'media_url' => 'nullable|string',
            'media_type' => 'nullable|string|in:image,video,audio',
            'hint' => 'nullable|string',
            'answers' => 'sometimes|required|array',
            'answers.*.id' => 'sometimes|exists:answers,id',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'required|boolean',
            'answers.*.explanation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // If changing question type, validate the new type's requirements
        $questionType = $request->question_type ?? $question->question_type;
        
        if ($request->has('answers')) {
            // Check if multiple_choice has at least 2 answers
            if ($questionType == 'multiple_choice' && count($request->answers) < 2) {
                return response()->json([
                    'message' => 'Multiple choice questions must have at least 2 answer options'
                ], 422);
            }
            
            // Verify at least one correct answer for multiple_choice and true_false
            if (in_array($questionType, ['multiple_choice', 'true_false'])) {
                $hasCorrectAnswer = collect($request->answers)->contains('is_correct', true);
                if (!$hasCorrectAnswer) {
                    return response()->json([
                        'message' => 'There must be at least one correct answer'
                    ], 422);
                }
            }
            
            // If it's true/false, ensure there are exactly 2 answers (true and false)
            if ($questionType == 'true_false' && count($request->answers) != 2) {
                return response()->json([
                    'message' => 'True/False questions must have exactly 2 answers'
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Update the question
            $question->fill($request->only([
                'question_text',
                'question_type',
                'points',
                'time_limit',
                'media_url',
                'media_type',
                'hint',
            ]));
            $question->save();
            
            // Update or create answers if provided
            if ($request->has('answers')) {
                // Get existing answer IDs
                $existingAnswerIds = $question->answers->pluck('id')->toArray();
                $providedAnswerIds = collect($request->answers)
                    ->pluck('id')
                    ->filter()
                    ->toArray();
                
                // Delete answers that are not in the request
                $answerIdsToDelete = array_diff($existingAnswerIds, $providedAnswerIds);
                if (!empty($answerIdsToDelete)) {
                    Answer::whereIn('id', $answerIdsToDelete)->delete();
                }
                
                // Update or create answers
                foreach ($request->answers as $answerData) {
                    if (isset($answerData['id'])) {
                        // Update existing answer
                        $answer = Answer::find($answerData['id']);
                        if ($answer) {
                            $answer->update([
                                'answer_text' => $answerData['answer_text'],
                                'is_correct' => $answerData['is_correct'],
                                'explanation' => $answerData['explanation'] ?? null,
                            ]);
                        }
                    } else {
                        // Create new answer
                        Answer::create([
                            'question_id' => $question->id,
                            'answer_text' => $answerData['answer_text'],
                            'is_correct' => $answerData['is_correct'],
                            'explanation' => $answerData['explanation'] ?? null,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Load the answers relationship for the response
            $question->load('answers');
            
            return response()->json([
                'message' => 'Question updated successfully',
                'question' => $question
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $question = Question::with('quiz')->findOrFail($id);
        
        // Check if user is authorized to delete this question
        if ($question->quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to delete this question'
            ], 403);
        }
        
        // Delete the question and its answers (via cascade)
        $question->delete();

        return response()->json([
            'message' => 'Question deleted successfully'
        ]);
    }
    
    /**
     * Import questions from CSV, Excel or JSON file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizzes,id',
            'file' => 'required|file|mimes:csv,xlsx,json|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $quiz = Quiz::findOrFail($request->quiz_id);
        
        // Check if user is authorized to add questions to this quiz
        if ($quiz->created_by !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'You are not authorized to add questions to this quiz'
            ], 403);
        }
        
        $file = $request->file('file');
        $fileExtension = $file->getClientOriginalExtension();
        
        try {
            DB::beginTransaction();
            
            $questionsAdded = 0;
            
            // Process based on file type
            if ($fileExtension == 'json') {
                $questionsAdded = $this->importFromJson($file, $quiz->id);
            } else if (in_array($fileExtension, ['csv', 'xlsx'])) {
                // Here you would use a package like Laravel Excel to import
                // As that would require additional setup, just returning a placeholder response
                return response()->json([
                    'message' => 'CSV/Excel import functionality requires additional packages. Please implement with Laravel Excel or similar package.'
                ], 501);
            }
            
            DB::commit();
            
            return response()->json([
                'message' => 'Successfully imported ' . $questionsAdded . ' questions',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error importing questions: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Import questions from a JSON file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  int  $quizId
     * @return int Number of questions added
     */
    private function importFromJson($file, $quizId)
    {
        $jsonContent = file_get_contents($file->getPathname());
        $questions = json_decode($jsonContent, true);
        
        if (!is_array($questions)) {
            throw new \Exception('Invalid JSON format');
        }
        
        $questionsAdded = 0;
        
        foreach ($questions as $questionData) {
            // Validate question data
            if (!isset($questionData['question_text']) || !isset($questionData['question_type']) || !isset($questionData['answers'])) {
                continue;
            }
            
            // Create question
            $question = Question::create([
                'quiz_id' => $quizId,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'points' => $questionData['points'] ?? 1,
                'time_limit' => $questionData['time_limit'] ?? null,
                'media_url' => $questionData['media_url'] ?? null,
                'media_type' => $questionData['media_type'] ?? null,
                'hint' => $questionData['hint'] ?? null,
            ]);
            
            // Create answers
            foreach ($questionData['answers'] as $answerData) {
                if (!isset($answerData['answer_text']) || !isset($answerData['is_correct'])) {
                    continue;
                }
                
                Answer::create([
                    'question_id' => $question->id,
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => $answerData['is_correct'],
                    'explanation' => $answerData['explanation'] ?? null,
                ]);
            }
            
            $questionsAdded++;
        }
        
        return $questionsAdded;
    }
}
