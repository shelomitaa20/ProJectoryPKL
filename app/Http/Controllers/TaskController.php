<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\UserProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $project = Project::findOrFail($request->project_id);
    
        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after_or_equal:' . $project->start_date . '|before_or_equal:' . $project->end_date,
        ], [
            'due_date.after_or_equal' => 'The due date must not be earlier than the project start date (' . $project->start_date . ').',
            'due_date.before_or_equal' => 'The due date must not be later than the project end date (' . $project->end_date . ').',
        ]);
    
        // Create the task
        Task::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
            'status' => 'To Do',
        ]);
    
        // Redirect based on user role
        $route = ($user->role === 'Admin') ? 'admin.projects.detail' : 'team.projects.detail';
        return redirect()->route($route, ['id' => $request->project_id, 'activeTab' => 'list'])->with('success', 'Task created successfully.');
    }    

    public function progress($id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        $task->update(['status' => 'In Progress']);

        return redirect()->back()->with('success', 'Task status updated to In Progress.')->with('activeTab', 'list');
    }

    public function complete($id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();
    
        // Fetch the user's role for the specific project from the user_projects table
        $userProject = UserProject::where('user_id', $user->id)
            ->where('project_id', $task->project_id)
            ->first();
    
        // Log the role for debugging
        \Log::info("User Role: " . ($userProject ? $userProject->role : 'No role found'));
    
        // If the user is an Admin, complete the task directly
        if ($user->role === 'Admin') {
            $task->update(['status' => 'Completed']);
            return redirect()->back()->with('success', 'Task completed successfully.')->with('activeTab', 'list');
        }

        // If the user is an Owner, complete the task directly
        if ($userProject && $userProject->role === 'Owner') {
            $task->update(['status' => 'Completed']);
            return redirect()->back()->with('success', 'Task completed successfully.')->with('activeTab', 'list');
        }
    
        // If the user is a Collaborator, set the task as pending completion
        if ($userProject && $userProject->role === 'Collaborator') {
            $task->update(['pending_completion' => true]);
            return redirect()->back()->with('success', 'Task completion is pending approval.');
        }
    
        // Handle unauthorized access
        abort(403, 'Unauthorized action.');
    }    

    public function approveCompletion($id)
    {
        $task = Task::findOrFail($id);

        // Ensure the logged-in user is the project owner
        if ($task->project->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->update([
            'status' => 'Completed',
            'pending_completion' => false
        ]);

        return redirect()->back()->with('success', 'Task approved and marked as completed.');
    }

    public function rejectCompletion(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Ensure the logged-in user is the project owner
        if ($task->project->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the task with the reject reason
        $task->update([
            'pending_completion' => false,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->back()->with('success', 'Task completion request rejected.');
    }

    public function cancel(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'pending_completion' => false,
        ]);

        return redirect()->back()->with('success', 'Task completion request canceled.');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.')->with('activeTab', 'list');
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|before_or_equal:' . $task->project->end_date,
        ], [
            'due_date.before_or_equal' => 'The due date must not be later than the project end date (' . $task->project->end_date . ').',
        ]);

        $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_date' => $request->due_date,
        ]);

        // Redirect based on user role
        $route = ($user->role === 'Admin') ? 'admin.projects.detail' : 'team.projects.detail';
        return redirect()->route($route, ['id' => $task->project_id, 'activeTab' => 'list'])->with('success', 'Task updated successfully.');
    }

    public function attachFile(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Handle file upload
        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $path = $file->store('task_files', 'public'); // Save the file to the 'public/task_files' directory
            $task->file_path = $path;
        }

        // Handle file link
        if ($request->input('file_link')) {
            $task->file_link = $request->input('file_link');
        }

        $task->save();

        return redirect()->back()->with('success', 'File/Link attached successfully.');
    }

    private function isAuthorized($project, $user)
    {
        // Admins are always authorized
        if ($user->role === 'Admin') {
            return true;
        }
    
        // Check if the user is part of the project and what their role is
        $userProject = UserProject::where('user_id', $user->id)
            ->where('project_id', $project->project_id)
            ->first();
    
        return $userProject && in_array($userProject->role, ['Owner', 'Collaborator']);
    }
}