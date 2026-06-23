<?php

namespace Tests\Browser;

use App\Helpers\SlotType;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Setting;
use App\Models\Slot;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EloadasIndexTest extends DuskTestCase
{
    private User $user;

    private Slot $slot;

    private Event $presentation;

    private Location $location;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::updateOrCreate(['key' => 'e5n.events.signup'], ['value' => '1']);

        $this->slot = Slot::factory()->create([
            'name' => 'Dusk Előadássáv',
            'slot_type' => SlotType::presentation->value,
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHour(),
        ]);

        $this->location = Location::factory()->create();

        $this->presentation = Event::factory()->create([
            'slot_id' => $this->slot->id,
            'location_id' => $this->location->id,
            'signup_type' => 'user',
            'signup_deadline' => now()->addDay(),
            'capacity' => null,
            'name' => 'Dusk Presentation',
        ]);

        $this->user = User::factory()->create([
            'email' => 'dusk-eloadas@example.test',
            'e5code' => '2022A01EJG021',
        ]);
        Permission::firstOrCreate(['user_id' => $this->user->id, 'code' => 'STD']);
    }

    protected function tearDown(): void
    {
        Attendance::where('event_id', $this->presentation->id)->forceDelete();
        Permission::where('user_id', $this->user->id)->forceDelete();
        $this->presentation->forceDelete();
        $this->slot->forceDelete();
        $this->location->forceDelete();
        $this->user->forceDelete();
        parent::tearDown();
    }

    public function test_page_renders_with_slot_tab(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->assertSee('Dusk Előadássáv');
        });
    }

    public function test_presentations_are_listed_for_slot(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->assertSee('Dusk Presentation');
        });
    }

    public function test_user_can_sign_up_to_a_presentation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->press('Jelentkezés')
                ->waitForText('Sikeres jelentkezés');

            $this->assertDatabaseHas('attendances', [
                'event_id' => $this->presentation->id,
                'user_id' => $this->user->id,
            ]);
        });
    }

    public function test_signed_up_row_shows_cancel_button(): void
    {
        Attendance::create(['event_id' => $this->presentation->id, 'user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->assertSee('Lemondás');
        });

        Attendance::where('event_id', $this->presentation->id)->where('user_id', $this->user->id)->forceDelete();
    }

    public function test_user_can_cancel_presentation_signup(): void
    {
        Attendance::create(['event_id' => $this->presentation->id, 'user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->press('Lemondás')
                ->waitForText('Biztosan')
                ->press('Lemondás')
                ->waitForText('Sikeresen lemondtad');

            $this->assertDatabaseMissing('attendances', [
                'event_id' => $this->presentation->id,
                'user_id' => $this->user->id,
            ]);
        });
    }

    public function test_closed_signup_shows_closed_label(): void
    {
        $this->presentation->forceFill(['signup_deadline' => now()->subMinute()])->save();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/eloadas')
                ->assertSee('Lezárult');
        });

        $this->presentation->forceFill(['signup_deadline' => now()->addDay()])->save();
    }

    public function test_user_without_e5code_is_redirected(): void
    {
        $noCode = User::factory()->create(['email' => 'dusk-nocode@example.test', 'e5code' => null]);

        $this->browse(function (Browser $browser) use ($noCode) {
            $browser->loginAs($noCode)
                ->visit('/eloadas')
                ->assertPathBeginsWith('/studentcode');
        });

        $noCode->forceDelete();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/eloadas')
                ->assertPathBeginsWith('/login');
        });
    }
}
