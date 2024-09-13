<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Report - {{ $report->month }} {{ $report->year }}</title>
    <style>
        @page {
            size: landscape;
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .report-title {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .total-projects {
            font-weight: bold;
            margin-bottom: 10px;
            text-align: left;
        }

        /* Column width adjustments */
        th:nth-child(3), td:nth-child(3) {
            width: 20%; /* Project Description */
        }

        th:nth-child(8), td:nth-child(8) {
            width: 20%; /* Task Description */
        }

        th:nth-child(11), td:nth-child(11) {
            width: 7%; /* Due to */
        }

        /* Ensure long tables span across pages */
        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
    </style>
</head>
<body>

    <div class="report-title">
        Project Report - {{ $report->month }} {{ $report->year }}
    </div>

    <div class="total-projects">Total Projects: {{ $projects->count() }}</div>

    @if($projects->isEmpty())
        <p>No projects available.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Project Description</th>
                <th>Owner</th>
                <th>Collaborators</th>
                <th>ID</th>
                <th>Task Name</th>
                <th>Task Description</th>
                <th>Task Status</th>
                <th>Assigned To</th>
                <th>Task Due Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                @if($project->tasks->isEmpty())
                    <tr>
                        <td>{{ $project->project_id}}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->owner->name }}</td>
                        <td>{{ $project->userProjects->where('role', 'Collaborator')->pluck('user.name')->implode(', ') }}</td>
                        <td colspan="5">No tasks assigned to this project.</td>
                    </tr>
                @else
                    @foreach($project->tasks as $task)
                    <tr>
                        <td>{{ $project->project_id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->owner->name }}</td>
                        <td>{{ $project->userProjects->where('role', 'Collaborator')->pluck('user.name')->implode(', ') }}</td>
                        <td>{{ $task->task_id}}</td>
                        <td>{{ $task->name ?? 'No tasks' }}</td>
                        <td>{{ $task->description ?? '-' }}</td>
                        <td>{{ $task->status ?? '-' }}</td>
                        <td>{{ $task->assignedTo->name ?? 'Unassigned' }}</td>
                        <td>{{ $task->due_date ?? 'No due date' }}</td>
                    </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
    @endif

</body>
</html>