<?php

namespace Tests\Feature\BaseSiteFeatures;

use App\Models\User;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * Test if auth fails if user is already logged in.
     */
    public function test_auth_fails_if_user_is_already_logged_in()
    {
        $user = User::first();
        $response = $this->actingAs($user)->get('/api/login');
        $response->assertStatus(400);
    }

    /**
     * test if redirect link is returned.
     */
    public function test_oauth_redirect_returns_redirect_link()
    {
        $response = $this->get('api/login');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'url',
        ]);
        $this->assertStringContainsString('https://accounts.google.com/o/oauth2/auth', $response->json('url'));
    }

    public function test_users_can_login_with_oauth(): void
    {
        $fakeUser = new class
        {
            public string $id = 'fake-google-id-12345';

            public string $email = 'new.oauth.user@test.example.com';

            public string $name = 'New OAuth User';

            public ?string $avatar = null;
        };

        $provider = \Mockery::mock(Provider::class);
        $provider->shouldReceive('stateless')->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($fakeUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get('/auth/callback');
        $response->assertOk();

        $this->assertDatabaseHas('users', ['email' => 'new.oauth.user@test.example.com']);
        $this->assertDatabaseHas('personal_access_tokens', []);
    }
}
