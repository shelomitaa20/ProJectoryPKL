<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                        <li class="breadcrumb-item"><a href="{{ route('team.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('team.projects') }}">Projects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Projects</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Project Tabs -->
        <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ session('activeTab', 'description') === 'description' ? 'active' : '' }}" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Project Description</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ session('activeTab', 'description') === 'list' ? 'active' : '' }}" id="list-tab" data-bs-toggle="tab" data-bs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="false">Project List</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ session('activeTab', 'description') === 'timeline' ? 'active' : '' }}" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="false">Timeline</button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="myTabContent">
            <!-- Project Description Tab -->
            <div class="tab-pane fade {{ session('activeTab', 'description') === 'description' ? 'show active' : '' }}" id="description" role="tabpanel" aria-labelledby="description-tab">
                <div class="card card-description-tab mb-3">
                    <div class="card-header text-primary">{{ $project->name }} Description</div>
                    <div class="card-body text-primary">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th scope="row">Owner</th>
                                    <td>
                                        <span class="badge bg-gray text-white">{{ $project->owner->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Team Members</th>
                                    <td>
                                        @if($project->teamMembers->isEmpty())
                                            <span>No team members assigned.</span>
                                        @else
                                            @foreach($project->teamMembers as $member)
                                                <span class="badge bg-gray text-white mb-2">{{ $member->name }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Description</th>
                                    <td>{{ $project->description }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td>
                                        @if($project->status === 'In Progress')
                                        <span class="badge bg-gray">{{ $project->status }}</span>
                                        @elseif($project->status === 'Completed')
                                        <span class="badge bg-blue">{{ $project->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Start Date</th>
                                    <td>{{ $project->start_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">End Date</th>
                                    <td>{{ $project->end_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Project List Tab -->
            <div class="tab-pane fade {{ session('activeTab', 'description') === 'list' ? 'show active' : '' }}" id="list" role="tabpanel" aria-labelledby="list-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold text-primary">{{ $project->name }} Tasks List</h5>
                    <button class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add New Task</button>
                </div>

            <!-- To Do Tasks -->
            <h4 class="task-heading">
                <span class="badge bg-blue">To Do Tasks</span>
            </h4>
            <div class="table-container">
                @php
                    $todoTasks = $tasks->where('status', 'To Do')->sortBy(function($task) {
                        return $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                    });
                @endphp
                @if($todoTasks->isEmpty())
                    <p class="text-muted">No tasks to do</p>
                @else
                    <table class="table table-hover custom-table">
                        <tbody>
                            @foreach($todoTasks as $task)
                                @php
                                    $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                    $isOverdue = $dueDate && $dueDate->isPast();
                                @endphp
                                <tr class="{{ $isOverdue ? 'text-danger' : '' }}">
                                    <td><i class="fas fa-tasks icon-small"></i> {{ $task->name }}</td>
                                    <td><i class="fas fa-user icon-small"></i> {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</td>
                                    <td><i class="fas fa-calendar icon-small"></i> 
                                        {{ $dueDate ? $dueDate->format('d M Y') : 'No due date' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-gray dropdown-toggle action-button" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <form action="{{ route('tasks.progress', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-arrow-right-circle me-1 text-primary"></i> Progress
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailTaskModal{{ $task->task_id }}">
                                                        <i class="bi bi-info-circle me-1"></i> Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item edit-task-btn" href="#" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTaskModal{{ $task->task_id }}" 
                                                    data-task-id="{{ $task->task_id }}"
                                                    data-task-name="{{ $task->name }}"
                                                    data-task-description="{{ $task->description }}"
                                                    data-task-assigned-to="{{ $task->assigned_to }}"
                                                    data-task-due-date="{{ $task->due_date }}">
                                                    <i class="bi bi-pencil-square me-1 text-success"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item delete-task-btn" data-task-name="{{ $task->name }}">
                                                            <i class="bi bi-trash me-1 text-danger"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @include('admin.projects.task-detail-modal', ['task' => $task])
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- In Progress Tasks -->
            <h4 class="task-heading">
                <span class="badge bg-purple">In Progress Tasks</span>
            </h4>
            <div class="table-container">
            @php
                $inProgressTasks = $tasks->where('status', 'In Progress')
                                        ->where('pending_completion', false)
                                        ->sortBy(function($task) {
                                            return $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                        });
            @endphp
                @if($inProgressTasks->isEmpty())
                    <p class="text-muted">No tasks in progress</p>
                @else
                    <table class="table table-hover custom-table">
                        <tbody>
                            @foreach($inProgressTasks as $task)
                                @php
                                    $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                    $isOverdue = $dueDate && $dueDate->isPast();
                                @endphp
                                <tr class="{{ $isOverdue ? 'text-danger' : '' }}">
                                    <td><i class="fas fa-spinner icon-small"></i> {{ $task->name }}</td>
                                    <td><i class="fas fa-user icon-small"></i> {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</td>
                                    <td><i class="fas fa-calendar icon-small"></i> 
                                        {{ $dueDate ? $dueDate->format('d M Y') : 'No due date' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-gray dropdown-toggle action-button" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <form action="{{ route('tasks.complete', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-check-circle me-1 text-primary"></i> Complete
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailTaskModal{{ $task->task_id }}">
                                                        <i class="bi bi-info-circle me-1"></i> Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <!-- Attach File/Link -->
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#attachFileModal{{ $task->task_id }}">
                                                        <i class="bi bi-paperclip me-1 text-primary"></i> Attach File/Link
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item edit-task-btn" href="#" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTaskModal{{ $task->task_id }}" 
                                                    data-task-id="{{ $task->task_id }}"
                                                    data-task-name="{{ $task->name }}"
                                                    data-task-description="{{ $task->description }}"
                                                    data-task-assigned-to="{{ $task->assigned_to }}"
                                                    data-task-due-date="{{ $task->due_date }}">
                                                    <i class="bi bi-pencil-square me-1 text-success"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item delete-task-btn" data-task-name="{{ $task->name }}">
                                                            <i class="bi bi-trash me-1 text-danger"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @include('admin.projects.task-detail-modal', ['task' => $task])
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Pending Completion Tasks -->
            <h4 class="task-heading">
                <span class="badge bg-gray">Pending Completion Tasks</span>
            </h4>
            <div class="table-container">
                @php
                    $pendingCompletionTasks = $tasks->where('pending_completion', true)
                                                    ->sortBy(function($task) {
                                                        return $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                                    });
                @endphp
                @if($pendingCompletionTasks->isEmpty())
                    <p class="text-muted">No tasks pending completion approval</p>
                @else
                    <table class="table table-hover custom-table">
                        <tbody>
                            @foreach($pendingCompletionTasks as $task)
                                @php
                                    $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast();
                                    $userRole = Auth::user()->role; // User's role from users table
                                    $projectRole = $task->project->userProjects->where('user_id', Auth::id())->first()->role ?? null; // Project-specific role
                                @endphp
                                <tr class="{{ $isOverdue ? 'overdue-task' : '' }}">
                                    <td><i class="fas fa-hourglass icon-small"></i> {{ $task->name }}</td>
                                    <td><i class="fas fa-user icon-small"></i> {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</td>
                                    <td><i class="fas fa-calendar icon-small"></i> 
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-gray dropdown-toggle action-button" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <!-- Detail Task -->
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailTaskModal{{ $task->task_id }}">
                                                        <i class="bi bi-info-circle me-1"></i> Detail
                                                    </a>
                                                </li>

                                                <!-- Cancel Task (Removes Pending Status) -->
                                                <li>
                                                    <form action="{{ route('tasks.cancel', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="pending_completion" value="0">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="bi bi-arrow-left-square me-1 text-danger"></i> Cancel
                                                        </button>
                                                    </form>
                                                </li>

                                                <!-- Approve Task (Only for Admin/Owner) -->
                                                @if($userRole === 'Admin' || $projectRole === 'Owner')
                                                    <li>
                                                        <form action="{{ route('tasks.approve', $task->task_id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="bi bi-check-circle me-1 text-success"></i> Approve
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif

                                                <!-- Reject Task (Only for Admin/Owner) -->
                                                @if($userRole === 'Admin' || $projectRole === 'Owner')
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rejectTaskModal{{ $task->task_id }}">
                                                            <i class="bi bi-x-circle me-1 text-danger"></i> Reject
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Detail Modal -->
                                @include('admin.projects.task-detail-modal', ['task' => $task])

                                <!-- Reject Task Modal -->
                                <div class="modal fade" id="rejectTaskModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="rejectTaskModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('tasks.reject', $task->task_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectTaskModalLabel">Reject Task Completion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="rejectReason class=mb-2">Rejection Reason</label>
                                                        <textarea name="rejection_reason" id="rejectReason" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-red">Submit Rejection</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Completed Tasks -->
            <h4 class="task-heading">
                <span class="badge bg-green">Completed Tasks</span>
            </h4>
            <div class="table-container">
                @php
                    $completedTasks = $tasks->where('status', 'Completed')
                                            ->sortBy(function($task) {
                                                return $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
                                            });
                @endphp
                @if($completedTasks->isEmpty())
                    <p class="text-muted">No completed tasks</p>
                @else
                    <table class="table table-hover custom-table">
                        <tbody>
                            @foreach($completedTasks as $task)
                                <tr>
                                    <td><i class="fas fa-check-circle icon-small"></i> {{ $task->name }}</td>
                                    <td><i class="fas fa-user icon-small"></i> {{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</td>
                                    <td><i class="fas fa-calendar icon-small"></i> 
                                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-gray dropdown-toggle action-button" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailTaskModal{{ $task->task_id }}">
                                                        <i class="bi bi-info-circle me-1"></i> Detail
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item edit-task-btn" href="#" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTaskModal{{ $task->task_id }}" 
                                                    data-task-id="{{ $task->task_id }}"
                                                    data-task-name="{{ $task->name }}"
                                                    data-task-description="{{ $task->description }}"
                                                    data-task-assigned-to="{{ $task->assigned_to }}"
                                                    data-task-due-date="{{ $task->due_date }}">
                                                    <i class="bi bi-pencil-square me-1 text-success"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('tasks.destroy', $task->task_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item delete-task-btn" data-task-name="{{ $task->name }}">
                                                            <i class="bi bi-trash me-1 text-danger"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @include('admin.projects.task-detail-modal', ['task' => $task])
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        
            </div>

            <!-- Timeline Tab -->
            <div class="tab-pane fade {{ session('activeTab', 'description') === 'timeline' ? 'show active' : '' }}" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                @include('admin.projects.calendar')
            </div> 
        </div>

            <!-- Add Task Modal -->
            <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('tasks.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->project_id }}">
                                <input type="hidden" id="activeTabInput" name="activeTab" value="{{ session('activeTab', 'list') }}"> <!-- Retain active tab -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Task Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Assign To</label>
                                    <select class="form-select" id="assigned_to" name="assigned_to">
                                        <option value="">-- Select a user (optional) --</option>
                                        @foreach($project->teamMembers as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date">
                                </div>
                                <button type="submit" class="btn btn-blue">Save Task</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Active Tab Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Store current active tab in session storage
            var activeTab = sessionStorage.getItem('activeTab') || "{{ session('activeTab', 'description') }}";
            
            // Show active tab on page load
            var myTab = new bootstrap.Tab(document.getElementById(activeTab + '-tab'));
            myTab.show();

            // Track tab changes and update session storage
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function (button) {
                button.addEventListener('shown.bs.tab', function (event) {
                    var activeTabId = event.target.id.replace('-tab', '');
                    sessionStorage.setItem('activeTab', activeTabId);

                    // Update hidden input in the form to track current tab
                    document.querySelector('#activeTabInput').value = activeTabId;
                });
            });

            // Validation for due date and end date during task submission
            const projectEndDate = "{{ $project->end_date }}";

            document.querySelector('#addTaskModal form').addEventListener('submit', function (event) {
                const dueDateInput = document.querySelector('#due_date');
                const dueDate = dueDateInput.value;

                if (dueDate && new Date(dueDate) > new Date(projectEndDate)) {
                    event.preventDefault(); // Prevent form submission
                    
                    // Show error message in the modal
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Due Date',
                        text: 'The due date cannot be later than the project end date (' + projectEndDate + ')',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    });

                    return false; // Block form submission
                }
            });

            // Remove SweetAlert for Progress/Complete actions
            document.querySelectorAll('form[action*="progress"], form[action*="complete"]').forEach(function (form) {
                form.querySelector('button[type="submit"]').addEventListener('click', function () {
                    form.submit(); // Simply submit the form without SweetAlert
                });
            });

            // SweetAlert for delete confirmation
            document.querySelectorAll('.delete-task-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    var form = this.closest('form');
                    var taskName = this.getAttribute('data-task-name');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to delete the task: " + taskName,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'btn btn-primary me-2',
                            cancelButton: 'btn btn-gray'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit form if confirmed
                        }
                    });
                });
            });
        });
    </script>

    <!-- Event listener Edit Task Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Event listener for edit task modal show event
            document.querySelectorAll('.edit-task-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    // Get the task data from data attributes
                    const taskId = this.getAttribute('data-task-id');
                    const taskName = this.getAttribute('data-task-name');
                    const taskDescription = this.getAttribute('data-task-description');
                    const taskAssignedTo = this.getAttribute('data-task-assigned-to');
                    const taskDueDate = this.getAttribute('data-task-due-date');

                    // Find the modal fields and populate them
                    const modal = document.querySelector('#editTaskModal' + taskId);
                    modal.querySelector('#name').value = taskName;
                    modal.querySelector('#description').value = taskDescription;
                    modal.querySelector('#assigned_to').value = taskAssignedTo;
                    modal.querySelector('#due_date').value = taskDueDate;
                });
            });
        });
    </script>

</body>

</html>