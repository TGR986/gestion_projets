<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleValidationPermission;
use Illuminate\Database\Seeder;

class RoleValidationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            'validation_financiere' => 'validation_financiere',
            'validation_technique' => 'validation_technique',
            'validation_administrative' => 'validation_administrative',
        ];

        foreach ($mappings as $roleCode => $validationType) {
            $role = Role::where('code', $roleCode)->first();

            if (! $role) {
                continue;
            }

            RoleValidationPermission::firstOrCreate([
                'role_id' => $role->id,
                'validation_type' => $validationType,
            ]);
        }
    }
}