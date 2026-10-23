<style>
    body { font-family: sans-serif; }
    .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
    .stat-box { width: 30%; display: inline-block; padding: 10px; background: #f3f4f6; border-radius: 10px; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { bg-color: #4f46e5; color: white; }
</style>

<div class="header">
    <h1>EduChat Performance Summary 📊</h1>
    <p>Period: <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong></p>
    <p style="font-size: 10px; color: gray;">Generated on: {{ $generated_at }}</p>
</div>

<div style="margin-top: 30px;">
    <div class="stat-box">
        <h3>Users</h3>
        <p>Students: {{ $total_students }} <br> Teachers: {{ $total_teachers }}</p>
    </div>
    <div class="stat-box">
        <h3>Activity</h3>
        <p>Total Sessions: {{ $total_sessions }}</p>
    </div>
    <div class="stat-box">
        <h3>Revenue</h3>
        <p>LKR {{ number_format($total_revenue) }}</p>
    </div>
</div>

<h3>Recent Enrollments</h3>
<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Session</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recent_enrollments as $row)
        <tr>
            <td>{{ $row->student }}</td>
            <td>{{ $row->session }}</td>
            <td>LKR {{ number_format($row->paid_amount) }}</td>
            <td>{{ $row->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>