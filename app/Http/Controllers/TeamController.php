<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // Laman Dashboard Team
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Fetch time frame filters for each card
        $totalProjectsTimeFrame = $request->query('totalProjects_time_frame', 'overall');
        $projectsInProgressTimeFrame = $request->query('projectsInProgress_time_frame', 'overall');
        $completedProjectsTimeFrame = $request->query('completedProjects_time_frame', 'overall');
        $totalTasksTimeFrame = $request->query('totalTasks_time_frame', 'overall');

        // Query to get all projects where the user is either an owner or a collaborator
        $projectsQuery = Project::query()->where(function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('userProjects', function ($query) use ($user) {
                  $query->where('user_id', $user->id)
                        ->where('role', 'Collaborator');
              });
        });

        // Total projects with filters
        if ($totalProjectsTimeFrame == 'month') {
            $projectsQuery->where(function ($q) {
                $q->whereMonth('start_date', '<=', now()->month)
                  ->whereMonth('end_date', '>=', now()->month);
            });
        } elseif ($totalProjectsTimeFrame == 'year') {
            $projectsQuery->where(function ($q) {
                $q->whereYear('start_date', '<=', now()->year)
                  ->whereYear('end_date', '>=', now()->year);
            });
        }

        // Execute query and count total projects
        $projects = $projectsQuery->get();
        $totalProjects = $projects->count();

        // Count projects in progress
        $projectsInProgressQuery = $projects->filter(function ($project) use ($projectsInProgressTimeFrame) {
            if ($projectsInProgressTimeFrame == 'month') {
                return $project->status == 'In Progress' &&
                    ($project->start_date->month <= now()->month && $project->end_date->month >= now()->month);
            } elseif ($projectsInProgressTimeFrame == 'year') {
                return $project->status == 'In Progress' &&
                    ($project->start_date->year <= now()->year && $project->end_date->year >= now()->year);
            }
            return $project->status == 'In Progress';
        });
        $projectsInProgress = $projectsInProgressQuery->count();

        // Count completed projects
        $completedProjectsQuery = $projects->filter(function ($project) use ($completedProjectsTimeFrame) {
            if ($completedProjectsTimeFrame == 'month') {
                return $project->status == 'Completed' &&
                    ($project->start_date->month <= now()->month && $project->end_date->month >= now()->month);
            } elseif ($completedProjectsTimeFrame == 'year') {
                return $project->status == 'Completed' &&
                    ($project->start_date->year <= now()->year && $project->end_date->year >= now()->year);
            }
            return $project->status == 'Completed';
        });
        $completedProjects = $completedProjectsQuery->count();

        // Total tasks assigned to the user
        $totalTasksQuery = Task::where('assigned_to', $user->id);
        if ($totalTasksTimeFrame == 'month') {
            $totalTasksQuery->whereMonth('due_date', now()->month);
        } elseif ($totalTasksTimeFrame == 'year') {
            $totalTasksQuery->whereYear('due_date', now()->year);
        }
        $totalTasks = $totalTasksQuery->count();

        // Tasks assigned to the user, sorted by due date (for upcoming tasks section)
        $tasks = $totalTasksQuery->orderBy('due_date', 'asc')->get();

        return view('team.dashboard', [
            'user' => $user,
            'totalProjects' => $totalProjects,
            'projectsInProgress' => $projectsInProgress,
            'completedProjects' => $completedProjects,
            'tasks' => $tasks,
            'totalTasks' => $totalTasks,
            'totalProjectsTimeFrame' => $totalProjectsTimeFrame,
            'projectsInProgressTimeFrame' => $projectsInProgressTimeFrame,
            'completedProjectsTimeFrame' => $completedProjectsTimeFrame,
            'totalTasksTimeFrame' => $totalTasksTimeFrame,
        ]);
    }
}