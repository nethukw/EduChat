@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manage Session ✏️</h1>
        <a href="{{ route('teacher.sessions.index') }}" class="text-gray-500 hover:text-gray-700">Back to List</a>
    </div>

    <div class="grid grid-cols-1 gap-8">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-slate-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2 dark:border-slate-700">Session Details</h3>
            
            <form action="{{ route('teacher.sessions.update', $session->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Session Title</label>
                    <input type="text" name="title" value="{{ $session->title }}" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-primary" required>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">{{ $session->description }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Meeting Link</label>
                    <input type="url" name="meeting_link" value="{{ $session->meeting_link }}" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3" placeholder="https://zoom.us/...">
                </div>

                <div class="grid grid-cols-2 gap-6 mb-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Start Time</label>
                       <input type="datetime-local" name="start_time" value="{{ date('Y-m-d\TH:i', strtotime($session->start_time)) }}" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Duration (Mins)</label>
                        <input type="number" name="duration_minutes" value="{{ $session->duration_minutes }}" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-slate-900/50 p-6 rounded-xl border border-indigo-100 dark:border-slate-700 mb-6">
                    <h3 class="font-bold text-indigo-700 dark:text-indigo-400 mb-4">💰 Payment Settings</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Fee Model</label>
                            <select name="fee_type" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                                <option value="free" {{ $session->fee_type == 'free' ? 'selected' : '' }}>Free Session</option>
                                <option value="shared" {{ $session->fee_type == 'shared' ? 'selected' : '' }}>Shared Fee</option>
                                <option value="per_person" {{ $session->fee_type == 'per_person' ? 'selected' : '' }}>Fixed Fee</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Amount (LKR)</label>
                            <input type="number" name="base_fee" value="{{ $session->base_fee }}" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                    💾 Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-slate-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 border-b pb-2 dark:border-slate-700">
                📚 Class Materials (PDF/Docs)
            </h3>

            <form action="{{ route('teacher.sessions.material.upload', $session->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">File Title</label>
                    <input type="text" name="title" placeholder="e.g. Lecture Slide 01" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-2" required>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Select File</label>
                    <input type="file" name="file" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg p-1.5 text-sm dark:text-gray-300" required>
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition">
                    <i class="fas fa-upload mr-2"></i> Upload
                </button>
            </form>

            <h4 class="text-sm font-bold text-gray-500 uppercase mb-3">Uploaded Files</h4>
            @php
                // Fetch materials directly here for simplicity
                $materials = \DB::table('materials')->where('session_id', $session->id)->get();
            @endphp

            <div class="space-y-3">
                @foreach($materials as $file)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                        <a href="{{ $file->file_path }}" target="_blank" class="text-gray-700 dark:text-gray-300 font-medium hover:text-primary hover:underline">
                            {{ $file->title }}
                        </a>
                    </div>
                    
                    <button class="text-red-500 hover:text-red-700 text-sm font-bold" title="Delete File">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @endforeach

                @if($materials->isEmpty())
                    <p class="text-gray-400 text-sm italic">No materials uploaded yet.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection