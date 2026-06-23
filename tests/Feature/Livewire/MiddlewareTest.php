<?php

namespace Tests\Feature\Livewire;

use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Covers EnsureHasE5Code and EnsureHasPermission middleware on the Livewire
 * web routes registered in routes/web.php.
 */
class MiddlewareTest extends TestCase
{
    use CreatesEntities;

    // ── EnsureHasE5Code ──────────────────────────────────────────────────────

    public function test_eloadas_redirects_to_studentcode_when_e5code_is_missing(): void
    {
        $user = $this->makeUser(['e5code' => null]);

        $this->actingAs($user)
            ->get('/eloadas')
            ->assertRedirectContains('/studentcode');
    }

    public function test_eloadas_is_accessible_when_e5code_is_present(): void
    {
        $user = $this->makeUser(['e5code' => $this->uniqueE5code()]);

        $this->actingAs($user)
            ->get('/eloadas')
            ->assertSuccessful();
    }

    public function test_studentcode_next_param_is_preserved_in_redirect(): void
    {
        $user = $this->makeUser(['e5code' => null]);

        $this->actingAs($user)
            ->get('/eloadas')
            ->assertRedirect(route('studentcode', ['next' => 'eloadas']));
    }

    // ── EnsureHasPermission ───────────────────────────────────────────────────

    public function test_admin_route_returns_403_for_non_admin(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_route_is_accessible_for_adm_user(): void
    {
        $user = $this->makeUser();
        $this->grant($user, 'ADM');

        $this->actingAs($user)
            ->get('/admin')
            ->assertSuccessful();
    }

    public function test_admin_permissions_route_returns_403_for_non_admin(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get('/admin/jogosultsagok')
            ->assertForbidden();
    }

    public function test_admin_sav_route_returns_403_for_non_admin(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get('/admin/sav')
            ->assertForbidden();
    }

    // ── Guest redirects ───────────────────────────────────────────────────────

    public function test_eloadas_redirects_guest_to_login(): void
    {
        $this->get('/eloadas')
            ->assertRedirect('/login');
    }

    public function test_admin_redirects_guest_to_login(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }
}
