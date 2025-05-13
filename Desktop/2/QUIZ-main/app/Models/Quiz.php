<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'created_by',
        'is_public',
        'time_limit',
        'passing_score',
        'difficulty',
        'randomize_questions',
        'show_answers',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'randomize_questions' => 'boolean',
        'show_answers' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the category that owns the quiz.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that created the quiz.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the attempts for the quiz.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Scope a query to only include public quizzes.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)
                    ->whereNotNull('published_at');
    }

    /**
     * Check if the quiz is published.
     */
    public function isPublished()
    {
        return !is_null($this->published_at) && $this->published_at->isPast();
    }
}
