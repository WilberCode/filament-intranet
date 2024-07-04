<!-- resources/views/exports/timesheets.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        thead td{
            background-color: #DADADA;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <td>User</td>
                <td>Calendar</td>
                <td>Type</td>
                <td>Day In</td>
                <td>Day Out</td>
                <td>Created At</td>
                <td>Updated At</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($timesheets as $timesheet)
                <tr>
                    <td>{{ $timesheet->user->name }}</td>
                    <td>{{ $timesheet->calendar->name }}</td>
                    <td>{{ $timesheet->type }}</td>
                    <td>{{ $timesheet->day_in }}</td>
                    <td>{{ $timesheet->day_out }}</td>
                    <td>{{ $timesheet->created_at }}</td>
                    <td>{{ $timesheet->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
