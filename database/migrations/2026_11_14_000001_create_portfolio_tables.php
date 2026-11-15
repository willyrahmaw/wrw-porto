<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profile / Settings Table
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('initials', 10)->default('WRW');
            $table->string('profession');
            $table->string('tagline');
            $table->text('subheadline')->nullable();
            $table->text('bio');
            $table->string('location');
            $table->string('availability_status')->default('Open for freelance & select full-time roles');
            $table->boolean('is_available')->default(true);
            $table->string('photo')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('email');
            $table->string('whatsapp');
            $table->string('linkedin')->nullable();
            $table->string('instagram')->nullable();
            $table->string('github')->nullable();
            $table->string('behance')->nullable();
            $table->string('dribbble')->nullable();
            $table->json('stats')->nullable(); // e.g., Years Exp, Projects Shipped, Clients, Industries
            $table->json('skills')->nullable(); // Grouped or flat skills/tools
            $table->integer('trusted_clients_count')->default(35);
            $table->integer('trusted_industries_count')->default(12);
            $table->timestamps();
        });

        // 2. Services Table
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('number', 10); // e.g., 01, 02
            $table->string('title');
            $table->text('description');
            $table->json('deliverables');
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category'); // e.g. Aplikasi Web, Company Profile, Toko Online
            $table->string('year', 4);
            $table->string('thumbnail')->nullable();
            $table->text('description');
            $table->json('tech_stack')->nullable(); // Tech stack tags (Laravel, Vue, Tailwind, etc.)
            $table->json('gallery')->nullable(); // Galeri foto (lebih dari 1 foto)
            $table->string('link')->nullable(); // Link demo / website
            $table->boolean('is_featured')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 4. Clients Table
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('industry')->nullable();
            $table->string('logo')->nullable();
            $table->string('website')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Testimonials Table
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('company');
            $table->string('avatar')->nullable();
            $table->text('quote');
            $table->integer('rating')->default(5);
            $table->boolean('is_approved')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. Messages (Contact Form Submissions) Table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('project_type');
            $table->string('budget_range')->nullable();
            $table->text('message');
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('services');
        Schema::dropIfExists('profiles');
    }
};

