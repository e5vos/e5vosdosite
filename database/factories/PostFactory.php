<?php

namespace Database\Factories;

use App\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'intro' => $this->faker->sentence().$this->faker->sentence(),
            'content' => $this->faker->text(),
            'author' => $this->faker->numberBetween(0,69),
            'publishdate' => $this->faker->date($format='Y-m-d', $max='now'),
        ];
    }
}