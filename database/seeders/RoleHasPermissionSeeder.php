<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleHasPermissions;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        RoleHasPermissions::create([
            'permission_id' => 1,
            'role_id' => 1
        ]);
        RoleHasPermissions::create([
            'permission_id' => 2,
            'role_id' => 2
        ]);
    }
}
