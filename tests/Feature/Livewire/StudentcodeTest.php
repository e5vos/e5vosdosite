<?php

namespace Tests\Feature\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

/**
 * Tests for the pages.studentcode Volt component.
 *
 * The component allows an authenticated user with no e5code to submit their
 * student ID.  E5CodeService::setCode() validates the code (optionally via an
 * external HTTP API), persists it, creates a STD permission, then redirects.
 *
 * E5VOS_FAKE_API is set to "true" via $_SERVER in setUp so that
 * E5CodeService::setCode() bypasses the external HTTP call. The api_rejection
 * test overrides this and uses Http::fake() to simulate an API refusal.
 */
class StudentcodeTest extends TestCase
{
    use CreatesEntities;

    /** Original value of E5VOS_FAKE_API from the server superglobal. */
    private string|false $originalFakeApi;

    protected function setUp(): void
    {
        parent::setUp();

        // Save and override the E5VOS_FAKE_API flag so every test in this
        // class can call E5CodeService::setCode() without a live API endpoint.
        $this->originalFakeApi = $_SERVER['E5VOS_FAKE_API'] ?? false;
        $_SERVER['E5VOS_FAKE_API'] = 'true';
    }

    protected function tearDown(): void
    {
        if ($this->originalFakeApi === false) {
            unset($_SERVER['E5VOS_FAKE_API']);
        } else {
            $_SERVER['E5VOS_FAKE_API'] = $this->originalFakeApi;
        }

        parent::tearDown();
    }

    /**
     * Generate an e5code whose year produces a valid ejg_class enum value.
     *
     * CreatesEntities::uniqueE5code() uses year 2099 (far future), which
     * causes E5CodeService to compute a class value outside the DB enum.
     * Year 2024 with letter C gives class 10.C for months before September.
     */
    private function validCode(): string
    {
        $n = str_pad((string) (++static::$entitySeq % 1000), 3, '0', STR_PAD_LEFT);

        return '2024C00EJG'.$n;
    }

    // -----------------------------------------------------------------------
    // Happy paths
    // -----------------------------------------------------------------------

    public function test_valid_code_saves_e5code_and_creates_std_permission(): void
    {
        $user = $this->makeUser();
        $code = $this->validCode();

        Volt::actingAs($user)->test('pages.studentcode')
            ->set('studentCode', $code)
            ->call('submit');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'e5code' => $code,
        ]);

        $this->assertDatabaseHas('permissions', [
            'user_id' => $user->id,
            'code' => 'STD',
        ]);
    }

    public function test_valid_code_redirects_to_eloadas_by_default(): void
    {
        $user = $this->makeUser();
        $code = $this->validCode();

        Volt::actingAs($user)->test('pages.studentcode')
            ->set('studentCode', $code)
            ->call('submit')
            ->assertRedirect('/eloadas');
    }

    public function test_valid_code_with_next_param_redirects_to_next(): void
    {
        $user = $this->makeUser();
        $code = $this->validCode();

        // Set `next` directly on the component state; this sidesteps the
        // Volt facade's withQueryParams() which binds to a different
        // LivewireManager instance than the one used inside Volt::test().
        Volt::actingAs($user)->test('pages.studentcode')
            ->set('next', '/someplace')
            ->set('studentCode', $code)
            ->call('submit')
            ->assertRedirect('/someplace');
    }

    // -----------------------------------------------------------------------
    // Failure paths
    // -----------------------------------------------------------------------

    public function test_invalid_format_produces_validation_error(): void
    {
        $user = $this->makeUser();

        Volt::actingAs($user)->test('pages.studentcode')
            ->set('studentCode', 'BADCODE')
            ->call('submit')
            ->assertHasErrors(['studentCode']);
    }

    public function test_invalid_format_does_not_redirect(): void
    {
        $user = $this->makeUser();

        Volt::actingAs($user)->test('pages.studentcode')
            ->set('studentCode', 'BADCODE')
            ->call('submit')
            ->assertNoRedirect();
    }

    public function test_api_rejection_sets_error_and_does_not_redirect(): void
    {
        // Temporarily disable the fake-API shortcut so the HTTP call is made.
        $_SERVER['E5VOS_FAKE_API'] = 'false';

        // Fake the API endpoint to return "false" (invalid code).
        Http::fake(['*' => Http::response('false', 200)]);

        $user = $this->makeUser();
        $code = $this->validCode();

        $component = Volt::actingAs($user)->test('pages.studentcode')
            ->set('studentCode', $code)
            ->call('submit');

        // Restore the fake-API flag for any subsequent tests in the suite.
        $_SERVER['E5VOS_FAKE_API'] = 'true';

        $component->assertNoRedirect();
        $component->assertSet('error', 'Érvénytelen EJG diákkód.');
    }

    public function test_user_with_existing_e5code_is_redirected_to_eloadas_on_mount(): void
    {
        $code = $this->validCode();
        $user = $this->makeUser(['e5code' => $code]);

        Volt::actingAs($user)->test('pages.studentcode')
            ->assertRedirect('/eloadas');
    }

    public function test_user_with_existing_e5code_redirects_to_next_on_mount(): void
    {
        $code = $this->validCode();
        $user = $this->makeUser(['e5code' => $code]);

        // Livewire::withQueryParams() passes params as GET query string in the
        // initial render. The Volt facade's withQueryParams() binds to a
        // different LivewireManager instance and does not propagate correctly.
        Livewire::withQueryParams(['next' => '/someplace']);
        Volt::actingAs($user)->test('pages.studentcode')
            ->assertRedirect('/someplace');
    }

    // -----------------------------------------------------------------------
    // Auth
    // -----------------------------------------------------------------------

    public function test_route_redirects_guest_to_login(): void
    {
        // The auth middleware redirects unauthenticated requests to the route
        // named "login".  In this app that resolves to /api/login (the API
        // route takes precedence over the Volt route of the same name).
        $response = $this->get(route('studentcode'));

        $response->assertRedirect();
        $this->assertStringContainsString('login', $response->headers->get('Location'));
    }
}
