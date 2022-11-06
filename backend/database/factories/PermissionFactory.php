<?php

namespace Database\Factories;

use App\Helpers\PermissionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Permission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => fake()->randomElement(array_column(PermissionType::cases(), 'value')),
        ];
    }
}
