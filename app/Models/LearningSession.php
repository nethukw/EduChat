<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningSession extends Model
{
    use HasFactory, SoftDeletes;

    // Mapping to the correct table name
    protected $table = 'learning_sessions';

    // Allowing mass assignment for all fields
    protected $guarded = [];

    /**
     * Relationship: The teacher who created the session.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relationship: Students enrolled in this session.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'session_id');
    }

    /**
     * Relationship: Feedback and ratings from students.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'session_id');
 
 
        }
        

    
   

    
}