<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserQuizController extends Controller
{
    /**
     * Create a new quiz attempt for authenticated users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createAttempt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::with('questions')->findOrFail($request->quiz_id);
        
        // Check if the quiz is published and public or if user is the creator
        if (!$quiz->isPublished() && !$quiz->is_public && $quiz->created_by !== $request->user()->id) {
            return response()->json([
                'message' => 'This quiz is not available'
            ], 403);
        }
        
        // Check if quiz has questions
        if ($quiz->questions->isEmpty()) {
            return response()->json([
                'message' => 'This quiz has no questions'
            ], 400);
        }
        
        $totalQuestions = $quiz->questions->count();
        
        // Create a new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $request->user()->id,
            'started_at' => now(),
            'total_questions' => $totalQuestions,
        ]);
        
        // Get questions for the quiz
        $questions = $quiz->questions()->with('answers')->get();
        
        // If quiz is set to randomize questions, shuffle them
        if ($quiz->randomize_questions) {
            $questions = $questions->shuffle();
        }

        // Prepare questions with answers
        $preparedQuestions = [];
        foreach ($questions as $question) {
            $answers = [];
            
            // For multiple choice and true/false, prepare answer options
            if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                foreach ($question->answers as $answer) {
                    $answers[] = [
                        'id' => $answer->id,
                        'answer_text' => $answer->answer_text,
                        // Don't include is_correct in the response
                    ];
                }
                
                // Shuffle the answers for multiple choice
                if ($question->question_type === 'multiple_choice') {
                    shuffle($answers);
                }
            }
            
            $preparedQuestions[] = [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'media_url' => $question->media_url,
                'media_type' => $question->media_type,
                'hint' => $question->hint,
                'time_limit' => $question->time_limit,
                'answers' => $answers,
            ];
        }

        return response()->json([
            'message' => 'Quiz attempt created successfully',
            'attempt_id' => $attempt->id,
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'time_limit' => $quiz->time_limit,
                'total_questions' => $totalQuestions,
            ],
            'questions' => $preparedQuestions,
        ]);
    }

    /**
     * Create a new quiz attempt for guest users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createGuestAttempt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $quiz = Quiz::with('questions')->findOrFail($request->quiz_id);
        
        // Check if the quiz is published and public
        if (!$quiz->isPublished() || !$quiz->is_public) {
            return response()->json([
                'message' => 'This quiz is not available'
            ], 403);
        }
        
        // Check if quiz has questions
        if ($quiz->questions->isEmpty()) {
            return response()->json([
                'message' => 'This quiz has no questions'
            ], 400);
        }
        
        $totalQuestions = $quiz->questions->count();
        
        // Generate a unique identifier for the guest
        $guestIdentifier = Str::uuid()->toString();
        
        // Create a new attempt
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'guest_identifier' => $guestIdentifier,
            'started_at' => now(),
            'total_questions' => $totalQuestions,
        ]);
        
        // Get questions for the quiz
        $questions = $quiz->questions()->with('answers')->get();
        
        // If quiz is set to randomize questions, shuffle them
        if ($quiz->randomize_questions) {
            $questions = $questions->shuffle();
        }

        // Prepare questions with answers
        $preparedQuestions = [];
        foreach ($questions as $question) {
            $answers = [];
            
            // For multiple choice and true/false, prepare answer options
            if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                foreach ($question->answers as $answer) {
                    $answers[] = [
                        'id' => $answer->id,
                        'answer_text' => $answer->answer_text,
                        // Don't include is_correct in the response
                    ];
                }
                
                // Shuffle the answers for multiple choice
                if ($question->question_type === 'multiple_choice') {
                    shuffle($answers);
                }
            }
            
            $preparedQuestions[] = [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'question_type' => $question->question_type,
                'media_url' => $question->media_url,
                'media_type' => $question->media_type,
                'hint' => $question->hint,
                'time_limit' => $question->time_limit,
                'answers' => $answers,
            ];
        }

        return response()->json([
            'message' => 'Guest quiz attempt created successfully',
            'attempt_id' => $attempt->id,
            'guest_identifier' => $guestIdentifier,
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'time_limit' => $quiz->time_limit,
                'total_questions' => $totalQuestions,
            ],
            'questions' => $preparedQuestions,
        ]);
    }

    /**
     * Submit an answer to a question for authenticated users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_attempt_id' => 'required|exists:quiz_attempts,id',
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required_without:text_answer|exists:answers,id',
            'text_answer' => 'required_without:answer_id|string|nullable',
            'time_taken' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attempt = QuizAttempt::findOrFail($request->quiz_attempt_id);
        
        // Verify this is the user's attempt
        if ($attempt->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'This is not your quiz attempt'
            ], 403);
        }
        
        // Verify attempt is not already completed
        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'This quiz attempt is already completed'
            ], 400);
        }
        
        $question = Question::findOrFail($request->question_id);
        
        // Verify the question belongs to the quiz
        if ($question->quiz_id !== $attempt->quiz_id) {
            return response()->json([
                'message' => 'This question does not belong to the quiz'
            ], 400);
        }
        
        // Check if this question was already answered
        $existingAnswer = UserAnswer::where('quiz_attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();
            
        if ($existingAnswer) {
            return response()->json([
                'message' => 'This question has already been answered'
            ], 400);
        }
        
        // Determine if the answer is correct
        $isCorrect = false;
        
        if ($question->question_type === 'text') {
            // For text questions, check if answer matches any correct answer
            // This is a simple implementation - you may want to implement more sophisticated text matching
            $correctAnswers = $question->answers()->where('is_correct', true)->get();
            foreach ($correctAnswers as $correctAnswer) {
                if (strtolower(trim($request->text_answer)) === strtolower(trim($correctAnswer->answer_text))) {
                    $isCorrect = true;
                    break;
                }
            }
            
            // Create the user answer
            $userAnswer = UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'text_answer' => $request->text_answer,
                'is_correct' => $isCorrect,
                'time_taken' => $request->time_taken,
            ]);
        } else {
            // For multiple choice and true/false
            $answer = Answer::findOrFail($request->answer_id);
            
            // Verify the answer belongs to the question
            if ($answer->question_id !== $question->id) {
                return response()->json([
                    'message' => 'This answer does not belong to the question'
                ], 400);
            }
            
            $isCorrect = $answer->is_correct;
            
            // Create the user answer
            $userAnswer = UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_id' => $answer->id,
                'is_correct' => $isCorrect,
                'time_taken' => $request->time_taken,
            ]);
        }
        
        // Update the attempt with correct answer count if this answer is correct
        if ($isCorrect) {
            $attempt->increment('correct_answers');
        }
        
        // Check if all questions have been answered
        $answeredCount = UserAnswer::where('quiz_attempt_id', $attempt->id)->count();
        
        if ($answeredCount >= $attempt->total_questions) {
            // All questions answered, complete the attempt
            $timeSpent = now()->diffInSeconds($attempt->started_at);
            $score = $attempt->scorePercentage();
            $passed = $score >= $attempt->quiz->passing_score;
            
            $attempt->update([
                'completed_at' => now(),
                'score' => $score,
                'time_spent' => $timeSpent,
                'passed' => $passed,
            ]);
            
            return response()->json([
                'message' => 'Answer submitted and quiz completed',
                'is_correct' => $isCorrect,
                'quiz_completed' => true,
                'score' => $score,
                'correct_answers' => $attempt->correct_answers,
                'total_questions' => $attempt->total_questions,
                'time_spent' => $timeSpent,
                'passed' => $passed,
            ]);
        }
        
        return response()->json([
            'message' => 'Answer submitted successfully',
            'is_correct' => $isCorrect,
            'quiz_completed' => false,
            'answered_count' => $answeredCount,
            'total_questions' => $attempt->total_questions,
        ]);
    }

    /**
     * Submit an answer to a question for guest users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitGuestAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_attempt_id' => 'required|exists:quiz_attempts,id',
            'guest_identifier' => 'required|string',
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required_without:text_answer|exists:answers,id',
            'text_answer' => 'required_without:answer_id|string|nullable',
            'time_taken' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attempt = QuizAttempt::where('id', $request->quiz_attempt_id)
            ->where('guest_identifier', $request->guest_identifier)
            ->first();
            
        if (!$attempt) {
            return response()->json([
                'message' => 'Invalid quiz attempt or guest identifier'
            ], 404);
        }
        
        // Verify attempt is not already completed
        if ($attempt->isCompleted()) {
            return response()->json([
                'message' => 'This quiz attempt is already completed'
            ], 400);
        }
        
        $question = Question::findOrFail($request->question_id);
        
        // Verify the question belongs to the quiz
        if ($question->quiz_id !== $attempt->quiz_id) {
            return response()->json([
                'message' => 'This question does not belong to the quiz'
            ], 400);
        }
        
        // Check if this question was already answered
        $existingAnswer = UserAnswer::where('quiz_attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();
            
        if ($existingAnswer) {
            return response()->json([
                'message' => 'This question has already been answered'
            ], 400);
        }
        
        // Determine if the answer is correct
        $isCorrect = false;
        
        if ($question->question_type === 'text') {
            // For text questions, check if answer matches any correct answer
            $correctAnswers = $question->answers()->where('is_correct', true)->get();
            foreach ($correctAnswers as $correctAnswer) {
                if (strtolower(trim($request->text_answer)) === strtolower(trim($correctAnswer->answer_text))) {
                    $isCorrect = true;
                    break;
                }
            }
            
            // Create the user answer
            $userAnswer = UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'text_answer' => $request->text_answer,
                'is_correct' => $isCorrect,
                'time_taken' => $request->time_taken,
            ]);
        } else {
            // For multiple choice and true/false
            $answer = Answer::findOrFail($request->answer_id);
            
            // Verify the answer belongs to the question
            if ($answer->question_id !== $question->id) {
                return response()->json([
                    'message' => 'This answer does not belong to the question'
                ], 400);
            }
            
            $isCorrect = $answer->is_correct;
            
            // Create the user answer
            $userAnswer = UserAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_id' => $answer->id,
                'is_correct' => $isCorrect,
                'time_taken' => $request->time_taken,
            ]);
        }
        
        // Update the attempt with correct answer count if this answer is correct
        if ($isCorrect) {
            $attempt->increment('correct_answers');
        }
        
        // Check if all questions have been answered
        $answeredCount = UserAnswer::where('quiz_attempt_id', $attempt->id)->count();
        
        if ($answeredCount >= $attempt->total_questions) {
            // All questions answered, complete the attempt
            $timeSpent = now()->diffInSeconds($attempt->started_at);
            $score = $attempt->scorePercentage();
            $passed = $score >= $attempt->quiz->passing_score;
            
            $attempt->update([
                'completed_at' => now(),
                'score' => $score,
                'time_spent' => $timeSpent,
                'passed' => $passed,
            ]);
            
            return response()->json([
                'message' => 'Answer submitted and quiz completed',
                'is_correct' => $isCorrect,
                'quiz_completed' => true,
                'score' => $score,
                'correct_answers' => $attempt->correct_answers,
                'total_questions' => $attempt->total_questions,
                'time_spent' => $timeSpent,
                'passed' => $passed,
            ]);
        }
        
        return response()->json([
            'message' => 'Answer submitted successfully',
            'is_correct' => $isCorrect,
            'quiz_completed' => false,
            'answered_count' => $answeredCount,
            'total_questions' => $attempt->total_questions,
        ]);
    }

    /**
     * Get quiz result for guests.
     *
     * @param  int  $attemptId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGuestResult($attemptId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'guest_identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('guest_identifier', $request->guest_identifier)
            ->with(['quiz:id,title,passing_score,show_answers', 'answers.question'])
            ->first();
            
        if (!$attempt) {
            return response()->json([
                'message' => 'Invalid quiz attempt or guest identifier'
            ], 404);
        }
        
        // If the attempt is not completed, return an error
        if (!$attempt->isCompleted()) {
            return response()->json([
                'message' => 'This quiz attempt is not yet completed'
            ], 400);
        }
        
        $result = [
            'quiz_title' => $attempt->quiz->title,
            'score' => $attempt->score,
            'correct_answers' => $attempt->correct_answers,
            'total_questions' => $attempt->total_questions,
            'time_spent' => $attempt->time_spent,
            'passed' => $attempt->passed,
        ];
        
        // Include detailed answers if the quiz is set to show answers
        if ($attempt->quiz->show_answers) {
            $answers = [];
            foreach ($attempt->answers as $userAnswer) {
                $answerDetail = [
                    'question_text' => $userAnswer->question->question_text,
                    'is_correct' => $userAnswer->is_correct,
                ];
                
                if ($userAnswer->question->question_type === 'text') {
                    $answerDetail['user_answer'] = $userAnswer->text_answer;
                    
                    // Get the correct answer for text questions
                    $correctAnswer = $userAnswer->question->answers()
                        ->where('is_correct', true)
                        ->first();
                        
                    if ($correctAnswer) {
                        $answerDetail['correct_answer'] = $correctAnswer->answer_text;
                    }
                } else {
                    // For multiple choice and true/false
                    $userSelectedAnswer = $userAnswer->answer;
                    if ($userSelectedAnswer) {
                        $answerDetail['user_answer'] = $userSelectedAnswer->answer_text;
                    }
                    
                    // Get all correct answers
                    $correctAnswers = $userAnswer->question->answers()
                        ->where('is_correct', true)
                        ->get();
                        
                    $answerDetail['correct_answers'] = $correctAnswers->pluck('answer_text');
                }
                
                $answers[] = $answerDetail;
            }
            
            $result['answers'] = $answers;
        }
        
        return response()->json($result);
    }

    /**
     * Get all quiz attempts for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUserAttempts(Request $request)
    {
        $attempts = QuizAttempt::where('user_id', $request->user()->id)
            ->with('quiz:id,title,category_id')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return response()->json($attempts);
    }

    /**
     * Get details for a specific quiz attempt.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAttemptDetails($id, Request $request)
    {
        $attempt = QuizAttempt::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with(['quiz:id,title,passing_score,show_answers', 'answers.question'])
            ->firstOrFail();
        
        $result = [
            'quiz_title' => $attempt->quiz->title,
            'score' => $attempt->score,
            'correct_answers' => $attempt->correct_answers,
            'total_questions' => $attempt->total_questions,
            'time_spent' => $attempt->time_spent,
            'passed' => $attempt->passed,
            'completed' => $attempt->isCompleted(),
            'started_at' => $attempt->started_at,
            'completed_at' => $attempt->completed_at,
        ];
        
        // Include detailed answers if the quiz is completed and set to show answers
        if ($attempt->isCompleted() && $attempt->quiz->show_answers) {
            $answers = [];
            foreach ($attempt->answers as $userAnswer) {
                $answerDetail = [
                    'question_text' => $userAnswer->question->question_text,
                    'is_correct' => $userAnswer->is_correct,
                ];
                
                if ($userAnswer->question->question_type === 'text') {
                    $answerDetail['user_answer'] = $userAnswer->text_answer;
                    
                    // Get the correct answer for text questions
                    $correctAnswer = $userAnswer->question->answers()
                        ->where('is_correct', true)
                        ->first();
                        
                    if ($correctAnswer) {
                        $answerDetail['correct_answer'] = $correctAnswer->answer_text;
                    }
                } else {
                    // For multiple choice and true/false
                    $userSelectedAnswer = $userAnswer->answer;
                    if ($userSelectedAnswer) {
                        $answerDetail['user_answer'] = $userSelectedAnswer->answer_text;
                    }
                    
                    // Get all correct answers
                    $correctAnswers = $userAnswer->question->answers()
                        ->where('is_correct', true)
                        ->get();
                        
                    $answerDetail['correct_answers'] = $correctAnswers->pluck('answer_text');
                }
                
                $answers[] = $answerDetail;
            }
            
            $result['answers'] = $answers;
        }
        
        return response()->json($result);
    }
}
