<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamConfiguration>
 */
class TeamConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'=> null, // MUST BE OVERRIDDEN
            'work_time'=> 45,
            'free_time' => 15,
            'status' => "ACTIVE"
        ];
    }
}
