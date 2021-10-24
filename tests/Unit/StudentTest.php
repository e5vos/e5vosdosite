<?php

namespace Tests\Unit;

use App\Exceptions\PresentationFullException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\TestCase;

/**
 * StudentTest
 *
 * @covers \App\Student
 *
 */
class StudentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_signupStudentNotAllowed
     *
     * @return void
     *
     */
    public function test_signupStudentNotAllowed(){
        $this->expectException(AuthorizationException::class);
        $presentation = \App\Presentation::factory()->make();
        $student = \App\Student::factory()->make();
        $student->signUp($presentation);
    }

    /**
     * test_signupPresentationFull
     *
     * @return void
     */
    public function test_signupPresentationFull(){
        $this->expectException(\App\Exceptions\PresentationFullException::class);

        $presentation = \App\Presentation::factory()->make();
        $presentation->capacity = 1;
        $presentation->save();


        $student = \App\Student::factory()->make();
        $student->allowed = true;
        $student->save();

        $student->signUp($presentation);

        $student = \App\Student::factory()->make();
        $student->allowed = true;
        $student->save();

        $student->signUp($presentation);

    }

    /**
     * test_signupStudentBusy
     *
     * @return void
     */
    public function test_signupStudentBusy(){
        $this->expectException(\App\Exceptions\StudentBusyException::class);


        $student = \App\Student::factory()->make();
        $student->allowed = true;
        $student->save();

        $presentation_a = \App\Presentation::factory()->make();
        $presentation_b = \App\Presentation::factory()->make();
        $presentation_a->slot = 1;
        $presentation_b->slot = 1;
        $presentation_a->save();
        $presentation_b->save();

        $student->signUp($presentation_a);

        $student->signUp($presentation_b);
    }
}
