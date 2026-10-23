<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    protected $fillable = ['title', 'session_id'];

    /**
 * Relationship: Link between Class and Quiz.
 * A single quiz belongs to only one learning session (Class).
 */
    public function session(): BelongsTo
    {
        // Here, we define that this quiz is associated with the 'LearningSession' Model. // The 'session_id' field is used as the Foreign Key for this relationship.
        return $this->belongsTo(LearningSession::class, 'session_id');
    }
}