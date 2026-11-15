<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_page_renders_with_custom_editorial_theme(): void
    {
        $this->seed();

        $response = $this->get('/halaman-yang-pasti-tidak-ada-12345');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman yang Anda tuju');
        $response->assertSee('Kembali ke Beranda');
    }

    public function test_error_views_compile_without_syntax_errors(): void
    {
        $this->seed();

        $v404 = view('errors.404')->render();
        $this->assertStringContainsString('404', $v404);

        $v403 = view('errors.403')->render();
        $this->assertStringContainsString('403', $v403);

        $v500 = view('errors.500')->render();
        $this->assertStringContainsString('500', $v500);

        $v419 = view('errors.419')->render();
        $this->assertStringContainsString('419', $v419);

        $v429 = view('errors.429')->render();
        $this->assertStringContainsString('429', $v429);

        $v503 = view('errors.503')->render();
        $this->assertStringContainsString('503', $v503);

        $v405 = view('errors.405')->render();
        $this->assertStringContainsString('405', $v405);
    }

    public function test_trailing_semicolon_in_url_is_cleanly_redirected(): void
    {
        $this->seed();

        // /admin/projects/create; redirects cleanly to /admin/projects/create
        $response = $this->get('/admin/projects/create;');
        $response->assertRedirect('/admin/projects/create');
    }
}
