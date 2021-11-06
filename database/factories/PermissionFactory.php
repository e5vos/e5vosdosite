<?php

namespace Database\Factories;

use App\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(0,499),
            'event_id' => $this->faker->numberBetween(0,49),
            'permission' => [NULL, 'ORG', 'ADM'][$this->faker->numberBetween(0,2)],
        ];
    }
}
