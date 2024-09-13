<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Project::query();

        // if user is a team member, only show projects where they are the owner or a collaborator
        if ($user->role === 'Team Member') {
            $query->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                  ->orWhereHas('userProjects', function ($query) use ($user) {
                      $query->where('user_id', $user->id);
                  });
            });
        }

        $projects = $query->get();

        $view = ($user->role === 'Admin') ? 'admin.projects.index' : 'team.projects.index';
        return view($view, compact('projects', 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'collaborators' => 'nullable|string',
        ]);

        // Split the collaborators string into an array by commas
        $collaborators = array_map('trim', explode(',', $request->collaborators));
        // Clean collaborators array by filtering out empty values
        $collaborators = array_filter($collaborators);

        // Validate collaborators registered emails
        if (!empty($collaborators)) {
            foreach ($collaborators as $email) {
                if (!User::where('email', $email)->exists()) {
                    $validator->after(function ($validator) use ($email) {
                        $validator->errors()->add('collaborators', "The email $email is not registered.");
                    });
                }
            }
        }
        // Validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the project
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $user->id,
            'status' => 'In Progress', // Default status
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Attach the owner to the project
        $project->users()->attach($user->id, ['role' => 'Owner']);

        // Attach valid collaborators to the project
        if (!empty($collaborators)) {
            $validCollaborators = User::whereIn('email', $collaborators)->get();
            foreach ($validCollaborators as $collaborator) {
                $project->users()->attach($collaborator->id, ['role' => 'Collaborator']);
            }
        }

        // Redirect to the appropriate route based on the user's role
        $route = ($user->role === 'Admin') ? 'admin.projects' : 'team.projects';
        return redirect()->route($route)->with('success', 'Project created successfully.');
    }
    
    public function edit($id)
    {
        $user = Auth::user();
        $project = Project::findOrFail($id);

        $view = ($user->role === 'Admin') ? 'admin.projects.edit' : 'team.projects.edit';
        return view($view, compact('project'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $project = Project::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        $route = ($user->role === 'Admin') ? 'admin.projects' : 'team.projects';
        return redirect()->route($route)->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $project = Project::findOrFail($id);

        $project->delete();

        $route = ($user->role === 'Admin') ? 'admin.projects' : 'team.projects';
        return redirect()->route($route)->with('success', 'Project deleted successfully.');
    }

    public function detail($id)
    {
        $user = Auth::user();
        $project = Project::with(['owner', 'teamMembers', 'tasks.assignedTo'])->findOrFail($id);
        $tasks = $project->tasks;

        $view = ($user->role === 'Admin') ? 'admin.projects.detail' : 'team.projects.detail';
        return view($view, compact('project', 'user', 'tasks'));
    }
}
