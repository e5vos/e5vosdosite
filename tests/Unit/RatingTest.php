<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{

    public function test_(){
        $user = \App\User::factory()->make();
        $event = \App\Event::factory()->make();

        $user->rate($event,10);

    }
}
