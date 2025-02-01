<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\TeamConfiguration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=> $this->faker->company(),
            'organization_id'=> 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Team $team) {
            TeamConfiguration::factory()->create([
                'team_id' => $team->id,
            ]);
        });
    }
}
