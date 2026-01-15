<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * 🤖 Handling Chatbot Queries in English
     */
    public function ask(Request $request)
    {
        $userQuery = $request->input('message');
        $user = Auth::user();
        
        // Setting the System Context to strictly English
        $systemPrompt = "You are 'EduBot', the official AI assistant for the EduChat platform. " .
                        "Your goal is to assist students and teachers regarding sessions, XP points, " .
                        "the promotion system (from Student to Instructor at 1000 XP), and forum usage. " .
                        "Current User: " . ($user ? $user->name : 'Guest') . ". " .
                        "Always respond clearly and helpfully in English language only.";

        return response()->json([
            'prompt' => $userQuery,
            'system_instruction' => $systemPrompt
        ]);
    }
}