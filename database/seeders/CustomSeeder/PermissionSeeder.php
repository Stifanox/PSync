<?php

namespace Database\Seeders\CustomSeeder;

use App\Enums\Permissions;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Permissions::cases() as $permission) {
            $model = new Permission();
            $model->name = $permission->name;
            $model->save();
        }
    }
}
