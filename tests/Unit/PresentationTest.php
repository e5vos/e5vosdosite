<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PresentationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_capacity(){
        $this->expectException(\Exception::class);
        $presentation = \App\Event::factory()->make();
        $presentation->is_presentation = true;
        $presentation->capacity = 10;
        $presentation->save();
        for($i = 0; $i<($presentation->capacity+1);$i++){
            \App\User::factory()->make()->signup($presentation);
        }

    }

    public function test_fillup(){
        $presentation = \App\Event::factory()->make();
        $presentation->is_presentation = true;
        $presentation->save();
        $students = \App\User::factory($presentation->capacity)->create();

        $excludedStudent = \App\User::factory()->make();
        $excludedStudent->save();

        foreach($students as $student){
            $student->save();
        }
        $presentation->fillUp();
        $this->assertFalse($presentation->hasCapacity());

        foreach($students as $student){
            $this->assertTrue($student->events()->get()->contains($presentation));
        }
        $this->assertFalse($excludedStudent->events()->get()->contains($presentation));
    }


}
