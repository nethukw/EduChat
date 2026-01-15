<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'body', 'subject_tag', 'views'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function likes() {
        // Assuming 'post_likes' table links to 'post_id' as question_id
        return $this->hasMany(PostLike::class, 'post_id'); 
    }
}