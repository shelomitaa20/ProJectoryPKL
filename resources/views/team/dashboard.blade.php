<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Include Header -->
    @include('layouts.header')

    <!-- Include Sidebar -->
    @include('layouts.sidebar_team')

    <!-- Main content -->
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="row mt-3">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('team.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Welcome Box -->
        <div class="welcome-box">
            <h1 class="h4 welcome-box-title">Welcome to your ProJectory Dashboard, {{ Auth::user()->name }}!</h1>
        </div>

        <!-- Profile Card -->
        <div class="row mt-1">
            <div class="col-12">
                <div class="profile-card">
                    <div class="profile-info d-flex align-items-center">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://via.placeholder.com/70' }}" 
                            alt="User" 
                            class="rounded-circle me-3" 
                            width="70" 
                            height="70"
                            style="object-fit: cover;">
                        <div class="details">
                            <h5>{{ Auth::user()->name }}</h5>
                            <div class="row mb-2">
                                <div class="col-3 text-right">
                                    <strong>Email</strong>
                                </div>
                                <div class="col-9">
                                    : {{ Auth::user()->email }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-3 text-right">
                                    <strong>Role</strong>
                                </div>
                                <div class="col-9">
                                    : {{ Auth::user()->role }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('team.profile.edit') }}" class="btn btn-blue">See Details</a>
                </div>
            </div>
        </div>

        <!-- Dashboard content -->
        <div class="row mt-1">
            @php
                $totalProjectsTimeFrame = request()->query('totalProjects_time_frame', 'overall');
                $projectsInProgressTimeFrame = request()->query('projectsInProgress_time_frame', 'overall');
                $completedProjectsTimeFrame = request()->query('completedProjects_time_frame', 'overall');
                $totalTasksTimeFrame = request()->query('totalTasks_time_frame', 'overall');
            @endphp

            <!-- Total Projects Card -->
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="dropdown float-end">
                        <button class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-dashboard p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; box-shadow: none;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="?totalProjects_time_frame=month">This Month</a></li>
                            <li><a class="dropdown-item" href="?totalProjects_time_frame=year">This Year</a></li>
                            <li><a class="dropdown-item" href="?totalProjects_time_frame=overall">Overall</a></li>
                        </ul>
                    </div>
                    <i class="bi bi-bar-chart-line card-icon fs-1 text-primary mb-2"></i>
                    <h5 class="card-title">Total Projects</h5>
                    <p class="card-text fs-4">{{ $totalProjects ?? 0 }}</p>
                    <div class="card-footer text-muted">
                        <span class="text-success">Active and ongoing</span>
                    </div>
                </div>
            </div>

            <!-- Projects in Progress Card -->
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="dropdown float-end">
                        <button class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-dashboard p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; box-shadow: none;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                            <li><a class="dropdown-item" href="?projectsInProgress_time_frame=month">This Month</a></li>
                            <li><a class="dropdown-item" href="?projectsInProgress_time_frame=year">This Year</a></li>
                            <li><a class="dropdown-item" href="?projectsInProgress_time_frame=overall">Overall</a></li>
                        </ul>
                    </div>
                    <i class="bi bi-gear-fill card-icon fs-1 text-warning mb-2"></i>
                    <h5 class="card-title">Projects in Progress</h5>
                    <p class="card-text fs-4">{{ $projectsInProgress ?? 0 }}</p>
                    <div class="card-footer text-muted">
                        <span class="text-warning">Under development</span>
                    </div>
                </div>
            </div>

            <!-- Completed Projects Card -->
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="dropdown float-end">
                        <button class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-dashboard p-0" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; box-shadow: none;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                            <li><a class="dropdown-item" href="?completedProjects_time_frame=month">This Month</a></li>
                            <li><a class="dropdown-item" href="?completedProjects_time_frame=year">This Year</a></li>
                            <li><a class="dropdown-item" href="?completedProjects_time_frame=overall">Overall</a></li>
                        </ul>
                    </div>
                    <i class="bi bi-check-circle-fill card-icon fs-1 text-success mb-2"></i>
                    <h5 class="card-title">Completed Projects</h5>
                    <p class="card-text fs-4">{{ $completedProjects ?? 0 }}</p>
                    <div class="card-footer text-muted">
                        <span class="text-success">Successfully completed</span>
                    </div>
                </div>
            </div>

            <!-- Total Tasks Card -->
            <div class="col-md-3">
                <div class="card-custom text-center">
                    <div class="dropdown float-end">
                        <button class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-dashboard p-0" type="button" id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false" style="border: none; box-shadow: none;">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                            <li><a class="dropdown-item" href="?totalTasks_time_frame=month">This Month</a></li>
                            <li><a class="dropdown-item" href="?totalTasks_time_frame=year">This Year</a></li>
                            <li><a class="dropdown-item" href="?totalTasks_time_frame=overall">Overall</a></li>
                        </ul>
                    </div>
                    <i class="bi bi-people-fill card-icon fs-1 text-info mb-2"></i>
                    <h5 class="card-title">Total Tasks</h5>
                    <p class="card-text fs-4">{{ $totalTasks ?? 0 }}</p>
                    <div class="card-footer text-muted">
                        <span class="text-info">Assigned tasks</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-1">
            <!-- calendar -->
            <div class="col-md-7 container">
                <div class="card card-custom p-4" style="background-color: #f9f9f9; border-radius: 8px;">
                    <div id="calendar"></div>
                </div>
            </div>

            <!-- task card -->
            <div class="col-md-5 container">
                <div class="card card-custom p-4">
                    <h5 class="mb-4 fw-bold text-center fs-5">Upcoming Tasks</h5>

                    @php
                        $filteredTasks = $tasks->sortBy('due_date')->take(6);
                    @endphp

                    @if($filteredTasks->isEmpty())
                        <p class="text-center text-muted">No upcoming tasks</p>
                    @else
                        @foreach ($filteredTasks as $task)
                        <div class="task-item d-flex justify-content-between align-items-center mb-3" style="background-color: {{ $loop->index % 2 == 0 ? 'rgba(37, 116, 252, 0.893)' : 'rgba(92, 92, 92, 0.818)' }}; border-radius: 15px; padding: 10px;">
                            <div>
                                <h6 class="text-white">{{ $task->name }}</h6>  
                                <p class="text-white">Due date: {{ \Carbon\Carbon::parse($task->due_date)->format('d F Y') }}</p>
                            </div>
                            <a href="{{ route('team.projects.detail', $task->project_id) }}" class="btn btn-light">Details</a>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar and jQuery Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach ($tasks as $task)
                    {
                        title: '{{ $task->name }}',
                        start: '{{ $task->due_date }}',
                        url: '{{ route('team.projects.detail', $task->project_id) }}', // Direct link to project detail page
                        description: 'Project ID: {{ $task->project_id }}'
                    },
                    @endforeach
                ],
                headerToolbar: {
                    left: 'prev,next today', 
                    center: 'title',
                    right: 'dayGridMonth'
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>

</html>
