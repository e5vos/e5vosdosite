<?php

namespace Tests\Browser;

use App\Models\Permission;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StudentcodeTest extends DuskTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'dusk-studentcode@example.test',
            'e5code' => null,
            'ejg_class' => null,
        ]);
    }

    protected function tearDown(): void
    {
        Permission::where('user_id', $this->user->id)->forceDelete();
        $this->user->forceDelete();
        parent::tearDown();
    }

    public function test_user_without_e5code_sees_the_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/studentcode')
                ->assertVisible('input[wire\\:model\\.live="studentCode"]')
                ->assertSee('Diákigazolvány');
        });
    }

    public function test_valid_code_saves_e5code_and_redirects(): void
    {
        // E5VOS_FAKE_API=true is set in .env.dusk.local or .env.testing
        $this->browse(function (Browser $browser) {
            $validCode = '2022A01EJG001';

            $browser->loginAs($this->user)
                ->visit('/studentcode')
                ->type('input[wire\\:model\\.live="studentCode"]', $validCode)
                ->press('button[type="submit"]')
                ->waitForLocation('/eloadas');

            $this->assertNotNull($this->user->fresh()->e5code);
            $this->assertDatabaseHas('permissions', [
                'user_id' => $this->user->id,
                'code' => 'STD',
            ]);
        });
    }

    public function test_invalid_code_format_shows_validation_error(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/studentcode')
                ->type('input[wire\\:model\\.live="studentCode"]', 'NOTACODE')
                ->press('button[type="submit"]')
                ->waitForText('studentCode')
                ->assertDontSeeIn('body', '/eloadas');
        });
    }

    public function test_user_with_e5code_is_redirected_immediately(): void
    {
        $this->user->forceFill(['e5code' => '2022A01EJG002'])->save();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/studentcode')
                ->waitForLocation('/eloadas');
        });
    }
}
