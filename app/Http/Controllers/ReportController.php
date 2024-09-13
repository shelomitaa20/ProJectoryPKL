<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get current month and year
        $month = Carbon::now()->format('F');
        $year = Carbon::now()->format('Y');
        $reportMonth = Carbon::now()->month;

        // Fetch or create the report for the current month and year
        $report = Report::firstOrCreate(
            ['month' => $month, 'year' => $year],
            ['admin_id' => Auth::id(), 'total_projects' => 0, 'total_in_progress' => 0, 'total_completed' => 0, 'total_users' => 0]
        );

        // Count total users
        $totalUsers = User::whereYear('created_at', '<=', $year)
                        ->whereMonth('created_at', '<=', $reportMonth)
                        ->count();

        // Count total projects (with date ranges overlapping the report month)
        $totalProjects = Project::where(function ($query) use ($reportMonth, $year) {
            $query->whereYear('start_date', '<=', $year)
                ->whereMonth('start_date', '<=', $reportMonth)
                ->where(function ($subQuery) use ($reportMonth, $year) {
                    $subQuery->whereYear('end_date', '>=', $year)
                            ->whereMonth('end_date', '>=', $reportMonth)
                            ->orWhereNull('end_date');
                });
        })->count();

        // Count projects in progress
        $projectsInProgress = Project::where('status', 'In Progress')
                                    ->where(function ($query) use ($reportMonth, $year) {
                                        $query->whereYear('start_date', '<=', $year)
                                            ->whereMonth('start_date', '<=', $reportMonth)
                                            ->where(function ($subQuery) use ($reportMonth, $year) {
                                                $subQuery->whereYear('end_date', '>=', $year)
                                                            ->whereMonth('end_date', '>=', $reportMonth)
                                                            ->orWhereNull('end_date');
                                            });
                                    })->count();

        // Count completed projects
        $completedProjects = Project::where('status', 'Completed')
                                    ->where(function ($query) use ($reportMonth, $year) {
                                        $query->whereYear('start_date', '<=', $year)
                                            ->whereMonth('start_date', '<=', $reportMonth)
                                            ->where(function ($subQuery) use ($reportMonth, $year) {
                                                $subQuery->whereYear('end_date', '>=', $year)
                                                        ->whereMonth('end_date', '>=', $reportMonth)
                                                        ->orWhereNull('end_date');
                                            });
                                    })->count();

        // Update the report data
        $report->update([
            'total_projects' => $totalProjects,
            'total_in_progress' => $projectsInProgress,
            'total_completed' => $completedProjects,
            'total_users' => $totalUsers,
        ]);

        // Fetch all reports ordered by year and month
        $reports = Report::orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return view('admin.reports.report', ['reports' => $reports]);
    }
    
    public function printProjectReport($report_id, $format)
    {
        $report = Report::findOrFail($report_id);
        $reportMonth = Carbon::parse($report->month)->month;

        // Fetch projects with date ranges overlapping the report month
        $projects = Project::where(function ($query) use ($reportMonth, $report) {
            $query->whereMonth('start_date', '<=', $reportMonth)
                  ->where(function ($subQuery) use ($reportMonth, $report) {
                      $subQuery->whereMonth('end_date', '>=', $reportMonth)
                               ->orWhereNull('end_date');
                  });
        })
        ->whereYear('start_date', '<=', $report->year)
        ->whereYear('end_date', '>=', $report->year)
        ->with(['owner', 'tasks.assignedTo', 'userProjects.user'])
        ->get();

        if ($format === 'pdf') {
            $html = view('admin.reports.project_html', compact('report', 'projects'))->render();

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($html);

            return $pdf->stream('project_report_' . $report->month . '_' . $report->year . '.pdf');
        } elseif ($format === 'excel') {
            $filename = 'project_report_' . $report->month . '_' . $report->year . '.csv';
            $csvContent = $this->generateProjectCsvContent($projects);

            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        }

        return back();
    }

    private function generateProjectCsvContent($projects)
    {
        $output = "Project ID,Project Name,Project Description,Owner,Collaborators,Task ID,Task Name,Task Description,Task Status,Assigned To,Task Due Date\n";

        foreach ($projects as $project) {
            $owner = $project->owner->name;
            $collaborators = $project->userProjects->where('role', 'Collaborator')->pluck('user.name')->implode(', ');

            foreach ($project->tasks as $task) {
                $assignedToName = $task->assignedTo ? $task->assignedTo->name : 'Unassigned';
                $dueDate = $task->due_date ?: 'No due date';

                $output .= "\"{$project->project_id}\",\"{$project->name}\",\"{$project->description}\",\"{$owner}\",\"{$collaborators}\",\"{$task->task_id}\",\"{$task->name}\",\"{$task->description}\",\"{$task->status}\",\"{$assignedToName}\",\"{$dueDate}\"\n";
            }

            if ($project->tasks->isEmpty()) {
                $output .= "\"{$project->project_id}\",\"{$project->name}\",\"{$project->description}\",\"{$owner}\",\"{$collaborators}\",\"No tasks\",\"\",\"\",\"\",\"\"\n";
            }
        }

        return $output;
    }

    public function printUserReport($report_id, $format)
    {
        $report = Report::findOrFail($report_id);
    
        // Get the month and year of the report
        $reportMonth = Carbon::parse($report->month)->month;
        $reportYear = $report->year;
    
        // Fetch users created up to and including the report month and year
        $users = User::whereYear('created_at', '<=', $reportYear)
                     ->whereMonth('created_at', '<=', $reportMonth)
                     ->get();
    
        if ($format === 'pdf') {
            // Render the HTML content
            $htmlContent = view('admin.reports.user_html', compact('report', 'users'))->render();
    
            // Convert HTML to PDF content
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($htmlContent);
    
            // Return the PDF stream or download it
            return $pdf->stream('user_report_' . $report->month . '_' . $report->year . '.pdf');
        } elseif ($format === 'excel') {
            $filename = 'user_report_' . $report->month . '_' . $report->year . '.csv';
            $csvContent = $this->generateCsvContent($users);
            return response($csvContent, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        }
    
        return back();
    }
    
    private function generateCsvContent($users)
    {
        $output = "Name,Email,Role,Created At\n";
        foreach ($users as $user) {
            $output .= "{$user->name},{$user->email},{$user->role},{$user->created_at}\n";
        }
        return $output;
    }    
}