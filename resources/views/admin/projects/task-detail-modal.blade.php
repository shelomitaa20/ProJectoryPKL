<!-- Task Detail Modal -->
<div class="modal fade" id="detailTaskModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="detailTaskModalLabel{{ $task->task_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailTaskModalLabel{{ $task->task_id }}">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="task-detail">
                    <div class="detail-item mb-3">
                        <strong>Name</strong>
                        <span class="ms-2">{{ $task->name }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <strong>Description</strong>
                        <span class="ms-2">{{ $task->description }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <strong>Assigned To</strong>
                        <span class="ms-2">{{ $task->assignedTo ? $task->assignedTo->name : 'Unassigned' }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <strong>Due Date</strong>
                        <span class="ms-2">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}</span>
                    </div>
                    <div class="detail-item mb-3">
                        <strong>Status</strong>
                        <span class="badge ms-2 {{ $task->status === 'In Progress' ? 'badge-warning' : ($task->status === 'Completed' ? 'badge-success' : 'badge-primary') }}">{{ $task->status }}</span>
                    </div>
                    @if($task->file_path || $task->file_link)
                        <hr>
                        <div class="detail-item mb-3">
                            <strong>Attachments</strong>
                            <ul class="list-unstyled mt-1 align-items-start">
                                @if($task->file_path)
                                    <li>
                                        <i class="bi bi-file-earmark me-1 mb-2"></i>
                                        <a href="{{ Storage::url($task->file_path) }}" target="_blank" class="ms-1 mb-2 text-decoration-none">Download Attached File</a>
                                    </li>
                                @endif
                                @if($task->file_link)
                                    <li>
                                        <i class="bi bi-link-45deg me-1"></i>
                                        <a href="{{ $task->file_link }}" target="_blank" class="ms-1 text-decoration-none">Visit Attached Link</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                    @if($task->rejection_reason)
                        <hr>
                        <div class="detail-item mb-3">
                            <strong>Rejection Reason</strong>
                            <span>{{ $task->rejection_reason }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tasks.update', $task->task_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $task->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{ $task->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">-- Select a user (optional) --</option>
                            @foreach($project->teamMembers as $member)
                                <option value="{{ $member->id }}" {{ $task->assigned_to == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $task->due_date }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Attach File/Link Modal -->
<div class="modal fade" id="attachFileModal{{ $task->task_id }}" tabindex="-1" aria-labelledby="attachFileModalLabel{{ $task->task_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('tasks.attachFile', $task->task_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="attachFileModalLabel{{ $task->task_id }}">Attach File/Link to Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_upload" class="form-label">Upload File (PDF, Word, etc.)</label>
                        <input type="file" class="form-control" id="file_upload" name="file_upload">
                    </div>
                    <div class="mb-3">
                        <label for="file_link" class="form-label">Or Enter Link</label>
                        <input type="url" class="form-control" id="file_link" name="file_link" placeholder="Enter file link">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-blue">Attach</button>
                </div>
            </form>
        </div>
    </div>
</div>
