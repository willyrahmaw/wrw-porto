<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Render the main portfolio landing page.
     * Preload model classes before Cache::remember so PHP unserialize() always resolves them cleanly.
     */
    public function index(): View
    {
        class_exists(Profile::class);
        class_exists(Service::class);
        class_exists(Project::class);
        class_exists(Client::class);
        class_exists(Testimonial::class);

        $data = Cache::remember('portfolio.home_data', now()->addMinutes(30), function () {
            $projects = Project::query()
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get();

            return [
                'profile' => Profile::query()->first(),
                'services' => Service::query()->orderBy('sort_order')->get(),
                'projects' => $projects,
                'categories' => $projects->pluck('category')->unique()->values(),
                'clients' => Client::query()->orderBy('sort_order')->get(),
                'testimonials' => Testimonial::query()->where('is_approved', true)->orderBy('sort_order')->limit(20)->get(),
            ];
        });

        return view('home', $data);
    }

    /**
     * Render XML sitemap for SEO.
     */
    public function sitemap()
    {
        $projects = Project::query()->orderBy('sort_order')->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('projects'))
            ->header('Content-Type', 'application/xml');
    }
}
