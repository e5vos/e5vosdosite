<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\E5N\EventController;
use Illuminate\Support\Facades\Http;

/** @var \Illuminate\Http\Request $request */

class RatingTest extends TestCase
{
    use WithFaker;
    function create_request($i){
        return Http::post(env('APP_URL'),[
            'rating' => $this->faker->randomFloat(3,0,1),
            'user_id' => $i,
            'event_id' => $i
        ]);
    }
    /**
     * Test Rating events
     *
     * @return void
     */
    public function test_example()
    {
       
    }
}
