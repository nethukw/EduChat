<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'amount',
        'gateway',
        'payment_intent_id',
        'status'
    ];
}
