<?php

namespace Tests\Browser;

use App\Models\Permission;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminPermissionsTest extends DuskTestCase
{
    private User $admin;

    private User $target;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'dusk-admin-perms@example.test',
            'e5code' => '2022A01EJG031',
        ]);
        Permission::firstOrCreate(['user_id' => $this->admin->id, 'code' => 'ADM']);

        $this->target = User::factory()->create([
            'name' => 'DuskTargetUser',
            'email' => 'dusk-target@example.test',
            'e5code' => '2022A01EJG032',
        ]);
    }

    protected function tearDown(): void
    {
        Permission::whereIn('user_id', [$this->admin->id, $this->target->id])->forceDelete();
        $this->admin->forceDelete();
        $this->target->forceDelete();
        parent::tearDown();
    }

    public function test_non_admin_gets_403(): void
    {
        $plain = User::factory()->create(['email' => 'dusk-plain@example.test']);

        $this->browse(function (Browser $browser) use ($plain) {
            $browser->loginAs($plain)
                ->visit('/admin/jogosultsagok')
                ->assertSee('403');
        });

        $plain->forceDelete();
    }

    public function test_admin_sees_the_permissions_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/jogosultsagok')
                ->assertSee('Jogosultságok');
        });
    }

    public function test_admin_can_search_for_a_user(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/jogosultsagok')
                ->type('input[wire\\:model\\.debounce\\.300ms="search"]', 'DuskTargetUser')
                ->waitForText('DuskTargetUser')
                ->assertSee('DuskTargetUser');
        });
    }

    public function test_admin_can_add_a_permission(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/jogosultsagok')
                ->type('input[wire\\:model\\.debounce\\.300ms="search"]', 'DuskTargetUser')
                ->waitForText('DuskTargetUser')
                ->press('DuskTargetUser')
                ->waitForText('Nincs jogosultsága')
                ->select('select[wire\\:model="newPermCode"]', 'TCH')
                ->press('Hozzáadás')
                ->waitForText('Jogosultság hozzáadva');

            $this->assertDatabaseHas('permissions', [
                'user_id' => $this->target->id,
                'code' => 'TCH',
            ]);
        });
    }

    public function test_admin_can_remove_a_permission(): void
    {
        Permission::firstOrCreate(['user_id' => $this->target->id, 'code' => 'TCH']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/jogosultsagok')
                ->type('input[wire\\:model\\.debounce\\.300ms="search"]', 'DuskTargetUser')
                ->waitForText('DuskTargetUser')
                ->press('DuskTargetUser')
                ->waitForText('TCH')
                ->press('Törlés')
                ->waitForText('Jogosultság törölve');

            $this->assertDatabaseMissing('permissions', [
                'user_id' => $this->target->id,
                'code' => 'TCH',
                'event_id' => null,
            ]);
        });
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/jogosultsagok')
                ->assertPathBeginsWith('/login');
        });
    }
}
