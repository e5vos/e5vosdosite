<?php

namespace Tests\Feature\Auth;

use App\Helpers\PermissionType;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use CreatesEntities;

    public function test_setting_e5code_requires_authentication()
    {
        $this->patchJson('/api/e5code', ['e5code' => '2099N00EJG001'])
            ->assertStatus(401);
    }

    public function test_user_can_set_a_valid_e5code()
    {
        // The external verification API approves the code.
        Http::fake(['*' => Http::response('true')]);
        $user = $this->makeUser();

        // A 2023 "A" code resolves to class 9.A given the current date.
        $this->actingAs($user)
            ->patchJson('/api/e5code', ['e5code' => '2023A00EJG001'])
            ->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'e5code' => '2023A00EJG001',
            'ejg_class' => '9.A',
        ]);
        // A student role is granted on first verification.
        $this->assertDatabaseHas('permissions', [
            'user_id' => $user->id,
            'code' => PermissionType::Student->value,
        ]);
    }

    public function test_invalid_e5code_is_rejected()
    {
        // The external verification API rejects the code.
        Http::fake(['*' => Http::response('false')]);
        $user = $this->makeUser();

        $this->actingAs($user)
            ->patchJson('/api/e5code', ['e5code' => '2099N00EJG002'])
            ->assertStatus(400);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'e5code' => null]);
    }

    public function test_oauth_redirect_rejects_already_authenticated_user()
    {
        $this->actingAs($this->makeUser())
            ->get('/api/login')
            ->assertStatus(400);
    }
}
