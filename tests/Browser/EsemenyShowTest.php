<?php

namespace Tests\Browser;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Setting;
use App\Models\Slot;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EsemenyShowTest extends DuskTestCase
{
    private User $user;

    private User $admin;

    private Event $event;

    private Slot $slot;

    private Location $location;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $this->slot = Slot::factory()->create(['starts_at' => now()->subHour(), 'ends_at' => now()->addHour()]);
        $this->location = Location::factory()->create();
        $this->event = Event::factory()->create([
            'slot_id' => $this->slot->id,
            'location_id' => $this->location->id,
            'signup_type' => 'user',
            'signup_deadline' => now()->addDay(),
            'capacity' => null,
            'name' => 'Dusk Test Event',
        ]);

        $this->user = User::factory()->create([
            'email' => 'dusk-show-user@example.test',
            'e5code' => '2022A01EJG011',
        ]);
        Permission::firstOrCreate(['user_id' => $this->user->id, 'code' => 'STD']);

        $this->admin = User::factory()->create([
            'email' => 'dusk-show-admin@example.test',
            'e5code' => '2022A01EJG012',
        ]);
        Permission::firstOrCreate(['user_id' => $this->admin->id, 'code' => 'ADM']);
    }

    protected function tearDown(): void
    {
        Attendance::where('event_id', $this->event->id)->forceDelete();
        Permission::whereIn('user_id', [$this->user->id, $this->admin->id])->forceDelete();
        $this->event->forceDelete();
        $this->slot->forceDelete();
        $this->location->forceDelete();
        $this->user->forceDelete();
        $this->admin->forceDelete();
        parent::tearDown();
    }

    public function test_event_title_is_displayed(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->assertSee('Dusk Test Event');
        });
    }

    public function test_user_can_sign_up(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->press('Jelentkezés')
                ->waitForText('Sikeresen jelentkeztél');

            $this->assertDatabaseHas('attendances', [
                'event_id' => $this->event->id,
                'user_id' => $this->user->id,
            ]);
        });
    }

    public function test_user_can_cancel_signup(): void
    {
        Attendance::create(['event_id' => $this->event->id, 'user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->press('Lemondás')
                ->waitForText('Biztosan')
                ->press('Lemondás')
                ->waitForText('Sikeresen lemondtad');

            $this->assertDatabaseMissing('attendances', [
                'event_id' => $this->event->id,
                'user_id' => $this->user->id,
            ]);
        });
    }

    public function test_already_signed_up_shows_cancel_button_not_signup(): void
    {
        Attendance::create(['event_id' => $this->event->id, 'user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->assertSee('Már jelentkeztél')
                ->assertSee('Lemondás');
        });
    }

    public function test_full_event_shows_error_on_signup(): void
    {
        $this->event->forceFill(['capacity' => 1])->save();
        $other = User::factory()->create(['email' => 'dusk-other@example.test', 'e5code' => '2022A01EJG099']);
        Attendance::create(['event_id' => $this->event->id, 'user_id' => $other->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->press('Jelentkezés')
                ->waitForText('Az esemény betelt');
        });

        Attendance::where('event_id', $this->event->id)->where('user_id', $other->id)->forceDelete();
        $other->forceDelete();
    }

    public function test_admin_sees_edit_and_delete_buttons(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit("/esemeny/{$this->event->id}")
                ->assertSee('Szerkesztés')
                ->assertSee('Törlés');
        });
    }

    public function test_admin_can_delete_event(): void
    {
        $slot = Slot::factory()->create(['starts_at' => now()->subHour(), 'ends_at' => now()->addHour()]);
        $location = Location::factory()->create();
        $event = Event::factory()->create([
            'slot_id' => $slot->id,
            'location_id' => $location->id,
            'signup_type' => 'user',
            'name' => 'Dusk Delete Me',
        ]);

        $this->browse(function (Browser $browser) use ($event) {
            $browser->loginAs($this->admin)
                ->visit("/esemeny/{$event->id}")
                ->press('Törlés')
                ->waitForText('Biztosan törlöd')
                ->press('Törlés')
                ->waitForLocation('/esemeny');
        });

        $this->assertSoftDeleted('events', ['id' => $event->id]);
        $slot->forceDelete();
        $location->forceDelete();
    }

    public function test_admin_sees_participants_table_after_signup(): void
    {
        Attendance::create(['event_id' => $this->event->id, 'user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit("/esemeny/{$this->event->id}")
                ->assertSee('Résztvevők');
        });

        Attendance::where('event_id', $this->event->id)->where('user_id', $this->user->id)->forceDelete();
    }

    public function test_regular_user_does_not_see_participants_table(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit("/esemeny/{$this->event->id}")
                ->assertDontSee('Résztvevők');
        });
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit("/esemeny/{$this->event->id}")
                ->assertPathBeginsWith('/login');
        });
    }
}
