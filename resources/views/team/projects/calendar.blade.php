<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Project timeline with interactive calendar display">
    <title>Project Timeline</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
    <div class="container mt-4">
        <div class="card mb-3">
            <div class="card-header text-primary">Project Timeline</div>
            <div class="card-body">
                <div id="calendar" style="max-width: 100%; margin: 0 auto;"></div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <!-- SweetAlert2 for popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    // Project duration highlighted in light blue
                    {
                        title: '{{ $project->name }}',
                        start: '{{ $project->start_date }}',
                        end: '{{ \Carbon\Carbon::parse($project->end_date)->addDay()->format("Y-m-d") }}',
                        display: 'background',
                        backgroundColor: '#bbd9fa',
                        borderColor: '#bbd9fa',
                        extendedProps: {
                            isTask: false
                        }
                    },

                    // Project tasks
                    @foreach ($project->tasks as $task)
                    {
                        title: '{{ $task->name }}',
                        start: '{{ $task->due_date }}',
                        backgroundColor: '{{ $task->status === 'Completed' ? 'rgba(38, 154, 38, 0.805)' : ($task->status === 'In Progress' ? 'rgba(118, 20, 222, 0.662)' : '#dc3545') }}',
                        borderColor: '#ffffff',
                        textColor: '#ffffff',
                        extendedProps: {
                            isTask: true,
                            description: '{{ $task->description }}',
                            status: '{{ $task->status }}',
                            assignedTo: '{{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}',
                            fileLink: '{{ $task->file_link }}',
                            filePath: '{{ $task->file_path }}',
                            rejectionReason: '{{ $task->rejection_reason }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    if (!info.event.extendedProps.isTask) {
                            return;
                    }
                    
                    info.jsEvent.preventDefault(); 
                    var eventObj = info.event.extendedProps;

                    var content = `
                        <table class="table table-bordered text-start">
                                <tr>
                                    <th><i class="fas fa-tasks me-1 text-primary"></i> Task</th>
                                    <td>${info.event.title}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-info-circle me-1 text-primary"></i> Status</th>
                                    <td>${eventObj.status}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user me-1 text-primary"></i> Assigned To</th>
                                    <td>${eventObj.assignedTo}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-align-left me-1 text-primary"></i> Description</th>
                                    <td>${eventObj.description || 'No description available.'}</td>
                                </tr>
                                ${eventObj.rejectionReason ? `<tr><th><i class="fas fa-times-circle me-1 text-primary"></i> Rejection Reason</th><td>${eventObj.rejectionReason}</td></tr>` : ''}
                        </table>
                    `;

                    Swal.fire({
                            title: 'Task Details',
                            html: content,
                            customClass: {
                                popup: 'swal-wide',
                                confirmButton: 'btn-gray',
                                title: 'text-primary'
                            },
                            confirmButtonText: 'Close'
                    });
                },
                aspectRatio: 1.8,
                height: 'auto',
                editable: true,
                selectable: false
            });
            calendar.render();
        });
    </script>
</body>
</html>