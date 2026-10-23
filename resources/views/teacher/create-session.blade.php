@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    
{{-- Page Header: Displays title and dynamic 'Back' navigation based on user role --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Create New Session 📚</h1>
        
        {{-- Logic to redirect Students (Role 0) vs Teachers/Advisors (Role 1+) back to their respective areas --}}
        @if(Auth::user()->role == 0)
            <a href="{{ route('student.sessions') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        @else
            <a href="{{ route('teacher.sessions.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        @endif
    </div>


    {{-- Main Card Container for the Session Creation Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg p-8 border border-gray-100 dark:border-slate-700">
        
    {{-- Form configuration: POST method to store route, includes enctype for file uploads --}}
        <form action="{{ route('teacher.sessions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

           {{-- Input: Session Title --}}
    <div class="mb-5">
        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Session Title</label>
        
        <input type="text" name="title" 
            value="{{ old('title', $preFilledTopic ?? '') }}" 
            class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-primary" 
            placeholder="e.g. Mastering Laravel Relationships" required>
    </div>

            {{-- Input: Subject / Category with a dynamic Datalist for suggestions --}}
            <div class="mb-5">
    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Subject / Category</label>
    
    <input type="text" name="category" list="category-list" 
           class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-primary" 
           placeholder="Type or select a subject (e.g. Science, ICT)" 
           onfocus="this.value=''" onchange="this.blur();"
           required>
    
           {{-- Dynamic Datalist: Populates existing subjects from the database --}}
      <datalist id="category-list">
        @if(isset($existingCategories) && count($existingCategories) > 0)
            @foreach($existingCategories as $cat)
                <option value="{{ $cat }}">
            @endforeach
        @endif
        
        {{-- Default fallback options for common subjects --}}
        <option value="Information Technology">Information Technology</option>
        <option value="Mathematics">Mathematics</option>
        <option value="Science">Science</option>
        <option value="English">English</option>
        <option value="History">History</option>
        <option value="Geography">Geography</option>
        <option value="Physics">Physics</option>
        <option value="Chemistry">Chemistry</option>
        <option value="Biology">Biology</option>
        <option value="Art">Art</option>
        <option value="Music">Music</option>
        <option value="Physical Education">Physical Education</option>
        <option value="Economics">Economics</option>
        <option value="Accounting">Accounting</option>
        <option value="Business Studies">Business Studies</option>
        <option value="Computer Science">Computer Science</option>
        <option value="Software Engineering">Software Engineering</option>
        <option value="Database Management">Database Management</option>
        <option value="Web Development">Web Development</option>
        <option value="Project Management">Project Management</option>
        <option value="Network Engineering">Network Engineering</option>
        <option value="Cyber Security">Cyber Security</option>
        <option value="Artificial Intelligence">Artificial Intelligence</option>
        <option value="Data Science">Data Science</option>
        <option value="Japanese">Japanese</option>
        <option value="French">French</option>
        <option value="German">German</option>
        <option value="Spanish">Spanish</option>
        <option value="Chinese">Chinese</option>
        <option value="Sinhala">Sinhala</option>
        <option value="Tamil">Tamil</option>
    </datalist>
    <p class="text-xs text-gray-400 mt-1">Click to see existing subjects or type a new one!</p>
</div>

{{-- Input: Meeting Link for Zoom or Google Meet integration --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                
                <textarea name="description" rows="4" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-primary" placeholder="What will students learn?" required></textarea>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Meeting Link (Zoom / Google Meet)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-link text-gray-400"></i>
                    </div>
                    <input type="url" name="meeting_link" class="w-full pl-10 border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-primary" placeholder="https://zoom.us/j/123456...">
                </div>
            </div>

            {{-- Layout Grid: Date and Time selection --}}
            <div class="grid grid-cols-2 gap-6 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Date</label>
                    <input type="date" name="session_date" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Time</label>
                    <input type="time" name="session_time" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3" required>
                </div>
            </div>

            {{-- Input: Session Duration in minutes --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Duration (Minutes)</label>
                <input type="number" name="duration" value="60" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
            </div>

            {{-- Business Logic: Check if the user is a Teacher/Advisor (Role >= 1) to enable Payment Settings --}}
            @if(Auth::user()->role >= 1)
                <div class="bg-indigo-50 dark:bg-slate-900/50 p-6 rounded-xl border border-indigo-100 dark:border-slate-700 mb-6">
                    <h3 class="font-bold text-indigo-700 dark:text-indigo-400 mb-4">💰 Payment Settings</h3>
                    <div class="grid grid-cols-2 gap-6">
                        {{-- Dropdown: Selection of Free, Shared, or Fixed payment models --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Fee Model</label>
                            <select name="fee_type" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                                <option value="free">Free Session</option>
                                <option value="shared">Shared Fee</option>
                                <option value="per_person">Fixed Fee</option>
                            </select>
                        </div>
                        {{-- Input: Base currency amount in LKR --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Base Amount (LKR)</label>
                            <input type="number" name="base_fee" value="0" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">*For 'Shared Fee', the Base Amount is the TOTAL cost for the class, divided by the number of students.</p>
                </div>
                {{-- Logic for Students: Enforce 'Free' session model for peer-to-peer learning --}}
            @else
                <input type="hidden" name="fee_type" value="free">
                <input type="hidden" name="base_fee" value="0">
                
                <div class="bg-green-50 text-green-800 p-4 rounded-lg mb-6 text-sm font-bold border border-green-200">
                    <i class="fas fa-info-circle mr-2"></i> As a Student, you can create Free Peer-Learning Sessions.
                </div>
            @endif

            {{-- Optional Section: Uploading PDF/DOC materials for the session --}}
            <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-xl border border-green-100 dark:border-green-800 mb-6">
                <h3 class="font-bold text-green-700 dark:text-green-400 mb-4">📂 Class Material (Optional)</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">File Title</label>
                        <input type="text" name="material_title" placeholder="e.g. Introduction PDF" class="w-full border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-3">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Select File</label>
                        <input type="file" name="material_file" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg p-2 text-sm bg-white dark:bg-slate-700 dark:text-white">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">You can add more files later by editing the session.</p>
            </div>

            {{-- Submit Button: Final publication of the session --}}
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition transform hover:scale-[1.02]">
                🚀 Publish Session
            </button>
        </form>
    </div>
</div>
@endsection