<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;

class AdminController extends Controller
{
    // Laman Dashboard Admin
    public function dashboard(Request $request)
    {
        // Fetch time frame filters for each card
        $totalProjectsTimeFrame = $request->query('totalProjects_time_frame', 'overall');
        $projectsInProgressTimeFrame = $request->query('projectsInProgress_time_frame', 'overall');
        $completedProjectsTimeFrame = $request->query('completedProjects_time_frame', 'overall');
        $totalUsersTimeFrame = $request->query('totalUsers_time_frame', 'overall');

        // Total Projects
        $totalProjectsQuery = Project::query();
        if ($totalProjectsTimeFrame == 'month') {
            $totalProjectsQuery->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereMonth('start_date', '<=', now()->month)
                    ->whereMonth('end_date', '>=', now()->month);
                });
            });
        } elseif ($totalProjectsTimeFrame == 'year') {
            $totalProjectsQuery->where(function ($query) {
                $query->whereYear('start_date', '<=', now()->year)
                    ->whereYear('end_date', '>=', now()->year);
            });
        }
        $totalProjects = $totalProjectsQuery->count();

        // Projects in Progress
        $projectsInProgressQuery = Project::where('status', 'In Progress');
        if ($projectsInProgressTimeFrame == 'month') {
            $projectsInProgressQuery->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereMonth('start_date', '<=', now()->month)
                    ->whereMonth('end_date', '>=', now()->month);
                });
            });
        } elseif ($projectsInProgressTimeFrame == 'year') {
            $projectsInProgressQuery->where(function ($query) {
                $query->whereYear('start_date', '<=', now()->year)
                    ->whereYear('end_date', '>=', now()->year);
            });
        }
        $projectsInProgress = $projectsInProgressQuery->count();

        // Completed Projects
        $completedProjectsQuery = Project::where('status', 'Completed');
        if ($completedProjectsTimeFrame == 'month') {
            $completedProjectsQuery->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereMonth('start_date', '<=', now()->month)
                    ->whereMonth('end_date', '>=', now()->month);
                });
            });
        } elseif ($completedProjectsTimeFrame == 'year') {
            $completedProjectsQuery->where(function ($query) {
                $query->whereYear('start_date', '<=', now()->year)
                    ->whereYear('end_date', '>=', now()->year);
            });
        }
        $completedProjects = $completedProjectsQuery->count();

        // Total Users (This section is unaffected by the filter)
        $totalUsers = User::count();

        // Upcoming Tasks
        $tasks = Task::with('project', 'assignedTo')
                    ->orderBy('due_date', 'asc')
                    ->get(['name', 'due_date', 'project_id']);

        return view('admin.dashboard', [
            'totalProjects' => $totalProjects,
            'projectsInProgress' => $projectsInProgress,
            'completedProjects' => $completedProjects,
            'totalUsers' => $totalUsers,
            'user' => Auth::user(),
            'tasks' => $tasks,
            'totalProjectsTimeFrame' => $totalProjectsTimeFrame,
            'projectsInProgressTimeFrame' => $projectsInProgressTimeFrame,
            'completedProjectsTimeFrame' => $completedProjectsTimeFrame,
            'totalUsersTimeFrame' => $totalUsersTimeFrame,
        ]);
    }

    // Laman User Management
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Team Member',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Team Member',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}