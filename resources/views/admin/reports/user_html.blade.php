<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report - {{ $report->month }} {{ $report->year }}</title>
    <style>
         @page {
            size: landscape;
            margin: 10mm; /* Adjust this margin as needed */
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5px;
        }

        h3 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 15px;
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
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Style for alternate row shading */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Total users display */
        .total-users {
            text-align: right;
            font-weight: bold,
            margin-top: 20px;
            font-size: 10px;
        }

        /* Print-friendly adjustments */
        @media print {
            body {
                font-size: 10px;
            }

            h3 {
                font-size: 15px;
            }

            th, td {
                padding: 1px;
            }
        }
    </style>
</head>
<body>
    <h3>User Report - {{ $report->month }} {{ $report->year }}</h3>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>ID User</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-users">
        Total Users : {{ $users->count() }}
    </div>
</body>
</html>
