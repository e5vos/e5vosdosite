<?php

namespace Tests\Feature\Auth;

use Illuminate\Contracts\Session\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\UserFactory;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_oauth_redirect_redirects()
    {
        $response = $this->get('/login');

        $response->assertStatus(302);
    }

   //public function test_users_can_login_with_oauth()
   //{
   //    $user = UserFactory::new()->create();

   //    Socialite::shouldReceive('driver->google')->andReturn($user);

   //    $response = $this->get('/auth/callback');
   //}
}
