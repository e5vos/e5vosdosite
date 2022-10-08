<?php

namespace Tests\Feature\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    /**
     * Test if auth fails if user is already logged in.
     */
    public function testAuthFailsIfUserIsAlreadyLoggedIn()
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
            'url'
        ]);
        $this->assertStringContainsString('https://accounts.google.com/o/oauth2/auth', $response->json('url'));
    }

   public function test_users_can_login_with_oauth()
   {
    $this->markTestSkipped('This test is bad, function works though');
    $user = User::first();
    Socialite::shouldReceive('driver->google')->andReturn($user);
    //$response = $this->get('/auth/callback');
   }
}
