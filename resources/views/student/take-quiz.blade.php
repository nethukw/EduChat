@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
        <p class="text-gray-500 mb-6">Answer all questions carefully.</p>

        <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @foreach($questions as $index => $q)
            <div class="mb-8 border-b border-gray-100 pb-6">
                <p class="font-bold text-lg text-gray-800 mb-4">{{ $index + 1 }}. {{ $q->question }}</p>
                
                <div class="space-y-3">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="q_{{ $q->id }}" value="A" class="form-radio text-indigo-600 h-5 w-5" required>
                        <span class="ml-3 text-gray-700">A) {{ $q->option_a }}</span>
                    </label>
                    
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="q_{{ $q->id }}" value="B" class="form-radio text-indigo-600 h-5 w-5">
                        <span class="ml-3 text-gray-700">B) {{ $q->option_b }}</span>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="q_{{ $q->id }}" value="C" class="form-radio text-indigo-600 h-5 w-5">
                        <span class="ml-3 text-gray-700">C) {{ $q->option_c }}</span>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="radio" name="q_{{ $q->id }}" value="D" class="form-radio text-indigo-600 h-5 w-5">
                        <span class="ml-3 text-gray-700">D) {{ $q->option_d }}</span>
                    </label>
                </div>
            </div>
            @endforeach

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 shadow-lg transition">
                Submit Answers
            </button>
        </form>
    </div>
</div>
@endsection