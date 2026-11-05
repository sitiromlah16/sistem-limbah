<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_login_page_is_not_cached_and_includes_a_csrf_token(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertHeader('Pragma', 'no-cache')
            ->assertSee('name="_token"', false)
            ->assertSee('class="login-card', false)
            ->assertSee('Akses cepat · akun demo', false)
            ->assertSee('<strong>DTY</strong> (<em>Draw Textured Yarn</em>)', false)
            ->assertSee('id="togglePassword"', false)
            ->assertSee('prefers-reduced-motion: reduce', false);

        $cacheControl = $response->headers->get('Cache-Control');

        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);
    }
}
