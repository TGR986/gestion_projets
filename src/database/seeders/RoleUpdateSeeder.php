<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleUpdateSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. RENOMMER LES ROLES EXISTANTS
        |--------------------------------------------------------------------------
        */

        // validateur financier → validation financière
        Role::where('code', 'validateur_financier')
            ->update([
                'code' => 'validation_financiere',
                'libelle' => 'Validation financière',
            ]);

        // validateur technique → validation technique
        Role::where('code', 'validateur_technique')
            ->update([
                'code' => 'validation_technique',
                'libelle' => 'Validation technique',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 2. AJOUTER LE ROLE VALIDATION ADMINISTRATIVE
        |--------------------------------------------------------------------------
        */

        Role::firstOrCreate(
            ['code' => 'validation_administrative'],
            ['libelle' => 'Validation administrative']
        );
    }
}