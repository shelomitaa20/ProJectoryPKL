<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Include Header -->
    @include('layouts.header')

    <!-- Include Sidebar -->
    @include('layouts.sidebar')

    <!-- Main content -->
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Report</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5">Project Reports</h2>
                </div>

                @if($reports->isEmpty())
                <div class="alert alert-primary mt-4" role="alert">
                    No reports available.
                </div>
                @else
                <div class="table-container p-4 rounded">
                    <table id="reportsTable" class="table table-hover mt-5">
                        <thead class="table mt-5">
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Total Projects</th>
                                <th>In Progress</th>
                                <th>Completed</th>
                                <th>Total Users</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->month }}</td>
                                <td>{{ $report->year }}</td>
                                <td>{{ $report->total_projects }}</td>
                                <td>{{ $report->total_in_progress }}</td>
                                <td>{{ $report->total_completed }}</td>
                                <td>{{ $report->total_users }}</td>
                                <td>
                                    <!-- Dropdown User -->
                                    <div class="dropdown mb-2">
                                        <button class="btn btn-blue btn-sm dropdown-toggle" type="button" id="dropdownUser{{ $report->report_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            User Report
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownUser{{ $report->report_id }}">
                                            <li><a class="dropdown-item" href="{{ route('report.printUser', ['report_id' => $report->report_id, 'format' => 'pdf']) }}">PDF</a></li>
                                            <li><a class="dropdown-item" href="{{ route('report.printUser', ['report_id' => $report->report_id, 'format' => 'excel']) }}">Excel</a></li>
                                        </ul>
                                    </div>

                                    <!-- Dropdown Project -->
                                    <div class="dropdown">
                                        <button class="btn btn-gray btn-sm dropdown-toggle" type="button" id="dropdownProject{{ $report->report_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Project Report
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownProject{{ $report->report_id }}">
                                            <li><a class="dropdown-item" href="{{ route('report.printProject', ['report_id' => $report->report_id, 'format' => 'pdf']) }}">PDF</a></li>
                                            <li><a class="dropdown-item" href="{{ route('report.printProject', ['report_id' => $report->report_id, 'format' => 'excel']) }}">Excel</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#reportsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>
</body>

</html>