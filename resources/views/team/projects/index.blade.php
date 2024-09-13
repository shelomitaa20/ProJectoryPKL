<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

</head>

<body>
    <!-- Include Header -->
    @include('layouts.header')

    <!-- Include Sidebar -->
    @include('layouts.sidebar_team')

    <!-- Main content -->
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('team.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Projects</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Success Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mt-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="tab-content mt-4" id="projectTabsContent">
            <!-- All Projects Tab -->
            <div class="tab-pane fade show active" id="all-projects" role="tabpanel" aria-labelledby="all-projects-tab">
                <!-- Projects List -->
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h5">All Projects</h2>
                            <button class="btn btn-blue" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add New Project</button>
                        </div>

                        @if($projects->isEmpty())
                        <div class="badge mt-4">
                            No projects available
                        </div>
                        @else
                        <!-- DataTables Example -->
                        <div class="table-container p-4 rounded">
                            <table id="example" class="table table-hover mt-5">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->description }}</td>
                                        <td>
                                            @if($project->status === 'In Progress')
                                            <span class="badge bg-gray">{{ $project->status }}</span>
                                            @elseif($project->status === 'Completed')
                                            <span class="badge bg-blue">{{ $project->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-blue" onclick="window.location.href='{{ route('team.projects.detail', $project->project_id) }}'">Detail</button>
                                            <button class="btn btn-sm btn-green" data-bs-toggle="modal" data-bs-target="#editProjectModal" 
                                                data-id="{{ $project->project_id }}" 
                                                data-name="{{ $project->name }}" 
                                                data-description="{{ $project->description }}" 
                                                data-start_date="{{ $project->start_date }}" 
                                                data-end_date="{{ $project->end_date }}" 
                                                data-status="{{ $project->status }}">Edit</button>
                                            
                                            <button type="button" class="btn btn-sm btn-red" onclick="confirmDelete('{{ route('team.projects.destroy', $project->project_id) }}')">Delete</button>
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
        </div>
    </div>

   <!-- Add Project Modal -->
    <div class="modal fade @if ($errors->any()) show @endif" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true" @if ($errors->any()) style="display:block;" @endif>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('team.projects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Notification for Collaborator Errors -->
                        @if ($errors->has('collaborators'))
                            <div class="alert alert-danger">
                                @foreach ($errors->get('collaborators') as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="collaborators" class="form-label">Collaborators (Email)</label>
                            <input type="text" class="form-control" id="collaborators" name="collaborators" placeholder="Enter emails separated by commas" value="{{ old('collaborators') }}">
                            <small class="form-text text-muted">Enter valid emails of registered users (optional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-blue">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Update Form Action URL dynamically based on selected project -->
                <form id="editProjectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date">
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date">
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-blue">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- modal tidak tertutup jika masih ada error -->
    <script>
        // Check if there are any errors in the session and open the modal if true
        @if ($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addProjectModal'), {
                keyboard: false
            });
            myModal.show();
        @endif
    </script>
    
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100]
            });

            // Handling data population when edit button is clicked
            $('#editProjectModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var projectId = button.data('id'); // Extract info from data-* attributes
                var name = button.data('name');
                var description = button.data('description');
                var start_date = button.data('start_date');
                var end_date = button.data('end_date');
                var status = button.data('status');

                var modal = $(this);
                modal.find('#edit_name').val(name);
                modal.find('#edit_description').val(description);
                modal.find('#edit_start_date').val(start_date);
                modal.find('#edit_end_date').val(end_date);
                modal.find('#edit_status').val(status);

                // Update form action URL dynamically
                $('#editProjectForm').attr('action', '/team/projects/' + projectId);
            });
        });
    </script>

    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleted data cannot be recovered",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete',
                customClass: {
                    confirmButton: 'btn btn-blue me-3',
                    cancelButton: 'btn btn-gray'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form element dynamically to submit the deletion
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    document.body.appendChild(form);
                    
                    // Add CSRF token and method input
                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);
                    
                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    // Submit the form
                    form.submit();
                }
            })
        }
    </script>

    <!-- Auto-dismiss alert after a few seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let alertInstance = new bootstrap.Alert(alert);
                    alertInstance.close();
                });
            }, 4000); // Alert will dismiss after 5 seconds
        });
    </script>
</body>

</html>