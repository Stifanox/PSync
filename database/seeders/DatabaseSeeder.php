<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Team;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\CustomSeeder\PermissionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            PermissionSeeder::class,
        ]);

        Organization::factory()->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@psync.pl',
        ]);
        User::find(1)->permissions()->attach(1);

        User::factory(10)->create()->each(function (User $user) {
            $user->permissions()->attach(2);
        });

        Team::factory(3)->create()->each(function (Team $team) {
            $users = User::inRandomOrder()->limit(rand(2, 5))->pluck('id');
            $team->users()->attach($users);
        });
    }
}
