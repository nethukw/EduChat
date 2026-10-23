@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">System Overview 📊</h1>
            <p class="text-gray-500 dark:text-gray-400">Real-time statistics and reports.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-lg font-bold shadow-lg transition flex items-center gap-2">
            <i class="fas fa-users-cog"></i> Manage Users
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Total Users</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalUsers }}</div>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Total Revenue</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">LKR {{ number_format($totalRevenue) }}</div>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-purple-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Sessions</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalSessions }}</div>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-chalkboard"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-orange-500 flex items-center justify-between">
            <div>
                <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wide">Teachers</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalTeachers }}</div>
            </div>
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xl">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-6">User Roles Distribution</h3>
            <div class="relative h-64">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-6">Session Status Overview</h3>
            <div class="relative h-64">
                <canvas id="sessionsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 dark:text-white">Newest Members</h3>
            <a href="{{ route('admin.users') }}" class="text-primary text-sm font-bold hover:underline">View All</a>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Joined Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                @foreach($recentUsers as $user)
                <tr>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.$user->name }}" class="w-8 h-8 rounded-full">
                        <div>
                            <p class="font-bold text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role == 0) <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">Student</span>
                        @elseif($user->role == 1) <span class="bg-indigo-100 text-indigo-600 px-2 py-1 rounded text-xs font-bold">Teacher</span>
                        @elseif($user->role == 2) <span class="bg-purple-100 text-purple-600 px-2 py-1 rounded text-xs font-bold">Advisor</span>
                        @elseif($user->role == 3) <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-bold">Admin</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 mb-8">
    <h3 class="font-bold text-gray-800 dark:text-white mb-4">Generate Periodical Report 📅</h3>
    
    <form action="{{ route('admin.reports.generate') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">From Date</label>
            <input type="date" name="from_date" class="border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-2.5 text-sm" required>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 uppercase mb-2">To Date</label>
            <input type="date" name="to_date" class="border-gray-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-lg p-2.5 text-sm" required>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </form>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. User Roles Chart (Doughnut)
    const ctx1 = document.getElementById('rolesChart');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Teachers', 'Advisors', 'Admins'],
            datasets: [{
                data: [{{ $studentCount }}, {{ $teacherCount }}, {{ $advisorCount }}, {{ $adminCount }}],
                backgroundColor: ['#6366f1', '#f97316', '#a855f7', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 2. Sessions Status Chart (Bar)
    const ctx2 = document.getElementById('sessionsChart');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                label: 'Sessions',
                data: [{{ $approvedSessions }}, {{ $pendingSessions }}, {{ $rejectedSessions }}],
                backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection