<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display the archive of all projects.
     */
    public function index(): View
    {
        $profile = Profile::query()->first();
        $projects = Project::query()->orderBy('sort_order')->get();
        $categories = $projects->pluck('category')->unique()->values();

        return view('projects.index', compact('profile', 'projects', 'categories'));
    }

    /**
     * Display the case study detail for a single project (route model binding by slug).
     */
    public function show(Project $project): View
    {
        $profile = Profile::query()->first();

        // Next project navigation (cycles back to first project if at the end)
        $nextProject = Project::query()
            ->where('sort_order', '>', $project->sort_order)
            ->orderBy('sort_order')
            ->first()
            ?? Project::query()->where('id', '!=', $project->id)->orderBy('sort_order')->first();

        return view('projects.show', compact('profile', 'project', 'nextProject'));
    }
}

