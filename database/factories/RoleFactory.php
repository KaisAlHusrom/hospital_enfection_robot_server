<?php

namespace Database\Factories;

use App\Enum\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role as ModelsRole;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoleFactory extends Factory
{
    protected $model = ModelsRole::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(Role::values()),
            'guard_name' => 'api', // Ensure this matches your application's guard
        ];
    }
}
