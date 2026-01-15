@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header Section -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Request a Topic 💡</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-2">Can't find what you are looking for? Request a session topic, and our teachers will create it for you.</p>
    </div>

    <!-- Request Form Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-8 border border-gray-100 dark:border-slate-700 mb-10">
        <form action="{{ route('student.requests.store') }}" method="POST">
            @csrf

            <!-- 1. Subject Selection Chips -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">1. Select Subject</label>
                <div class="flex flex-wrap gap-3">
                    {{-- ✅ Added a check to prevent undefined variable error --}}
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $cat)
                            <label class="relative">
                                <input type="radio" name="subject" value="{{ $cat }}" class="peer sr-only" required>
                                <div class="px-4 py-2 text-sm font-semibold border rounded-full cursor-pointer transition 
                                    border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700
                                    peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 
                                    dark:peer-checked:bg-indigo-900/30 dark:peer-checked:text-indigo-400">
                                    {{ $cat }}
                                </div>
                            </label>
                        @endforeach
                    @else
                        {{-- Fallback subjects if database is empty --}}
                        @foreach(['General', 'IT', 'Mathematics', 'Science'] as $defaultCat)
                            <label class="relative">
                                <input type="radio" name="subject" value="{{ $defaultCat }}" class="peer sr-only" required>
                                <div class="px-4 py-2 text-sm font-semibold border rounded-full cursor-pointer transition border-gray-200 hover:bg-gray-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600">
                                    {{ $defaultCat }}
                                </div>
                            </label>
                        @endforeach
                    @endif
                    
                    <label class="relative">
                        <input type="radio" name="subject" value="Other" class="peer sr-only">
                        <div class="px-4 py-2 text-sm font-semibold border border-dashed rounded-full cursor-pointer transition 
                            border-gray-300 dark:border-slate-600 hover:bg-gray-50
                            peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600">
                            Other
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. Topic Input -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">2. What topic do you want to learn?</label>
                <input type="text" name="topic" placeholder="e.g. Advanced Python for Data Science" class="w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition" required>
            </div>

            <!-- 3. Description -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">3. Any specific details? (Optional)</label>
                <textarea name="description" rows="3" class="w-full border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 transition" placeholder="Tell teachers what exactly you need to cover..."></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 dark:shadow-none transform hover:-translate-y-0.5">
                <i class="fas fa-paper-plane mr-2"></i> Submit Request
            </button>
        </form>
    </div>

    <!-- Request History Table -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Your Request History</h3>
        <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">{{ $myRequests->count() }} Total Requests</span>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-900/50 text-gray-500 dark:text-gray-400 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 font-bold">Subject & Topic</th>
                    <th class="px-6 py-4 font-bold text-center">Requested Date</th>
                    <th class="px-6 py-4 font-bold text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($myRequests as $req)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase mb-1">{{ $req->subject ?? 'General' }}</div>
                        <div class="font-bold text-gray-900 dark:text-white">{{ $req->topic }}</div>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($req->created_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        @php
                            $status = $req->status ?? 'open';
                        @endphp
                        @if($status == 'open')
                            <span class="bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Pending</span>
                        @elseif($status == 'approved')
                            <span class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Scheduled</span>
                        @else
                            <span class="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Closed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-gray-400 italic">
                        <i class="fas fa-inbox block text-3xl mb-2 opacity-20"></i>
                        You haven't made any requests yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection