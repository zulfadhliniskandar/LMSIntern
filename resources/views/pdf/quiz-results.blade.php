<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quiz Results - {{ $quiz->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #1e293b;
        }

        .header p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 11px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 6px;
            font-size: 11px;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .results-table th,
        .results-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }

        .results-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .badge-clean {
            color: #15803d;
            font-weight: bold;
        }

        .badge-warning {
            color: #b91c1c;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Quiz Results Report</h1>
        <p>{{ $subject->code }} - {{ $subject->name }} | Generated on {{ now()->format('M d, Y - h:i A') }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td><strong>Quiz Title:</strong> {{ $quiz->title }}</td>
            <td><strong>Total Questions:</strong> {{ $quiz->total_questions }}</td>
        </tr>
        <tr>
            <td><strong>Time Limit:</strong> {{ $quiz->time_limit ? $quiz->time_limit . ' mins' : 'No Limit' }}</td>
            <td><strong>Max Attempts:</strong> {{ $quiz->max_attempts ?? 1 }}</td>
        </tr>
    </table>

    <table class="results-table">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Integrity Status</th>
                <th>Completed At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attempts as $attempt)
                <tr>
                    <td>{{ $attempt->user->student->student_id ?? 'N/A' }}</td>
                    <td>{{ $attempt->user->name ?? 'User #' . $attempt->user_id }}</td>
                    <td>{{ $attempt->score }} / {{ $attempt->total_questions }}</td>
                    <td>{{ round(($attempt->score / max(1, $attempt->total_questions)) * 100) }}%</td>
                    <td>
                        @if(($attempt->tab_switch_count ?? 0) > 0)
                            <span class="badge-warning">Flagged: {{ $attempt->tab_switch_count }} {{ $attempt->tab_switch_count === 1 ? 'Switch' : 'Switches' }}</span>
                        @else
                            <span class="badge-clean">Clean Attempt</span>
                        @endif
                    </td>
                    <td>{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y - h:i A') : 'In Progress' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>