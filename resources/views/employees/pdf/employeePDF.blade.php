<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.css') }}">
    <link href="public\css\shifthub.css" rel="stylesheet">
    <title>Employees</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            height: 25px;
            background-color: #333;
            color: white;
            font-size: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2%;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            text-align: center;
            height: 55px;
        }

        tbody td {
            padding: 7px;
        }
    </style>
</head>

<body>

    <h3> Emplyees List </h3> <br>
    <table class="table table-hover table-condensed">
        <thead>
            <tr style="text-align: center">
                <th style="width: 20px">No</th>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>NRC</th>
                <th>Career</th>
                <th>Level</th>
                <th>BirthDate</th>
                <th>Address</th>
            </tr>
        </thead>

        @php
            $count = 1;
        @endphp
        <tbody>
            @foreach ($employees as $employee)
                <tr class="small-row">
                    <td>{{ $count++ }}</td>
                    <td>{{ $employee->employee_id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->nrc }}</td>

                    <td>{{ $employee->career }}</td>

                    <td>{{ $employee->level }}</td>

                    <td>{{ $employee->dateOfBirth }}</td>
                    <td>{{ $employee->address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
